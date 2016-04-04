<?php
/**
 * The template for displaying all single profile posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bbgRedesign
 */
/* we go through the loop once and reset it in order to get some vars for our og tags */
if ( have_posts() ) {
	the_post();


	$metaAuthor = get_the_author();
	$metaAuthorTwitter = get_the_author_meta( 'twitterHandle' );
	$ogTitle = get_the_title();

	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];

	$socialImageID = get_post_meta( $post->ID, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}

	$occupation=get_post_meta( get_the_ID(), 'occupation', true );
	$email=get_post_meta( get_the_ID(), 'email', true );
	$phone=get_post_meta( get_the_ID(), 'phone', true );
	$twitterHandle=get_post_meta( get_the_ID(), 'twitter_handle', true );
	$profilePhoto=get_post_meta( get_the_ID(), 'profile_photo', true );
	$relatedLinksTag=get_post_meta( get_the_ID(), 'related_links_tag', true );

	$ogDescription = get_the_excerpt(); //get_the_excerpt()

	rewind_posts();
}

get_header(); ?>
	<h1>PROFILE SINGLE FOR $email</h1>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php

			$projectCategoryID = get_cat_id('Project');
			$isProject = has_category($projectCategoryID);
			$prevLink = "";
			$nextLink = "";

			//Default adds a space above header if there's no image set
			$featuredImageClass = " bbg__article--no-featured-image";
				
			//the title/headline field, followed by the URL and the author's twitter handle
			$twitterText = "";
			$twitterText .= html_entity_decode( get_the_title() );
			$twitterHandle = get_the_author_meta( 'twitterHandle' );
			$twitterHandle = str_replace( "@", "", $twitterHandle );
			if ( $twitterHandle && $twitterHandle != '' ) {
				$twitterText .= " by @" . $twitterHandle;
			} else {
				$authorDisplayName = get_the_author();
				if ( $authorDisplayName && $authorDisplayName!='' ) {
					$twitterText .= " by " . $authorDisplayName;
				}
			}
			$twitterText .= " " . get_permalink();
			$hashtags="";
			//$hashtags="testhashtag1,testhashtag2";

			///$twitterURL="//twitter.com/intent/tweet?url=" . urlencode(get_permalink()) . "&text=" . urlencode($ogDescription) . "&hashtags=" . urlencode($hashtags);
			$twitterURL="//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
			$fbUrl="//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>
				<?php
					$hideFeaturedImage = get_post_meta( get_the_ID(), "hide_featured_image", true );
					if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
						echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline="";
						$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
						if ($thumbnail_image && isset($thumbnail_image[0])) {
							$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
						}
						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
						//echo '<div style="position: absolute;"><h5 class="bbg-label">Label</h5></div>';
						echo the_post_thumbnail( 'large-thumb' );

						echo '</div>';
						echo '</div> <!-- usa-grid-full -->';

						if ($featuredImageCutline != "") {
							echo '<div class="usa-grid">';
								echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
							echo '</div> <!-- usa-grid -->';
						}
					}
				?><!-- .bbg__article-header__thumbnail -->

				<div class="bbg__article__nav">
					<?php echo $prevLink; ?>
					<?php echo $nextLink; ?>
				</div><!-- .bbg__article__nav -->


				<div class="usa-grid">

					<?php echo '<header class="entry-header bbg__article-header'.$featuredImageClass.'">'; ?>

					<?php echo bbginnovate_post_categories(); ?>
					<!-- .bbg-label -->

						<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
						<!-- .bbg__article-header__title -->

						<div class="entry-meta bbg__article-meta">
							<?php bbginnovate_posted_on(); ?>
						</div><!-- .bbg__article-meta -->
					</header><!-- .bbg__article-header -->
					<div class="container" style="position: relative;">
					<ul class="bbg__article-share">
						<li class="bbg__article-share__link facebook">
							<a href="<?php echo $fbUrl; ?>">
								<span class="bbg__article-share__icon facebook"></span>
								<span class="bbg__article-share__text">Share</span>
							</a>
						</li>
						<li class="bbg__article-share__link twitter">
							<a href="<?php echo $twitterURL; ?>">
								<span class="bbg__article-share__icon twitter"></span>
								<span class="bbg__article-share__text">Tweet</span>
							</a>
						</li>
					</ul>

					<div class="entry-content bbg__article-content">
						<?php the_content(); ?>



					<!-- old nav location -->



					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar">
						<?php
							$usersInProjectStr = get_post_meta( get_the_ID(), 'users_in_project', true );

							if(!in_category('project')) {
								bbg_post_author_bottom_card(get_the_author_meta('ID'));
							} elseif ( $usersInProjectStr != "" ) {
								$userIDs = explode( ',', $usersInProjectStr );
								array_walk( $userIDs, 'intval' );
								$args = array( 'include' => $userIDs, 'orderby' => 'include' );

								$blogusers = get_users( $args );
								// Loop through the users to create the staff profiles
								if ( $blogusers ) {
									echo "<div class='bbg__portfolio-members'><h3 class='bbg__portfolio-members__title'>ODDI Team</h3> <ul class='bbg__portfolio-members__list'>";
									foreach ( $blogusers as $user ) {
										$authorName = esc_html( $user -> display_name );
										$authorUrl = get_author_posts_url( $user -> ID, $user -> user_nicename );
										$authorOccupation = esc_html( $user -> occupation );
										echo "<li class='bbg__portfolio-members__member-name'><a href='$authorUrl'>$authorName</a><br/><span class='bbg__portfolio-members__member-job'>$authorOccupation</span></li>";
									}
									echo "</ul></div>";
								}

							}
						?>
					</div> <!-- .bbg__article-sidebar -->
					
					</div><!-- container -->
				</div><!-- .usa-grid -->

				<!-- <footer class="entry-footer bbg__article-footer">
					<?php bbginnovate_entry_footer(); ?>
				</footer> --><!-- .entry-footer -->
			</article><!-- #post-## -->


			<div class="bbg__article-footer usa-grid">
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( !in_category('Project') &&(comments_open() || get_comments_number())):
						comments_template();
					endif;
				?>
			</div>
		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

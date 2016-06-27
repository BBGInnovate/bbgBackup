<?php
/**
 * The template for displaying expaneded profile posts (e.g the CEO).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bbgRedesign
  template name: Profile (Expanded)

 */
/* we go through the loop once and reset it in order to get some vars for our og tags */
if ( have_posts() ) {
	the_post();

	$metaAuthor = get_the_author();
	$metaAuthorTwitter = get_the_author_meta( 'twitterHandle' );
	$ogTitle = get_the_title();

	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));

	$id = get_the_ID();

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'Full' );
	$ogImage = $thumb['0'];

	$socialImageID = get_post_meta( $id, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}

	$bannerPosition=get_post_meta( $id, 'adjust_the_banner_image', true);

	$occupation=get_post_meta( $id, 'occupation', true );
	$email=get_post_meta( $id, 'email', true );
	$phone=get_post_meta( $id, 'phone', true );
	$twitterProfileHandle=get_post_meta( $id, 'twitter_handle', true );
	$relatedLinksTag=get_post_meta( $id, 'related_links_tag', true );

	//Collapse the left column on mobile if it's empty.
	$collapseColumnCSS = "";
	if ($email=="" && $twitterProfileHandle=="" && $phone==""){
		$collapseColumnCSS = "bbg__article-sidebar--collapse";
	}

	$active = get_post_meta( $id, 'active', true );
	$formerCSS="";
	if (!$active){
		$occupation = "(Former) " . $occupation;
		$formerCSS=" bbg__former-member";
	}


	$profilePhotoID=get_post_meta( $id, 'profile_photo', true );
	$profilePhoto = "";

	if ($profilePhotoID) {
		$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
		$profilePhoto = $profilePhoto[0];
	}

	$timelineUrl=get_post_meta( $id, 'timelinejs_url', true );
	if ($timelineUrl!=""){
		$timelineUrl = '<iframe src="' . $timelineUrl . '" height="800" width="600" frameborder="0" class="bbg__iframe__timelinejs"></iframe>';
	}

	$latestTweetsStr="";
	if ($twitterProfileHandle != "") {
		$showLatestTweets=get_post_meta( $id, 'show_latest_tweets', true );
		if ($showLatestTweets) {
			$widgetID="730138164040507394";	//change this to bbg acct
			$latestTweetsStr='<a data-chrome="noheader nofooter noborders transparent noscrollbar" data-tweet-limit="4" class="twitter-timeline" href="https://twitter.com/'.$twitterProfileHandle.'" data-widget-id="'.$widgetID.'" data-screen-name="'.$twitterProfileHandle.'" >Tweets by @'.$twitterProfileHandle.'</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		}	
	}

	$ogDescription = get_the_excerpt();

	rewind_posts();
}

get_header(); ?>
	<div id="primary" class="content-area bbg__profile">
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

				<?php if($post->post_parent) {
					//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
					$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
					$parent_link = get_permalink($post->post_parent);
					?>
					<h5 class="bbg__label"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>
				<?php } ?>

				<?php
					$hideFeaturedImage = get_post_meta( $id, "hide_featured_image", true );
					if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
						echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline="";
						$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment'));
						if ($thumbnail_image && isset($thumbnail_image[0])) {
							$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
						}

						$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner--profile" style="background-image: url('.$src[0].'); background-position: '.$bannerPosition.'">';
						echo '</div>';
						echo '</div> <!-- usa-grid-full -->';
					}
				?><!-- .bbg__article-header__thumbnail -->

				<div class="bbg__article__nav">
					<?php echo $prevLink; ?>
					<?php echo $nextLink; ?>
				</div><!-- .bbg__article__nav -->


				<div class="usa-grid">

					<?php echo '<header class="entry-header bbg__article-header'.$featuredImageClass.'">'; ?>

						<div class="bbg__profile-photo">
							<img src="<?php echo $profilePhoto; ?>" class="bbg__profile-photo__image<?php echo $formerCSS; ?>"/>
						</div>
						<div class="bbg__profile-title">

							<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>

							<!-- .bbg__article-header__title -->
							<h5 class="entry-category bbg__label">
								<?php echo $occupation; ?>
							</h5><!-- .bbg__label -->

						</div>
					</header><!-- .bbg__article-header -->


					<div class="bbg__article-sidebar--left">

						<h3 class="bbg__sidebar-label bbg__contact-label">Share </h3>
						<ul class="bbg__article-share">
							<li class="bbg__article-share__link facebook">
								<a href="<?php echo $fbUrl; ?>">
									<span class="bbg__article-share__icon facebook"></span>
								</a>
							</li>
							<li class="bbg__article-share__link twitter">
								<a href="<?php echo $twitterURL; ?>">
									<span class="bbg__article-share__icon twitter"></span>
								</a>
							</li>
						</ul>

					</div>




					<div class="entry-content bbg__article-content largeXXX <?php echo $featuredImageClass; ?>">
						<div class="bbg__profile__content">
						<?php the_content(); ?>

						<p class="bbg-tagline" style="text-align: right;">Last modified: <?php the_modified_date('F d, Y'); ?></p>

						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<a href="<?php echo $parent_link; ?>" class="bbg__tagline-link">Back to <?php echo $parent->post_title; ?></a>
						<?php } ?>
						</div>



						<?php 
							//Add blog posts below the main content
							$relatedCategory=get_field('profile_related_category', $id);
							
							if ( $relatedCategory != "" ) {
								$qParams2=array(
									'post_type' => array('post'),
									'posts_per_page' => 2,
									'cat' => $relatedCategory->term_id,
									'orderby' => 'date',
									'order' => 'DESC'
								);
								$categoryUrl = get_category_link($relatedCategory->term_id);
								$custom_query = new WP_Query( $qParams2 );
								if ($custom_query -> have_posts()) {
									echo '<h6 class="bbg__label"><a href="'.$categoryUrl.'">'.$relatedCategory->name.'</a></h6>';
									echo '<div class="usa-grid-full">';
									while ( $custom_query -> have_posts() )  {
										$custom_query->the_post();
										get_template_part( 'template-parts/content-portfolio', get_post_format() );
									}
									echo '</div>';
								}
								wp_reset_postdata();
							}
						?>

					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar largeXXX">
						<ul class="bbg__article-share ">
						<?php 
						if ($email != "" || $phone != ""){
						?>
							<h3 class="bbg__sidebar-label bbg__contact-label">Contact </h3>
						<?php } elseif ($twitterProfileHandle != "") {?>
							<h3 class="bbg__sidebar-label bbg__contact-label">Follow on Twitter</h3>
						<?php } ?>


						<?php 
						if ($email != ""){
							echo '<li class="bbg__article-share__link email"><a href="mailto:'.$email.'" title="Email '.get_the_title().'"><span class="bbg__article-share__icon email"></span><span class="bbg__article-share__text">'.$email.'</span></a></li>'; 
						}
						if ($twitterProfileHandle != ""){
							echo '<li class="bbg__article-share__link twitter"><a href="https://twitter.com/'.$twitterProfileHandle.'" title="Follow '.get_the_title().' on Twitter"><span class="bbg__article-share__icon twitter"></span><span class="bbg__article-share__text">@'.$twitterProfileHandle.'</span></a></li>'; 
						}

						if ($phone != ""){
							echo '<li class="bbg__article-share__link phone"><span class="bbg__article-share__icon phone"></span><span class="bbg__article-share__text">'.$phone.'</span></li>'; 
						}
						?>
						&nbsp;
						</ul>
						<?php echo $latestTweetsStr; ?>

						<?php 

							if ( $relatedLinksTag != "" ) {
								$qParams2=array(
									'post_type' => array('post'),
									'posts_per_page' => 5,
									'tag' => $relatedLinksTag,
									'orderby' => 'date',
									'order' => 'DESC'
								);
								$custom_query = new WP_Query( $qParams2 );
								if ($custom_query -> have_posts()) {
									echo '<h3 class="bbg__sidebar-label">Related posts  <!--(tag "$relatedLinksTag")--></h3>';
									echo '<ul class="bbg__profile__related-link__list">';
									while ( $custom_query -> have_posts() )  {
										$custom_query->the_post();
										$link = get_the_permalink();
										$title = get_the_title();
										echo '<li class="bbg__profile__related-link"><a href="' . $link . '">' . $title . '</a></li>';
									}
									echo "</ul>";
								}
								wp_reset_postdata();
							}
						?>

					</div><!-- .bbg__article-sidebar -->

				<?php echo $timelineUrl; ?>

				</div><!-- .usa-grid -->

			</article><!-- #post-## -->

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<section class="usa-grid">
		<?php get_sidebar(); ?>
	</section>
<?php get_footer(); ?>

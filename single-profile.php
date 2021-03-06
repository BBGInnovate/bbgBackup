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
	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));

	/**** CREATE OG TAGS ***/
	$ogDescription = get_the_excerpt();
	$ogTitle = get_the_title();
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];
	$socialImageID = get_post_meta( $post->ID, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}
	
	/**** CREATE $bannerAdjustStr *****/
	$bannerPosition=get_post_meta( get_the_ID(), 'adjust_the_banner_image', true);
	$bannerPositionCSS = get_field( 'adjust_the_banner_image_css', '', true);
	$bannerAdjustStr = "";
	if ($bannerPositionCSS) {
		$bannerAdjustStr = $bannerPositionCSS;
	} else if ($bannerPosition) {
		$bannerAdjustStr = $bannerPosition;
	}

	/**** Get profile fields *****/
	$occupation=get_post_meta( get_the_ID(), 'occupation', true );
	$email=get_post_meta( get_the_ID(), 'email', true );
	$phone=get_post_meta( get_the_ID(), 'phone', true );
	$twitterProfileHandle=get_post_meta( get_the_ID(), 'twitter_handle', true );
	$relatedLinksTag=get_post_meta( get_the_ID(), 'related_links_tag', true );

	/**** Adjustments for retired employees ***/
	$active = get_post_meta( get_the_ID(), 'active', true );
	if (!$active){
		$occupation = "(Former) " . $occupation;
	}

	/*** Get the profile photo mugshot ***/
	$profilePhotoID=get_post_meta( get_the_ID(), 'profile_photo', true );
	$profilePhoto = "";
	if ($profilePhotoID) {
		$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
		$profilePhoto = $profilePhoto[0];
	}

	/*** Get extra details for interns ***/
	$internTagline = "";
	$internDate = get_post_meta( get_the_ID(), 'intern_date', true );
	if ( $internDate ) {
		$internName = get_the_title();
		$internTagline = "<p class='bbg__post__author-tagline'>— " . $internName . ", " . $internDate . "</p>";
	}


	

	rewind_posts();
}

get_header(); ?>
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
			$twitterText .= "Profile of " . html_entity_decode( get_the_title() );
			$twitterText .= " by @bbggov " . get_permalink(); 
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

						$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner--profile" style="background-image: url('.$src[0].'); background-position: '.$bannerAdjustStr.'">';
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
						<?php 
						$noProfilePhoto = "";
						if ($profilePhotoID) { 
						?>
							<div class="bbg__profile-photo">
								<img src="<?php echo $profilePhoto; ?>" class="bbg__profile-photo__image"/>
							</div>
						<?php } else {
							$noProfilePhoto = "max-width: none;";
						} ?>
						<div class="bbg__profile-title" style="<?php echo $noProfilePhoto; ?>">

							<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>

							<!-- .bbg__article-header__title -->
							<h5 class="entry-category bbg__profile-tagline">
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

					<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">
						<?php the_content(); ?>

						<?php echo $internTagline; ?>

						<!--Last modified: <?php the_modified_date('F d, Y'); ?>-->

					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar">

						<?php 
						if ($email != "" || $phone != ""){
						?>
							<h3 class="bbg__sidebar-label bbg__contact-label">Contact </h3>
						<?php } elseif ($twitterProfileHandle != "") {?>
							<h3 class="bbg__sidebar-label bbg__contact-label">Follow on Twitter</h3>
						<?php } ?>

						<ul class="bbg__article-share ">

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
						</ul>


						<?php 
							if ( $relatedLinksTag != "" ) {
								$qParams2=array(
									'post_type' => array('post'),
									'posts_per_page' => 5,
									'tag' => $relatedLinksTag
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

				</div><!-- .usa-grid -->

			</article><!-- #post-## -->

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<section class="usa-grid">
		<?php get_sidebar(); ?>
	</section>
<?php get_footer(); ?>

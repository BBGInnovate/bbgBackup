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

	$bannerPosition=get_post_meta( get_the_ID(), 'adjust_the_banner_image', true);

	$meetingTime=get_post_meta( get_the_ID(), 'board_meeting_time', true );
	$meetingDate=get_post_meta( get_the_ID(), 'board_meeting_date', true );
	$meetingLocation=get_post_meta( get_the_ID(), 'board_meeting_location', true );
	$meetingSummary=get_post_meta( get_the_ID(), 'board_meeting_summary', true );
/*
	$occupation=get_post_meta( get_the_ID(), 'occupation', true );
	$email=get_post_meta( get_the_ID(), 'email', true );
	$phone=get_post_meta( get_the_ID(), 'phone', true );
	$twitterProfileHandle=get_post_meta( get_the_ID(), 'twitter_handle', true );
	$relatedLinksTag=get_post_meta( get_the_ID(), 'related_links_tag', true );

	$active = get_post_meta( get_the_ID(), 'active', true );
	if (!$active){
		$occupation = "(Former) " . $occupation;
	}

	$profilePhotoID=get_post_meta( get_the_ID(), 'profile_photo', true );
	$profilePhoto = "";

	if ($profilePhotoID) {
		$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
		$profilePhoto = $profilePhoto[0];
	}
*/



	$ogDescription = get_the_excerpt();

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

						$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__profile-header__banner" style="background-image: url('.$src[0].'); background-position: '.$bannerPosition.'">';
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

						<div class="bbg__event-title">

							<h5 class="entry-category bbg-label">Board meeting: <?php echo $meetingDate; ?></h5><!-- .bbg-label -->
							<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
							<!-- .bbg__article-header__title -->

						</div>
					</header><!-- .bbg__article-header -->

					<div class="bbg__article-sidebar--left">
						<h3>February 26, 2016</h3>
						<h5>BBG Headquarters<br/>Washington, DC</h5>
						<p class="bbg-tagline">For more information, please contact BBG Public Affairs at (202) 203-4400 or by e-mail at pubaff@bbg.gov.</p>
					</div>

					<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar">
						<h3 class="bbg__sidebar-label">Download</h3>
						<ul class="bbg__profile__related-link__list">
							<li class="bbg__profile__related-link"><a href="http://www.bbg.gov/wp-content/media/2015/10/Minutes-of-Dec-16-2015.pdf" title="Download the BBG board meeting minutes from Feb. 26, 2016.">Board meeting minutes</a></li>
							<li class="bbg__profile__related-link"><a href="http://www.bbg.gov/wp-content/media/2015/10/Record-of-Decisions-2-26-2016.pdf">Record of Decisions</a></li>
							<li class="bbg__profile__related-link"><a href="http://www.bbg.gov/wp-content/media/2015/10/Resolution-Honoring-Almigdad-Mojalli.pdf">Resolution Honoring Almigdad Mojalli</a></li>
							<li class="bbg__profile__related-link"><a href="http://www.bbg.gov/wp-content/media/2015/10/Resolution-VOA-Creole-30th-Anniversary.pdf">Resolution VOA Creole 30th Anniversary</a></li>
						</ul>

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

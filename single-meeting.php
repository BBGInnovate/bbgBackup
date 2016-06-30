<?php
/**
 * The template for displaying board meetings and events.
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
	//$meetingDate=get_post_meta( get_the_ID(), 'board_meeting_date', true );
	$meetingLocation=get_post_meta( get_the_ID(), 'board_meeting_location', true );
	$meetingSummary=get_post_meta( get_the_ID(), 'board_meeting_summary', true );

	if ( $meetingTime != "") {
		$meetingTime = $meetingTime . ", ";
	}


	$eventbriteID = get_post_meta( get_the_ID(), 'board_meeting_eventbrite_id', true );

	$eventbriteIframeHeight = get_post_meta( get_the_ID(), 'board_meeting_eventbrite_iframe_height', true );
	if (!$eventbriteIframeHeight || $eventbriteIframeHeight=="") {
		$eventbriteIframeHeight=270;
	}
	$eventStr="";
	if ($eventbriteID) {
		$iframeNarrowHeight = $eventbriteIframeHeight+75;
		$eventStr='<style>';
		$eventStr .= '#eventbriteContainer { width: 100%; text-align:left; height: ' . $eventbriteIframeHeight .'px;}';
		$eventStr .= '@media  (max-width: 480px) { #eventbriteContainer { height:'.$iframeNarrowHeight.'px;} }';
		$eventStr .= '</style>';
		$eventStr .= '<div id="eventbriteContainer"><iframe src="//eventbrite.com/tickets-external?eid='.$eventbriteID.'&ref=etckt" frameborder="0" height="100%" width="100%" vspace="0" hspace="0" marginheight="5" marginwidth="5" scrolling="auto" allowtransparency="true"></iframe></div>';
		$eventStr .= "<button class='usa-button'><a style='color:white;text-decoration:none;' href='https://www.eventbrite.com/e/feaux-halloween-board-meeting-tickets-26165052376?ref=elink'>Register for this Event</button></a><BR>";
	}

	$ogDescription = get_the_excerpt();

	rewind_posts();
}
$eventPageHeader = "Event";
if (in_category("Board Meetings")) {
	$eventPageHeader = "Board Meeting";
}


//Add featured video
$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );


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
			$postDate = get_the_date();

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>

				<?php
					//If a featured video is set, include it.
					//ELSE if a featured image is set, include it.
					$hideFeaturedImage = FALSE;
					if ($videoUrl!="") {
						echo featured_video($videoUrl);
						$hideFeaturedImage = TRUE;
					} elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
						echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline="";
						$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
						if ($thumbnail_image && isset($thumbnail_image[0])) {
							$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
						}
						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
						//echo '<div style="position: absolute;"><h5 class="bbg__label">Label</h5></div>';
						echo the_post_thumbnail( 'large-thumb' );

						if ( $featuredImageCutline != "" ) {
							echo '<div class="usa-grid">';
								echo "<div class='wp-caption-text'>$featuredImageCutline</div>";
							echo '</div> <!-- usa-grid -->';
						}

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

							<h5 class="entry-category bbg__label"><?php echo $eventPageHeader; ?>: <?php echo $postDate; ?></h5><!-- .bbg__label -->
							<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
							<!-- .bbg__article-header__title -->

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

					<div class="entry-content bbg__article-content">
						<?php 
						 echo $eventStr;
						 the_content(); ?>
					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar">
						<h5>WHEN: <?php echo $meetingTime; ?><br/><?php echo $postDate; ?></h5>
						<h5>WHERE: <?php echo $meetingLocation; ?></h5>
						<p class="bbg-tagline bbg-tagline--main">For more information, please contact BBG Public Affairs at (202) 203-4400 or by e-mail at pubaff@bbg.gov.</p>

						<?php
							if( have_rows('board_meeting_related_documents') ):
							 	echo '<h3 class="bbg__sidebar-label">Downloads</h3>';
							 	echo '<ul class="bbg__profile__related-link__list">';
							    while ( have_rows('board_meeting_related_documents') ) : the_row();
							        echo '<li class="bbg__profile__related-link">';
							        $dl = get_sub_field('board_meeting_related_document');
							        echo "<a href='" . $dl['url'] . "'>" . $dl['title'] . "</a>";
							        echo '</li>';
							    endwhile;
							    echo '</ul>';
							endif;
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

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

	$bannerPosition = get_post_meta( get_the_ID(), 'adjust_the_banner_image', true);

	$meetingTime = get_post_meta( get_the_ID(), 'board_meeting_time', true );
	//$meetingDate=get_post_meta( get_the_ID(), 'board_meeting_date', true );
	$meetingLocation = get_post_meta( get_the_ID(), 'board_meeting_location', true );
	$meetingSummary = get_post_meta( get_the_ID(), 'board_meeting_summary', true );

	if ( $meetingTime != "") {
		$meetingTime = $meetingTime . ", ";
	}

	$meetingSpeakers = get_post_meta( get_the_ID(), 'board_meeting_speakers', true );

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

/**
 ** Sidebar content **
 **/

// Sidebar items (links, quotes, individual downloads)
$includeSidebar = get_post_meta( get_the_ID(), 'sidebar_include', true );
if ( $includeSidebar ) {
	// check if the flexible content field has rows of data
	$sidebar = "";
	$s = "";

	if ( have_rows('sidebar_items') ):
		$sidebarTitle = get_post_meta( get_the_ID(), 'sidebar_title', true );

		if ( $sidebarTitle != "" ) {
			$s = "<h5 class='bbg__label small bbg__sidebar__download__label'>" . $sidebarTitle ."</h5>";
		}

		while ( have_rows('sidebar_items') ) : the_row();

			if ( get_row_layout() == 'sidebar_download_file' ) {

				$sidebarDownloadTitle = get_sub_field( 'sidebar_download_title' );
				$sidebarDownloadThumbnail = get_sub_field( 'sidebar_download_thumbnail' );
				$sidebarDownloadLink = get_sub_field( 'sidebar_download_link' );
				$sidebarDownloadDescription = get_sub_field( 'sidebar_download_description', false);

				$fileID = $sidebarDownloadLink['ID'];
				$file = get_attached_file( $fileID );
				$ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
				$filesize = formatBytes(filesize($file));

				$sidebarImage = "";
				if ($sidebarDownloadThumbnail && $sidebarDownloadThumbnail != "") {
					$sidebarImage = "<img src='" . $sidebarDownloadThumbnail . "' class='bbg__sidebar__download__thumbnail' alt='Thumbnail image for download' />";
				}

				$sidebarDescription = "";
				if ($sidebarDownloadDescription && $sidebarDownloadDescription != "") {
					$sidebarDescription = "<p class='bbg__sidebar__download__description'>" . $sidebarDownloadDescription . "</p>";
				}

				$sidebarDownload = "";
				$sidebarDownload = "<a href='" . $sidebarDownloadLink . "'>" . $sidebarImage . "</a><h5 class='bbg__sidebar__download__title'><a href='" . $sidebarDownloadLink . "'>" . $sidebarDownloadTitle . " ($ext, $filesize)</a></h5>" . $sidebarDescription;

				$s .= "<div class='bbg__sidebar__download'>" . $sidebarDownload . "</div>";
			} elseif (get_row_layout() == 'sidebar_quote'){

				$sidebarQuotationText = get_sub_field( 'sidebar_quotation_text', false);
				$sidebarQuotationSpeaker = get_sub_field( 'sidebar_quotation_speaker' );
				$sidebarQuotationSpeakerTitle = get_sub_field( 'sidebar_quotation_speaker_title' );

				$s .= '<div><h5>"' . $sidebarQuotationText . '"</h5><p>' . $sidebarQuotationSpeaker . ', ' . $sidebarQuotationSpeakerTitle ."</p></div>";
			} else if (get_row_layout() == 'sidebar_external_link'){

				$sidebarLinkTitle = get_sub_field( 'sidebar_link_title', false);
				$sidebarLinkLink = get_sub_field( 'sidebar_link_link' );
				$sidebarLinkDescription = get_sub_field( 'sidebar_link_description', false);

				$sidebarDescription = "";
				if ($sidebarLinkDescription && $sidebarLinkDescription != ""){
					$sidebarDescription = "<p class=''>" . $sidebarLinkDescription . "</p>";
				}

				$s .= '<div class=""><h5 class=""><a href="' . $sidebarLinkLink . '">' . $sidebarLinkTitle . '</a></h5>' . $sidebarDescription . '</div>';
			} else if (get_row_layout() == 'sidebar_internal_link') {

				$sidebarInternalTitle = get_sub_field( 'sidebar_internal_title', false);
				$sidebarInternalLocation = get_sub_field( 'sidebar_internal_location' );
				$sidebarInternalDescription = get_sub_field( 'sidebar_internal_description', false);

				// get data out of WP object
				$url = get_permalink( $sidebarInternalLocation->ID ); // Use WP object ID to get permalink for link
				$title = $sidebarInternalLocation->post_title; // WP object title

				$sidebarSectionTitle = "";
				// Set text for the internal link
				if ($sidebarInternalTitle && $sidebarInternalTitle != "") {
					// User-defined title
					$sidebarSectionTitle = "<p>" . $sidebarInternalTitle . "</p>";
				} else {
					// WP object title (set above)
					$sidebarSectionTitle = "<p>" . $title . "</p>";
				}

				$sidebarDescription = "";
				// Set text for description beneath link
				if ($sidebarInternalDescription && $sidebarInternalDescription != "") {
					// User-defined description
					$sidebarDescription = "<p>" . $sidebarInternalDescription . "</p>";
				}

				$s .= '<div class=""><h5 class=""><a href="' . $url . '">' . $sidebarSectionTitle . '</a></h5>' . $sidebarDescription . '</div>';
			} else if (get_row_layout() == 'sidebar_photo'){

				$sidebarPhotoImage = get_sub_field( 'sidebar_photo_image' );
				$sidebarPhotoTitle = get_sub_field( 'sidebar_photo_title', false);
				$sidebarPhotoCaption = get_sub_field( 'sidebar_photo_caption', false);

				$sidebarImage = "";
				if ($sidebarPhotoImage && $sidebarPhotoImage != ""){
					$sidebarPhotoImageSrc = $sidebarPhotoImage['sizes']['medium'];
					$sidebarImage = '<img class="" src="' . $sidebarPhotoImageSrc . '"/>';
				}
				/*
				helpful for debugging
				var_dump($sidebarPhotoImage);
				foreach ($sidebarPhotoImage as $key=>$value) {
					echo "$key -> $value<BR>";
					if ($key == 'sizes') {
						var_dump($value);
					}
				}
				var_dump($sidebarPhotoImage['sizes']);
				*/

				$sidebarImageTitle = "";
				if ($sidebarPhotoTitle && $sidebarPhotoTitle != ""){
					$sidebarImageTitle = "<h5 class=''>" . $sidebarPhotoTitle . "</h5>";
				}

				$sidebarDescription = "";
				if ($sidebarPhotoCaption && $sidebarPhotoCaption != ""){
					$sidebarDescription = "<p class=''>" . $sidebarPhotoCaption . "</p>";
				}

				$s .= '<div class="">' . $sidebarImage . $sidebarImageTitle . $sidebarDescription . '</div>';
			}
		endwhile;

		$sidebar .= $s;
	endif;
}

// Sidebar multiple downloads drop-down
$sidebarInclude = get_field( 'sidebar_downloads_include', '', true);
$sidebarDownloads = "";
if( $sidebarInclude ) {
	$downloadsTitle = get_field( 'sidebar_downloads_title' );
	$optionDefault = get_field ( 'sidebar_downloads_default' );
	$rows = get_field( 'sidebar_downloads' );
	if ( $rows ) {
		$s = '<form style="">';
		$s .= '<label for="options" style="display: inline-block; font-size: 2rem; font-weight: bold; margin-top: 0;">' . $downloadsTitle . '</label>';
		$s .= '<select name="file_download_list" id="file_download_list" style="display: inline-block;">';
		$s .= '<option>' . $optionDefault . '</option>';

		foreach( $rows as $row ) {
			$s .= '<option value="' . $row['sidebar_download_file'] .'">' . $row["sidebar_download_title"] . '</option>';
		}

		$s .= '</select>';
		$s .= '</form>';

		$s .= '<button class="usa-button" id="downloadFile" style="width: 100%;">Download</button>';
		$sidebarDownloads = $s;
	}
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
			$twitterURL = "//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
			$fbUrl = "//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );
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

					<?php echo '<header class="entry-header bbg__article-header' . $featuredImageClass . '">'; ?>

						<div class="bbg__event-title">

							<h5 class="entry-category bbg__label"><a href="/news/events/"><?php echo $eventPageHeader; ?></a>: <?php echo $postDate; ?></h5><!-- .bbg__label -->
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

						<!-- Speakers -->
						<?php
							// check if the flexible content field has rows of data
							if ( have_rows('board_meeting_speakers') ) {
								echo '<h3 class="bbg__sidebar-label">Speakers</h3>';
							     // loop through the rows of data
							    while ( have_rows('board_meeting_speakers') ) : the_row();
							    	// echo "<h5>we have speakers</h5>";

							        // show internal speaker list
							        if ( get_row_layout() == 'board_meeting_speakers_internal' ) {
							        	// echo "<p>we have internal speakers</p>";

						        		if ( get_sub_field('bbg_speaker_name') ) {
						        			// echo count( get_sub_field('bbg_speaker_name') );
						        			$profiles = get_sub_field('bbg_speaker_name');

					        				foreach ( $profiles as $profile ) {
					        					$pID = $profile->ID;
					        					// echo $pID;

					        					$profileID = get_post_meta( $pID );
												$includeProfile = false;

												if ( $profileID ) {
													$includeProfile = true;
													$profilePhotoID = get_post_meta( $pID, 'profile_photo', true );
													$profilePhoto = "";

													if ($profilePhotoID) {
														$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
														$profilePhoto = $profilePhoto[0];
													}

													$twitterProfileHandle = get_post_meta( $pID, 'twitter_handle', true );
													$profileName = get_the_title( $pID );
													$occupation = get_post_meta( $pID, 'occupation', true );
													$profileLink = get_page_link( $pID );
													// $profileExcerpt = get_the_excerpt( $pID );

													// $relatedProfile = '<div class="bbg__sidebar__primary">';
													// $relatedProfile = '<a href="' . $profileLink . '"><img class="bbg__sidebar__primary-image" src="'. $profilePhoto .'"/></a>';
													$relatedProfile = '<span class="bbg__profile-excerpt__occupation">' . $occupation . '</span>';
													$relatedProfile .= '<h3 class="bbg__sidebar__primary-headline"><a href="' . $profileLink . '">' . $profileName . '</a></h3>';

													// $relatedProfile .= '<p class="">' . $profileExcerpt . '</p></div>';
												}

												if ($includeProfile) {
													echo $relatedProfile;
												}

					        				}
						        		}
							        }
							    endwhile;
							}
						?>

						<!-- Related documents -->
						<?php
							if ( have_rows('board_meeting_related_documents') ):
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

						<!-- Additional sidebar content -->
						<?php
							echo "<!-- Sidebar content -->";
							if ( $includeSidebar && $sidebarTitle != "" ) {
								echo $sidebar;
							}

							if ( $secondaryColumnContent != "" ) {
								echo $secondaryColumnContent;
							}

							echo $sidebarDownloads;
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

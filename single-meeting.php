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
	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));
	$ogTitle = get_the_title();
	$ogDescription = get_the_excerpt();

	$eventPageHeader = "Event";
	$isBoardMeeting = false;
	$isPressRelease = false;
	if (in_category("Board Meetings")) {
		$eventPageHeader = "Board Meeting";
		$isBoardMeeting = true;
	}
	if (in_category("Press Release")) {
		$isPressRelease = true;
	}

	/**** CREATE OG:IMAGE *****/
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];
	$socialImageID = get_post_meta( $post->ID, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}

	/**** CREATE $bannerAdjustStr *****/
	$bannerPosition = get_post_meta( get_the_ID(), 'adjust_the_banner_image', true);
	$bannerPositionCSS = get_field( 'adjust_the_banner_image_css', '', true);
	$bannerAdjustStr = "";
	if ($bannerPositionCSS) {
		$bannerAdjustStr = $bannerPositionCSS;
	} else if ($bannerPosition) {
		$bannerAdjustStr = $bannerPosition;
	}

	$meetingTime = get_post_meta( get_the_ID(), 'board_meeting_time', true );

	$today = new DateTime("now", new DateTimeZone('America/New_York'));
	$todayStr = $today->format('Y-m-d H:i:s');

	$meetingRegistrationCloseTime = get_post_meta( get_the_ID(), 'board_meeting_registration_close_time', true );
	$commentFormCloseTime = get_post_meta( get_the_ID(), 'board_meeting_comment_form_close_time', true );

	$registrationIsClosed = false;
	if ($meetingRegistrationCloseTime) {
		$registrationIsClosed = ($meetingRegistrationCloseTime <  $todayStr);

		//get a display friendly version of this date for later
		$meetingRegistrationCloseDateObj = DateTime::createFromFormat('Y-m-d H:i:s', $meetingRegistrationCloseTime);
		$meetingRegistrationCloseDateStr = $meetingRegistrationCloseDateObj->format("F j, Y");
	}

	$commentFormIsClosed = false;
	if ($commentFormCloseTime) {
		$commentFormIsClosed = ($commentFormCloseTime <  $todayStr);
		$commentFormCloseDateObj = DateTime::createFromFormat('Y-m-d H:i:s', $commentFormCloseTime);

		//get a display friendly version of this date for later
		$commentFormCloseStr = $commentFormCloseDateObj->format("F j, Y");
	}

	$meetingLocation = get_post_meta( get_the_ID(), 'board_meeting_location', true );
	$meetingSummary = get_post_meta( get_the_ID(), 'board_meeting_summary', true );
	$meetingContactTagline = get_post_meta( get_the_ID(), 'board_meeting_contact_tagline', true );
	if (!$meetingContactTagline || $meetingContactTagline == "") {
		$meetingContactTagline = "For more information, please contact BBG Public Affairs at (202) 203-4400 or by e-mail at pubaff@bbg.gov.";
	}
	if ( $meetingTime != "") {
		$meetingTime = $meetingTime . ", ";
	}
	$meetingSpeakers = get_post_meta( get_the_ID(), 'board_meeting_speakers', true );

	/*** CREATE EVENTBRITE IFRAME ****/
	$eventBriteButtonStr = "";
	$eventbriteUrl = get_post_meta( get_the_ID(), 'board_meeting_eventbrite_url', true );
	if ($eventbriteUrl && $eventbriteUrl != "" && !$isPressRelease) {
		if (!$registrationIsClosed) {
			$eventBriteButtonStr = "<a target='_blank' class='usa-button style='color:white;text-decoration:none;' href='" . $eventbriteUrl . "'>Register for this Event</a>";
		} else {
			$eventBriteButtonStr = "<p style='font-style:italic;' class='registrationClosed'>Registration for this event has closed.</p>";
		}
	}

	rewind_posts();
}

//Add shared sidebar
include get_template_directory() . "/inc/shared_sidebar.php";

//Add featured video
$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );

// Add support for sidebar dropdown
$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

include get_template_directory() . "/inc/shared_sidebar.php";

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php

			$projectCategoryID = get_cat_id('Project');
			$isProject = has_category($projectCategoryID);

			//Default adds a space above header if there's no image set
			$featuredImageClass = " bbg__article--no-featured-image";

			//the title/headline field, followed by the URL and the author's twitter handle
			$twitterText = "";
			$twitterText .= html_entity_decode( get_the_title() );
			$twitterText .= " by @bbggov " . get_permalink();
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

						if (isset($_GET['success'])) {
							echo '<div class="usa-alert usa-alert-success">
									<div class="usa-alert-body">
									<h3 class="usa-alert-heading">Submission Successful</h3>
									<p class="usa-alert-text">Your comment was successfully submitted.</p>
									</div>
									</div><BR>';
						}

						 the_content();
						 ?>
						 <?php
						 	if (!$isPressRelease && $isBoardMeeting && !isset($_GET['success'])):
						 		if ($commentFormIsClosed):
						 			echo "<p>The deadline for public comments for this meeting has passed.</p>";
						 		else:
						 ?>
									<h3>Public Comments Form</h3>
									<p>Public comments related to U.S. international media are now being accepted for review by the board. Comments intended for the <?php echo $postDate; ?> meeting of the board must be submitted by <b><?php echo $commentFormCloseStr; ?></b>.</p>
									<p>Comments received after that date will be forwarded to the board for the following meeting.</p>

									<p>The public comments you provide to the Broadcasting Board of Governors are collected by the agency voluntarily and may be publicly disclosed on the Internet and/or via requests submitted to the BBG under the Freedom of Information Act.</p>

									<p>By providing public comments, you are consenting to their use and consideration by the Board and to their possible public dissemination. Personal contact information will not be made available to the public and will only be used by agency staff to engage with submitters regarding their own comments.</p>
						<?php
									$redirectLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

									if(strpos($redirectLink, "?" )) {
										$redirectLink .= "&";
									} else {
										$redirectLink .= "?";
									}
									$redirectLink .= "success=true";
									echo do_shortcode("[si-contact-form form='2' redirect='$redirectLink']");
									echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/js/meeting-comment-form.js"></script>';
								endif;
							endif;

							//echo "<pre>"; var_dump($_POST); echo "</pre>";
						?>

					</div><!-- .entry-content -->

					<div class="bbg__article-sidebar">
						<h5>WHEN: <?php echo $meetingTime; ?><br/><?php echo $postDate; ?></h5>
						<h5>WHERE: <?php echo $meetingLocation; ?></h5>

						<?php echo $eventBriteButtonStr; ?>

						<p class="bbg-tagline bbg-tagline--main"><?php echo $meetingContactTagline; ?></p>

						<!-- Speakers -->
						<?php
							// check if the flexible content field has rows of data
							if ( have_rows('board_meeting_speakers') ) {
								$speakersLabel = get_field('board_meeting_speaker_label');

								echo '<h3 class="bbg__sidebar-label">' . $speakersLabel . '</h3>';
							     // loop through the rows of data
							    while ( have_rows('board_meeting_speakers') ) : the_row();

							        // show internal speaker list
							        if ( get_row_layout() == 'board_meeting_speakers_internal' ) {

						        		if ( get_sub_field('bbg_speaker_name') ) {
						        			$profiles = get_sub_field('bbg_speaker_name');

					        				echo "<ul class='usa-unstyled-list'>";
					        				foreach ( $profiles as $profile ) {
					        					$pID = $profile->ID;
					        					$profileID = get_post_meta( $pID );
												$includeProfile = false;

												if ( $profileID ) {
													$includeProfile = true;
													/*$profilePhotoID = get_post_meta( $pID, 'profile_photo', true );
													$profilePhoto = "";

													if ($profilePhotoID) {
														$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
														$profilePhoto = $profilePhoto[0];
													}*/ // HIDING PHOTO

													$twitterProfileHandle = get_post_meta( $pID, 'twitter_handle', true );
													$profileName = get_the_title( $pID );
													$occupation = get_post_meta( $pID, 'occupation', true );
													$profileLink = get_page_link( $pID );
													// $profileExcerpt = get_the_excerpt( $pID ); // HIDING EXCERPT

													// $relatedProfile = '<a href="' . $profileLink . '"><img class="bbg__sidebar__primary-image" src="'. $profilePhoto .'"/></a>'; // HIDING PHOTO
													$relatedProfile = '<li>';
														$relatedProfile .= '<h5 class="bbg__sidebar__primary-headline"><a href="' . $profileLink . '">' . $profileName . '</a></h5>';
														$relatedProfile .= '<span class="bbg__profile-excerpt__occupation">' . $occupation . '</span>';
													$relatedProfile .= '</li>';
													// $relatedProfile .= '<p class="">' . $profileExcerpt . '</p>'; // HIDING EXCERPT
												}

												if ($includeProfile) {
													echo $relatedProfile;
												}

					        				}
					        				echo "</ul>";
						        		// end internal speaker list
						        		}
							        } else if ( get_row_layout() == 'board_meeting_speakers_external' ) {
							        // show external speaker list
						        		if ( get_sub_field('meeting_speaker') ) {
						        			$profiles = get_sub_field('meeting_speaker');

					        				echo "<ul class='usa-unstyled-list'>";
					        				foreach ( $profiles as $profile ) {
					        					$speakerName = $profile["meeting_speaker_name"];
					        					$speakerTitle = $profile["meeting_speaker_title"];
					        					$speakerLink = $profile["meeting_speaker_url"];

												if ( $speakerName && $speakerLink != "" ) {
													$externalSpeaker = '<li>';
														$externalSpeaker .= '<h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name"><a href="' . $speakerLink . '">' . $speakerName . '</a></h5>';
														$externalSpeaker .= '<span class="bbg__profile-excerpt__occupation">' . $speakerTitle . '</span>';
													$externalSpeaker .= '</li>';
												} else {
													$externalSpeaker = '<li>';
														$externalSpeaker .= '<h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name">' . $speakerName . '</h5>';
														$externalSpeaker .= '<span class="bbg__profile-excerpt__occupation">' . $speakerTitle . '</span>';
													$externalSpeaker .= '</li>';
												}

												echo $externalSpeaker;

					        				}
					        				echo "</ul>";
						        		}
							        }
							    endwhile;
							}
						?>

						<!-- Related documents -->
						<?php
							if ( have_rows('board_meeting_related_documents') ):
							 	echo '<h3 class="bbg__sidebar-label">Meeting documents</h3>';
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
							if ( $includeSidebar) {
								echo $sidebar;
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

<?php
/**
 * Custom landing page for the "Who we are" and "Our Work" sections
 *
 * template name: About
 *
 * @author Gigi Frias <gfrias@bbg.gov>
 * @package bbgRedesign
 */

/* @Check if number of pages is odd or even
*  Return BOOL (true/false) */
function isOdd( $pageTotal ) {
	return ( $pageTotal % 2 ) ? TRUE : FALSE;
}
function showUmbrellaArea($atts) {
	
	$itemTitle = $atts['itemTitle'];
	$columnTitle = $atts['columnTitle'];
	$link = $atts['link'];
	$gridClass = $atts['gridClass'];
	$description = $atts['description'];
	$forceContentLabels = $atts['forceContentLabels'];
	$thumbPosition = "center center";
	$thumbSrc = $atts['thumbSrc'];
	$columnType = $atts['columnType'];
	$anchorTarget = "";
	$layout = $atts['layout'];
	$linkSuffix = "";
	if ($columnType == "file") {
		$fileSize = $atts['fileSize'];
		$fileExt = $atts['fileExt'];
		$linkSuffix = ' <span class="bbg__file-size">(' . $fileExt . ', ' . $fileSize . ')</span>';
	
	}
	if ($columnType == "external" || $columnType == "file") {
		$anchorTarget = " target='_blank' ";
	}

	if ( $layout == 'full' ) {
		// Output variables
		echo '<article class="' . $gridClass . ' bbg__about__grandchild bbg__about__child">';
		if ($columnTitle == "") {
			if ($forceContentLabels) {
				echo '<h6 class="bbg__label">&nbsp;</h6>';	
			}
		} else {
			if ($link != "") {
				$columnTitle = '<a ' . $anchorTarget . ' href="' . $link . '">' . $columnTitle . '</a>';
			}
			echo '<h6 class="bbg__label">' . $columnTitle . '</h6>';	
		}
		
		if ($thumbSrc) {
			echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--medium">'; 
			//echo '<a target="_blank" href="' . $link . '" rel="bookmark" tabindex="-1"><img width="1040" height="624" ' . ar_responsive_image($thumbnailID,'medium-thumb',1200) . 'class="attachment-large-thumb size-large-thumb"></a>';
			echo '<a ' . $anchorTarget . ' href="' . $link . '" rel="bookmark" tabindex="-1"><img width="1040" height="624" src="' . $thumbSrc .  '" class="attachment-large-thumb size-large-thumb"></a>';
			echo '</div>';	
		}
		
		echo '<h3 class="bbg__about__grandchild__title"><a ' . $anchorTarget . ' href="' . $link . '">' . $itemTitle . '</a>'  . $linkSuffix . '</h3>';
		echo $description; // Output page excerpt
		echo '</article>';
	} else {

		echo '<article class="' . $gridClass . ' bbg__about__grandchild">';
		$columnTitle = $itemTitle;
		if ($link != "") {
			$columnTitle = '<a '  . $anchorTarget . ' href="' . $link . '">' . $columnTitle . '</a>';
		}
		$columnTitle = $columnTitle . $linkSuffix;
		echo '<h3 class="bbg__about__grandchild__title">' . $columnTitle . '</h3>';	
		echo '<a '  . $anchorTarget . ' href="' . $link . '">';
		echo '<div class="bbg__about__grandchild__thumb" style="background-image: url(' . $thumbSrc . '); background-position:center center;"></div></a>' . $description;
		echo '</article>';
	}
}

$templateName = "about";

$bannerPosition = get_field( 'adjust_the_banner_image', '', true);
$videoUrl = get_field( 'featured_video_url', '', true );
$addFeaturedGallery = get_post_meta( get_the_ID(), 'featured_gallery_add', true );

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();


get_header();

?>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">
				<header class="page-header">
					<?php the_title( '<h5 class="bbg__label--mobile large">', '</h5>' ); ?>
				</header><!-- .page-header -->
			</div>

			<div class="usa-grid-full">
				<?php
					if ( $addFeaturedGallery ) {
						echo "<div class='usa-grid-full bbg__article-featured__gallery'>";
							$featuredGalleryID = get_post_meta( get_the_ID(), 'featured_gallery_id', true );
							putUniteGallery($featuredGalleryID);
						echo "</div>";
					}
				?>
			</div>

			<?php
				$hideFeaturedImage = FALSE;

				if ( $addFeaturedGallery ) {
					$hideFeaturedImage = true; // Hide featured image if there's a gallery
				}

				if ( $videoUrl != "" ) {
					echo featured_video( $videoUrl );
					$hideFeaturedImage = TRUE; // Hide featured image if there's a video
				} elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
					echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline = "";
						$thumbnail_image = get_posts( array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment') );

						if ( $thumbnail_image && isset($thumbnail_image[0]) ) {
							$featuredImageCutline = $thumbnail_image[0] -> post_excerpt;
						}

						$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

						// Output featured image
						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner" style="background-image: url(' . $src[0] . '); background-position: ' . $bannerPosition . '"></div>';

						// Output caption for featured image
						if ($featuredImageCutline != "") {
							echo '<div class="usa-grid">';
								echo '<div class="bbg__article-header__caption">' . $featuredImageCutline . '</div>';
							echo '</div><!-- usa-grid -->';
						}
					echo '</div><!-- usa-grid-full -->';
				}
			?><!-- .bbg__article-header__thumbnail -->

			<!-- Page introduction (content) -->
			<section id="page-intro" class="usa-section usa-grid bbg__about__intro">
				<?php echo $pageContent; ?>
			</section>

			<!-- Child pages -->
			<div id="page-children" class="usa-section usa-grid bbg__about__children">
			<?php
				// check if the flexible content field has rows of data
				if ( have_rows( 'about_flexible_page_rows' ) ):
					$counter = 0;
					$pageTotal = 1;
					$containerClass = 'bbg__about__child ';

					while ( have_rows('about_flexible_page_rows') ) : the_row();
						$counter++;

						if ( get_row_layout() != 'about_ribbon_page' ) { // Check if row is a ribbon
							echo '<!-- ROW ' . $counter . '-->'; // Add row counter
							echo '<section class="usa-grid-full bbg__about__children--row">'; // Open row
						} else {
							echo '<!-- ROW ' . $counter . '-->'; // Add row counter
							echo '<section class="usa-grid-full bbg__about__children--row bbg__ribbon--thin">'; // Open row and add ribbon class
						}

						if ( get_row_layout() == 'marquee'):

							$marqueeHeading = get_sub_field('marquee_heading');
							$marqueeLink = get_sub_field('marquee_link');
							$marqueeContent = get_sub_field('marquee_content');
							$marqueeContent = apply_filters( 'the_content', $marqueeContent );
							$marqueeContent = str_replace( ']]>', ']]&gt;', $marqueeContent ); 

							echo '<section class="usa-grid-full bbg__about__children--row bbg__about--marquee">';
							echo '<article id="post-25948" class="bbg__about__excerpt bbg__about__child bbg__about__child--mission bbg-grid--1-1-1 post-25948 page type-page status-publish has-post-thumbnail hentry">';
								echo '<header class="entry-header bbg__about__excerpt-header"><h6 class="bbg__label"><a href="' . $marqueeLink . '">' . $marqueeHeading . '</a></h6></header>';
								echo '<div class="entry-content bbg__about__excerpt-content">' . $marqueeContent . '</div>';
							echo '</article>';
							echo '</section>';

						
						elseif ( get_row_layout() == 'umbrella' ): 
							/*** BEGIN DISPLAY OF ENTIRE UMBRELLA ROW ***/
							
							$sectionHeading = get_sub_field('umbrella_section_heading');
							$sectionHeadingLink = get_sub_field('umbrella_section_heading_link');
							$forceContentLabels = get_sub_field('umbrella_force_content_labels');
							$sectionIntroText = get_sub_field('umbrella_section_intro_text');
							$sectionIntroText = apply_filters( 'the_content', $sectionIntroText );
							$sectionIntroText = str_replace( ']]>', ']]&gt;', $sectionIntroText );


							//output intro section label if it exists (with or without link)
							if ($sectionHeading != "") {
								if ( $sectionHeadingLink ) { // if the label has a URL add link and right arrow
									$sectionHeading = '<a href="' . $sectionHeadingLink . '">' . $sectionHeading . '</a> <span class="bbg__links--right-angle-quote" aria-hidden="true">&raquo;</span>';
								} 
								echo '<h6 class="bbg__label">' . $sectionHeading . '</h6>';	
							} 
							
							//output intro section text if it exists
							if ($sectionIntroText != "") {
								if ( $sectionIntroText ) { 
									echo '<div class="bbg__about__child__intro">' . $sectionIntroText . '</div>';
								}
							}

							$numRows = count(get_sub_field('umbrella_content'));
							$containerClass = 'bbg-grid--1-2-2';
							if ( isOdd($numRows)) {
								$containerClass = 'bbg-grid--1-3-3'; // add 3-col grid class
							}

							echo '<div class="usa-grid-full bbg__about__grandchildren">'; // open grandchildren container
							while ( have_rows('umbrella_content') ) : the_row();
								if (get_row_layout() == 'umbrella_content_external') {
									$thumbnail = get_sub_field('umbrella_content_external_thumbnail');
									$thumbnailID = $thumbnail['ID'];
									$thumbSrc = wp_get_attachment_image_src( $thumbnailID , 'medium-thumb' );
									if ($thumbSrc) {
										//$thumbSrc = 'src="' . $thumbSrc[0] . '"';	
										$thumbSrc = $thumbSrc[0];
									} 
									showUmbrellaArea(array(
										'columnTitle' => get_sub_field('umbrella_content_external_column_title'),
										'itemTitle' => get_sub_field('umbrella_content_external_item_title'),
										'description' => get_sub_field('umbrella_content_external_description'),
										'link' => get_sub_field('umbrella_content_external_link'),
										'thumbSrc' => $thumbSrc,
										'gridClass' => $containerClass,
										'forceContentLabels' => $forceContentLabels,
										'columnType' => 'external',
										'layout' => get_sub_field('umbrella_content_external_layout')
									));
								} elseif (get_row_layout() == 'umbrella_content_internal') {

									$pageObj = get_sub_field('umbrella_content_internal_link');
									$id = $pageObj[0]->ID;
									$link = get_the_permalink($id);
									$title = "";
									$includeTitle = get_sub_field('umbrella_content_internal_include_item_title');
									$titleOverride = get_sub_field('umbrella_content_internal_title');
									$secondaryHeadline = get_post_meta( $id, 'headline', true );
 
									if ( $includeTitle ) {
										$titleOverride = get_sub_field('umbrella_content_internal_item_title');
										if ($titleOverride != "" ) {
											$title = $titleOverride;
										} else {
											if ($secondaryHeadline) {
												$title = $secondaryHeadline;	
											} else {
												$title = $pageObj[0]->post_title;	
											}
										}
									}
									
									$showFeaturedImage = get_sub_field('umbrella_content_internal_include_featured_image');
									$thumbSrc = "";
									if ($showFeaturedImage) {
										$thumbSrc = wp_get_attachment_image_src( get_post_thumbnail_id($id) , 'medium-thumb' );
										if ($thumbSrc) {
											//$thumbSrc = 'src="' . $thumbSrc[0] . '"';	
											$thumbSrc = $thumbSrc[0];
										}
									}
									
									$showExcerpt = get_sub_field('umbrella_content_internal_include_excerpt');
									$description = "";
									if ($showExcerpt) {
										$description = my_excerpt( $id );
										//$description = $description . '<a href="' . $link . '" class="bbg__about__grandchild__link">Read more »</a>';
										$description = apply_filters( 'the_content', $description );
										$description = str_replace( ']]>', ']]&gt;', $description );

									}

									showUmbrellaArea(array(
										'columnTitle' => get_sub_field('umbrella_content_internal_column_title'),
										'itemTitle' => $title,
										'description' => $description,
										'link' => $link, 
										'thumbSrc' => $thumbSrc,
										'gridClass' => $containerClass,
										'forceContentLabels' => $forceContentLabels,
										'columnType' => 'internal',
										'layout' => get_sub_field('umbrella_content_internal_layout')
									));

								} elseif (get_row_layout() == 'umbrella_content_file') {

									$fileObj = get_sub_field('umbrella_content_file_file');
									$description = get_sub_field('umbrella_content_file_description');
									$layout = get_sub_field('umbrella_content_file_layout');

									$thumbnail = get_sub_field('umbrella_content_file_thumbnail');
									$thumbnailID = $thumbnail['ID'];
									$thumbSrc = wp_get_attachment_image_src( $thumbnailID , 'medium-thumb' );
									if ($thumbSrc) {
										//$thumbSrc = 'src="' . $thumbSrc[0] . '"';	
										$thumbSrc = $thumbSrc[0];
									}
									
									$description = get_sub_field('umbrella_content_file_description');
									$description = apply_filters( 'the_content', $description );
									$description = str_replace( ']]>', ']]&gt;', $description );
									
									$fileTitle = get_sub_field('umbrella_content_file_item_title');
									//parse information about the file so we can append file sizeto append to our file title
									$fileID = $fileObj['ID'];
									$fileURL = $fileObj['url'];
									$file = get_attached_file( $fileID );
									$fileExt = strtoupper( pathinfo( $file, PATHINFO_EXTENSION ) ); // set extension to uppercase
									$fileSize = formatBytes( filesize( $file ) ); // file size
									
									// if ($layout == 'full') {
									// 	$fileTitle = $fileTitle . ' <span class="bbg__file-size">(' . $fileExt . ', ' . $fileSize . ')</span>';
									// } else {
									// 	$description = $description . ' <span class="bbg__file-size">(' . $fileExt . ', ' . $fileSize . ')</span>';	
									// }
									//$fileTitle = $fileTitle . ' <span class="bbg__file-size">(' . $fileExt . ', ' . $fileSize . ')</span>';
 
									showUmbrellaArea(array(
										'columnTitle' => get_sub_field('umbrella_content_file_column_title'),
										'itemTitle' => $fileTitle,
										'description' => $description,
										'link' => $fileURL, 
										'thumbSrc' => $thumbSrc,
										'gridClass' => $containerClass,
										'forceContentLabels' => $forceContentLabels,
										'columnType' => 'file',
										'layout' => $layout,
										'fileExt' => $fileExt,
										'fileSize' => $fileSize
									));
								}
							endwhile;
							echo '</div>';
							echo '</section>'; // close row
						/*** END DISPLAY OF ENTIRE UMBRELLA ROW ***/

						elseif( get_row_layout() == 'about_ribbon_page' ):
						/*** BEGIN DISPLAY OF ENTIRE RIBBON ROW ***/
							// Set variables
							$labelText = get_sub_field( 'about_ribbon_label' );
							$labelLink = get_sub_field( 'about_ribbon_label_link' );
							$headlineText = get_sub_field( 'about_ribbon_headline' );
							$headlineLink = get_sub_field( 'about_ribbon_headline_link' );
							$summary = get_sub_field( 'about_ribbon_summary' );
							$imageURL = get_sub_field( 'about_ribbon_image' );

							// allow shortcodes in intro text
							$summary = apply_filters( 'the_content', $summary );
							$summary = str_replace( ']]>', ']]&gt;', $summary );

							echo '<div class="usa-grid">';
								echo '<div class="bbg__announcement__flexbox" name="' . $labelText . '">'; // open ribbon container and set div name to $labelText

									if ( $imageURL ) { // Output image thumbnail if set
										echo '<div class="bbg__announcement__photo" style="background-image: url(' . $imageURL . ');"></div>';
									}

									echo '<div>'; // Open ribbon text container
										if ( $labelLink ) { // Output label with link if set
											echo '<h6 class="bbg__label"><a href="' . get_permalink($labelLink) . '">' . $labelText . '</a></h6>';
										} else { // Else output link only
											echo '<h6 class="bbg__label">' . $labelText . '</h6>';
										}

										if ( $headlineLink ) { // Output headline with link if set
											echo '<h2 class="bbg__announcement__headline"><a href="' . get_permalink($headlineLink) . '">' . $headlineText . '</a></h2>';
										} else { // Else output headline only
											echo '<h2 class="bbg__announcement__headline">' . $headlineText . '</h2>';
										}

										echo $summary;
									echo '</div>'; // close ribbon text container
								echo '</div><!-- .bbg__announcement__flexbox -->'; // close ribbon container
							echo '</div><!-- .usa-grid -->';
						echo '</section>'; // close row
						/*** END DISPLAY OF ENTIRE RIBBON ROW ***/

						elseif ( get_row_layout() == 'about_office' ):
						/*** BEGIN DISPLAY OF OFFICE ROW ***/
							$officeTag = get_sub_field( 'office_tag' );
							$officeTagBoolean = get_sub_field('office_tags_boolean_operator');
							$officeTitle = get_sub_field( 'office_title' );
							$officeEmail = get_sub_field( 'office_email' );
							$officeEmail = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $officeEmail . '" title="Contact us">' . $officeEmail . '</a></li>';
							$officePhone = get_sub_field( 'office_phone' );
							$officePhone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel:' . $officePhone . '">' . $officePhone . '</a></li>';
							$officeFacebook = get_sub_field( 'office_facebook' );
							$officeTwitter = get_sub_field( 'office_twitter' );
							$officeYoutube = get_sub_field( 'office_youtube' );
							$officeEvent = false;
							$postIDsUsed = [];

							// set upcoming events query parameters
							$qParamsUpcoming = array(
								'post_type' => array( 'post' )
								,'cat' => get_cat_id( 'Event' )
								,'post_status' => array( 'future' )
								,'order' => 'ASC'
								,'posts_per_page' => 1
							);
							$tagIDs = array();
							foreach($officeTag as $term) {
								$tagIDs []= $term->term_id;
							}
							if (count($officeTag)) {
								if ($officeTagBoolean == "AND") {
									$qParamsUpcoming['tag__and'] = $tagIDs;
								} else {
									$qParamsUpcoming['tag__in'] = $tagIDs;
								}
							}

							// execute upcoming events query
							$future_events_query = new WP_Query( $qParamsUpcoming );
							$eventDetail = [];

							// if upcoming events query has posts
							if ( $future_events_query -> have_posts() ) {
								$officeEvent = true; // set events variable to true

								// Loop through all event posts
								while ( $future_events_query -> have_posts() ) {
									$future_events_query -> the_post();
									// set variables from post array
									$id = get_the_ID();
									$eventDetail['url'] = get_the_permalink();
									$eventDetail['title'] = get_the_title();
									$eventDetail['thumb'] = get_the_post_thumbnail( $id, 'medium-thumb' );
									$eventDetail['excerpt'] = my_excerpt( $id );
									$eventDetail['id'] = $id;
									$postIDsUsed[] = $id;
								}
							}
							$maxPosts = 4; // set max number of events

							// define office query parameters
							$qParamsOffice = array(
								'post_type' => array( 'post' ),
								'posts_per_page' => $maxPosts,
								'orderby' => 'date',
								'order' => 'DESC',
								'post__not_in' => $postIDsUsed
							);
							if (count($officeTag)) {
								if ($officeTagBoolean == "AND") {
									$qParamsOffice['tag__and'] = $tagIDs;
								} else {
									$qParamsOffice['tag__in'] = $tagIDs;
								}
							}

							// set address variables
							$street = get_field( 'agency_street', 'options', 'false' );
							$city = get_field( 'agency_city', 'options', 'false' );
							$state = get_field( 'agency_state', 'options', 'false' );
							$zip = get_field( 'agency_zip', 'options', 'false' );
							$address = ""; // create full address variable

							// If all the address variables exists
							if ( $street != "" && $city != "" && $state != "" && $zip != "" ) {
								// concatenate all address variables
								$address = $street . '<br/>' . $city . ', ' . $state . ' ' . $zip;

								// Strip spaces for url-encoding.
								$street = str_replace( " ", "+", $street );
								$city = str_replace( " ", "+", $city );
								$state = str_replace( " ", "+", $state );
								$mapLink = 'https://www.google.com/maps/place/' . $street . ',+' . $city . ',+' . $state . '+' . $zip . '/';

								// set full address variable
								$address = '<p itemprop="address" aria-label="address"><a href="'. $mapLink . '">' . $address . '</a></p>';
							}
							$tagLink = get_tag_link( $officeTag[0] -> term_id );
			// temporarily close PHP ?>
							<style>
								.bbg-blog__officeEvent-label { margin-top:15px !important; }
							</style>

							<article class="bbg__article bbg__kits__section">
								<div class="usa-grid-full">
									<?php if ( $officeEvent ): ; // if there are events ?>
										<section class="usa-section">
											<div class="usa-alert usa-alert-info">
											    <div class="usa-alert-body">
											      <h3 class="usa-alert-heading"><?php echo '<a href="' . $eventDetail['url'] . '">' . $eventDetail['title'] . '</a>'; ?></h3>
											      <p class="usa-alert-text"><?php echo $eventDetail['excerpt']; ?></p>
											    </div>
											</div>
										</section>
									<?php endif; ?>

									<div class="entry-content bbg__article-content large">
										<!-- Highlights section -->
										<section id="recent-posts" class="usa-section bbg__home__recent-posts">
											<h2>Recent Highlights</h2>

											<div class="bbg__kits__recent-posts">
												<div class="usa-width-one-half bbg__secondary-stories">
													<?php
														/* BEWARE: sticky posts add a record */
														/**** START FETCH related highlights ****/

														// Run press releases query
														query_posts( $qParamsOffice );

														if ( have_posts() ) {
															$counter = 0;
															$includeImage = TRUE;

															while ( have_posts() ) : the_post();
																$counter++;
																$includeMeta = false;
																$gridClass = 'bbg-grid--full-width';
																$includeExcerpt = false;

																if ( $counter > 1 ) {
																	$includeImage = false;
																	$includeMeta = false;
																	if ( $counter == 2 ) {
																		echo '</div><div class="usa-width-one-half tertiary-stories">';
																	}
																}
																if ( $counter == 1 ) {
																	$includePortfolioDescription = false;
																	get_template_part( 'template-parts/content-portfolio', get_post_format() );
																} else {
																	get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
																}
															endwhile;

															echo '<br/><a href="' . $tagLink . '" class="bbg__kits__intro__more--link">View all highlights »</a>';
														}
														wp_reset_query();
													?>
												</div>
											</div>
										</section><!-- .BBG News -->
									</div>
									<!-- Contact card (tailored to audience) -->
									<div class="bbg__article-sidebar large">
										<aside>
											<div class="bbg__contact-card">
												<div class="bbg__contact-card__text">
													<?php
														echo '<h3>' . $officeTitle . '</h3>';
														echo $address;
														echo '<ul class="usa-unstyled-list">';
															echo $officePhone;
															echo $officeEmail;
														echo '</ul>';

														echo '<!-- Social media profiles -->';
														echo '<div class="bbg__kits__social">';
															$officeFacebook = get_sub_field( 'office_facebook' );
															$officeTwitter = get_sub_field( 'office_twitter' );
															$officeYoutube = get_sub_field( 'office_youtube' );

															if ( $officeFacebook ) {
																echo '<a class="bbg__kits__social-link usa-link-facebook" href="' . $officeFacebook . '" role="img" aria-label="facebook"></a>';
															}
															if ( $officeTwitter ) {
																echo '<a class="bbg__kits__social-link usa-link-twitter" href="' . $officeTwitter . '" role="img" aria-label="twitter"></a>';
															}
															if ( $officeYoutube ) {
																echo '<a class="bbg__kits__social-link usa-link-youtube" href="' . $officeYoutube . '" role="img" aria-label="youtube"></a>';
															}
														echo '</div>';

													?>
												</div>
											</div>
										</aside>
									</div>
								</div>
							</article>
						<?php // reopen PHP
						echo '</section>'; // close row
						/*** END DISPLAY OF OFFICE ROW ***/
						endif;
					endwhile;
					echo '<!-- END ROWS -->';
				endif;
			?>
			</div> <!-- End id="page-children" -->

			<?php
				$showNetworks = get_field( 'about_networks_row' );
				if ( $showNetworks ) { ?>

				<!-- Entity list -->
				<section id="entities" class="usa-section bbg__staff">
					<div class="usa-grid">
						<h6 class="bbg__label"><a href="<?php echo get_permalink( get_page_by_path( 'networks' ) ); ?>" title="List of all BBG broadcasters">Our networks</a></h6>
						<div class="usa-intro bbg__broadcasters__intro">
							<h3 class="usa-font-lead">Every week, more than <?php echo do_shortcode('[audience]'); ?> listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters.</h3>
						</div>
						<?php echo outputBroadcasters('2'); ?>
					</div>
				</section><!-- entity list -->
			<?php
				}
			wp_reset_postdata();
			?>

		</main>
	</div><!-- #primary .content-area -->
</div><!-- #main .site-main -->

<?php get_footer(); ?>

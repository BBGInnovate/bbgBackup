<?php
/**
 * Custom landing page for the "Who we are" and "Our Work" sections
 *
 * template name: About
 *
 * @author Gigi Frias <gfrias@bbg.gov>
 * @package bbgRedesign
 */

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
								echo '<div class="bbg__article-header__caption">$featuredImageCutline</div>';
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

					/* @Check if number of pages is odd or even
					*  Return BOOL (true/false) */
					function checkNum( $pageTotal ) {
						return ( $pageTotal % 2 ) ? TRUE : FALSE;
					}

					while ( have_rows('about_flexible_page_rows') ) : the_row();
						$counter++;

						if ( get_row_layout() != 'about_ribbon_page' ) { // Check if row is a ribbon
							echo '<!-- ROW ' . $counter . '-->'; // Add row counter
							echo '<section class="usa-grid-full bbg__about__children--row">'; // Open row
						} else {
							echo '<!-- ROW ' . $counter . '-->'; // Add row counter
							echo '<section class="usa-grid-full bbg__about__children--row bbg__ribbon--thin">'; // Open row and add ribbon class
						}

						if ( get_row_layout() == 'about_multicolumn' ):

							/*** BEGIN DISPLAY OF ENTIRE MULTICOLUMN ROW ***/
							$relatedPages = get_sub_field( 'about_muliticolumn_related_pages' );
							$pageTotal = count ( $relatedPages ); // count total number of pages
							$largeNumber = FALSE;

							if ( $pageTotal == 5 ) { // if number of pages is 5
								$five = TRUE; // set variable to true
							} else {
								$five = FALSE; // else set to false
							}

							foreach ( $relatedPages as $rPage ) { // loop through each field in the $relatedPages array
								// Set variables
								$rPageHeadline = $rPage -> headline;
								$rPageTagline = $rPage -> page_tagline;
								$rPageDescription = $rPage -> about_include_exerpt;
								$rPageExcerpt = my_excerpt( $rPage -> ID );
								$rPageExcerpt = apply_filters( 'the_content', $rPageExcerpt );
								$rPageExcerpt = str_replace( ']]>', ']]&gt;', $rPageExcerpt );

								// Set up query based on ID
								$qParams = array(
									'post_type' => 'page',
									'post_status' => 'publish',
									'post__in' => array( $rPage -> ID )
								);

								// Run query
								query_posts( $qParams );

								if ( have_posts() ) { // if there are posts
									while ( have_posts() ) { // loop through all the posts
										the_post();

										if ( get_the_title() == "Mission" ) { // check for Mission page
											$pageTotal = $pageTotal - 1; // Lower page count if Mission page exists
										}

										// Check if total number of pages is odd or even
										if ( checkNum($pageTotal) === TRUE ) {
											// if TRUE: number is odd
											$gridClass = 'bbg-grid--1-3-3'; // add 3-col grid class
										} else {
											// if FALSE: number is even
											$gridClass = 'bbg-grid--1-2-2'; // add 2-col grid class
										}
										$grids = $gridClass; // set grid class variable

										$specialMissionClass = $five; // change mission class when there are 5 items

										$headline = $rPageHeadline; // custom field for secondary page headline
										$tagline = $rPageTagline; // custom field for page tagline
										$excerpt = $rPageExcerpt; // define the page excerpt as back-up to tagline
										$includePageDescription = $rPageDescription;
										get_template_part( 'template-parts/content-about', get_post_format() );

										if ( get_the_title() == "Mission" ) { // Close Mission row if present
											echo '</section>';
											echo '<section class="usa-grid-full bbg__about__children--row">'; // open new row
										}
									}
								}
								wp_reset_query();
							}
						echo '</section>'; // close row
						/*** END DISPLAY OF ENTIRE MULTICOLUMN ROW ***/

						elseif ( get_row_layout() == 'about_umbrella_page' ):
						/*** BEGIN DISPLAY OF ENTIRE UMBRELLA ROW ***/
							$umbrellaPages = get_sub_field( 'about_umbrella_related_pages' );
							$pageTotal = count ( $umbrellaPages ); // count subpages

							// Check if page count is even or odd
							if ( checkNum($pageTotal) === TRUE ) {
								// if TRUE: number is odd
								$containerClass = 'bbg-grid--1-3-3'; // add 3-col grid class
							} else {
								// if FALSE: number is even
								$containerClass = 'bbg-grid--1-2-2'; // add 2-col grid class
							}

							$labelText = get_sub_field( 'about_umbrella_label' );
							$labelLink = get_sub_field( 'about_umbrella_label_link' );
							$introText = get_sub_field( 'about_umbrella_intro_text' );

							// allow shortcodes in intro text
							$introText = apply_filters( 'the_content', $introText );
							$introText = str_replace( ']]>', ']]&gt;', $introText );

							if ( $labelLink ) { // if the label has a URL add link and right arrow
								echo '<h6 class="bbg__label"><a href="' . $labelLink . '">' . $labelText . '</a> <span class="bbg__links--right-angle-quote" aria-hidden="true">&raquo;</span></h6>';
							} else { // else only show label
								echo '<h6 class="bbg__label">' . $labelText . '</h6>';
							}

							// Output umbrella section intro text
							if ( $introText ) {
								echo '<div class="bbg__about__child__intro">' . $introText . '</div>';
							}

							// Output grandchild pages (subpages)
							echo '<div class="usa-grid-full bbg__about__grandchildren">'; // open grandchildren container
								if ( $umbrellaPages ) {
									// Loop through all the grandchild pages
									foreach ( $umbrellaPages as $rp ) {
										// Define all variables
										$url = get_the_permalink( $rp -> ID );
										$title = get_the_title( $rp ->  ID);
										$headline = $rp -> headline;
										$thumbSrc = wp_get_attachment_image_src( get_post_thumbnail_id($rp -> ID) , 'medium-thumb' );
										$thumbPosition = $rp -> adjust_the_banner_image;
										$excerpt = my_excerpt( $rp -> ID );
										$excerpt = $excerpt . '<a href="' . $url . '" class="bbg__about__grandchild__link">Read more »</a>';
										$excerpt = apply_filters( 'the_content', $excerpt );
										$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );

										// Output variables
										echo '<article class="' . $containerClass . ' bbg__about__grandchild">';
											if ( $headline ) { // Insert headline if set on the page
												echo '<h3 class="bbg__about__grandchild__title"><a href="' . $url . '">' . $headline . '</a></h3>';
											} else { // else use the page title
												echo '<h3 class="bbg__about__grandchild__title"><a href="' . $url . '">' . $title . '</a></h3>';
											}

											if ( $thumbSrc ) { // Add thumbnail if set
												echo '<a href="' . $url . '"><div class="bbg__about__grandchild__thumb" style="background-image: url(' . $thumbSrc[0] .  '); background-position:'  . $thumbPosition . ';"></div></a>';
											}
											echo $excerpt; // Output page excerpt
										echo '</article>';
									}
								}
							echo '</div>'; // close grandchildren container
						echo '</section>'; // close row
						/*** END DISPLAY OF ENTIRE UMBRELLA ROW ***/

						elseif ( get_row_layout() == 'about_downloads_files' ):
						/*** BEGIN DISPLAY OF DOWNLOAD LINKS ROW ***/
							$downloadFiles = get_sub_field( 'about_downloads_file' );
							$totalFiles = count ( $downloadFiles ); // count number of files

							// Check function return
							if ( checkNum( $totalFiles ) === TRUE ) {
								// if TRUE: number is odd
								$containerClass = 'bbg-grid--1-3-3'; // add 3-col grid class
							} else {
								// if FALSE: number is even
								$containerClass = 'bbg-grid--1-2-2'; // add 2-col grid class
							}

							$downloadsLabel = get_sub_field( 'about_downloads_label' );
							$downloadsIntro = get_sub_field( 'about_downloads_description' );

							// Allow shortcodes in intro text
							$downloadsIntro = apply_filters( 'the_content', $downloadsIntro );
							$downloadsIntro = str_replace( ']]>', ']]&gt;', $downloadsIntro );

							if ( $downloadsLabel ) { // Output label if set
								echo '<h6 class="bbg__label">' . $downloadsLabel . '</h6>';
							}

							// Output downloads section intro text
							echo '<div class="bbg__about__child__intro">' . $downloadsIntro . '</div>';

							// Output grandchild pages (subpages)
							echo '<div class="usa-grid-full bbg__about__grandchildren">'; // open granchildren container
								if ( $downloadFiles ) {
									// Loop through all the grandchild pages/files
									foreach ($downloadFiles as $file) {
										// Define all variables
										$fileName = $file['downloads_link_name'];
										$fileImageObject = $file['downloads_file_image'];
											// retrieve ID from image object and load "mugshot" size
											$thumbSrc = wp_get_attachment_image_src( $fileImageObject['ID'] , 'mugshot' );
										$fileDescription = $file['downloads_file_description'];
										$fileDescription = apply_filters( 'the_content', $fileDescription );
										$fileDescription = str_replace( ']]>', ']]&gt;', $fileDescription );

										// Create array from file object
										$fileObj = $file['downloads_file'];
											// Define variables from array fields
											$fileID = $fileObj['ID'];
											$fileURL = $fileObj['url'];
											$file = get_attached_file( $fileID );
											$fileExt = strtoupper( pathinfo( $file, PATHINFO_EXTENSION ) ); // set extension to uppercase
											$fileSize = formatBytes( filesize( $file ) ); // file size

										// Output variables
										echo '<article class="' . $containerClass . ' bbg__about__grandchild">';
											// Output file title/name
											echo '<h3 class="bbg__about__grandchild__title"><a href="' . $fileURL . '" target="_blank">' . $fileName . '</a> <span class="bbg__file-size">(' . $fileExt . ', ' . $fileSize . ')</span></h3>';
											// Output file thumbnail
											if ( $thumbSrc ) {
												echo '<a href="' . $fileURL . '" target="_blank">';
													echo '<div class="bbg__about__grandchild__thumb" style="background-image: url(' . $thumbSrc[0] . ');"></div>';
												echo '</a>';
											}
											// Output file description if set
											if ( $fileDescription ) {
												echo $fileDescription;
											}
										echo '</article>';
									}
								}
							echo '</div>'; // close granchildren container
						echo '</section>'; // close row
						/*** END DISPLAY OF DOWNLOAD LINKS ROW ***/

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
								,'tag' => $officeTag[0] -> slug
								,'post_status' => array( 'future' )
								,'order' => 'ASC'
								,'posts_per_page' => 1
							);

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
								'tag' => $officeTag[0]->slug,
								'orderby' => 'date',
								'order' => 'DESC',
								'post__not_in' => $postIDsUsed
							);

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
																	} elseif ( $counter == 1 ) {
																		$includePortfolioDescription = false;
																		get_template_part( 'template-parts/content-portfolio', get_post_format() );
																	}
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

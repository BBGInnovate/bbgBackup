<?php
/**
 * Custom template for displaying informational kits — Press Room, Congressional.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * @author Gigi Frias <gfrias@bbg.gov>
   template name: Info Kits
 */

$bannerPosition = get_field( 'adjust_the_banner_image', '', true );
$bannerPositionCSS = get_field( 'adjust_the_banner_image_css', '', true );
$bannerAdjustStr="";
if ($bannerPositionCSS) {
	$bannerAdjustStr = $bannerPositionCSS;
} elseif ($bannerPosition) {
	$bannerAdjustStr = $bannerPosition;
}

$videoUrl = get_field( 'featured_video_url', '', true );
$secondaryColumnLabel = get_field( 'secondary_column_label', '', true );
$secondaryColumnContent = get_field( 'secondary_column_content', '', true );

$headline = get_field( 'headline', '', true );
$headlineStr = "";

$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

include get_template_directory() . "/inc/shared_sidebar.php";

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageName = get_the_title();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

get_header(); ?>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">
				<header class="page-header">
					<?php the_title( '<h5 class="bbg__label--mobile large">', '</h5>' ); ?>
				</header><!-- .page-header -->
			</div>

			<!-- SET UNIVERSAL VARIABLES -->
			<?php
				// access site-wide variables
				global $post;
				// set locale for number filters
				setlocale( LC_ALL, 'en_US.UTF-8' ); // currency

			    /* BBG settings variables */
			    // Load url for mission page
			    $missionURL = get_field( 'site_setting_mission_statement_link', 'options', 'false' );

			    if ( $missionURL ) {
			    	// get mission page ID
			    	$missionID = url_to_postid( $missionURL );
			    	// load excerpt from page
					$mission = my_excerpt( $missionID );
					// apply content filters to excerpt
					$mission = apply_filters( 'the_content', $mission );
					$mission = str_replace( ']]>', ']]&gt;', $mission );
					// remove last few characters from excerpt
					$mission = substr($mission, 0, -5);
					// add link to url at end of excerpt
					$mission = $mission . ' <a href="' . $missionURL . '" class="bbg__kits__intro__more--link">Network missions »</a></p>';
			    } else {
			    	// get mission from BBG settings "mission statement" variable
			    	$mission = get_field( 'site_setting_mission_statement', 'options', 'false' );
			    	// apply content filters to text
					$mission = apply_filters( 'the_content', $mission );
					$mission = str_replace( ']]>', ']]&gt;', $mission );
			    }

			    // numbers
			    $networks = get_field( 'site_setting_total_networks', 'options', 'false' ) . " networks";
			    $languages = get_field( 'site_setting_total_languages', 'options', 'false' ) . " languages";
			    $countries = get_field( 'site_setting_total_countries', 'options', 'false' ) . " countries";
			    $audience = get_field( 'site_setting_unduplicated_audience', 'options', 'false' ) . " million";
			    $affiliates = get_field( 'site_setting_total_affiliates', 'options', 'false' );
					$affiliates = number_format( $affiliates ) . " affiliates"; // format number and append value desc
			    $transmittingSites = get_field( 'site_setting_transmitting_sites', 'options', 'false' );
					$transmittingSites = number_format( $transmittingSites ) . " transmitting sites"; // format number and append value desc
			    $programming = get_field( 'site_setting_weekly_programming', 'options', 'false' );
					$programming = number_format( $programming ) . " hours"; // format number and append value desc

				/* Contact information */
				$phone = get_field( 'agency_phone', 'options', 'false' );
				$phone_link = str_replace( array('(',') ','-'), '' , $phone );

				$phoneMedia = get_field( 'agency_phone_inquiries', 'options', 'false' );
				$phoneMedia_link = str_replace( array('(',') ','-'), '' , $phoneMedia );

				$email = get_field( 'agency_email', 'options', 'false' );
				$emailPress = get_field( 'agency_email_press', 'options', 'false' );
				$emailCongress = get_field( 'agency_email_congress', 'options', 'false' );

				$street = get_field( 'agency_street', 'options', 'false' );
				$city = get_field( 'agency_city', 'options', 'false' );
				$state = get_field( 'agency_state', 'options', 'false' );
				$zip = get_field( 'agency_zip', 'options', 'false' );
				// $address = "";
				// $mapLink = "";
				// $includeContactBox = FALSE;

				/* Format all contact information */
				// Show corresponding phone number
				if ( $phoneMedia != "" && $pageName == "Press room" || $pageName == "Congressional affairs" )  {
					$phone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel=+01' . $phoneMedia_link . '">' . $phoneMedia . '</a></li>';
				} else {
					$phone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel=+01' . $phone_link . '">' . $phone . '</a></li>';
				}

				// Show corresponding email address
				if ( $emailPress != "" && $pageName == "Press room" ) {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $emailPress . '" title="Contact us">' . $emailPress . '</a></li>';
				} elseif ( $emailPress != "" && $pageName == "Congressional affairs" ) {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $emailCongress . '" title="Contact us">' . $emailCongress . '</a></li>';
				} else {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $email . '" title="Contact us">' . $email . '</a></li>';
				}

				if ($street != "" && $city != "" && $state != "" && $zip != "") {
					$address = $street . '<br/>' . $city . ', ' . $state . ' ' . $zip;

					//Strip spaces for url-encoding.
					$street = str_replace(" ", "+", $street);
					$city = str_replace(" ", "+", $city);
					$state = str_replace(" ", "+", $state);
					$mapLink = 'https://www.google.com/maps/place/' . $street . ',+' . $city . ',+' . $state . '+' . $zip . '/';

					$address = '<p itemprop="address" aria-label="address"><a href="'. $mapLink . '">' . $address . '</a></p>';
				}

				if ( $address != "" || $phone != "" || $email != "" || $phoneMedia != "" || $emailPress != "" ){
					$includeContactBox = TRUE;
				}

				// get all contact cards for dropdown
				$allContacts = get_field( 'kits_network_contacts' );

				$contactPostIDs = get_post_meta( $post->ID, 'contact_post_id', true );
			?>

			<!-- Page introduction -->
			<!-- <div id="page-intro" class="usa-section usa-grid bbg__kits__intro">
				<?php echo $mission; ?>
			</div> -->

			<!-- Inquiries -->
			<article class="bbg__article bbg__kits__section">
				<div class="usa-grid">
					<!-- Inquiries section -->
					<div class="entry-content bbg__article-content large">
						<div class="bbg__kits__inquiries">
							<h3>Inquiries</h3><!-- NEED TO MAKE THIS CONTENT DYNAMIC FOR EACH KIT -->
							<p>The Office of Public Affairs is the main point of contact for members of the press and requests for collaborations.</p>
							<p class="bbg__kits__inquiries__text--half"><strong>Want to stay informed?</strong> BBG sends out press releases, newsletters, event notices and media clips regularly.</p>
							<a target="_blank" class="usa-button bbg__kits__inquiries__button--half" href="http://visitor.r20.constantcontact.com/d.jsp?llr=bqfpwmkab&p=oi&m=1110675265025">Sign up for updates</a>
						</div>
					</div>
					<!-- Contact card (tailored to audience) -->
					<div class="bbg__article-sidebar large">
						<?php if ( $includeContactBox ) { ?>
							<aside>
								<div class="bbg__contact-card">
									<div class="bbg__contact-card__text">
										<?php
											// Main contact information
											if ( $pageName == "Press room" ) {
												echo '<h3>Office of Public Affairs</h3>';
											} elseif ( $pageName == "Congressional affairs" ) {
												echo '<h3>Office of Congressional Affairs</h3>';
											} else {
												echo '<h3>Contact information</h3>';
											}
											echo $address;
											echo '<ul class="usa-unstyled-list">';
												echo $phone;
												echo $email;
											echo '</ul>';

											echo '<!-- Social media profiles -->';
											// check that budget repeater field exists
											$allSocial = get_field( 'agency_social_media_profiles', 'options', 'false' );

											echo '<div class="bbg__kits__social">';
												if ( $allSocial ) {
													// loop through repeater rows
													foreach ( $allSocial as $socials ) {
														// populate variables for each row
														$socialPlatform = $socials['social_media_platform'];
														$socialProfile = $socials['social_media_profile_name'];
														$socialURL = $socials['social_media_url'];

														echo '<a class="bbg__kits__social-link usa-link-' . strtolower( $socialPlatform ) . '" href="' . $socialURL . '" role="img" aria-label="' . $socialPlatform  . '"></a>';
													}
												}
											echo '</div>';

											echo '<div class="bbg__kits__contacts">';
												renderContactSelect($contactPostIDs);
											echo '</div>';
										?>
									</div>
								</div>
							</aside>
						<?php } ?>
					</div>
				</div>
			</article>

			<div class="usa-section usa-grid bbg__kits__section" id="page-sections">
		        <!-- 3-COL ROW -->
		        <section class="usa-grid-full bbg__kits__section--row">
		        	<h2 class="entry-title">BBG by the numbers</h2>
		        	<div class="usa-grid-full bbg__kits__section--tiles">
		        		<!-- Elevator pitch/intro tile -->
		        		<!-- <article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<div class="bbg__kits__pitch--rev">
								<?php echo $pageContent; ?>
				        	</div>
		        		</article> -->

						<!-- DISTRIBUTION tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">International operations</h3>
							<p class="bbg__kits__section--tile__list"><span class="bbg__kits__section--tile__list--serif"><?php echo $networks; ?></span> and a system of <span class="bbg__kits__section--tile__list--sans"><?php echo $affiliates; ?></span> and over <span class="bbg__kits__section--tile__list--sans"><?php echo $transmittingSites; ?></span> distribute <span class="bbg__kits__section--tile__list--sans"><?php echo $programming; ?></span> of original content globally each week.</p>
		        		</article>

		        		<!-- AUDIENCE tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Global audience</h3>
							<p class="bbg__kits__section--tile__list">A worldwide unduplicated audience of <span class="bbg__kits__section--tile__list--serif"><?php echo $audience; ?></span> from more than <span class="bbg__kits__section--tile__list--sans"><?php echo $countries; ?></span> tune in weekly in <span class="bbg__kits__section--tile__list--sans"><?php echo $languages; ?></span>.</p>
		        		</article>

		        		<!-- BUDGET tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Annual budget</h3>
		        			<table class="bbg__kits__section--tile__table--borderless">
								<tbody>
								<?php
									// check that budget repeater field exists
									$allBudgets = get_field( 'site_setting_annual_budgets', 'options', 'false' );
									//arsort($allBudgets);
									//var_dump( $allBudgets );
									// echo $allBudgets;

									if( $allBudgets ) {
										//build a new array with the key and value
										foreach($allBudgets as $key => $value) {
											//still going to sort by firstname
											$budget[$key] = $value['fiscal_year'];
										}
										// sort multi-dimensional array by new array
										array_multisort( $budget, SORT_DESC, $allBudgets );

										// loop through repeater rows
										foreach( $allBudgets as $budget ) {
											// populate variables for each row
											$budgetFY = 'FY' . $budget['fiscal_year'];
											$budgetStatus = $budget['status'];
											$budgetAmount = $budget['dollar_amount'];

											echo '<!-- ' . $budgetFY . ' budget -->';
											echo '<tr>';
												// fiscal year column
												echo '<th scope="row">' . $budgetFY . ' <span class="bbg__file-size">(' . $budgetStatus  . ')</span></th>';
												// amount column
												echo '<td class="bbg__kits__section--tile__list--sans">' . money_format( '%.1n', $budgetAmount ) . 'M</td>';
											echo '</tr>';
										}
									}
								?>
								</tbody>
							</table>
		        		</article>
		        	</div>
		        </section>

				<?php
				// check if the flexible content field has rows of data
				if ( have_rows('kits_flexible_page_rows') ):
					$counter = 0;
					$pageTotal = 1;
					$containerClass = "bbg__kits__child ";

					/* @Check if number of pages is odd or even
					*  Return BOOL (true/false) */
					function checkNum($pageTotal) {
						return ( $pageTotal%2 ) ? TRUE : FALSE;
					}

					while ( have_rows('kits_flexible_page_rows') ) : the_row();
						$counter++;

						if ( get_row_layout() != 'kits_ribbon_page') {
							// we wrap a  usa-grid container around every row.
							echo '<!-- ROW ' . $counter . '-->';
							echo '<section class="usa-grid-full bbg__kits__section--row">';
						} else {
							// we wrap a  usa-grid container around every row.
							echo '<!-- ROW ' . $counter . '-->';
							echo '<section class="usa-grid-full bbg__kits__section--row bbg__ribbon--thin">';
						}

						if ( get_row_layout() == 'kits_ribbon_page' ):
						/*** BEGIN DISPLAY OF ENTIRE RIBBON ROW ***/
							$labelText = get_sub_field('kits_ribbon_label');
							$labelLink = get_sub_field('kits_ribbon_label_link');
							$headlineText = get_sub_field('kits_ribbon_headline');
							$headlineLink = get_sub_field('kits_ribbon_headline_link');
							$summary = get_sub_field('kits_ribbon_summary');
							$imageURL = get_sub_field('kits_ribbon_image');

							// allow shortcodes in intro text
							$summary = apply_filters('the_content', $summary);
							$summary = str_replace(']]>', ']]&gt;', $summary);

							echo "<div class='usa-grid'>";
								echo "<div class='bbg__announcement__flexbox'>";

									if ($imageURL) {
										echo "<div class='bbg__announcement__photo' style='background-image: url($imageURL);'></div>";
									}

									echo "<div>";

										if ($labelLink) {
											echo "<h6 class='bbg__label'><a href='" . get_permalink($labelLink) . "'>$labelText</a></h6>";
										} else {
											echo "<h6 class='bbg__label'>$labelText</h6>";
										}

										if ($headlineLink) {
											echo "<h2 class='bbg__announcement__headline'><a href='" . get_permalink($headlineLink) . "'>$headlineText</a></h2>";
										} else {
											echo "<h2 class='bbg__announcement__headline'>$headlineText</h2>";
										}

										echo $summary;

									echo "</div>";
								echo "</div><!-- .bbg__announcement__flexbox -->";
							echo "</div><!-- .usa-grid -->";
							// echo "</section>";
						echo "</section>";
						/*** END DISPLAY OF ENTIRE RIBBON ROW ***/

						elseif ( get_row_layout() == 'kits_downloads_files' ):
						/*** BEGIN DISPLAY OF DOWNLOAD LINKS ROW ***/
							$downloadsLabel = get_sub_field('kits_downloads_label');

							if ($downloadsLabel) {
								echo "<h2 class='bbg__label'>$downloadsLabel</h2>";
							}

							echo "<div class='usa-grid-full bbg__kits__section--tiles'>";

							$downloadFiles = get_sub_field('kits_downloads_file');
							// count the number of files
							$countFiles = count ( $downloadFiles );

							// Check number of files function return
							if ( checkNum($countFiles) === TRUE ) {
								// if TRUE: number is odd, set 3 column grid
								$containerClass = 'bbg-grid--1-3-3';
							} else {
								// if FALSE: number is even, set 2 column grid
								$containerClass = 'bbg-grid--1-2-2';
							}

							if ( $downloadFiles ) {
								// Loop through all the grandchild pages
								foreach ($downloadFiles as $file) {
									// Define all variables
									$fileImageObject = $file['downloads_file_image'];
									// var_dump( $fileImageObject );
										// retrieve ID from image object and load "mugshot" size
										$thumbSrc = wp_get_attachment_image_src( $fileImageObject['ID'] , 'large-thumb' );


								    $supportPageTitle = $file['kits_related_page_name'];
								    // Related page array
								    $supportPage = $file['kits_related_page'];
								    	// page data
								    	if ( $supportPageTitle ) {
								    		$pageHeadline = $supportPageTitle;
								    	} else {
									    	$pageHeadline = get_the_title( $supportPage->ID );
								    	}
								    	$pageURL = get_permalink( $supportPage->ID );
										$pageExcerpt = my_excerpt( $supportPage->ID );
										$pageExcerpt = apply_filters( 'the_content', $pageExcerpt );
										$pageExcerpt = str_replace( ']]>', ']]&gt;', $pageExcerpt );

									$fileTitle = $file['downloads_link_name'];
								    // Files object array
									$fileObj = $file['downloads_file'];
										// file data
										// var_dump( $fileObj );
										if ( $fileTitle ) {
											$fileName = $fileTitle;
										} else {
											$fileName = $fileObj['title'];
										}
										$fileID = $fileObj['ID'];
										$fileURL = $fileObj['url'];
										$file = get_attached_file( $fileID );
										$fileExt = strtoupper( pathinfo($file, PATHINFO_EXTENSION) );
										$fileSize = formatBytes( filesize($file) );

									// Output variables in HTML format
									echo "<article class='$containerClass bbg__kits__section--tile'>";
										echo "<header class='bbg__kits__section--tile__header'>";
											// Output page data
											if ( $supportPage ) {
												echo "<h3 class='bbg__kits__section--tile__title'>" . "<a href='" . $pageURL . "'>" . $pageHeadline . "</a></h3>";
											} else {
												echo "<h3 class='bbg__kits__section--tile__title'>" . "<a href='" . $fileURL . "' target='_blank'>" . $fileName . "</a></h3>";
											}
										echo "</header>";

										// Output file image
										if ( $thumbSrc ) {
											echo "<a href='" . $fileURL . "' target='_blank'>";
												echo "<div class='bbg__kits__section--tile__thumb' style='background-image: url(" . $thumbSrc[0] . ");'></div>";
											echo "</a>";

										echo $pageExcerpt;

										// Output file title/name
										echo "<p class='bbg__kits__section--tile__downloads'><a href='" . $fileURL . "' target='_blank'>" . $fileName ."</a> <span class='bbg__file-size'>(" . $fileExt . ", " . $fileSize . ")</span></p>";
										}
									echo "</article>";
								}
							}
							echo '</div>';
						echo '</section>';
						/*** END DISPLAY OF DOWNLOAD LINKS ROW ***/

						endif;
					endwhile;
					echo '<!-- END ROWS -->';
				endif;
				?>


				<!-- Recent posts (Featured, left 2 headline/teasers, right soapbox/headlines) -->
				<section id="recent-posts" class="usa-section bbg__home__recent-posts">
					<?php 
						$prCategoryObj = get_category_by_slug( 'press-release' );
						$prCategoryID = $prCategoryObj -> term_id;
					?>
					<div class="usa-grid">
						<h6 class="bbg__label"><a href="<?php echo get_category_link( $prCategoryID ); ?>">Recent Press Releases</a></h6>
					</div>

					<!-- Featured Post -->
					<div class="usa-grid-full">

						<!-- Headlines -->
						<div class="usa-grid bbg__ceo-post">
							<div class="usa-width-one-half bbg__secondary-stories">
								<?php
									/* BEWARE: sticky posts add a record */
									$maxPostsToShow=9;

									/**** START FETCH related press releases ****/
									$pressReleases = array();
									
									$qParams = array(
										'post_type' => array( 'post' ),
										'posts_per_page' => 9,
										'category__and' => array( $prCategoryID ),
										'orderby', 'date',
										'order', 'DESC',
										'tax_query' => array(
											array(
												'taxonomy' => 'post_format',
												'field' => 'slug',
												'terms' => 'post-format-quote',
												'operator' => 'NOT IN'

											)
										),
										'category__not_in' => get_cat_id( 'Award' )
									);

									query_posts($qParams);
									if ( have_posts() ) {
										$counter = 0;
										$includeImage = TRUE;
										while ( have_posts() ) : the_post();
											$counter++;
											$postIDsUsed[] = get_the_ID();
											$gridClass = "bbg-grid--full-width";
											if ($counter > 2) {
												$includeImage = false;
												$includeMeta=false;
												$includeExcerpt=false;
												if ($counter==3) {
													//<header class="page-header"><h6 class="page-title bbg__label small">More news</h6></header>
													echo '</div><div class="usa-width-one-half tertiary-stories">';
												}
											}
											get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
										endwhile;
									}
									wp_reset_query();
								?>
							</div>
						</div><!-- headlines -->
					</div>
				</section><!-- .BBG News -->
			</div> <!-- End id="page-sections" -->
<?php get_footer(); ?>
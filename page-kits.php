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

			<?php
				$hideFeaturedImage = FALSE;
				if ($videoUrl != "") {
					echo featured_video($videoUrl);
					$hideFeaturedImage = TRUE;
				} elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
					echo '<div class="usa-grid-full">';
					$featuredImageClass = "";
					$featuredImageCutline = "";
					$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment'));
					if ($thumbnail_image && isset($thumbnail_image[0])) {
						$featuredImageCutline = $thumbnail_image[0]->post_excerpt;
					}

					$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

					echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner" style="background-image: url(' . $src[0] . '); background-position: ' . $bannerPosition . '">';
					echo '</div>';

					// Caption for featured image
					if ($featuredImageCutline != "") {
						echo '<div class="usa-grid">';
							echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
						echo '</div> <!-- usa-grid -->';
					}

					echo '</div> <!-- usa-grid-full -->';

				}
			?><!-- .bbg__article-header__thumbnail -->

			<!-- SET UNIVERSAL VARIABLES -->
			<?php
				// access site-wide variables
				global $post;
				// set locale for number filters
				setlocale( LC_ALL, 'en_US' ); // currency

			    /* BBG settings variables */
			    $mission = get_field( 'site_setting_mission_statement', 'options', 'false' );
			    $missionURL = get_field( 'site_setting_mission_statement_link', 'options', 'false' );
			    // numbers
			    $networks = get_field( 'site_setting_total_networks', 'options', 'false' ) . " networks";
			    $languages = get_field( 'site_setting_total_languages', 'options', 'false' ) . " languages";
			    $countries = get_field( 'site_setting_total_countries', 'options', 'false' ) . " countries";
			    $audience = get_field( 'site_setting_unduplicated_audience', 'options', 'false' ) . " million";
			    $affiliates = get_field( 'site_setting_total_affiliates', 'options', 'false' );
					$affiliates = number_format( $affiliates ) . " affiliates"; // format number and append value desc
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
				$address = "";
				$mapLink = "";
				$includeContactBox = FALSE;

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
			?>

			<!-- Page introduction -->
			<div id="page-intro" class="usa-section usa-grid bbg__kits__intro">
				<?php echo '<p>' . $mission . ' <a href="' . $missionURL . '" class="bbg__kits__intro__more--link">Network missions »</a></p>'; ?>
			</div>

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
							<aside class="bbg__article-sidebar__aside">
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
												if( $allSocial ) {
													// loop through repeater rows
													foreach( $allSocial as $socials ) {
														// populate variables for each row
														$socialPlatform = $socials['social_media_platform'];
														$socialProfile = $socials['social_media_profile_name'];
														$socialURL = $socials['social_media_url'];

														echo '<a class="bbg__kits__social-link usa-link-' . strtolower( $socialPlatform ) . '" href="' . $socialURL . '" role="img" aria-label="' . $socialPlatform  . '"></a>';
													}
												}
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
		        	<h2 class="entry-title">BBG at-a-glance</h2>
		        	<div class="usa-grid-full bbg__kits__section--tiles">
		        		<!-- Elevator pitch/intro tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<div class="bbg__kits__pitch--rev">
								<?php echo $pageContent; ?>
				        	</div>
		        		</article>
		        		<!-- Stats tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Universal reach</h3>
							<ul class="bbg__kits__section--tile__list">
								<li><span class="bbg__kits__section--tile__list--serif"><?php echo $networks; ?></span></li>
								<li>serving <span class="bbg__kits__section--tile__list--sans"><?php echo $audience; ?></span> weekly</li>
								<li>in <span class="bbg__kits__section--tile__list--sans"><?php echo $languages; ?></span> in</li>
								<li>more than <span class="bbg__kits__section--tile__list--sans"><?php echo $countries; ?></span>.</li>
							</ul>
		        		</article>
						<!-- Stats tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Global distribution</h3>
							<ul class="bbg__kits__section--tile__list">
								<li>Nearly <span class="bbg__kits__section--tile__list--sans"><?php echo $affiliates; ?></span> help to</li>
								<li>distribute <span class="bbg__kits__section--tile__list--serif"><?php echo $programming; ?></span> of original programming each week.</li>
							</ul>
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
								echo "<h2 class='entry-title'>$downloadsLabel</h2>";
							}

							// show section intro text
							// echo "<div class='bbg__about__child__intro'>$downloadsIntro</div>";

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
									$fileName = $file['downloads_link_name'];
									// $fileURL = $file['downloads_file'];
									$fileImageObject = $file['downloads_file_image'];
										// retrieve ID from image object and load "mugshot" size
										$thumbSrc = wp_get_attachment_image_src( $fileImageObject['ID'] , 'mugshot' );

									// Files object array
									$fileObj = $file['downloads_file'];
										// file data
										$fileID = $fileObj['ID'];
										$fileURL = $fileObj['url'];
										$file = get_attached_file( $fileID );
										$fileExt = strtoupper( pathinfo($file, PATHINFO_EXTENSION) );
										$fileSize = formatBytes( filesize($file) );


									print_r( $fileObj );
								    // Related page array
								    // $pageObj = $file['downloads_page_link'];
								    	// page data
								    	// $pageID = $pageObj['ID'];

									// Output variables in HTML format
									echo "<article class='$containerClass bbg__kits__section--tile'>";
										// Output file title/name
										echo "<h3 class='bbg__kits__section--tile__title'><a href='" . $fileURL . "' target='_blank'>" . $fileName ."</a> <span class='bbg__file-size'>(" . $fileExt . ", " . $fileSize . ")</span></h3>";
										// Output file image
										if ( $thumbSrc ) {
											echo "<a href='" . $fileURL . "' target='_blank'>";
												echo "<div class='bbg__kits__section--tile__thumb' style='background-image: url(" . $thumbSrc[0] . ");'></div>";
											echo "</a>";
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
			</div> <!-- End id="page-sections" -->

			<!-- BUDGET TABLE -->
			<section class="usa-grid-full">
				<div class="bbg__article-content large">
					<table class="usa-table bbg__table__money">
						<caption>
							<h3 class="bbg__table__caption">BBG annual combined budget</h3>
							<!-- <h6 class="bbg__table__caption-tagline">($ in thousands)</h6> -->
						</caption>
						<thead>
							<tr>
								<th scope="col">Fiscal year</th>
								<th scope="col">Appropriation status</th>
								<th scope="col">Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php
								// check that budget repeater field exists
								$allBudgets = get_field( 'site_setting_annual_budgets', 'options', 'false' );

								if( $allBudgets ) {
									// loop through repeater rows
									foreach( $allBudgets as $budget ) {
										// populate variables for each row
										$budgetFY = 'FY ' . $budget['fiscal_year'];
										$budgetStatus = $budget['status'];
										$budgetAmount = $budget['dollar_amount'];

										echo '<!-- ' . $budgetFY . ' budget -->';
										echo '<tr>';
											// fiscal year column
											echo '<th scope="row">' . $budgetFY . '</td>';
											// status column
											echo '<td>' . $budgetStatus . '</td>';
											// amount column
											echo '<td>' . money_format( '%.1n', $budgetAmount ) . ' million</td>';
										echo '</tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
				<div class="bbg__article-sidebar large">
					<div class="bbg__sidebar__primary">
						<img class="bbg__sidebar__primary-image wp-image-13769" title="budget.2016-front" src="https://www.bbg.gov/wp-content/media/2011/12/2017CBJBudgetRequest_Cover_Web-232x300.jpg" alt="FY 2016 Budget Submission Cover"/>
						<h3 class="bbg__sidebar__primary-headline">FY 2017 budget request</h3>
						<ul>
						<li><a href="https://www.bbg.gov/wp-content/media/2016/02/FY2017Budget_ExecutiveSummary_2_25.pdf" target="_blank" class="ugdv_link">Download 2017 Budget Submission Executive Summary</a></li>
						<li><a href="url=https://www.bbg.gov/wp-content/media/2016/02/BBG_FY2017_Budget-Highlights.pdf" target="_blank" class="ugdv_link">Download the 2017 Budget Submission Highlights</a></li>
						<li><a href="https://www.bbg.gov/wp-content/media/2011/12/FY-2017-Budget-Submission.pdf" target="_blank" class="ugdv_link">Download 2017 Budget Submission</a></li>
						</ul>
					</div>
				</div>
			</section>
			<?php
				$showNetworks = get_field( 'kits_networks_row' );
				if ( $showNetworks ) { ?>

				<!-- Entity list -->
				<section id="entities" class="usa-section bbg__staff">
					<div class="usa-grid">
						<h6 class="bbg__label"><a href="<?php echo get_permalink( get_page_by_path( 'broadcasters' ) ); ?>" title="A list of the BBG broadcasters.">Our networks</a></h6>
						<div class="usa-intro bbg__broadcasters__intro">
							<h3 class="usa-font-lead">Every week, more than 226 million listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters.</h3>
						</div>
						<?php echo outputBroadcasters('2'); ?>
					</div>
				</section><!-- entity list -->
			<?php
				}
			wp_reset_postdata();
			?>











		        <!-- 1-COL ROW -->
		        <section class="usa-grid-full bbg__kits__section--row">

		        	<div class="bbg__kits__child__intro">
						<?php echo $pageContent; ?>
		        	</div>
		        </section>

		        <!-- 2-COL ROW -->
		        <section class="usa-grid-full bbg__kits__section--row">
		            <article class="bbg__kits__excerpt bbg__kits__child bbg__kits__child--the evolution of u.s. civilian international broadcasting bbg-grid--1-2-2 post-274 page type-page status-publish has-post-thumbnail hentry" id="post-274">
		                <header class="entry-header bbg__kits__excerpt-header">
		                    <!-- Child page title -->
		                    <h6 class="bbg__label"><a href="http://localhost/innovationWP/bbg/who-we-are/history/" rel="bookmark">Our History</a></h6>
		                    <!-- Child page thumbnail -->
		                    <div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
		                    	<a href="http://localhost/innovationWP/bbg/who-we-are/history/" rel="bookmark" tabindex="-1"><img alt="This is the central programming services division of Voice of America radio in New York, seen Feb. 27, 1953. These men and women write news, commentaries and dramatic programs for VOA. (AP Photo/John Rooney)" class="attachment-medium-thumb size-medium-thumb wp-post-image" height="360" sizes="(max-width: 600px) 100vw, 600px" src="http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-600x360.jpg" srcset="http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-600x360.jpg 600w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-1040x624.jpg 1040w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-300x180.jpg 300w" width="600"></a>
		                    </div>
		                </header>
		            </article>

		            <article class="bbg__kits__excerpt bbg__kits__child bbg__kits__child--bbg staff executes board decisions and provides support bbg-grid--1-2-2 post-24762 page type-page status-publish has-post-thumbnail hentry" id="post-24762">
		                <header class="entry-header bbg__kits__excerpt-header">
		                    <!-- Child page title -->
		                    <h6 class="bbg__label"><a href="http://localhost/innovationWP/bbg/who-we-are/organizational-structure/" rel="bookmark">Our Structure</a></h6>
		                    <!-- Child page thumbnail -->
		                    <div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
		                    	<a href="http://localhost/innovationWP/bbg/who-we-are/organizational-structure/" rel="bookmark" tabindex="-1"><img alt="structure" class="attachment-medium-thumb size-medium-thumb wp-post-image" height="360" sizes="(max-width: 600px) 100vw, 600px" src=
		                        "http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2016/05/structure-600x360.png"
		                        srcset="http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2016/05/structure-600x360.png 600w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2016/05/structure-1040x624.png 1040w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2016/05/structure-300x180.png 300w" width="600"></a>
		                    </div>
		                </header>
		            </article>
		        </section>

		        <!-- 3-COL ROW -->
		        <section class="usa-grid-full bbg__kits__section--row">
		        	<div class="usa-grid-full bbg__kits__section--tiles">
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title"><a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/board/">The Board</a></h3>
		        			<a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/board/"><div class="bbg__kits__section--tile__thumb" style="background-image: url(http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/board-room-1223326-600x360.jpg); background-position: right center;"></div></a>
		        			<p>The BBG is headed by a bi-partisan board with nine members with expertise in the fields of mass communications, broadcast media, or international affairs. <a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/board/" class="bbg__kits__section--tile__link">Read more »</a></p>
		        		</article>
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title"><a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/senior-management/">Senior Management</a></h3>
		        			<a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/senior-management/"><div class="bbg__kits__section--tile__thumb" style="background-image: url(http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/12/chess-581110_1920-600x360.jpg); background-position: left bottom;"></div></a>
		        			<p>The BBG staff operates as an extension of the Board to carry out Board decisions and oversight for all of U.S. international broadcasting. <a href="http://localhost/innovationWP/bbg/who-we-are/our-leadership/senior-management/" class="bbg__kits__section--tile__link">Read more »</a></p>
		        		</article>
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title"><a href="http://localhost/innovationWP/bbg/organizational-chart/">Organizational Chart</a></h3>
		        			<p>The mission of the BBG is to inform, engage and connect people around the world in support of freedom and democracy. <a href="http://localhost/innovationWP/bbg/organizational-chart/" class="bbg__kits__section--tile__link">Read more »</a></p>
		        		</article>
		        	</div>
		        </section>

		        <!-- 2- + 1-COL ROW -->
		        <section class="usa-grid-full bbg__kits__section--row">
		        	<article class="bbg__kits__excerpt bbg__kits__child bbg__kits__child--mission bbg-grid--1-1-1 post-49 page type-page status-publish hentry" id="post-49">
		                <header class="entry-header bbg__kits__excerpt-header">
		                    <!-- Child page title -->
		                    <h6 class="bbg__label"><a href= "http://localhost/innovationWP/bbg/who-we-are/mission/" rel="bookmark">Mission</a></h6>
		                </header>
		                <!-- Child page excerpt -->
		                <div class="entry-content bbg__kits__excerpt-content">
		                    <p>The mission of the BBG is to inform, engage and connect people around the world in support of freedom and democracy.</p>
		                </div>
		            </article>
		        	<div class="usa-grid-full bbg__kits__section--tiles">
		        		<article class="bbg__kits__excerpt bbg__kits__child bbg__kits__child--the evolution of u.s. civilian international broadcasting bbg-grid--1-2-2 post-274 page type-page status-publish has-post-thumbnail hentry" id="post-274">
			                <header class="entry-header bbg__kits__excerpt-header">
			                    <!-- Child page title -->
			                    <h6 class="bbg__label"><a href="http://localhost/innovationWP/bbg/who-we-are/history/" rel="bookmark">Our History</a></h6>
			                    <!-- Child page thumbnail -->
			                    <div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
			                    	<a href="http://localhost/innovationWP/bbg/who-we-are/history/" rel="bookmark" tabindex="-1"><img alt="This is the central programming services division of Voice of America radio in New York, seen Feb. 27, 1953. These men and women write news, commentaries and dramatic programs for VOA. (AP Photo/John Rooney)" class="attachment-medium-thumb size-medium-thumb wp-post-image" height="360" sizes="(max-width: 600px) 100vw, 600px" src="http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-600x360.jpg" srcset="http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-600x360.jpg 600w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-1040x624.jpg 1040w, http://localhost/innovationWP/bbg/wp-content/uploads/sites/2/2011/11/AP_VOA_Central_News-300x180.jpg 300w" width="600"></a>
			                    </div>
			                </header>
			            </article>
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title"><a href="http://localhost/innovationWP/bbg/organizational-chart/">Organizational Chart</a></h3>
		        			<p>The mission of the BBG is to inform, engage and connect people around the world in support of freedom and democracy. <a href="http://localhost/innovationWP/bbg/organizational-chart/" class="bbg__kits__section--tile__link">Read more »</a></p>
		        		</article>
		        	</div>
		        </section>
		        <!-- END ROWS -->
		    </div>
		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
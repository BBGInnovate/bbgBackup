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
					echo '</div> <!-- usa-grid-full -->';
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
				if ( have_rows('about_flexible_page_rows') ):
					$counter = 0;
					$pageTotal = 1;
					$containerClass = "bbg__about__child ";

					/* @Check if number of pages is odd or even
					*  Return BOOL (true/false) */
					function checkNum($pageTotal) {
						return ($pageTotal%2) ? TRUE : FALSE;
					}

					while ( have_rows('about_flexible_page_rows') ) : the_row();
						$counter++;

						if ( get_row_layout() != 'about_ribbon_page') {
							// we wrap a  usa-grid container around every row.
							echo '<!-- ROW ' . $counter . '-->';
							echo '<section class="usa-grid-full bbg__about__children--row">';
						} else {
							// we wrap a  usa-grid container around every row.
							echo '<!-- ROW ' . $counter . '-->';
							echo '<section class="usa-grid-full bbg__about__children--row bbg__ribbon--thin">';
						}

						if ( get_row_layout() == 'about_multicolumn' ):

							/*** BEGIN DISPLAY OF ENTIRE MULTICOLUMN ROW ***/
							$relatedPages = get_sub_field( 'about_muliticolumn_related_pages' );
							$pageTotal = count ( $relatedPages );
							$largeNumber = FALSE;

							// Check function return
							if ( checkNum($pageTotal) === TRUE ) {
								// if TRUE: number is odd
								$gridClass = 'bbg-grid--1-3-3';
							} else {
								// if FALSE: number is even
								$gridClass = 'bbg-grid--1-2-2';
							}

							if ( $pageTotal == 5 ) {
								$five = TRUE;
							} else {
								$five = FALSE;
							}

							foreach ($relatedPages as $rPage) {
								$rPageHeadline = $rPage->headline;
								$rPageTagline = $rPage->page_tagline;
								$rPageDescription = $rPage->about_include_exerpt;
								$rPageExcerpt = my_excerpt($rPage->ID);
								$rPageExcerpt = apply_filters('the_content', $rPageExcerpt);
								$rPageExcerpt = str_replace(']]>', ']]&gt;', $rPageExcerpt);

								$qParams = array(
									'post_type' => 'page',
									'post_status' => 'publish',
									'post__in' => array( $rPage->ID )
								);

								query_posts( $qParams );

								if ( have_posts() ) {
									while ( have_posts() ) {
										the_post();
										$specialMissionClass = $five;
										$grids = $gridClass;
										$headline = $rPageHeadline; // custom field for secondary page headline
										$tagline = $rPageTagline; // custom field for page tagline
										$excerpt = $rPageExcerpt; // define the page excerpt as back-up to tagline
										$includePageDescription = $rPageDescription;
										get_template_part( 'template-parts/content-about', get_post_format() );
									}
								}
								wp_reset_query();
							}
						echo "</section>";
						/*** END DISPLAY OF ENTIRE MULTICOLUMN ROW ***/

						elseif ( get_row_layout() == 'about_umbrella_page' ):
						/*** BEGIN DISPLAY OF ENTIRE UMBRELLA ROW ***/
							$umbrellaPages = get_sub_field('about_umbrella_related_pages');
							$pageTotal = count ( $umbrellaPages );

							// Check function return
							if ( checkNum($pageTotal) === TRUE ) {
								// if TRUE: number is odd
								$containerClass = 'bbg-grid--1-3-3';
							} else {
								// if FALSE: number is even
								$containerClass = 'bbg-grid--1-2-2';
							}

							$labelText = get_sub_field('about_umbrella_label');
							$labelLink = get_sub_field('about_umbrella_label_link');
							$introText = get_sub_field('about_umbrella_intro_text');
							$imageURL = get_sub_field('about_umbrella_image');

							//allow shortcodes in intro text
							$introText = apply_filters('the_content', $introText);
							$introText = str_replace(']]>', ']]&gt;', $introText);

							if ($labelLink) {
								echo "<h6 class='bbg__label'><a href='$labelLink'>$labelText</a> <span class='bbg__links--right-angle-quote' aria-hidden=”true”>&raquo;</span></h6>";
							} else {
								echo "<h6 class='bbg__label'>$labelText</h6>";
							}

							if ($imageURL) {
								echo "<div class='single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium' style='background-image: url(" . $imageURL .  "); height: 100%; min-height: 150px;'></div>";
							}

							// show umbrella section intro text
							echo "<div class='bbg__about__child__intro'>$introText</div>";
							echo "<div class='usa-grid-full bbg__about__grandchildren'>";

							if ( $umbrellaPages ) {
								// Loop through all the grandchild pages
								foreach ($umbrellaPages as $rp) {
									// Define all variables
									$url = get_the_permalink($rp->ID);
									$title = get_the_title($rp->ID);
									$headline = $rp->headline;
									$thumbSrc = wp_get_attachment_image_src( get_post_thumbnail_id($rp->ID) , 'mugshot' );
									$thumbPosition = $rp->adjust_the_banner_image;
									$excerpt = my_excerpt($rp->ID);
									$excerpt = $excerpt . " <a href='$url' class='bbg__about__grandchild__link'>Read more »</a>";
									$excerpt = apply_filters('the_content', $excerpt);
									$excerpt = str_replace(']]>', ']]&gt;', $excerpt);

									// Output variables in HTML format
									echo "<article class='$containerClass bbg__about__grandchild'>";

									if ( $headline ) {
										echo "<h3 class='bbg__about__grandchild__title'><a href='$url'>$headline</a></h3>";
									} else {
										echo "<h3 class='bbg__about__grandchild__title'><a href='$url'>$title</a></h3>";
									}

									if ($thumbSrc) {
										echo "<a href='$url'><div class='bbg__about__grandchild__thumb' style='background-image: url(" . $thumbSrc[0] .  "); background-position: " . $thumbPosition . ";'></div></a>";
									}
									echo $excerpt;
									echo "</article>";
								}
							}
							echo '</div>';
						echo '</section>';
						/*** END DISPLAY OF ENTIRE UMBRELLA ROW ***/

						elseif ( get_row_layout() == 'about_downloads_files' ):
						/*** BEGIN DISPLAY OF DOWNLOAD LINKS ROW ***/
							$downloadFiles = get_sub_field('about_downloads_file');
							$totalFiles = count ( $downloadFiles );

							// Check function return
							if ( checkNum($totalFiles) === TRUE ) {
								// if TRUE: number is odd
								$containerClass = 'bbg-grid--1-3-3';
							} else {
								// if FALSE: number is even
								$containerClass = 'bbg-grid--1-2-2';
							}

							$downloadsLabel = get_sub_field('about_downloads_label');
							$downloadsImage = get_sub_field('about_downloads_image');
							$downloadsIntro = get_sub_field('about_downloads_description');

							//allow shortcodes in intro text
							$downloadsIntro = apply_filters('the_content', $downloadsIntro);
							$downloadsIntro = str_replace(']]>', ']]&gt;', $downloadsIntro);

							if ($downloadsLabel) {
								echo "<h6 class='bbg__label'>$downloadsLabel</h6>";
							}

							// show section image
							if ($downloadsImage) {
								// echo $downloadsImage;
								echo "<div class='single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium' style='background-image: url(" . $downloadsImage .  "); height: 100%; min-height: 150px;'></div>";
							}

							// show umbrella section intro text
							echo "<div class='bbg__about__child__intro'>$downloadsIntro</div>";
							echo "<div class='usa-grid-full bbg__about__grandchildren'>";

							if ( $downloadFiles ) {
								// Loop through all the grandchild pages
								foreach ($downloadFiles as $file) {
									// Define all variables
									$fileName = $file['downloads_link_name'];
									// $fileURL = $file['downloads_file'];
									$fileImageObject = $file['downloads_file_image'];
										// retrieve ID from image object and load "mugshot" size
										$thumbSrc = wp_get_attachment_image_src( $fileImageObject['ID'] , 'mugshot' );
									$fileDescription = $file['downloads_file_description'];
									$fileDescription = apply_filters('the_content', $fileDescription);
									$fileDescription = str_replace(']]>', ']]&gt;', $fileDescription);

									// Files object array
									$fileObj = $file['downloads_file'];
										// file data
										$fileID = $fileObj['ID'];
										$fileURL = $fileObj['url'];
										$file = get_attached_file( $fileID );
										$fileExt = strtoupper( pathinfo($file, PATHINFO_EXTENSION) );
										$fileSize = formatBytes( filesize($file) );

									// Output variables in HTML format
									echo "<article class='$containerClass bbg__about__grandchild'>";
										// Output file title/name
										echo "<h3 class='bbg__about__grandchild__title'><a href='" . $fileURL . "' target='_blank'>" . $fileName ."</a> <span class='bbg__file-size'>(" . $fileExt . ", " . $fileSize . ")</span></h3>";
										// Output file image
										if ( $thumbSrc ) {
											echo "<a href='" . $fileURL . "' target='_blank'>";
												echo "<div class='bbg__about__grandchild__thumb' style='background-image: url(" . $thumbSrc[0] . ");'></div>";
											echo "</a>";
										}
										// Output file description
										if ( $fileDescription ) {
											echo $fileDescription;
										}
									echo "</article>";
								}
							}
							echo '</div>';
						echo '</section>';
						/*** END DISPLAY OF DOWNLOAD LINKS ROW ***/

						elseif( get_row_layout() == 'about_ribbon_page' ):
						/*** BEGIN DISPLAY OF ENTIRE RIBBON ROW ***/
							$labelText = get_sub_field('about_ribbon_label');
							$labelLink = get_sub_field('about_ribbon_label_link');
							$headlineText = get_sub_field('about_ribbon_headline');
							$headlineLink = get_sub_field('about_ribbon_headline_link');
							$summary = get_sub_field('about_ribbon_summary');
							$imageURL = get_sub_field('about_ribbon_image');

							// allow shortcodes in intro text
							$summary = apply_filters('the_content', $summary);
							$summary = str_replace(']]>', ']]&gt;', $summary);

							// echo "<section id='ribbon-children' class='usa-section bbg__about__children bbg__ribbon--thin'>"; // Open new child div
							// echo "<!-- RIBBON: ROW $counter-->"; // add row count
							// echo "<section class='usa-section bbg__ribbon--thin'>"; // open ribbon container

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

		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * template name: Legislation
 *
 * @author Gigi Frias <gfrias@bbg.gov>
 * @package bbgRedesign
 */

$templateName = "legislation";

$bannerPosition = get_field( 'adjust_the_banner_image', '', true);
$videoUrl = get_field( 'featured_video_url', '', true );

$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}

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
					<?php the_title( '<h5 class="bbg-label--mobile large">', '</h5>' ); ?>
					<?php echo $pageTagline; ?>
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
				if( have_rows('about_flexible_page_rows') ):
					$counter = 0;

					while ( have_rows('about_flexible_page_rows') ) : the_row();
						$counter++;

						if ( get_row_layout() != 'about_ribbon_page') {
							//we wrap a  usa-grid container around every row.
							echo '<!-- ROW ' . $counter . '-->';
							echo '<section class="usa-grid-full bbg__about__children--row">';
						}

						if ( get_row_layout() == 'about_multicolumn' ):

							/*** BEGIN DISPLAY OF ENTIRE MULTICOLUMN ROW ***/
							$relatedPages = get_sub_field( 'about_muliticolumn_related_pages' );
							$containerClass = "bbg__about__child";

							if (count($relatedPages) == 2) {
								$containerClass .= ' bbg-grid--1-2-2';
							} else if (count($relatedPages) == 3) {
								$containerClass .= ' bbg-grid--1-3-3';
							}

							foreach ($relatedPages as $rPage) {
								// Define all variables
								$url = get_the_permalink($rPage->ID);
								$title = get_the_title($rPage->ID);
								$excerpt = my_excerpt($rPage->ID);
								$excerpt = $excerpt . " <a href='$url' class='bbg__about__grandchild__link'>Learn more »</a>";
								$excerpt = $excerpt;
								$excerpt = apply_filters('the_content', $excerpt);
								$excerpt = str_replace(']]>', ']]&gt;', $excerpt);

								// Output variables in HTML format
								echo "<article class='bbg__about__excerpt bbg__about__child--" . strtolower(get_the_title()) . " $containerClass'>";
								echo sprintf( "<a href='%s'>", $postPermalink );
								echo "<h6 class='bbg-label'>" . $title . "</h6></a>";
								echo "<div class='entry-content'>";
								echo $excerpt;
								echo "</div>";
								echo "</article>";

							}
						echo "</section>";
						/*** END DISPLAY OF ENTIRE MULTICOLUMN ROW ***/

						elseif( get_row_layout() == 'about_umbrella_page' ):
						/*** BEGIN DISPLAY OF ENTIRE UMBRELLA ROW ***/
							$relatedPages = get_sub_field('about_umbrella_related_pages');
							// $containerClass = "bbg__about__child";

							if ( count( $relatedPages ) == 2 ) {
								$containerClass = 'bbg-grid--1-2-2';
							} else if ( count( $relatedPages ) == 3 ) {
								$containerClass = 'bbg-grid--1-3-3';
							}

							$labelText = get_sub_field('about_umbrella_label');
							$labelLink = get_sub_field('about_umbrella_label_link');
							$introText = get_sub_field('about_umbrella_intro_text');
							$imageURL = get_sub_field('about_umbrella_image');

							//allow shortcodes in intro text
							$introText = apply_filters('the_content', $introText);
							$introText = str_replace(']]>', ']]&gt;', $introText);

							if ($labelLink) {
								echo "<h6 class='bbg-label'><a href='$labelLink'>$labelText</a></h6>";
							} else {
								echo "<h6 class='bbg-label'>$labelText</h6>";
							}

							// show umbrella section intro text
							echo "<div class='bbg__about__child__intro'>$introText</div>";
							echo "<div class='usa-grid-full bbg__about__grandchildren'>";

							if ( $relatedPages ) {
								// Loop through all the grandchild pages
								foreach ($relatedPages as $rp) {
									// Define all variables
									$url = get_the_permalink($rp->ID);
									$commonName = get_the_title($rp->ID);
									$legalName = $rp->headline;
									$excerpt = my_excerpt($rp->ID);
									$excerpt = $excerpt . " <a href='$url' class='bbg__about__grandchild__link'>Learn more »</a>";
									$excerpt = apply_filters('the_content', $excerpt);
									$excerpt = str_replace(']]>', ']]&gt;', $excerpt);

									// Output variables in HTML format
									echo "<article class='$containerClass bbg__about__grandchild'>";
									echo "<h3 class='bbg__about__grandchild__title'><a href='$url'>$commonName</a></h3>";
									echo "<h5 class='bbg__about__grandchild__subtitle'>" . $legalName . "</h5>";
									echo $excerpt;
									echo "</article>";
								}
							}
							echo '</div>';
						echo '</section>';
						/*** END DISPLAY OF ENTIRE UMBRELLA ROW ***/

						elseif( get_row_layout() == 'about_ribbon_page' ):
						/*** BEGIN DISPLAY OF ENTIRE RIBBON ROW ***/
							$labelText = get_sub_field('about_ribbon_label');
							$labelLink = get_sub_field('about_ribbon_label_link');
							$headlineText = get_sub_field('about_ribbon_headline');
							$headlineLink = get_sub_field('about_ribbon_headline_link');
							$summary = get_sub_field('about_ribbon_summary');
							$imageURL = get_sub_field('about_ribbon_image');

							// allow shortcodes in intro text
							$summary = apply_filters('the_content', $introText);
							$summary = str_replace(']]>', ']]&gt;', $introText);

							echo "</div>"; // closes <div id="page-children" class="usa-section usa-grid bbg__about__children">
							echo "<section id='ribbon-children' class='usa-section bbg__about__children bbg__ribbon--thin'>"; // Open new child div
							echo "<!-- RIBBON: ROW ' . $counter . '-->"; // add row count
							// echo "<section class='usa-section bbg__ribbon--thin'>"; // open ribbon container

							echo "<div class='usa-grid'>";
								echo "<div class='bbg__announcement__flexbox'>";

									if ($imageURL) {
										echo "<div class='bbg__announcement__photo' style='background-image: url($imageURL);'></div>";
									}

									echo "<div>";

										if ($labelLink) {
											echo "<h6 class='bbg-label'><a href='" . get_permalink($labelLink) . "'>$labelText</a></h6>";
										} else {
											echo "<h6 class='bbg-label'>$labelText</h6>";
										}

										if ($headlineLink) {
											echo "<h2><a href='" . get_permalink($headlineLink) . "'>$headlineText</a></h2>";
										} else {
											echo "<h2>$headlineText</h2>";
										}

										echo $summary;

									echo "</div>";
								echo "</div>";
							// echo "</section>";
						echo "</section>";
						/*** END DISPLAY OF ENTIRE RIBBON ROW ***/
						endif;
					endwhile;
					echo '<!-- END ROWS -->';
				endif;
			?>
			</div>
			<?php wp_reset_postdata(); ?>

		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
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

			<!-- Page header -->
			<div id="page-header" class="usa-grid">
				<!-- Parent title -->
				<?php if ($post->post_parent) {
					$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
					$parent_link = get_permalink($post->post_parent);
				?>
					<h5 class="entry-category bbg-label"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>
				<?php } ?>

				<!-- Page title -->
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
			</div>

			<!-- Page introduction (content) -->
			<section id="page-intro" class="usa-section usa-grid bbg__about__intro">
				<?php echo $pageContent; ?>
			</section>

			<!-- Child pages -->
			<section id="page-children" class="usa-section usa-grid bbg__about__children">
			<?php
				// check if the flexible content field has rows of data
				if( have_rows('about_flexible_page_rows') ):
					$counter = 0;
					while ( have_rows('about_flexible_page_rows') ) : the_row();
						$counter++;

						//we wrap a  usa-grid container around every row.
						echo '<!-- ROW ' . $counter . '-->';
						echo '<div class="usa-grid-full bbg__about__children--row">';

						if( get_row_layout() == 'about_multicolumn' ):

							/*** BEGIN DISPLAY OF ENTIRE MULTICOLUMN ROW ***/
							$relatedPages = get_sub_field( 'about_muliticolumn_related_pages' );
							$containerClass = "bbg__about__child";

							if (count($relatedPages) == 2) {
								$containerClass='bbg-grid--1-2-2';
							} else if (count($relatedPages) == 3) {
								$containerClass='bbg-grid--1-3-3';
							}

							foreach ($relatedPages as $rPage) {
								echo "<div class='usa-grid-full bbg__about__children--row'>";

								$rPageHeadline = $rPage->headline;

								$qParams = array(
									'post_type' => 'page',
									'post_status' => 'publish',
									'post__in' => array( $rPage->ID )
								);

								query_posts( $qParams );

								if ( have_posts() ) {
									while ( have_posts() ) {
										the_post();
										$gridClass = $containerClass;
										$headline = $rPageHeadline;
										$includePortfolioDescription = TRUE;
										get_template_part( 'template-parts/content-about', get_post_format() );
									}
								}
								wp_reset_query();
							}
						echo "</div>";

						/*** END DISPLAY OF ENTIRE MULTICOLUMN ROW ***/
						elseif( get_row_layout() == 'about_umbrella_page' ):
						/*** BEGIN DISPLAY OF ENTIRE UMBRELLA ROW ***/
							$relatedPages = get_sub_field('about_umbrella_related_pages');
							$containerClass = "bbg__about__child";

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
								echo "<h6 class='bbg-label'><a href='$labelLink'>$labelText</a> <span class='bbg__links--right-angle-quote' aria-hidden=”true”>&raquo;</span></h6>";
							} else {
								echo "<h6 class='bbg-label'>$labelText</h6>";
							}

							// echo <div style=""
							// show umbrella section image
							if ($imageURL) {
								$image = '<div class="bbg__about__child__banner" style="background-image: url(' . $imageURL . ');">';

								// echo '<div class="usa-grid-full">';
								echo $image;
								// echo '</div>';
								echo '</div>';
							}

							// show umbrella section intro text
							echo "<div class='usa-font-lead bbg__about__child'>$introText</div>";

							if ( $relatedPages ) {
								echo "<div class='usa-grid-full'>";
								foreach ($relatedPages as $rp) {
									echo "<article class='$containerClass bbg__about__grandchild'>";
									$excerpt = my_excerpt($rp->ID);
									$excerpt = apply_filters('the_content', $excerpt);
									$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
									$title = get_the_title($rp->ID);
									$url = get_the_permalink($rp->ID);
									echo "<h3><a href='$url'>$title</a></h3>";
									echo $excerpt;
									echo "</article>";
								}
								echo "</div>";
							}
						/*** END DISPLAY OF ENTIRE UMBRELLA ROW ***/
						echo '</div>';
						endif;
					endwhile;
					echo '<!-- END ROWS -->';
					// echo '</div>'; //usa-grid-full
				endif;
			?>
			</section>
			<?php wp_reset_postdata(); ?>

		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>

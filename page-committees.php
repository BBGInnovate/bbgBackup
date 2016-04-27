<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Committees
 */

$pageContent ="";
while ( have_posts() ) : the_post();
	$pageContent=get_the_content();
	$pageContent = apply_filters('the_content', $pageContent);
	$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
endwhile;

$committeeStr = '<div class="usa-grid-full">committees will go here</div>';
$pageContent = str_replace("[committees]", $committeeStr, $pageContent);

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

			<?php
				$hideFeaturedImage = get_post_meta( get_the_ID(), "hide_featured_image", true );
				if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
					echo '<div class="usa-grid-full">';
					$featuredImageClass = "";
					$featuredImageCutline="";
					$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
					if ($thumbnail_image && isset($thumbnail_image[0])) {
						$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
					}
					echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
					//echo '<div style="position: absolute;"><h5 class="bbg-label">Label</h5></div>';
					echo the_post_thumbnail( 'large-thumb' );

					echo '</div>';
					echo '</div> <!-- usa-grid-full -->';

					if ($featuredImageCutline != "") {
						echo '<div class="usa-grid">';
							echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
						echo '</div> <!-- usa-grid -->';
					}
				}
			?><!-- .bbg__article-header__thumbnail -->


				<section class="usa-section usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'about-the-agency/history/congressional-oversight-committees/' ) ); ?>" title="A list of  BBG relateed Congressional Oversiht Committees.">Congressional Oversight Committees</a></h6>
					<div class="bbg-grid__container">

						<?php 

							echo $pageContent;

						?>
					</div><!-- .bbg-grid__container -->
				</section>

				


			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>



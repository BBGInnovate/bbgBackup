<?php
/**
 * The template for containing maps created using our ammap.js Vector maps
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * template name: Map Container Hotspot
 */

$pageContent="";
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
echo getNetworkExcerptJS();
 ?>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/vendor/ammap.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/mapdata-worldLow.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/map-hotspot.js'></script>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<div class="usa-grid">
					<header class="page-header">
						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<h5 class="bbg__label--mobile large"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>
						<?php } ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .page-header -->
				</div><!-- div.usa-grid -->
			</div><!-- div.usa-grid-full -->
			<section class="usa-section">
				<div class="bbg__map-area__container " style="postion: relative;">
					<div id="chartdiv"></div>
				</div>
			</section>
			<section id="" class="usa-section usa-grid" style="margin-bottom: 2rem;">
				<?php echo $pageContent; ?>
			</section>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
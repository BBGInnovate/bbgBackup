<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * template name: RSS - Media Clips
 */

$parentTitle = "";
if($post->post_parent) {
	$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
	$parentTitle = $parent->post_title;
}

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

$today = date('Ymd');
$qParams = array(
	'post_type' => array( 'media_clip' ),
	'posts_per_page' => 999,
	'orderby' => 'meta_value',
	'meta_key' => 'mail_date'
	'order', 'DESC',
	'meta_query' => array(
		array(
			'key'		=> 'mail_date',
			'compare'	=> '=',
			'value'		=> $today,
		)
	)
);
$custom_query = new WP_Query( $qParams );
if ( $custom_query -> have_posts() ) {
	while ( $custom_query -> have_posts() )  {
		$custom_query -> the_post();
		$id = get_the_ID();
		$pressReleases[] = array( 'url' => get_permalink($id), 'title' => get_the_title($id), 'excerpt' => get_the_excerpt());
	}
}
echo "<pre>";
var_dump($pressReleases);
echo "</pre>";

die();

wp_reset_postdata();


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid-full">

				<?php while ( have_posts() ) : the_post();
					
					get_template_part( 'template-parts/content', 'page' );

				?>

					<div class="bbg-post-footer">
					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>
					</div>

				<?php endwhile; // End of the loop. ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

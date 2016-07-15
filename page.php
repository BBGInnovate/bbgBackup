<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

$parentTitle = "";
if($post->post_parent) {
	$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
	$parentTitle = $parent->post_title;
}


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid-full">

				<?php while ( have_posts() ) : the_post();
					$parentID = wp_get_post_parent_id( $post_ID );

					// IF the page is a law under the Legislation parent:
					if ($parentTitle == "Legislation") {
					//if ($parentID == 3243) {
						get_template_part( 'template-parts/content-law', 'page' );
					} /*elseif ($parentTitle == "Apps") {
						// IF the page is a mobile app project under the Apps parent:
						get_template_part( 'template-parts/content-page-project', 'page' );
					} */ else {
						get_template_part( 'template-parts/content', 'page' );
					}
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

<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid-full">

				<?php while ( have_posts() ) : the_post();
					$parentID = wp_get_post_parent_id( $post_ID );

					// IF the page is a law under the Legislation parent:
					if ($parentID == 3243) {
						get_template_part( 'template-parts/content-law', 'page' );
					} else {
						get_template_part( 'template-parts/content', 'page' );
					}


					// $sidebarTitle = get_sub_field( 'sidebar_title' );
					// $sidebarContent = get_sub_field( 'sidebar_items' );


					/*foreach ($sidebarContent as $rPage) {
						$rPageHeadline = $rPage->headline;
						$rPageTagline = $rPage->page_tagline;
						$rHideLink = $rPage->hide_link;

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
								$headline = $rPageHeadline; // custom field for secondary page headline
								$tagline = $rPageTagline; // custom field for page tagline
								$hideLink = $rHideLink;
								$includePageDescription = TRUE;
								get_template_part( 'template-parts/content-about', get_post_format() );
							}
						}
						wp_reset_query();
					}*/

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

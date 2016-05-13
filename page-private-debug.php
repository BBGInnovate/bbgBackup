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
   template name: Debugger
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<?php 
					$templates =  wp_get_theme()->get_page_templates();
					foreach ( $templates as $template_filename => $template_name ) {
						echo "<h3>$template_name ($template_filename)</h3>";
						$pages = get_pages(array(
							'meta_key' => '_wp_page_template',
							'meta_value' => "$template_filename"
						));
						echo "<ul style='margin-left:20px'>";
						foreach($pages as $page){

							echo "<li><a target='_blank' href='$page->guid'>$page->post_title</a></li>";
var_dump($page);
							echo "<li><a target='_blank' href='" . get_permalink($page->ID) . "'>$page->post_title</a></li>";
						}
						echo "</ul>";
					}
				 ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

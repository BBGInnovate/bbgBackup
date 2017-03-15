<?php
/**
 * This private page is a utility to show the number of pages built using a particualr template.
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Utility: Map Data: Language Service Countries
 */

function getMapData() {

	$terms = get_terms( "language_services" , array('hide_empty' => false));
	foreach ($terms as $t) {
		echo "<h3>" . $t->name ."</h3>";
		$qParams=array(
			'post_type' => 'country'
			,'post_status' => array('publish')
			,'posts_per_page' => -1
			,'orderby' => 'post_title'
			,'order' => 'asc'
			,'tax_query' => array(
			    array(
			        'taxonomy' => 'language_services',
			        'field' => 'slug',
			        'terms' => array ($t->slug)
			    )
			)
		);
		$custom_query = new WP_Query($qParams);
		$countries = array();
		while ( $custom_query -> have_posts() )  {
			$custom_query->the_post();
			$id = get_the_ID();
			$countryName = get_the_title();
			$countries []= $countryName;
		}
		echo "<ul>";
		foreach ($countries as $c) {
			echo "<li>$c</li>";
		}
		echo "</ul>";
	}
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<?php getMapData(); ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

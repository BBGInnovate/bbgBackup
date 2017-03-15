<?php
/**
 * This private page is a utility to show the number of pages built using a particualr template.
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Utility: Map Data: Language Service Countries
 */

function outputTerm($t, $headerTag) {
	echo "<$headerTag>" . $t->name ."</$headerTag>";
	$termMeta = get_term_meta( $t->term_id );
	
	$siteName = "";
	$siteUrl = "";
	if ( count( $termMeta ) ) {
		$siteName = $termMeta['language_service_site_name'][0];
		$siteUrl = $termMeta['language_service_site_url'][0];
	}
	echo "<em>Links to: </em>&nbsp; <a href='$siteUrl' target='_blank'>" . $siteUrl . "</a><BR>";
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
	// echo "<ul style='margin-top:1em;'>";
	// foreach ($countries as $c) {
	// 	echo "<li>$c</li>";
	// }
	// echo "</ul>";
	echo "<em>Countries: </em>&nbsp;" ;
	$i=0;
	
	foreach ($countries as $c) {
		$i++;
		if ($i > 1) {
			echo ", ";
		}
		echo $c;
	}

	echo "<BR><BR>";
}

function getMapData() {

	$networks = array();
	$terms = get_terms(  "language_services" , array('hide_empty' => false));
	if ($terms) {
		$categoryHierarchy = array();
		sort_terms_hierarchically($terms, $categoryHierarchy);
	}

	foreach ($categoryHierarchy as $ch) {
		outputTerm($ch, "h2");
		echo "<div style='margin-left:2em;'>";
		foreach ($ch->children as $t) {
			outputTerm($t, "h3");
		}
		echo "</div>";
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

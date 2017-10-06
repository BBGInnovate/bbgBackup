<?php 

/*
    Author: Joe Flowers
    Description: The map data for worldwide ops is represented as follows:
    	custom post type "country"
    	custom taxonomy called "Language Services"
    	custom field "website name" and "website url" on each language service taxonomy term
    	
    	To keep the data model simple and in a format that could be managed with WordPress's built in taxonomy and custom field capabilities, we elected to duplicate language services when they have more than one website. For instance, many RFERL services have sites in both their native language as well as Russian.

        Data source: We asked the Office of Policy and Research to provide us a list of countries that each of our five networks target, and also which language services are used to target each country. 
*/
?>

<style>
	h1,h2 { margin-bottom:0px; }
</style>

<a href="outputCountries.php">View By Country</a> | <a href="outputServices.php">View By Service</a> <BR />

<?php 
require ('../../../../wp-load.php');
function getMapData() {

	$entities = array(
		'voa' => array(
			'countries' => array()
			,'services' => array()
		),
		'rferl' => array(
			'countries' => array()
			,'services' => array()
		),
		'ocb' => array(
			'countries' => array()
			,'services' => array()
		),
		'rfa' => array(
			'countries' => array()
			,'services' => array()
		),
		'mbn' => array(
			'countries' => array()
			,'services' => array()
		),
	);

	$qParams=array(
		'post_type' => 'country'
		,'post_status' => array('publish')
		,'posts_per_page' => -1
		,'orderby' => 'post_title'
		,'order' => 'asc'
	);
	$custom_query = new WP_Query($qParams);
	$countries = array();

	while ( $custom_query -> have_posts() )  {
		$custom_query->the_post();
		$id = get_the_ID();
		$countryName = get_the_title();
		$countryAmmapCode = get_post_meta( $id, 'country_ammap_country_code', true );

		$networks = array();
		$terms = get_the_terms( $id, "language_services" , array('hide_empty' => false));
		if ($terms) {
			$categoryHierarchy = array();
			sort_terms_hierarchically($terms, $categoryHierarchy);
			foreach ( $categoryHierarchy as $t ) {
				$n1 = array(
					'networkName' => $t->name,
					'services' => array()
				);
				$entities [strtolower($t->name)]['countries'][$countryName] = 1;
				foreach ($t->children as $service) {
					$n1['services'][]= $service -> name;
				}
				$networks []= $n1;
			}
		}
		$countries[$countryName] = array(
			"countryName" => $countryName,
			"ammapCode" => $countryAmmapCode,
			"networks" => $networks
		);

	}
	$terms = get_terms( array(
		'taxonomy' => 'language_services',
		'hide_empty' => false,
	) );

	$parentTerms = array();
	foreach ( $terms as $t ) {
		$isParent = ($t -> parent == 0);
		if ($isParent) {
			$parentTerms[$t->term_id] = $t->name;
		}
	}

	$servicesByName = array();
	foreach ( $terms as $t ) {
		$isParent = ($t -> parent == 0);
		$parentTerm="";

		if (!$isParent) {
			$parentTerm = $parentTerms[$t->parent];
		}

		$termMeta = get_term_meta( $t->term_id );

		$siteName = "";
		$siteUrl = "";
		if ( count( $termMeta ) ) {
			$siteName = $termMeta['language_service_site_name'][0];
			$siteUrl = $termMeta['language_service_site_url'][0];
		}
		$servicesByName[$t->name] = array(
			'serviceName' => $t->name
			,'siteName' => $siteName
			,'siteUrl' => $siteUrl
			,'parent' => $parentTerm
			,'countries' => array() //filled out by JS
		);
	}
	
	foreach ($countries as $c) {
		echo "<h1><strong style='font-size:28px;'>" . $c['countryName'] . "</strong></h1>";
		$networks = $c['networks'];
		echo "<ul>";
		for ($j = 0; $j < count($networks); $j++) {
			echo "<li style='font-size:22px;'>" . $networks[$j]['networkName'] . "</li>";
			$services = $networks[$j]['services'];
			echo "<ul>";
			for ($k=0; $k < count($services); $k++) {
				$s = $services[$k];
				echo "<li style='font-size:18px;'>" . $s . " <span style='font-size:12px;'>" . $servicesByName[$s]['siteUrl'].  "</span></li>";
			}
			echo "</ul>";
		}
		echo "</ul>";
		echo "<BR>";
	}

	if (isset($_GET['json'])) {
		echo "<pre>";
		var_dump($countries);
		echo "</pre>";
	}
}?>

<?php getMapData(); ?>


<?php
define('WP_USE_THEMES', true);
require ('../../wp-blog-header.php');

if ($_GET['endpoint'] == 'networkNews') {

	$postsPerPage =20;

	$qParams=array(
		'post_type' => array('post')
		,'cat' => get_cat_id('Map it')
		,'posts_per_page' => $postsPerPage
		,'post_status' => array('publish')
	);

	/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
	$custom_query_args= $qParams;
	$custom_query = new WP_Query( $custom_query_args );

	$features = array();

	if ( $custom_query->have_posts() ) :
			$counter = 0;
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$id = get_the_ID();
				$location = get_post_meta( $id, 'map_location', true );
				$storyLink = get_permalink();
				$mapHeadline = get_post_meta( $id, 'map_headline', true );
				//$mapHeadline = "<a href='". $storyLink ."'>" . $mapHeadline . '</a>';

				$mapDescription = get_the_title();
				$mapDate = get_the_date();
				$mapDescription = $mapDescription . " <span class='bbg__map__infobox__date'>(" . $mapDate . ")</span>";

				$pinColor = "#981b1e";
				if (has_category('VOA')){
					$pinColor = "#344998";
					$mapHeadline = "<h5><a href='". $storyLink ."'>VOA | " . $mapHeadline . '</a></h5>';
				} elseif (has_category('RFA')){
					$pinColor = "#009c50";
					$mapHeadline = "<h5><a href='". $storyLink ."'>RFA | " . $mapHeadline . '</a></h5>';
				} elseif (has_category('RFE/RL')){
					$pinColor = "#ea6828";
					$mapHeadline = "<h5><a href='". $storyLink ."'>RFE/RL | " . $mapHeadline . '</a></h5>';
				} else {
					$mapHeadline = "<h5><a href='". $storyLink ."'>" . $mapHeadline . '</a></h5>';
				}
				$features[] = array(
					'type' => 'Feature',
					'geometry' => array( 
						'type' => 'Point',
						'coordinates' => array($location['lng'],$location['lat'])
					),
					'properties' => array(
						'title' => $mapHeadline,
						'description' => $mapDescription,
						'marker-color' => $pinColor,
						'marker-size' => 'large', 
						'marker-symbol' => ''
					)
				);
			endwhile;
			$geojsonObj= array(array(
				'type' => 'FeatureCollection',
				'features' => $features
			));
			$geojsonStr=json_encode(new ArrayValue($geojsonObj), JSON_PRETTY_PRINT, 10);

			echo "<script type='text/javascript'>\n";
			echo "geojson = $geojsonStr";
			echo "</script>";
			//echo $geojsonStr;

	endif; 
}
?>
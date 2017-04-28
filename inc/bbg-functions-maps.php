<?php

	function pressFreedomMap() {
	$freeNotFreeObj = array_map('str_getcsv', file(get_stylesheet_directory_uri() . '/data/freeNotFree.csv'));
	if (count($freeNotFreeObj)) {

		//remove the first row from the array because it's headers, not data
		array_shift($freeNotFreeObj);
	}
	$freeNotFreeStr = json_encode(new ArrayValue($freeNotFreeObj), JSON_PRETTY_PRINT);	
    ob_start();
?>
		<!-- Styles -->
		<style>
			/* start expanding the print styles */
			#chartdiv {
				border: 1px solid #CCC;
				font-size: 11px;
				height: 200px;
				width: 100%;
		}
		@media screen and (min-width: 600px) {
			#chartdiv {
				height: 400px;
			}
		}
		@media screen and (min-width: 900px) {
			#chartdiv {
				height: 500px;
			}
		}
		.amcharts-legend-div {
			position: fixed !important;
			top: 520px !important;
			padding: 10px;
		}

		.amcharts-chart-div > a {
			display: none !important;
		}

		#loading {
			display: none;
			z-index: 9997; 
			position: absolute;
			bottom: 5%;
			left: 5%;
			width: 50px;
			height: 50px;
		}
		.legendBox {
			width: 15px;
			height: 15px;
			display:inline-block;
			background: #000000;
		}
		#main-content {
			padding-top: 0px !important;
		}
		#legendContainer {
			margin-top: 1rem;
		}
	</style>
	<!-- Resources -->
	<script src="https://www.amcharts.com/lib/3/ammap.js"></script>
	<script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>

	<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri(); ?>/data/threats.js'></script>
	<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri(); ?>/js/map-pressfreedom.js'></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <h2>Press Freedom Scores</h2>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non lacus velit. Proin porta ultricies ex non vulputate. Aenean maximus convallis varius. Invidual threats are mapped below with the <i class="fa fa-map-pin" aria-hidden="true"></i> icon and you may <a style="text-decoration: underline;" href="https://www.bbg.gov/2016-threats-archive/" target="_blank">view a full list</a> on the bbg.gov site.</p> -->
	
	<div class="bbg__map-area__container " style="postion: relative;">
		<div id="chartdiv"></div>
		<div align="center" id="legendContainer">
					<div align="center" >
						<div class="legendBox free"></div> Free 
						<div class="legendBox partially-free"></div> Partially Free 
						<div class="legendBox not-free"></div> Not Free 
					</div>
				</div>
	</div>
<?php 
	echo "<script type='text/javascript'>\n";
	echo "freeNotFree = $freeNotFreeStr";
	echo "</script>";
	$str = ob_get_clean();
	return $str;
	}

add_shortcode( 'pressFreedomMap', 'pressFreedomMap' );

/*$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];
<section class="usa-section">
<div class="usa-grid">
</div>
</section>*/

	function getFeaturedMapGeoJsonStr( $featuredMapItems ) {
		$features = [];

		for ($i=0; $i < count($featuredMapItems); $i++) {
			$item = $featuredMapItems[$i];
			$featuredMapItemLocation = $item['featured_map_item_coordinates'];
			$featuredMapItemTitle = $item['featured_map_item_title'];
			$featuredMapItemDescription = $item['featured_map_item_description'];
			$featuredMapItemLink = $item['featured_map_item_link'];
			$featuredMapItemVideoLink = $item['featured_map_item_video_link'];
			$im = $item['featured_map_item_image'];

			$featuredMapItemImageUrl = $im['sizes']['medium'];

			$popupBody = "";
			if ($featuredMapItemLink != "") {
				$popupBody .= "<h5><a style='font-weight: bold; ' href='$featuredMapItemLink'>$featuredMapItemTitle</a></h5><div class='u--show-medium-large'><img src='$featuredMapItemImageUrl'></div><BR>$featuredMapItemDescription";
			} else {
				$popupBody .= "<h5><span style='font-weight: bold;'>$featuredMapItemTitle</span></h5><div class='u--show-medium-large'><img src='$featuredMapItemImageUrl'></div><BR>$featuredMapItemDescription";
			}

			$features[] = array(
				'type' => 'Feature',
				'geometry' => array(
					'type' => 'Point',
					'coordinates' => array($featuredMapItemLocation['lng'],$featuredMapItemLocation['lat'])
				),
				'properties' => array(
					'title' => "<a href='$featuredMapItemLink'>$featuredMapItemTitle</a>",
					'description' => $popupBody,
					'marker-size' => 'large',
					'marker-symbol' => ''
				)
			);
		}
		$geojsonObj= array(array(
			'type' => 'FeatureCollection',
			'features' => $features
		));
		$geojsonStr=json_encode(new ArrayValue($geojsonObj), JSON_PRETTY_PRINT, 10);
		return $geojsonStr;
	}
?>
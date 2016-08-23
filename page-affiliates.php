<?php
/**
 * The template for displaying highlights from across the 5 BBG entities.
 * Features a banner map of recent headlines about the entities
 * and a subsection for each of the entities.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Affiliates
 */

$pageTitle="";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageTitle = get_the_title();
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();


$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg__label--mobile large"><?php echo $pageTitle; ?></h5>
					<?php echo $pageTagline; ?>
				</header><!-- .page-header -->
			</div>

			<!-- this section holds the map and is populated later in the page by javascript -->
			<section class="usa-section">
				<div id="map" class="bbg__map--banner"></div>
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

	$string = file_get_contents( get_template_directory() . "/external-feed-cache/affiliates.json");
	$allAffiliates = json_decode($string, true);
	$counter=0;

	foreach ($allAffiliates as $a) {
		$counter++;
		if ($counter < 3000) {
			$title = "<h5><a href='#'>" . $a[0] . "</a></h5>";
			$lat = $a[1];
			$lon = $a[2];
			$city = $a[3];
			$country = $a[4];
			$freq = $a[5];
			$url = $a[6];
			$smurl = $a[7];
			$platform = $a[8];

			$features[] = array(
				'type' => 'Feature',
				'geometry' => array( 
					'type' => 'Point',
					'coordinates' => array($lon,$lat)
				),
				'properties' => array(
					'title' => $title,
					'description' => "<strong>Location: </strong>$city<BR><strong>Delivery Platform: </strong>$platform<BR>",
					'marker-color' => "#344998",
					'marker-size' => 'large', 
					'marker-symbol' => ''
				)
			);
		}
	}

	$geojsonObj= array(array(
		'type' => 'FeatureCollection',
		'features' => $features
	));
	$geojsonStr=json_encode(new ArrayValue($geojsonObj), JSON_PRETTY_PRINT, 10);

	echo "<script type='text/javascript'>\n";
	echo "geojson = $geojsonStr";
	echo "</script>";
	//echo $geojsonStr;


?>




<?php /* include map stuff -------------------------------------------------- */ ?>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />

<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />

<style>
	.marker-cluster-small {
		background-color: rgba(255, 255, 255, 0.6) !important;
	}
	.marker-cluster-small div {
		background-color: rgba(255, 0, 0, 0.6) !important;
	}
</style>

<script type="text/javascript">
L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';

//console.log(geojson[0].features[0].properties);
//console.log('description: '+ geojson[0].features[0].properties['description'])

var map = L.mapbox.map('map', 'mapbox.emerald')
//        .setView([-37.82, 175.215], 14);

    var markers = new L.MarkerClusterGroup({
    	maxClusterRadius:20,
		iconCreateFunction: function (cluster) {
			var childCount = cluster.getChildCount();
			var c = ' marker-cluster-';
			if (childCount < 10) {
			    c += 'small';
			} else if (childCount < 100) {
			    c += 'medium';
			} else {
			    c += 'large';
			}
			return new L.DivIcon({ html: '<div><span><b>' + childCount + '</b></span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
		}
	});

    for (var i = 0; i < geojson[0].features.length; i++) {
        var coords = geojson[0].features[i].geometry.coordinates;
        var title = geojson[0].features[i].properties.title; //a[2];
        var description = geojson[0].features[i].properties['description'];
        var marker = L.marker(new L.LatLng(coords[1], coords[0]), {
            icon: L.mapbox.marker.icon({
            	'marker-symbol': '', 
            	'marker-color': geojson[0].features[i].properties['marker-color']
           	})
        });
        var popupText = title + description;
        marker.bindPopup(popupText);
        markers.addLayer(marker);
    }

    map.addLayer(markers);

	//Disable the map scroll/zoom so that you can scroll the page.
	map.scrollWheelZoom.disable();

	function centerMap(){
		map.fitBounds(markers.getBounds());
	}

	centerMap();


	//Recenter the map on resize
	function resizeStuffOnResize(){
	  waitForFinalEvent(function(){
			centerMap();
	  }, 500, "some unique string");
	}

	//Wait for the window resize to 'end' before executing a function---------------
	var waitForFinalEvent = (function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId) {
				uniqueId = "Don't call this twice without a uniqueId";
			}
			if (timers[uniqueId]) {
				clearTimeout (timers[uniqueId]);
			}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();

	window.addEventListener('resize', function(event){
		resizeStuffOnResize();
	});

	resizeStuffOnResize();
</script>



<?php get_sidebar(); ?>
<?php get_footer(); ?>


<?php /*

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
			}*/ ?>

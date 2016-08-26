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
$pageContent = "";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageTitle = get_the_title();
		$pageContent = get_the_content();
		$pageContent = apply_filters( 'the_content', $pageContent );
		$pageContent = str_replace( ']]>', ']]&gt;', $pageContent );
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();


$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}

$string = file_get_contents( get_template_directory() . "/external-feed-cache/affiliates.json");
$allAffiliates = json_decode($string, true);
$counter=0;

foreach ($allAffiliates as $a) {
	$counter++;
	if ($counter < 3000) {
		//$title = "<h5><a href='#'>" . $a[0] . "</a></h5>";
		$title = $a[0];
		$lat = $a[1];
		$lon = $a[2];
		$city = $a[3];
		$country = $a[4];
		$freq = $a[5];
		$url = $a[6];
		$smurl = $a[7];
		$platform = $a[8];

		$headline = "<h5>" . $title . "</h5>";
		if ($url != "") {
			if (strpos($url, "http") === false) {
				///echo "fixing " . $url . "<BR>";
				$url = "http://" . $url;
			}
			$headline = "<h5><a target='_blank' href='" . $url . "'>" . $title . "</a></h5>";
		}


		$features[] = array(
			'type' => 'Feature',
			'geometry' => array( 
				'type' => 'Point',
				'coordinates' => array($lon,$lat)
			),
			'properties' => array(
				'title' => $headline,
				'description' => "<strong>Location: </strong>$city<BR><strong>Delivery Platform: </strong>$platform<BR>",
				'marker-color' => "#344998",
				'marker-size' => 'large', 
				'marker-symbol' => '',
				'platform' => $platform
			)
		);
	}
}

$geojsonObj= array(array(
	'type' => 'FeatureCollection',
	'features' => $features
));
$geojsonStr=json_encode(new ArrayValue($geojsonObj), JSON_PRETTY_PRINT, 10);


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
			<section class="usa-section" style="position: relative;">
				<div id="map" class="bbg__map--banner"></div>

				
				<img id="resetZoom" src="<?php echo get_template_directory_uri(); ?>/img/home.png" class="bbg__map__button"/>
<!--
				<img id="filter_radio" src="<?php echo get_template_directory_uri(); ?>/img/Studio-mic-icon.png" style="width:30px; height:30px; cursor:pointer;"/>Radio
				<img id="filter_television" src="<?php echo get_template_directory_uri(); ?>/img/tv-icon.png" style="width:30px; height:30px; cursor:pointer;"/>TV
				<img id="filter_mobile" src="<?php echo get_template_directory_uri(); ?>/img/iPhone-Icon.png" style="width:30px; height:30px; cursor:pointer;"/>Mobile
				<img id="filter_internet" src="<?php echo get_template_directory_uri(); ?>/img/3d-glasses-icon.png" style="width:30px; height:30px; cursor:pointer;"/>Internet

				"864930000" => "Radio",
		"864930001" => "TV",
		"864930002" => "Newspaper",
		"864930003" => "Satellite",
		"864930004" => "Web",
		"864930005" => "Mobile",
		"864930006" => "Other",
				-->
				<style> 
					#mapFilters label { margin-left:15px; }
				</style>
				<div align="center" id="mapFilters">
					<input type="radio" checked name="deliveryPlatform" id="delivery_all" value="all" /><label for="delivery_all"> All</label>
					<input type="radio" name="deliveryPlatform" id="delivery_radio" value="radio" /><label for="delivery_radio"> Radio</label>
					<input type="radio" name="deliveryPlatform" id="delivery_tv" value="tv" /><label for="delivery_tv"> TV</label>
					<input type="radio" name="deliveryPlatform" id="delivery_web" value="web" /><label for="delivery_web"> Web</label>
					<input type="radio" name="deliveryPlatform" id="delivery_other" value="other" /><label for="delivery_other"> Other</label>
					<input type="radio" name="deliveryPlatform" id="delivery_satellite" value="satellite" /><label for="delivery_satellite"> Satellite</label>
					<input type="radio" name="deliveryPlatform" id="delivery_newspaper" value="newspaper" /><label for="delivery_newspaper"> Newspaper</label>
					<input type="radio" name="deliveryPlatform" id="delivery_mobile" value="mobile" /><label for="delivery_mobile"> Mobile</label>
				</div>
			</section>

			<section class="usa-section">
				<div class="usa-grid" style="margin-bottom: 3rem">
					<?php 
						/*<h2 class="entry-title bbg-blog__excerpt-title--featured"> echo $pageTitle; </h2> */
					?>
					<?php
						echo '<h3 class="usa-font-lead">';
						echo $pageContent; // or $pageExcerpt
						echo '</h3>';
					?>
				</div>
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

	echo "<script type='text/javascript'>\n";
	echo "geojson = $geojsonStr";
	echo "</script>";
	//echo $geojsonStr;
	//http://gis.stackexchange.com/questions/182442/whats-the-most-appropriate-way-to-load-mapbox-studio-tiles-in-leaflet

?>

<?php /* include map stuff -------------------------------------------------- */ ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0-rc.3/leaflet.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0-rc.3/leaflet.js"></script>
<!-- <link rel="stylesheet" href="http://ghybs.github.io/Leaflet.FeatureGroup.SubGroup/examples/screen.css" /> -->
<link rel="stylesheet" href="https://cdn.rawgit.com/Leaflet/Leaflet.markercluster/v1.0.0-beta.2.0/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://cdn.rawgit.com/Leaflet/Leaflet.markercluster/v1.0.0-beta.2.0/dist/MarkerCluster.Default.css" />
<script src="https://cdn.rawgit.com/Leaflet/Leaflet.markercluster/v1.0.0-beta.2.0/dist/leaflet.markercluster-src.js"></script>
<script src="https://cdn.rawgit.com/ghybs/Leaflet.FeatureGroup.SubGroup/v1.0.0/dist/leaflet.featuregroup.subgroup-src.js"></script>
<script src="https://cdn.rawgit.com/jseppi/Leaflet.MakiMarkers/master/Leaflet.MakiMarkers.js"></script>

<style>

	.marker-cluster-small {
		background-color: rgba(241, 211, 87, 0);
	}
	.marker-cluster-small div {
		background-color: rgba(240, 194, 12, 1);
	}

	.marker-cluster-medium {
		background-color: rgba(253, 156, 115, 0);
	}
	.marker-cluster-medium div {
		background-color: rgba(241, 128, 23, 1);
	}

	.marker-cluster-large { 
		background-color: rgba(255, 0, 0, 0);
	}
	.marker-cluster-large div {
		background-color: rgba(255, 0, 0, 1);
	}

</style>

<script type="text/javascript">
	//var tilesetUrl = 'https://api.mapbox.com/styles/v1/mapbox/emerald-v8/tiles/{z}/{x}/{y}?access_token=<?php echo MAPBOX_API_KEY; ?>';
	var mbToken = '<?php echo MAPBOX_API_KEY; ?>';
	var tilesetUrl = 'https://a.tiles.mapbox.com/v4/mapbox.emerald/{z}/{x}/{y}@2x.png?access_token='+mbToken;
	var attribStr = '&copy; <a href="https://www.mapbox.com/map-feedback/">Mapbox</a>  &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
	//https://b.tiles.mapbox.com/v4/mapbox.emerald/2/0/1.png
	var tiles = L.tileLayer(tilesetUrl, {
		maxZoom: 18,
		attribution: attribStr
	});
	var latlng = L.latLng(-37.82, 175.24);

	var map = L.map('map', {center: latlng, zoom: 13, layers: [tiles]});

    var mcg = new L.MarkerClusterGroup({
    	maxClusterRadius:35,
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

//, 'marker-color': geojson[0].features[i].properties['marker-color']
            	
    var iconImages= {};
    iconImages["radio"] = "Studio-mic-icon.png";
    iconImages["tv"] = "tv-icon.png";
    iconImages["newspaper"] = "3d-glasses-icon.png";
    iconImages["satellite"] = "3d-glasses-icon.png";
    iconImages["web"] = "modem-icon.png";
    iconImages["mobile"] = "iPhone-Icon.png";
    iconImages["other"] = "webcam-icon.png";

    var maki = {};
    maki["radio"] = {"name": "music", "color":"#ccc"};
    maki["tv"] = {"name": "aerialway", "color":"#b0b"};
    maki["newspaper"] = {"name": "library", "color":"#ccc"};
    maki["satellite"] = {"name": "heliport", "color":"#ccc"};
    maki["web"] = {"name": "ferry", "color":"#ccc"};
    maki["mobile"] = {"name": "pitch", "color":"#ccc"};
    maki["other"] = {"name": "fuel", "color":"#ccc"};

	var deliveryLayers={};    
    for (var deliveryPlatform in iconImages) {
     	if (iconImages.hasOwnProperty(deliveryPlatform)) {
     		var newLayer = L.featureGroup.subGroup(mcg);
     		newLayer.addTo(map);
     		deliveryLayers[deliveryPlatform] = newLayer;
     	}
    }
  
    //First, specify your Mapbox API access token
	L.MakiMarkers.accessToken = mbToken;

	// An array of icon names can be found in L.MakiMarkers.icons or at https://www.mapbox.com/maki/
    for (var i = 0; i < geojson[0].features.length; i++) {
        var coords = geojson[0].features[i].geometry.coordinates;
        var title = geojson[0].features[i].properties.title; //a[2];
        var description = geojson[0].features[i].properties['description'];
        var platform = geojson[0].features[i].properties['platform'].toLowerCase();



        var icon = L.MakiMarkers.icon({icon: maki[platform].name, color: maki[platform].color, size: "m"});
  //       var oldIcon = L.icon({
		// 	"iconUrl": "<?php echo get_template_directory_uri(); ?>/img/" + iconImages[platform],
		// 	"iconSize": [20, 20],
		// 	"iconAnchor": [10, 10]
		// });

        var marker = L.marker(new L.LatLng(coords[1], coords[0]), {
            icon:icon
        });
       
        var popupText = title + description;
        marker.bindPopup(popupText);
        var targetLayer = deliveryLayers[platform.toLowerCase()];
        marker.addTo(targetLayer);
    }

    map.addLayer(mcg);

	map.scrollWheelZoom.disable();

	function centerMap(){
		map.fitBounds(mcg.getBounds());
	}

	centerMap();


	//Recenter the map on resize
	function resizeStuffOnResize(){
	  waitForFinalEvent(function(){
			centerMap();
	  }, 500, "some unique string");
	}
	jQuery( "#resetZoom" ).click(function() {
		centerMap();
	});

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

	// A $( document ).ready() block.
	jQuery( document ).ready(function() {
		jQuery('input[type=radio][name=deliveryPlatform]').change(function() {
			for (var p in deliveryLayers) {
				if (deliveryLayers.hasOwnProperty(p)) {
					map.removeLayer(deliveryLayers[p]);
				}
			}
			if (this.value == "all") {
				for (var p in deliveryLayers) {
					if (deliveryLayers.hasOwnProperty(p)) {
						map.addLayer(deliveryLayers[p]);
					}
				}
			} else {
				map.addLayer(deliveryLayers[this.value]);
			}
			//centerMap();
		});
		
	});


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

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
$secondaryColumnLabel = get_field( 'secondary_column_label', '', true );
$secondaryColumnContent = get_field( 'secondary_column_content', '', true );

$fullPath = get_template_directory() . "/external-feed-cache/affiliates.json";

$string = file_get_contents( $fullPath);
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
		$platformOther = $a[9];

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
<style> 
	

	#mapFilters label { margin-left:15px; }
	
	/* the threats map looked great at < 480 wihtout this height adjustment 
	because its bounds allow a further in zoom, but we need to adjust this map or else there are gray bars at < 480
	 */
	@media screen and (max-width: 480px) {
		.bbg__map--banner  {
		  background-color: #f1f1f1;
		  height: 215px;
		  width: 100%;
		}
	}
	
	
	@media screen and (min-width: 900px) {
	  .bbg__map--banner {
	    height: 450px;
	  }
	}
	
</style>

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
				<?php 
					/*if (file_exists($fullPath)) {
					    echo '<div align="center"><p class="bbg-tagline" style="margin=top:1.25rem; margin-bottom: 0.5 rem; font-size: 1.25rem !important;" >Last updated: ' . date ("F d, Y", filemtime($fullPath)) . '</p></div>';
					} */
				?>
				<div align="center" id="mapFilters" class="u--show-medium-large">
					<input type="radio" checked name="deliveryPlatform" id="delivery_all" value="all" /><label for="delivery_all"> All</label>
					<input type="radio" name="deliveryPlatform" id="delivery_radio" value="radio" /><label for="delivery_radio"> Radio</label>
					<input type="radio" name="deliveryPlatform" id="delivery_tv" value="tv" /><label for="delivery_tv"> TV</label>
					<input type="radio" name="deliveryPlatform" id="delivery_web" value="web" /><label for="delivery_web"> Digital</label>
				</div>
				<div align="center" id="mapFilters" class="u--hide-medium-large">
					<p></p><h3>Select a delivery platform</h3>
					<select name="deliverySelect">
						<option value="all">All</option>
						<option value="radio">Radio</option>
						<option value="tv">TV</option>
						<option value="web">Digital</option>
						
					</select>
				</div>
			</section>

			<div class="usa-grid">
				<div class="entry-content bbg__article-content large <?php echo $featuredImageClass; ?>">
					<div class="bbg__profile__content">
						<?php
							echo $pageContent;
						?>
					</div>
				</div><!-- .entry-content -->

				<div class="bbg__article-sidebar large">

					<?php
						if ( $secondaryColumnContent != "" ) {

							if ( $secondaryColumnLabel != "" ) {
								echo '<h5 class="bbg__label small">' . $secondaryColumnLabel . '</h5>';
							}

							echo $secondaryColumnContent;
						}
					?>

				</div><!-- .bbg__article-sidebar -->
			</div>
		</div>


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
	<?php 
		if (file_exists($fullPath)) {
		    $lastUpdatedStr = date ("m/d/Y", filemtime($fullPath)) ;
		    echo "attribStr += '<BR><div align=\'right\'>Affiliates last updated $lastUpdatedStr</div>';"; 
		}
	?>
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
    maki["radio"] = {"name": "music", "color":"#0014CC"};
    maki["tv"] = {"name": "aerialway", "color":"#A30000"};
    maki["newspaper"] = {"name": "library", "color":"#ccc"};
    maki["satellite"] = {"name": "heliport", "color":"#b0b"};
    maki["web"] = {"name": "ferry", "color":"#000"};
    maki["mobile"] = {"name": "pitch", "color":"#0b0"};
    maki["other"] = {"name": "fuel", "color":"#FF6600"};

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



        var icon = L.MakiMarkers.icon({icon: "circle", color: maki[platform].color, size: "m"});
   //     var icon = L.MakiMarkers.icon({size: "m", color: maki[platform].color});
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
console.log('test');
	function setSelectedPlatform(platform, displayMode) {
		for (var p in deliveryLayers) {
			if (deliveryLayers.hasOwnProperty(p)) {
				map.removeLayer(deliveryLayers[p]);
			}
		}
		if (platform == "all") {
			for (var p in deliveryLayers) {
				if (deliveryLayers.hasOwnProperty(p)) {
					console.log('adding layer ' + p);
					map.addLayer(deliveryLayers[p]);
				}
			}
		} else {
			map.addLayer(deliveryLayers[platform]);
		}
		//at mobile (when we're showing a select box) it helps to recenter the map after changing platforms
		if (displayMode=='select') {
			centerMap();	
		}
		
	}

	jQuery( document ).ready(function() {
		jQuery('input[type=radio][name=deliveryPlatform]').change(function() {
			setSelectedPlatform(this.value, 'radio');
		});
		jQuery('select[name=deliverySelect]').change(function() {
			var selectedPlatform = jQuery(this).val();
			setSelectedPlatform(selectedPlatform,'select');
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
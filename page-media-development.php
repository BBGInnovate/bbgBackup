<?php
/**
 * The template for displaying highlights from across the 5 BBG entities.
 * Features a banner map of recent headlines about the entities
 * and a subsection for each of the entities.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Media Development
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
 
$postsPerPage = 50;

$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Media Development Map')
	,'posts_per_page' => $postsPerPage
	,'post_status' => array('publish')
);

/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );

$features = array();
$years = array();

if ( $custom_query->have_posts() ) :
		$counter = 0;
		$imgCounter=0;
		$trainingByYear=array();
		while ( $custom_query->have_posts() ) : $custom_query->the_post();
			$id = get_the_ID();
			$location = get_post_meta( $id, 'media_dev_coordinates', true );
			$storyLink = get_permalink($id);
			$trainingName = get_post_meta( $id, 'media_dev_name_of_training', true );
			$trainingYear = get_post_meta( $id, 'media_dev_years', true );

			$mapHeadline = "<h5><a target='blank' href='". $storyLink ."'>" . $trainingName . '</a></h5>';

			//media_dev_country,
			$country = get_post_meta( $id, 'media_dev_country', true );
			$description = get_post_meta( $id, 'media_dev_description', true );
			$participants = get_post_meta( $id, 'media_dev_number_of_participants', true );
			$trainingDate = get_post_meta( $id, 'media_dev_date', true );
			$trainingPhoto = get_field( 'media_dev_photo', $id, true );
 			
			$mapDescription = get_post_meta( $id, 'media_dev_description', true );

			$years = explode(",", $trainingYear);
			for ($i=0; $i < count($years); $i++) {
				$year = $years[$i];
				$o = array(
					'title' => $trainingName,
					'country' => $country,
					'trainingDate' => $trainingDate,
					'storyLink' => $storyLink
				);
				if (!isset($trainingByYear[$year])) {
					$trainingByYear[$year] = array();
				}
				array_push($trainingByYear[$year],  $o);

			}

			//$mapDate = get_the_date();
			
			$popupBody = "<span class='bbg__map__infobox__date' style='font-weight:bold;'>" . $trainingDate . " in " . $country . "</span>";
			//echo "<pre>"; var_dump($trainingPhoto); echo "</pre>"; die(); 
			
			if ($trainingPhoto) {
				$imgCounter++;
				$trainingPhotoUrl = $trainingPhoto['sizes']['medium'];
				//we need to give the width and height so that the scrolling happens properly the first time image laods
				$w = $trainingPhoto['sizes']['medium-width'];
				$h = $trainingPhoto['sizes']['medium-height'];
				$popupBody .= "<div class='u--show-medium-large'><BR><BR><img src='$trainingPhotoUrl'></div>";
			}
			$popupBody .= "<BR><BR>" . $mapDescription . " &nbsp;&nbsp;<a style='font-weight:bold;' href='$storyLink' target='_blank'>Read More &gt; &gt;</a>";

			$pinColor = "#FF0000";
			
			$features[] = array(
				'type' => 'Feature',
				'geometry' => array( 
					'type' => 'Point',
					'coordinates' => array($location['lng'],$location['lat'])
				),
				'properties' => array(
					'title' => $mapHeadline,
					'description' => $popupBody,
					'year' => $trainingYear,
					'marker-color' => $pinColor,
					'marker-size' => 'large', 
					'marker-symbol' => ''
				)
			);
		endwhile;

		$s = "";
		for ($i=2012; $i<2030; $i++) {
			if (isset($trainingByYear[$i])) {
				//echo "<h3>" . $i , "</h3>";
				$s.='<div class="usa-accordion bbg__committee-list">';
				$s.='<ul class="usa-unstyled-list">';
				$s.='<li>';
				$s.='<button class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-'.$i.'">';
				$s.= $i . " Trainings";
				$s.='</button>';
				$s.='<div id="collapsible-'.$i.'" aria-hidden="true" class="usa-accordion-content">';

				$yearContent=$trainingByYear[$i];
				for ($j=0; $j <count($yearContent); $j++) {
					$o = $yearContent[$j];
					$link =$o['storyLink'];
					$title = $o['title'];
					$trainingDate = $o['trainingDate'];
					$country = $o['country'];
					$s .= "<a href='$storyLink'>$title</a> in $country<BR>";
				}
				$s.= '</div>';
				$s.= '</li>';
				$s.= '</ul>';
				$s.= '</div>';
			}
		}
		$trainingStr=$s;


		// echo "<pre>";
		// var_dump($trainingByYear);
		// echo "</pre>";
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
				<div class="usa-grid">
					<p class="bbg__article-header__caption">This map displays the training opportunities that the BBG has offered over on a year by year basis.</p>
				</div>
				
				<img id="resetZoom" src="<?php echo get_template_directory_uri(); ?>/img/home.png" class="bbg__map__button"/>

				<style> 
					#mapFilters label { margin-left:15px; }
				</style>
				<div align="center" id="mapFilters" class="u--show-medium-large">
					<input type="radio" checked name="trainingYear" id="delivery_all" value="all" /><label for="delivery_all"> All</label>
					<input type="radio" name="trainingYear" id="trainingYear_2016" value="2016" /><label for="trainingYear_2016"> 2016</label>
					<input type="radio" name="trainingYear" id="trainingYear_2015" value="2015" /><label for="trainingYear_2015"> 2015</label>
					<input type="radio" name="trainingYear" id="trainingYear_2014" value="2014" /><label for="trainingYear_2014"> 2014</label>
					<input type="radio" name="trainingYear" id="trainingYear_2013" value="2013" /><label for="trainingYear_2013"> 2013</label>
					<input type="radio" name="trainingYear" id="trainingYear_2012" value="2012" /><label for="trainingYear_2012"> 2012</label>
					
				</div>
				<div align="center" id="mapFilters" class="u--hide-medium-large">
					<p></p><h3>Select a year</h3>
					<select name="trainingSelect">
						<option value="all">All</option>
						<option value="2016">2016</option>
						<option value="2015">2015</option>
						<option value="2014">2014</option>
						<option value="2013">2013</option>
						<option value="2012">2012</option>
					</select>
				</div>
			</section>

			<section class="usa-section">
				<div class="usa-grid" style="margin-bottom: 3rem">
					<?php
						echo '<h3 class="usa-font-lead">';
						echo $pageContent; // or $pageExcerpt
						echo '</h3>';
						echo $trainingStr;/*<h2 class="entry-title bbg-blog__excerpt-title--featured"> echo $pageTitle; </h2> */
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
            	
    var maki = {};
    maki["2016"] = {"name": "library", "color":"#f00"};
    maki["2015"] = {"name": "library", "color":"#f00"};
    maki["2014"] = {"name": "library", "color":"#b0b"};
    maki["2013"] = {"name": "heliport", "color":"#ccc"};
    maki["2012"] = {"name": "ferry", "color":"#ccc"};

	var deliveryLayers={};    
    for (var deliveryPlatform in maki) {
     	if (maki.hasOwnProperty(deliveryPlatform)) {
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
        var year = geojson[0].features[i].properties['year'];
        var icon = L.MakiMarkers.icon({icon: maki[year].name, color: maki[year].color, size: "m"});

        var marker = L.marker(new L.LatLng(coords[1], coords[0]), {
            icon:icon
        });
       
        var popupText = title + description;
       	
       	//rather than just use html, do this - http://stackoverflow.com/questions/10889954/images-size-in-leaflet-cloudmade-popups-dont-seem-to-count-to-determine-popu
       	var divNode = document.createElement('DIV');
		divNode.innerHTML =popupText;
        marker.bindPopup(divNode);
        
        var targetLayer = deliveryLayers[year];
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
	function setSelectedPlatform(platform, displayMode) {
		for (var p in deliveryLayers) {
			if (deliveryLayers.hasOwnProperty(p)) {
				map.removeLayer(deliveryLayers[p]);
			}
		}
		if (platform == "all") {
			for (var p in deliveryLayers) {
				if (deliveryLayers.hasOwnProperty(p)) {
					map.addLayer(deliveryLayers[p]);
				}
			}
		} else {
			map.addLayer(deliveryLayers[platform]);
		}
		//at mobile (when we're showing a select box) it helps to recenter the map after changing platforms
		//if (displayMode=='select') {
			centerMap();	
		//}
		
	}

	jQuery( document ).ready(function() {
		jQuery('input[type=radio][name=trainingYear]').change(function() {
			setSelectedPlatform(this.value, 'radio');
		});
		jQuery('select[name=trainingSelect]').change(function() {
			var selectedPlatform = jQuery(this).val();
			setSelectedPlatform(selectedPlatform,'select');
		});

		
 
	});


</script>



<?php get_sidebar(); ?>
<?php get_footer(); ?>

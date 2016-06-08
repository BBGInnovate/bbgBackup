<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Network News
 */


$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}

/*
$pageTagline = get_field( 'page_tagline', '', true );
if ($pageTagline && $pageTagline != ""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg-label--mobile large">Network news</h5>
					<?php echo $pageTagline; ?>
				</header><!-- .page-header -->
			</div>

			<!-- this empty section holds the map threats and is populated in later in the page by javascript -->
			<section class="usa-section">
				<div id="map" class="bbg__map--banner"></div>
			</section>


			<div class="usa-grid-full">
			<?php
				$entities = ['voa','rferl', 'ocb', 'rfa', 'mbn'];
				foreach ($entities as $e) {
					/**** START FETCH related press releases ****/
					$entitySlug = $e . '-press-release';
					$entityString = $e;
					if ($entityString == 'rferl'){
						$entityString = 'RFE/RL';
					}
					$pressReleases = array();
					if ($entitySlug != "") {
						$prCategoryObj = get_category_by_slug($entitySlug );
						if (is_object($prCategoryObj)) {
							$prCategoryID = $prCategoryObj->term_id;
							$qParams = array(
								'post_type' => array('post'),
								'posts_per_page' => 5,
								'category__and' => array(
														$prCategoryID
												  ),
								'orderby', 'date',
								'order', 'DESC',
								'tax_query' => array(
									array(
										'taxonomy' => 'post_format',
										'field' => 'slug',
										'terms' => 'post-format-quote',
										'operator' => 'NOT IN'

									)
								)
							);
							$custom_query = new WP_Query($qParams);
							if ($custom_query -> have_posts()) {
								while ( $custom_query -> have_posts() )  {
									$custom_query->the_post();
									$id = get_the_ID();
									$pressReleases[] = array('url'=>get_permalink($id), 'title'=> get_the_title($id), 'excerpt'=>get_the_excerpt(), 'thumb'=>get_the_post_thumbnail( $id, 'small-thumb' ));
								}
							}
							wp_reset_postdata();
							wp_reset_query();
						}
					}
					$s = '<section class="usa-section">';
					$s .= '<div class="usa-grid">';
					$entityPermalink = get_permalink( get_page_by_path( 'networks/' . $e ) );
					$s .= '<h5 class="bbg-label small"><a href="' . $entityPermalink . '">'. $entityString .'</a></h5>';
					$s .= '</div>';
					$s .= '<div class="usa-grid">';
					if (count($pressReleases)) {
						//$s.= '<h2>Recent '. $abbreviation .' press releases</h2>';
						$counter = 0;
						foreach ($pressReleases as $pr) {
							$counter++;
							$url = $pr['url'];
							$title = $pr['title'];

							if ($counter == 1) {
								$s .= '<div class="bbg-grid--1-1-1-2 secondary-stories">';	
							} else if ($counter == 2) {
								$s .= '<div class="bbg-grid--1-1-1-2 tertiary-stories">';	
							}
							
							
							if ($counter == 1) {
								$s .= '<article id="post-'. get_the_ID(). '" class="' . implode(" ", get_post_class( "bbg__article" )) . '">';
								$s .= '<header class="entry-header bbg-blog__excerpt-header"><h3><a href="'.$url.'">'.$title.'</a></h3></header>';
								$s .= '<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--small ">';
								$s .= $pr['thumb'];
								$s .= '</div>';
								$s .= '<div class="entry-content bbg-blog__excerpt-content"><p>';
								$s .= $pr['excerpt'];
								$s .= '</p></div>';
							} else {
								$s .= '<article id="post-'. get_the_ID(). '" class="' . implode(" ", get_post_class( "bbg-blog__excerpt--list" )) . '">';
								$s .= '<header class="entry-header bbg-blog__excerpt-header"><h3 class="entry-title bbg-blog__excerpt-title--list"><a href="'.$url.'">'.$title.'</a></h3></header>';
							}	
							$s .= '</article>';
							if ($counter == 1 || $counter == 5) {
								$s .= '</div>';
							}

						}
					}
					$s .= '</div></section>';
					echo $s;
				}
			?>
			


		</main><!-- #main -->
	</div><!-- #primary -->

<?php

$postsPerPage =20;

$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Map it')
	,'posts_per_page' => $postsPerPage
	,'post_status' => array('publish')
);

/*
//Sample GeoJSON format
var geojson = [
	{
		"type": "FeatureCollection",
		"features": [
		{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
					-77.016556,
					38.887226
				]
			},
			"properties": {
				"title": "Africa Rizing HQ",
				"description": "description could go here.",
				"marker-color": "#F7941E",
				"marker-size": "large",
				"marker-symbol": "building"
			}
		},

		{
			"type": "Feature",
			"geometry": {
			"type": "Point",
			"coordinates": [
			  -0.200000,
			  5.550000
			]
			},
			"properties": {
			"title": "Adam Martin (<a href='http://twitter.com/'>@adamjmartin</a>) — Accra, Ghana",
			"description": "<img src='http://54.243.239.169/brian/africa.rizing/images/mugshot_adamjmartin.jpg' style='width: 30%; float: left; margin-right: 10px; '> #BOS #DCA #ACC Tweets on #beisbol #media #tech dir. of tech & innovation @BBGInnovate former #pubmedia @NPRTechTeam and @NPRNews always RadioBoston dot Com",
			"marker-color": "#FBB040",
			"marker-size": "large"
			}
		}
	  ]
	}
];
*/

/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );

$geojson = 'var geojson = [
	{
		"type": "FeatureCollection",
		"features": [
';
$geojsonGuts = "";


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

			$counter++;

			if ($counter > 1){
				$geojsonGuts .= ",";
			}
			$geojsonGuts .= '{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
					'. $location['lng'] .',
					'. $location['lat'] .'
				]
			},
			"properties": {
				"title": "'. $mapHeadline .'",
				"description": "'. $mapDescription .'",
				"marker-color": "'. $pinColor .'",
				"marker-size": "large",
				"marker-symbol": ""
			}
		}';
			
		endwhile;
		$geojson .= $geojsonGuts;
		$geojson .= '	  ]
	}
];';
		echo '<script type="text/javascript">';
		echo $geojson;
		echo '</script>';
endif; 



?>




<?php /* include map stuff -------------------------------------------------- */ ?>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />

<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<!--<script src="https://www.mapbox.com/mapbox.js/assets/data/realworld.388.js"></script>-->


<script type="text/javascript">
L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';

//console.log(geojson[0].features[0].properties);
//console.log('description: '+ geojson[0].features[0].properties['description'])

var map = L.mapbox.map('map', 'mapbox.streets')
//        .setView([-37.82, 175.215], 14);

    var markers = new L.MarkerClusterGroup();

    for (var i = 0; i < geojson[0].features.length; i++) {
        var a = geojson[0].features[i].geometry.coordinates;
        var title = geojson[0].features[i].properties.title; //a[2];
        var marker = L.marker(new L.LatLng(a[1], a[0]), {
            icon: L.mapbox.marker.icon({'marker-symbol': '', 'marker-color': geojson[0].features[i].properties['marker-color']}),
            title: title,
            description: geojson[0].features[i].properties['description']
        });
        marker.bindPopup(title);
        markers.addLayer(marker);
    }

    map.addLayer(markers);

	//Disable the map scroll/zoom so that you can scroll the page.
	map.scrollWheelZoom.disable();

	function centerMap(){
		map.fitBounds(markers.getBounds());
	}


/*
var map = L.mapbox.map('map', 'mapbox.streets')
        .setView([-37.82, 175.215], 14);

    var markers = new L.MarkerClusterGroup();

    for (var i = 0; i < addressPoints.length; i++) {
        var a = addressPoints[i];
        var title = a[2];
        var marker = L.marker(new L.LatLng(a[0], a[1]), {
            icon: L.mapbox.marker.icon({'marker-symbol': 'post', 'marker-color': '0044FF'}),
            title: title
        });
        marker.bindPopup(title);
        markers.addLayer(marker);
    }

    map.addLayer(markers);
*/

//console.log(geojson[0].features.length);

/*
var map = L.mapbox.map('map', 'mapbox.emerald');

var myLayer = L.mapbox.featureLayer().addTo(map);
	myLayer.setGeoJSON(geojson);

	//Disable the map scroll/zoom so that you can scroll the page.
	map.scrollWheelZoom.disable();

	function centerMap(){
		map.fitBounds(myLayer.getBounds());
	}
*/
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

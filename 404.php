<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">


				<div class="usa-grid">
					<header class="page-header">
						<h1 class="page-title"><span style="color: #900;">404!</span> That page can’t be found.</h1>
					</header><!-- .page-header -->

					<h6 class="bbg__page-header__tagline">Here are some recent BBG highlights around the world.</h6>
				</div>

				<!-- this section holds the map and is populated later in the page by javascript -->
				<section class="usa-section">
					<div id="map" class="bbg__map--banner" style="max-height: 350px;"></div>
				</section>


			<section class="error-404 not-found usa-grid">




				<div class="page-content">

					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					<?php if ( bbginnovate_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'bbginnovate' ); ?></h2>
						<ul>
						<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
						?>
						</ul>
					</div><!-- .widget -->
					<?php endif; ?>

					<?php
						/* translators: %1$s: smiley */
						$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'bbginnovate' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->



<?php

$postsPerPage = 50;

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

var map = L.mapbox.map('map', 'mapbox.streets')
//        .setView([-37.82, 175.215], 14);

    var markers = new L.MarkerClusterGroup({
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




<?php get_footer(); ?>

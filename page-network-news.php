<?php
/**
 * The template for displaying highlights from across the 5 BBG entities.
 * Features a banner map of recent headlines about the entities
 * and a subsection for each of the entities.
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

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg__label--mobile large">Network news</h5>
					<?php echo $pageTagline; ?>
				</header><!-- .page-header -->
			</div>

			<!-- this section holds the threats map and is populated later in the page by javascript -->
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
					$s .= '<h5 class="bbg__label small"><a href="' . $entityPermalink . '">'. $entityString .'</a></h5>';
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
								if ($counter == 5) {
									$idObj = get_category_by_slug($entitySlug); 
				  					$id = $idObj->term_id;
									$s .= '<article>' . '<a href="' . get_category_link($id) . '">All ' . strtoupper($entityString) . ' News</a></article>';
								}
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

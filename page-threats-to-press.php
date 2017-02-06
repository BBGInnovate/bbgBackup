<?php
/**
 * The template for displaying the Threats to Press page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Threats to Press
 */

function getThreatsCustomPosts($trailingDays) {
	/* get two of the most recent 6 impact posts for use on the homepage */
	$qParams = array(
		'post_type'=> 'threat_to_press',
		'post_status' => 'publish',
		'orderby' => 'post_date',
		'order' => 'desc',
		'posts_per_page' => -1,
		'date_query' => array(
	        array(
	            'after' => "$trailingDays days ago"
	        )
	    )
	);

	$custom_query = new WP_Query( $qParams );
	
	$threats = array();
	if ( $custom_query->have_posts() ) :
		while ( $custom_query->have_posts() ) : $custom_query->the_post();
			$id = get_the_ID();
			$country = get_post_meta( $id, 'threats_to_press_country', true );
			$targetNames = get_post_meta( $id, 'threats_to_press_target_names', true );
			$networks = get_post_meta( $id, 'threats_to_press_network', true );
			$coordinates = get_post_meta( $id, 'threats_to_press_coordinates', true );
			$status = get_post_meta( $id, 'threats_to_press_status', true );
			$link = get_post_meta( $id, 'threats_to_press_link', true );
			
			$t = array(
				'country' => $country,
				'name' => $targetNames,
				'date' => get_the_date(),
				'year' => get_the_date('Y'),
				'status' => $status,
				'description' => get_the_content(),
				'mugshot' => '',
				'network' => $networks,
				'link' => $link,
				'latitude' => $coordinates['lat'],
				'longitude' => $coordinates['lng'],
				'headline' => get_the_title()
			);
			$threats[] = $t;
		endwhile;
	endif;
	
	return $threats;
}

$pageContent = "";
$pageTitle = "";
$pageExcerpt = "";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageTitle = get_the_title();
		$pageExcerpt = get_the_excerpt();
		$pageContent = apply_filters( 'the_content', $pageContent );
		$pageContent = str_replace( ']]>', ']]&gt;', $pageContent );
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

/* Map options */
$trailingDays = get_post_meta( $id, 'threats_to_press_map_trailing_days', true );
$maxClusterRadius = get_post_meta( $id, 'threats_to_press_map_maximum_cluster_radius', true );


/* Adding optional quotation to the bottom of the page */
$includeQuotation = get_field( 'quotation_include', '', true );
$quotation = "";

if ( $includeQuotation ) {
	$quotationText = get_field( 'quotation_text', '', false );
	$quotationSpeaker = get_field( 'quotation_speaker', '', false );
	$quotationTagline = get_field( 'quotation_tagline', '', false );

	$quoteMugshotID = get_field( 'quotation_mugshot', '', false );
	$quoteMugshot = "";

	if ( $quoteMugshotID ) {
		$quoteMugshot = wp_get_attachment_image_src( $quoteMugshotID , 'mugshot');
		$quoteMugshot = $quoteMugshot[0];
		$quoteMugshot = '<img src="' . $quoteMugshot .'" class="bbg__quotation-attribution__mugshot">';
	}

	$quotation = '<h2 class="bbg__quotation-text--large">“'. $quotationText .'”</h2>';
	$quotation .= '<div class="bbg__quotation-attribution__container">';
	$quotation .= '<p class="bbg__quotation-attribution">';
	$quotation .= $quoteMugshot;
	$quotation .= '<span class="bbg__quotation-attribution__text"><span class="bbg__quotation-attribution__name">'. $quotationSpeaker .'</span><span class="bbg__quotation-attribution__credit">'. $quotationTagline .'</span></span>';
	$quotation .= '</p>';
	$quotation .= '</div>';
}

$wall = "";
$journalist = "";
$journalistName = "";
$mugshot = "";
$altText = "";

$postsPerPage = 6;
$qParams = array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Threats to Press')
	,'posts_per_page' => $postsPerPage
	,'post_status' => array('publish')
);

$custom_query_args = $qParams;
$custom_query = new WP_Query( $custom_query_args );

//echo "showing " . count($threatsFilteredByDate) . " threats <BR>";
$threats = getThreatsCustomPosts($trailingDays);
$threatsJSON = "<script type='text/javascript'>\n";
$threatsJSON .= "threats=" . json_encode(new ArrayValue($threats), JSON_PRETTY_PRINT, 10) . ";";
$threatsJSON .="</script>";
get_header();
echo $threatsJSON;

$threatsMapCaption = get_field( 'threats_to_press_map_caption' );
$threatsCat = get_category_by_slug( 'threats-to-press' );
$threatsPermalink = get_category_link( $threatsCat->term_id );


 ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( $custom_query->have_posts() ) : ?>

			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg__label--mobile large">Threats to Press</h5>
				</header><!-- .page-header -->
			</div>

			<section class="usa-section" style="position: relative;">
					<div id="map-threats" class="bbg__map--banner"></div>
					<img id="resetZoom" src="/wp-content/themes/bbgRedesign/img/home.png" class="bbg__map__button"/>
					<div class="usa-grid">
						<p class="bbg__article-header__caption"><?php echo $threatsMapCaption ?></p>
					</div>
				<style> 
					#mapFilters label { margin-left:15px; }
				</style>
			
				<div align="center" id="mapFilters" class="u--show-medium-large">
					<input type="radio" checked name="trainingYear" id="delivery_all" value="all" /><label for="delivery_all"> All</label>
					<input type="radio" name="trainingYear" id="trainingYear_2017" value="2017" /><label for="trainingYear_2017"> 2017</label>
					<input type="radio" name="trainingYear" id="trainingYear_2016" value="2016" /><label for="trainingYear_2016"> 2016</label>
					<input type="radio" name="trainingYear" id="trainingYear_2015" value="2015" /><label for="trainingYear_2015"> 2015</label>
					<input type="radio" name="trainingYear" id="trainingYear_2014" value="2014" /><label for="trainingYear_2014"> 2014</label>
					<input type="radio" name="trainingYear" id="trainingYear_2013" value="2013" /><label for="trainingYear_2013"> 2013</label>
				</div>
				<div align="center" id="mapFilters" class="u--hide-medium-large">
					<p></p><h3>Select a year</h3>
					<select name="trainingSelect">
						<option value="all">All</option>
						<option value="2017">2017</option>
						<option value="2016">2016</option>
						<option value="2015">2015</option>
						<option value="2014">2014</option>
						<option value="2013">2013</option>
					</select>
				</div>
			
			</section>

			<section class="usa-section">
				<div class="usa-grid" style="margin-bottom: 3rem">
					<h2 class="entry-title bbg-blog__excerpt-title--featured"><?php echo $pageTitle; ?></h2>
					<?php
						echo '<h3 class="usa-font-lead">';
						echo $pageContent; // or $pageExcerpt
						echo '</h3>';
					?>
				</div>
			</section>

			<?php
				$featuredJournalists = "";
				$profilePhoto = "";

				// check if the flexible content field has rows of data
				if( have_rows('featured_journalists_section') ){

				 	// loop through the rows of data
				    while ( have_rows('featured_journalists_section') ) : the_row();

					// display a sub field value
					$featuredJournalistsSectionLabel = get_sub_field('featured_journalists_section_label');

					if ( have_rows('featured_journalist') ){
						//echo the_sub_field('featured_journalists_section_label');
						$featuredJournalists .= '<section class="usa-section">';
							$featuredJournalists .= '<div class="usa-grid-full">';
								$featuredJournalists .= '<div class="usa-grid">';
									$featuredJournalists .= '<header class="page-header">';
										$featuredJournalists .= '<h5 class="bbg__label">' . $featuredJournalistsSectionLabel . '</h5>';
									$featuredJournalists .= '</header><!-- .page-header -->';
								$featuredJournalists .= '</div>';

								$featuredJournalists .= '<div class="usa-grid">';

							    while ( have_rows('featured_journalist') ) : the_row();
									//var_dump(get_sub_field('featured_journalist_profile'));
									$relatedPages = get_sub_field( 'featured_journalist_profile' );
									$profileTitle = $relatedPages->post_title;
									$profileName = $relatedPages->first_name . " " . $relatedPages->last_name;
									$profileOccupation = $relatedPages->occupation;
									$profilePhoto = $relatedPages->profile_photo;
									$profileUrl = get_permalink( $relatedPages->ID );
									//$profileExcerpt = get_the_excerpt($relatedPages->ID);
									$profileExcerpt = my_excerpt($relatedPages->ID); //get_the_excerpt($relatedPages->ID);

									$profileOccupation = '<span class="bbg__profile-excerpt__occupation">' . $profileOccupation .'</span>';

									if ($profilePhoto) {
										$profilePhoto = wp_get_attachment_image_src( $profilePhoto , 'Full');
										$profilePhoto = $profilePhoto[0];
										$profilePhoto = '<a href="' . $profileUrl . '"><img src="' . $profilePhoto . '" class="bbg__profile-featured__profile__mugshot"/></a>';
									}

									$featuredJournalists .= '<div class="bbg__profile-excerpt">';
										$featuredJournalists .= '<h3 class="bbg__profile__name"><a href="' . $profileUrl . '">'. $profileName .'</a></h3>';
										$featuredJournalists .= '<p class="bbg__profile-excerpt__text">' . $profilePhoto . $profileOccupation . $profileExcerpt . '</p>';
									$featuredJournalists .= '</div>';

							    endwhile;
								$featuredJournalists .= '</div>';
							$featuredJournalists .= '</div>';
						$featuredJournalists .= '</section>';
					}
				    endwhile;
				}
			?>

			<section class="usa-section">
				<div class="usa-grid">

					<?php /* Start the Loop */
						$counter = 0;
					?>
					<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>

						<?php

							$counter++;
							//Add a check here to only show featured if it's not paginated.
							if(  $counter == 1 ){
								echo '<h5 class="bbg__label"><a href="' . $threatsPermalink . '">News + updates</a></h5>';
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							//} elseif( $counter==3 ){
							} elseif( $counter == 2 ){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								//echo '<h6 class="page-title bbg__label small">More news</h6>';
								echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt = FALSE;
							}
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );

						?>
					<?php endwhile; ?>
					</div><!-- .bbg-grid right column -->
				</div><!-- .usa-grid -->
			</section>
			<?php endif; ?>

			<?php echo $featuredJournalists; ?>

			<section class="usa-section bbg__memorial">
				<div class="usa-grid-full">
					<div class="usa-grid">
						<h5 class="bbg__label">Fallen journalists</h5>
					</div>

					<div class="usa-grid">
						<div id="memorialWall">
							<?php echo $wall; ?>
						</div>
					</div>
				</div>
			</section>

			<section class="usa-section ">
				<div class="usa-grid">
					<div class="bbg__quotation ">
						<?php echo $quotation; ?>
					</div>
				</div>
			</section>


			<script src='https://api.mapbox.com/mapbox.js/v3.0.1/mapbox.js'></script>
			<link href='https://api.mapbox.com/mapbox.js/v3.0.1/mapbox.css' rel='stylesheet' />


			<script src='https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.3/leaflet.markercluster.js'></script>
			<link href='https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.3/MarkerCluster.css' rel='stylesheet' />
			<link href='https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.0.3/MarkerCluster.Default.css' rel='stylesheet' />
			<!-- <script src="https://cdn.rawgit.com/ghybs/Leaflet.FeatureGroup.SubGroup/v1.0.0/dist/leaflet.featuregroup.subgroup-src.js"></script>-->
			<script src="https://cdn.rawgit.com/ghybs/Leaflet.FeatureGroup.SubGroup/master/src/subgroup.js"></script>

			<style>
				.marker-cluster-small {
					background-color: rgba(255, 255, 255, 0.6) !important;
				}
				.marker-cluster-small div {
					background-color: rgba(255, 200, 0, 0.6) !important;
				}
				/*experimenting with styling the clusters*/
				.marker-cluster-medium div {

					background-color: rgba(255, 100, 0, 0.6) !important;
				}
				.marker-cluster-large div {

					background-color: rgba(255, 0, 0, 0.6) !important;
				}
				.marker-cluster-killed div {
					background-color: rgba(0, 0, 0, 1) !important;
					color:#FFF;
				}
			</style>

			<script type="text/javascript">
				L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';
				var map = L.mapbox.map('map-threats', 'mapbox.emerald',{attributionControl: false});
				
				var attribStr = '';
				
				
			//	attribStr += '<div align=\'right\'>&copy; <a href="https://www.mapbox.com/map-feedback/">Mapbox</a>  &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a></div>';
				<?php 
					$sinceDate = date('m/d/Y', strtotime("-$trailingDays days"));
					echo "attribStr += '<div align=\'right\'>Showing threats since $sinceDate</div>';"; 
				?>

				var attribution = L.control.attribution();
				attribution.setPrefix('');
				attribution.addAttribution(attribStr);
				attribution.addTo(map);

				var mcg = new L.MarkerClusterGroup({
					maxClusterRadius: <?php echo $maxClusterRadius; ?>,
					iconCreateFunction: function (cluster) {
						var childCount = cluster.getChildCount();
						var c = ' marker-cluster-';
						if (childCount < 10) {
						    c += 'small';
						} else if (childCount < 20) {
						    c += 'medium';
						} else {
						    c += 'large';
						}
						return new L.DivIcon({ html: '<div><span><b>' + childCount + '</b></span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
					}
				});
				var killedMarkers = new L.MarkerClusterGroup({
					iconCreateFunction: function (cluster) {
						var childCount = cluster.getChildCount();
						var c = ' marker-cluster-killed';
						return new L.DivIcon({ html: '<div><span><b>' + childCount + '</b></span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
					}
				});

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


				var markerColor = "#900";
				for (var i = 0; i < threats.length; i++) {
					var t = threats[i];

					var headline = t.name;
					if (t.headline != "") {
						headline = t.headline;
					}
					var titleLink = "<h5>" + headline + "</h5>";
					if (t.link != "") {
						titleLink="<h5><a href='" + t.link + "'>" + headline + "</a></h5>";
					}

					if (false && t.status == "Killed"){
						markerColor = "#000";
						var marker = L.marker(new L.LatLng(t.latitude, t.longitude), {
							icon: L.mapbox.marker.icon({
								'marker-symbol': '',
								'marker-color': markerColor
							})
						});
						marker.bindPopup(titleLink + t.description);
						killedMarkers.addLayer(marker);
						//marker.addTo(map);

					} else {
						if ( t.status == "threatened") {
							markerColor = "#900";
						} else if ( t.status == "missing") {
							markerColor = "#999";
						} else if (t.status == "killed") {
							markerColor = "#000";
						} else {
							//check this pin to see what the status is
							markerColor = "#931fe5";
						}
						var marker = L.marker(new L.LatLng(t.latitude, t.longitude), {
							icon: L.mapbox.marker.icon({
								'marker-symbol': '',
								'marker-color': markerColor
							})
						});

						marker.bindPopup(titleLink + t.description);
						console.log("add to layer " + t.year + " it is " + deliveryLayers[t.year]);
						var targetLayer = deliveryLayers[t.year];
        				marker.addTo(targetLayer);
					}
				}

			    map.addLayer(mcg);
			    //map.addLayer(killedMarkers);

				//Disable the map scroll/zoom so that you can scroll the page.
				map.scrollWheelZoom.disable();

				function centerMap(){
					//console.log('centeringMap');
					console.log('center map on ' + mcg.getBounds());
					map.fitBounds(mcg.getBounds());
				}
				//centerMap();


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

				/*
				//Test if zoomed in
				//Could be used for hiding or graying the home/reset button
				function zoomLevel(){
					console.log('check zoom: ' + map.getZoom());
					return map.getZoom();
				}

				map.on('click', zoomLevel);
				markers.on('click', zoomLevel);
				*/
			</script>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



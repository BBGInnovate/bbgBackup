<?php
/**
 * The template for displaying the Threats to Press page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Threats to Press
 */


/****** BEGIN HELPER FUNCTION SORT BY DATE ****/
function threatDateCompare($a, $b) {
	$t1 = ($a['dateTimestamp']);
	$t2 = ($b['dateTimestamp']);
	return $t2 - $t1;
}
/****** END HELPER FUNCTION SORT BY DATE ****/

$spreadsheetKey = "1JzULIRzp4Meuat8wxRwO8LUoLc8K2dB6HVfHWjepdqo";
$spreadsheetUrl = "https://docs.google.com/spreadsheets/d/" . $spreadsheetKey . "/pubhtml";
$csvUrl = "https://docs.google.com/spreadsheets/d/" . $spreadsheetKey . "/export?gid=0&format=csv";
$threatsCSVArray = getCSV( $csvUrl,'threats',10 );
array_shift( $threatsCSVArray ); //our first row contained headers


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

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage = 6;
$numPostsSubsequentPages = 6;

$threatsCat = get_category_by_slug( 'threats-to-press' );
$threatsPermalink = get_category_link( $threatsCat->term_id );


$postsPerPage = $numPostsFirstPage;
$offset = 0;
if ( $currentPage > 1 ) {
	$postsPerPage = $numPostsSubsequentPages;
	$offset = $numPostsFirstPage + ( $currentPage-2 ) * $numPostsSubsequentPages;
}

$hasTeamFilter = false;
$mobileAppsPostContent = "";

$qParams = array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Threats to Press')
	,'posts_per_page' => $postsPerPage
	,'offset' => $offset
	,'post_status' => array('publish')
);

$custom_query_args = $qParams;
$custom_query = new WP_Query( $custom_query_args );

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

$threats = array();
foreach($threatsCSVArray as $t) {
	$t = array_map('utf8_encode', $t);
	$threats[] = array(
		'country' => $t[0],
		'name' => $t[1],
		'date' => $t[2],
		'status' => $t[3],
		'description' => $t[4],
		'mugshot' => $t[5],
		'network' => $t[6],
		'link' => $t[7],
		'latitude' => $t[8],
		'longitude' => $t[9],
		'headline' => $t[10]

	);
}

for ( $i=0; $i < count($threats); $i++ ) {
	$t = &$threats[$i];
	$dateStr = $t['date'];
	$dateObj = explode("/", $dateStr);
	if (count($dateObj) == 3) {
		$month = $dateObj[0];
		$day = $dateObj[1];
		$year = $dateObj[2];
	} elseif (count($dateObj) == 2) {
		$month = $dateObj[0];
		$day = 1;
		$year = $dateObj[1];
	} else {
		$month=1;
		$day=1;
		$year=$dateObj[0];
	}
	$dateTimestamp = mktime(0, 0, 0, $month, $day, $year);
	$t['dateTimestamp'] = $dateTimestamp;
}
usort( $threats, 'threatDateCompare' );

$wall = "";
$journalist = "";
$journalistName = "";
$mugshot = "";
$altText = "";

for ( $i=0; $i < count($threats); $i++ ) {
	$t = &$threats[$i];
	$mugshot = $t['mugshot'];
	$link = $t['link'];
	$name = $t['name'];
	$date = $t['date'];
	$status = $t['status'];

	if ( $status == "Killed" ){
		if ( $mugshot == "" ) {
			$mugshot = "/wp-content/media/2016/07/blankMugshot.png";
			$altText = "";
		} else {
			$altText = "Photo of $name";
		}
		$imgSrc = '<img src="' . $mugshot . '" alt="' . $altText . '" class="bbg__profile-grid__profile__mugshot"/>';
		if ($link != "") {
			$journalistName = '<a href="' . $link . '">' . $name . "</a>";
			$imgSrc = '<a href="' . $link . '">' . $imgSrc . "</a>";
		} else {
			$journalistName = $name;
		}
		$journalist = "";
		$journalist .= '<div class="bbg__profile-grid__profile">';
		$journalist .= $imgSrc;
		$journalist .= '<h4 class="bbg__profile-grid__profile__name">' . $journalistName . '</h4>';
		$journalist .= '<h5 class="bbg__profile-grid__profile__dates">Killed ' . $date . '</h5>';
		$journalist .= '<p class="bbg__profile-grid__profile__description"></p>';
		$journalist .= '</div>';

		$wall .= $journalist;
	}
}

$threatsFilteredByDate = [];
for ($i=0; $i<count($threats); $i++) {
	$t = $threats[$i];
	$str = time() - (($t['dateTimestamp']));
	$daysSinceThreat = floor($str/3600/24);
	//echo " days since " . date('m/d/Y', $t['dateTimestamp']) . " is " . $daysSinceThreat . "<BR>";
	if ($daysSinceThreat <= $trailingDays) {
		$threatsFilteredByDate[] = $threats[$i];
	}
}
//echo "showing " . count($threatsFilteredByDate) . " threats <BR>";
$threatsJSON = "<script type='text/javascript'>\n";
$threatsJSON .= "threats=" . json_encode(new ArrayValue($threatsFilteredByDate), JSON_PRETTY_PRINT, 10) . ";";
$threatsJSON .="</script>";
get_header();
echo $threatsJSON;

$threatsMapCaption = get_field( 'threats_to_press_map_caption' );


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
						//$featuredJournalists .= '<h5 class="bbg__label">' . get_sub_field('featured_journalists_section_label') . '</h5>';
						$featuredJournalists .= '<h5 class="bbg__label">' . $featuredJournalistsSectionLabel . '</h5>';
						$featuredJournalists .= '</header><!-- .page-header -->';
						$featuredJournalists .= '</div>';

						$featuredJournalists .= '<div class="usa-grid">';

					    while ( have_rows('featured_journalist') ) : the_row();
							//var_dump(get_sub_field('featured_journalist_profile'));
							$relatedPages = get_sub_field( 'featured_journalist_profile' );


							$profileTitle = $relatedPages ->post_title;
							$profileName = $relatedPages ->first_name . " " . $relatedPages ->last_name;
							$profileOccupation = $relatedPages ->occupation;
							$profilePhoto = $relatedPages ->profile_photo;
							$profileUrl = get_permalink($relatedPages->ID);
							//$profileExcerpt = get_the_excerpt($relatedPages->ID);
							$profileExcerpt = my_excerpt($relatedPages->ID); //get_the_excerpt($relatedPages->ID);

							$profileOccupation = '<span class="bbg__profile-excerpt__occupation">' . $profileOccupation .'</span>';

							if ($profilePhoto) {
								$profilePhoto = wp_get_attachment_image_src( $profilePhoto , 'Full');
								$profilePhoto = $profilePhoto[0];
								$profilePhoto = '<a href="' . $profileUrl . '"><img src="' . $profilePhoto . '" class="bbg__profile-featured__profile__mugshot"/></a>';
							}

							$featuredJournalists .= '<div class="bbg__profile-excerpt">';

							//$featuredJournalists .= '<div class="usa-grid">';
							/*
							$featuredJournalists .= '<div class="usa-width-one-third">';
							$featuredJournalists .= $profilePhoto;
							$featuredJournalists .= '</div>';
							*/
							//$featuredJournalists .= '<div class="usa-width-two-thirds">';
							$featuredJournalists .= '<h3 class="bbg__profile__name"><a href="' . $profileUrl . '">'. $profileName .'</a></h3>';
							$featuredJournalists .= '<p class="bbg__profile-excerpt__text">' . $profilePhoto . $profileOccupation . $profileExcerpt . '</p>';
							//$featuredJournalists .= '</div>';
							//$featuredJournalists .= '</div>';

							$featuredJournalists .= '</div>';

					    endwhile;
						$featuredJournalists .= '</div>';
						$featuredJournalists .= '</div>';
						$featuredJournalists .= '</section>';
					}

				    endwhile;

				} else {
					//echo "<h1>nope</h1>";
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
							if(  $counter==1 ){
								echo '<h5 class="bbg__label"><a href="' . $threatsPermalink . '">News + updates</a></h5>';
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							//} elseif( $counter==3 ){
							} elseif( $counter==2 ){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								//echo '<h6 class="page-title bbg__label small">More news</h6>';
								echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt=FALSE;
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
				var map = L.mapbox.map('map-threats', 'mapbox.emerald');
				var markers = new L.MarkerClusterGroup({
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


				var markerColor = "#900";
				for (var i = 0; i < threats.length; i++) {
					var t = threats[i];
					if (t.status == "Killed"){
						markerColor = "#000";
						var marker = L.marker(new L.LatLng(t.latitude, t.longitude), {
							icon: L.mapbox.marker.icon({
								'marker-symbol': '',
								'marker-color': markerColor
							})
						});
						var titleLink = "<h5><a href='" + t.link + "'>" + t.name + "</a></h5>";
						marker.bindPopup(titleLink + t.description);
						killedMarkers.addLayer(marker);
						//marker.addTo(map);

					} else {
						if ( t.status == "Threatened") {
							markerColor = "#900";
						} else if ( t.status == "Missing") {
							markerColor = "#999";
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
						var headline = t.name;
						if (t.headline != "") {
							headline = t.headline;
						}
						var titleLink = "<h5><a href='" + t.link + "'>" + headline + "</a></h5>";
						marker.bindPopup(titleLink + t.description);
						markers.addLayer(marker);
					}


				}

			    map.addLayer(markers);
			    map.addLayer(killedMarkers);

				//Disable the map scroll/zoom so that you can scroll the page.
				map.scrollWheelZoom.disable();

				function centerMap(){
					//console.log('centeringMap');
					map.fitBounds(markers.getBounds());
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



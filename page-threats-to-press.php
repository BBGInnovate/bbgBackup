<?php
/**
 * The template for displaying the Threats to Press page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Threats to Press
 */

/****** BEGIN HELPER CLASS FOR SERIALIZING PHP TO JSON ****/
class ArrayValue implements JsonSerializable {
    public function __construct(array $array) {
        $this->array = $array;
    }

    public function jsonSerialize() {
        return $this->array;
    }
}
/****** END HELPER CLASS FOR SERIALIZING PHP TO JSON ****/

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
$threatsCSVArray = getCSV($csvUrl,'threats',10);
array_shift($threatsCSVArray); //our first row contained headers


$pageContent = "";
$pageTitle = "";
$pageExcerpt = "";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageTitle = get_the_title();
		$pageExcerpt = get_the_excerpt();
		$pageContent = apply_filters('the_content', $pageContent);
		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage=6;
$numPostsSubsequentPages=6;

$threatsCat=get_category_by_slug('threats-to-press');
$threatsPermalink = get_category_link($threatsCat->term_id);


$postsPerPage=$numPostsFirstPage;
$offset=0;
if ($currentPage > 1) {
	$postsPerPage=$numPostsSubsequentPages;
	$offset=$numPostsFirstPage + ($currentPage-2)*$numPostsSubsequentPages;
}

$hasTeamFilter=false;
$mobileAppsPostContent="";

$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Threats to Press')
	,'posts_per_page' => $postsPerPage
	,'offset' => $offset
	,'post_status' => array('publish')
);

$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );



/* Adding optional quotation to the bottom of the page */
$includeQuotation = get_field( 'quotation_include', '', true );
$quotation = "";

if ( $includeQuotation ) {
	$quotationText = get_field( 'quotation_text', '', false );
	$quotationSpeaker = get_field( 'quotation_speaker', '', false );
	$quotationTagline = get_field( 'quotation_tagline', '', false );

	$quoteMugshotID=get_field( 'quotation_mugshot', '', false );
	$quoteMugshot = "";

	if ($quoteMugshotID) {
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
		'longitude' => $t[9]
	);
}

for ($i=0; $i < count($threats); $i++) {
	$t = &$threats[$i];
	$dateStr = $t['date'];
	$dateObj = explode("/", $dateStr);
	$month = $dateObj[0];
	$day = $dateObj[1];
	$year = $dateObj[2];
	$dateTimestamp = mktime(0, 0, 0, $month, $day, $year);
	$t['dateTimestamp'] = $dateTimestamp;
}
usort($threats, 'threatDateCompare');

$wall = "";
$journalist = "";
$journalistName = "";
$mugshot = "";
$altText = "";

for ($i=0; $i < count($threats); $i++) {
	$t = &$threats[$i];
	$mugshot = $t['mugshot'];
	$link = $t['link'];
	$name = $t['name'];
	$date = $t['date'];
	$status = $t['status'];
	if ($status == "Killed"){
		if ($mugshot == "") {
			$mugshot = "http://placehold.it/300x400";
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


$threatsJSON = "<script type='text/javascript'>\n";
$threatsJSON .= "threats=" . json_encode(new ArrayValue($threats), JSON_PRETTY_PRINT) . ";";
$threatsJSON .="</script>";
get_header();
echo $threatsJSON;



 ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">


			<?php if ( $custom_query->have_posts() ) : ?>

			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg-label--mobile large">Threats to Press</h5>
				</header><!-- .page-header -->
			</div>

			<section class="usa-section">
					<div id="map-threats" class="bbg__map--banner"></div>
					<div class="usa-grid">
						<p class="bbg__article-header__caption">This map tracks the courageous journalists reporting for Voice of America, Radio Free Europe/Radio Liberty, Radio and TV Marti, Middle East Broadcasting Networks (Alhurra and Radio Sawa), and Radio Free Asia, and the threats that they face on a regular basis. </p>
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
								echo '<h5 class="bbg-label"><a href="' . $threatsPermalink . '">News + updates</a></h5>';
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							//} elseif( $counter==3 ){
							} elseif( $counter==2 ){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								//echo '<h6 class="page-title bbg-label small">More news</h6>';
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

			<section class="usa-section bbg__memorial">
				<div class="usa-grid-full">
					<div class="usa-grid">
						<h5 class="bbg-label">Fallen journalists</h5>
					</div>

					<div class="usa-grid">
						<!--
						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="https://bbgredesign.voanews.com/wp-content/media/2016/06/mugshot__Mukarram_Khan_Aatif__VOA__01-17-12.jpg" class="bbg__profile-grid__profile__mugshot"/>
							<h4 class="bbg__profile-grid__profile__name">Mukarram Khan Aatif</h4>
							<h5 class="bbg__profile-grid__profile__dates">Killed Jan. 17, 2012</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>
						-->
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
			</style>

			<script type="text/javascript">
				L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';
				var map = L.mapbox.map('map-threats', 'mapbox.emerald');
				var markers = new L.MarkerClusterGroup({
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

				var markerColor = "#900";
				for (var i = 0; i < threats.length; i++) {
					var t = threats[i];
					if (t.status == "Killed"){
						markerColor = "#000";
					} else if ( t.status == "Threatened") {
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
					var titleLink = "<h5><a href='" + t.link + "'>" + t.name + "</a></h5>";
					marker.bindPopup(titleLink + t.description);
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
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



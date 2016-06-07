<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

			<?php if ( have_posts() ) : ?>


			<div class="usa-grid">
				<header class="page-header">
					<h6 class="bbg-label--mobile large">News + information</h6>
				</header><!-- .page-header -->
			</div>




				<?php if ( is_home() && ! is_front_page() ) : ?>
					<!--
					<header class="page-header">
						<h6 class="page-title screen-reader-text bbg-label large"><?php single_post_title(); ?></h6>
					</header>
					-->
				<?php endif; ?>


				<?php /* Start the Loop */
					$counter = 0;
				?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php

						$counter++;


						//Add a check here to only show featured if it's not paginated.
						if (  (!is_paged() && $counter==1) ){
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						} else {
							if( (!is_paged() && $counter == 2) || (is_paged() && $counter==1) ){
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							} elseif( (!is_paged() && $counter == 4) || (is_paged() && $counter==3)){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								echo '<h6 class="page-title bbg-label small">More news</h6>';
								echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt=FALSE;
							}
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						}
					?>
				<?php endwhile; ?>
					</div><!-- .bbg-grid right column -->


			<div id="map" class="bbg__map--threats"></div>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

			</div><!-- .usa-grid -->
			<div class="usa-grid">
				<?php the_posts_navigation(); ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->



<?php /* include map stuff -------------------------------------------------- */ ?>
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />

<script type="text/javascript">
L.mapbox.accessToken = 'pk.eyJ1IjoidmlzdWFsam91cm5hbGlzdCIsImEiOiIwODQxY2VlNDRjNTBkNWY1Mjg2OTk3NWIzMmJjMGJhMSJ9.ZjwAspfFYSc4bijF6XS7hw';
var map = L.mapbox.map('map', 'mapbox.streets')
	.setView([38.91338, -77.03236], 16);
	<?php /* echo '.setView(['. $lat . ', ' . $lng . '], ' . $zoom . ');';*/ ?>

L.mapbox.featureLayer({
	// this feature is in the GeoJSON format: see geojson.org
	// for the full specification
	type: 'Feature',
	geometry: {
		type: 'Point',
		// coordinates here are in longitude, latitude order because
		// x, y is the standard for GeoJSON and many formats
		coordinates: [
			-77.03221142292,
			38.913371603574
			<?php /* echo $lng . ', ' . $lat;*/ ?>
		]
	},
	properties: {
		title: 'Title goes here',
		description: 'Description goes here',
		// one can customize markers by adding simplestyle properties
		// https://www.mapbox.com/guides/an-open-platform/#simplestyle
		'marker-size': 'large',
		'marker-color': '#981b1e',
		'marker-symbol': ''
	}
}).addTo(map);

</script>



<?php get_sidebar(); ?>
<?php get_footer(); ?>

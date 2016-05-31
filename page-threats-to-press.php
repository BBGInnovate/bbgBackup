<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Threats to Press
 */

/***** BEGIN PROJECT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage=7;
$numPostsSubsequentPages=6;

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

/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );

$totalPages=1;
if ($custom_query->found_posts > $numPostsFirstPage) {
	$totalPages = 1 + ceil( ($custom_query->found_posts - $numPostsFirstPage)/$numPostsSubsequentPages);
}

//query_posts($qParams);


/*** SHARING VARS ****/
/*
$teamCategoryID=$_GET["cat"];
$teamCategory=get_category($teamCategoryID);
$portfolioDescription=$teamCategory->description;
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



			<div class="usa-grid">


			<?php if ( $custom_query->have_posts() ) : ?>

				<header class="page-header">
					<h5 class="bbg-label--mobile large">Threats to Press</h5>
					<h6 class="bbg__page-header__tagline">Tagline explaining Threats to Press goes here and here.</h6>
				</header><!-- .page-header -->
			</div>

			<div class="usa-grid-full">

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
			<?php endif; ?>


			</div><!-- .usa-grid-full -->


			<section class="usa-section">
				<div class="usa-grid-full">
					<div class="usa-grid">
						<h5 class="bbg-label">Threats to press map</h5>
						<h6 id="food"></h6>
					</div>
					<div id="map-threats" class="map" style="background-color: #CCC; height: 500px; width: 100%;"></div>
				</div>
			</section>


		<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
		<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />
		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/js/vendor/tabletop.js"></script>

		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/js/map-threats.js"></script>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



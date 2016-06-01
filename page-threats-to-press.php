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

			<section class="usa-section">
					<div id="map-threats" class="bbg__map--threats"></div>
			</section>



			<div class="usa-grid-full">

				<?php /* Start the Loop */
					$counter = 0;
				?>
				<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>

					<?php

						$counter++;
						//Add a check here to only show featured if it's not paginated.
						if(  $counter==1 ){
							echo '</div>';
							echo '<div class="usa-grid">';
							echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
						} elseif( $counter==3 ){
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
						
					?>
				<?php endwhile; ?>
					</div><!-- .bbg-grid right column -->
			<?php endif; ?>


			</div><!-- .usa-grid-full -->

<!--
			<section class="usa-section">
				<div class="usa-grid-full">
					<div class="usa-grid">
						<h5 class="bbg-label">Threats around the world</h5>
					</div>
					<div id="map-threats" class="bbg__map--threats"></div>
				</div>
			</section>
-->

			<section class="usa-section" style="background-color: #333;">
				<div class="usa-grid-full">
					<div class="usa-grid">
						<h5 class="bbg-label">Fallen journalists</h5>
					</div>

					<div class="usa-grid">
						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot"/>
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

						<div class="bbg__profile-grid__profile usa-width-one-sixth">
							<img src="http://placehold.it/300x400" class="bbg__profile-grid__profile__mugshot" />
							<h3 class="bbg__profile-grid__profile__name">Name goes here</h3>
							<h5 class="bbg__profile-grid__profile__dates">Oct. 6, 1977 - July 11, 2016</h5>
							<p class="bbg__profile-grid__profile__description"></p>
						</div>

					</div>
				</div>
			</section>

			<section class="usa-section ">
				<div class="usa-grid">
					<div class="bbg__quotation ">
						<h2 class="bbg__quotation-text--large">“This is the particular invaluable contribution that a station like RFA, dedicated to the strengthening of democratic values and freedoms all over the world, has to make.”</h2>
						<div class="bbg__quotation-attribution__container">
							<p class="bbg__quotation-attribution">
								<img src="https://bbgredesign.voanews.com/wp-content/media/2016/05/aung_san_suu_kyi-small-square.jpg" class="bbg__quotation-attribution__mugshot">
								<span class="bbg__quotation-attribution__text"><span class="bbg__quotation-attribution__name">Aung San Suu Kyi</span><span class="bbg__quotation-attribution__credit">Nobel Peace Prize recipient</span></span>
							</p>
						</div>
					</div>
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


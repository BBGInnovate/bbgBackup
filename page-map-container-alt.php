<?php
/**
 * The template for containing maps created using our ammap.js Vector maps
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * template name: Map Container Alt
 */

$pageContent="";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

get_header();
echo getNetworkExcerptJS();
 ?>
<style>
/*temp styles */
</style>

	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/vendor/ammap.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/mapdata-worldLow.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/map-entity-reach.js'></script>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full" style="margin-bottom: 5rem;">
				<div class="usa-grid">
					<header class="page-header">

						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<h5 class="bbg__label--mobile large"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>

						<?php } ?>

						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					</header><!-- .page-header -->
					<h3 id="site-intro" class="usa-font-lead"><?php echo get_the_excerpt(); ?> <!--<a href="/who-we-are/" class="bbg__read-more">LEARN MORE »</a>--></h3>
				</div><!-- div.usa-grid -->
			</div><!-- div.usa-grid-full -->

			<section class="usa-section">

				<div class="usa-grid">
					<!-- 
					<div class="usa-grid">
						<form style="margin-bottom: 2rem; max-width: none;">
							<label for="options" style="display: inline-block; font-size: 2rem; font-weight: bold;">Select an entity</label>
							<select id="entity" name="options" id="options" style=" display: inline-block;">
								<option value="bbg">BBG</option>
								<option value="voa">VOA</option>
								<option value="rfa">RFA</option>
								<option value="rferl">RFERL</option>
								<option value="ocb">OCB</option>
								<option value="mbn">MBN</option>
							</select>
						</form>
					</div>
					-->
					<div class="btn-group entity-buttons" role="group" aria-label="..." style="display: inline; clear: none;">
						<button type="button" title="BBG" class=" btn-default bbg"><span class="bbg__map__button-text">BBG</span></button><!--
						--><button type="button" title="VOA" class=" btn-default voa"><span class="bbg__map__button-text">VOA</span></button><!--
						--><button type="button" title="RFA" class=" btn-default rfa"><span class="bbg__map__button-text">RFA</span></button><!--
						--><button type="button" title="RFERL" class=" btn-default rferl"><span class="bbg__map__button-text">RFERL</span></button><!--
						--><button type="button" title="OCB" class=" btn-default ocb"><span class="bbg__map__button-text">OCB</span></button><!--
						--><button type="button" title="MBN" class=" btn-default mbn"><span class="bbg__map__button-text">MBN</span></button>
					</div>
					<h5 class="bbg__map__entity-buttons__instructions" style=""> (Select a network) </h5>
				</div>


				<div class="usa-grid">
					<div class="usa-width-two-thirds">
						<div class="bbg__map-area__container " style="position: relative;">
							<div id="chartdiv"></div>
							<img id="loading" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" style="z-index=9999" />
						</div>
					</div><!--

					--><div class="usa-width-one-third">

						<select id="country-list" style="margin-bottom: 3rem;">
							<option value="0">Select a country...</option>
						</select>

						<div class="">
							<h2 id="country-name"></h2>
							<p class="detail"  style="font-family: sans-serif; "></p>
						</div>

						<div class="country-details">
							<p style="font-family: sans-serif; "><strong>Languages supported: </strong><span class="languages-served"></span></p>
							<div class="groups-and-subgroups"></div>
						</div>

						<div class="subgroup-block" style="margin-top: 2rem;">
							<select id="subgroup-list">
								<option value="0">Select a subgroup...</option>
							</select>
							<button id="view-on-map">View on map</button>
							<button id="submit">Visit site</button>
						</div>

					</div><!-- div.usa-width-one-third -->

				</div><!-- div.usa-grid -->

			</section><!-- map -->



			<section id="" class="usa-section usa-grid" style="margin-bottom: 2rem;">
				<?php /* echo $pageContent;*/ ?>
			</section>

			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

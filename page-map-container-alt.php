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
	.btn-default.selected {background:green;}
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
					<h3 id="site-intro" class="usa-font-lead">The mission of the Broadcasting Board of Governors is to inform, engage, and connect people around the world in support of freedom and democracy. The agency’s mission is reinforced by those of the individual broadcasters that are overseen by the BBG. <a href="https://bbgredesign.voanews.com/who-we-are/" class="bbg__read-more">LEARN MORE »</a></h3>
				</div><!-- div.usa-grid -->
			</div><!-- div.usa-grid-full -->

			<section class="usa-section">

				<div class="usa-grid">
					<div class="btn-group entity-buttons" role="group" aria-label="...">
				        <button type="button" class="btn btn-default">BBG</button>
				        <button type="button" class="btn btn-default">VOA</button>
				        <button type="button" class="btn btn-default">RFA</button>
				        <button type="button" class="btn btn-default">RFERL</button>
				        <button type="button" class="btn btn-default">OCB</button>
				        <button type="button" class="btn btn-default">MBN</button>
				    </div>
				</div>


				<div class="usa-grid">
					<div class="usa-width-two-thirds">
						<div class="bbg__map-area__container " style="postion: relative;">
							<img id="loading" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" />
							<div id="chartdiv"></div>
						</div>
					</div>
					<div class="usa-width-one-third">
						<h2 id="country-name"></h2>
						<p class="detail" ></p>

						<div class="usa-gridxxx">
							<select id="country-list">
							<option value="0">Select a country...</option>
							</select>
						</div>


						<div class="usa-gridxxx" style="">
							<div class="usa-width-one-thirdxx">
								<div class="country-details">
									<p><span style="font-family: sans-serif; font-weight: bold;">Languages supported: </span><span class="languages-served"></span></p>
									<div class="groups-and-subgroups"></div>
								</div>
							</div>
						</div>

						<div class="subgroup-block" style="margin-top: 2rem;">
							<h4>Select a [VOA] language</h4>
							<select id="subgroup-list">
								<option value="0">Select a subgroup...</option>
							</select>
							<button id="view-on-map">View on Map</button>
							<button id="submit">Go</button>
						</div>


					</div>


				</div>

				<!--
				<div class="usa-grid">
					<h2 id="country-name"></h2>
					<p class="detail" ></p>
				</div>
				-->

				<div class="usa-grid-full">
					<!--
					<div class="usa-grid">
						<select id="country-list">
						<option value="0">Select a country...</option>
						</select>
						<br>
						<br>
						<div class="subgroup-block">
							<select id="subgroup-list">
								<option value="0">Select a subgroup...</option>
							</select>
							<button id="view-on-map">View on Map</button>
							<button id="submit">Go</button>
						</div>
					</div>
					
					<div class="usa-grid">
						<div class="entity-details"></div>
						<div class="country-details"></div>
					</div>

					<div class="usa-grid" style="margin-top: 3rem;">
						<div class="usa-width-two-thirds">
							
						</div>
						<div class="usa-width-one-third">
							<div class="country-details">
								<div class="groups-and-subgroups"></div>
								<p><span><strong>Languages Served: </strong></span><span class="languages-served"></span></p>
							</div>
						</div>
					</div>
					-->
				</div>
			</section>



			<section id="" class="usa-section usa-grid" style="margin-bottom: 2rem;">
				<?php echo $pageContent; ?>
			</section>

			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
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
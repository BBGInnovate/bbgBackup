<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * template name: Map Container
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

get_header(); ?>

	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/vendor/ammap.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/mapdata-worldLow.js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/map-entity-reach.js'></script>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

				<div class="usa-grid">
					<header class="page-header">

						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<h5 class="bbg-label--mobile large"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>

						<?php } ?>

						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					</header><!-- .page-header -->
				</div>
			</div><!-- div.usa-grid-full -->

			<section class="usa-section">
				<div class="usa-gridxxx">
					<div class="bbg__map-area__container " style="postion: relative;">
						<img id="loading" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" />
						<div id="chartdiv"></div>
					</div>
				</div>

			<div class="usa-grid-full">
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
				
				<div class="usa-grid">
					<div class="country-details">
						<div class="col-md-4">
							<h2>Fake Entity Data</h2>
						</div>
						<!--
						<div class="col-md-4">
							<h2>Subgroups</h2>
							<ul class="detail" id="subgroups">
							</ul>
						</div>
						<div class="col-md-4">
							<h2>Languages</h2>
							<ul class="detail" id="languages">
							</ul>
						</div>
						-->
					</div>
				</div>
				
				<div class="usa-grid">
				</div>-->
				<div class="usa-grid" style="margin-top: 3rem;">
					<div class="usa-width-two-thirds">
						<h2 id="country-name">Name</h2>
						<p class="detail" >Description</p>
						<p><strong style="font-family: sans-serif;">LANGUAGES SERVED: </strong><span class="languages-served">a, b, and c</span></p>
					</div>
					<div class="usa-width-one-third">
						<div class="country-details">
							<div class="groups-and-subgroups">
								<h5>Group</h5>
								<ul>
									<li><a target="_blank" href="http://www.voanews.com">Subgroup 1</a></li>
									<li><a target="_blank" href="http://www.golos-ameriki.ru/">Subgroup 2</a></li>
									<li><a target="_blank" href="http://ukrainian.voanews.com/">Subgroup 3</a></li>
								</ul>
							</div>
						</div>
					</div>
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

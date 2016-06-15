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

	<script src="https://www.amcharts.com/lib/3/ammap.js"></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/worldLow.js'></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/entity-reach.js"></script>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

				<?php echo $pageContent; ?>

				<img id="loading" src="img/loading.gif" />
				<div class="usa-grid">
					<select id="entity">
						<option value="voa">VOA</option>
						<option value="rfa">RFA</option>
						<option value="rferl">RFERL</option>
						<option value="ocb">OCB</option>
						<option value="mbn">MBN</option>
						<option value="hb">HB</option>
					</select>
				</div>

				<div id="chartdiv"></div>

				<div class="usa-grid">
					<div class="country-details">
						<div class="col-md-4">
							<h2>Groups</h2>
							<ul class="detail" id="groups">
							</ul>
						</div>
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
					</div>
				</div>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

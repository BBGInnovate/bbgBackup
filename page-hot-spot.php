<?php
/**
 * The template for displaying 2 column pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Hot Spot
 */

$challenges = get_field( 'hot_spot_challenges', '', true );
$priorities = get_field( 'hot_spot_strategic_priorities', '', true );
$programming = get_field( 'hot_spot_special_programming', '', true );
$programming = get_field( 'hot_spot_special_programming', '', true );
$headline = get_field( 'headline', '', true );
$headlineStr = "";


$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

include get_template_directory() . "/inc/shared_sidebar.php";

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main bbg__2-column" role="main">
			<div class="usa-grid-full">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
						<div class="usa-grid-full">
							<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner--profile" style="background-image: url(https://bbgredesign.voanews.com/wp-content/media/2016/10/AP_325918556026.jpg); background-position: center top"></div>
						</div> <!-- usa-grid-full --><!-- .bbg__article-header__thumbnail -->
						<div class="usa-grid">
							<header class="entry-header bbg__article-header">
								<div class="bbg__profile-photo">
									<img src="https://bbgredesign.voanews.com/wp-content/media/2016/10/russia2.jpg" class="bbg__profile-photo__image">
								</div>
								<div class="bbg__profile-title">
									<h1 class="entry-title bbg__article-header__title">Russia</h1>
									<h5 class="entry-category bbg__profile-tagline">
										<a href="http://www.voanews.com">Hot Spots</a>									</h5><!-- .bbg__label -->
								</div>
							</header>
						</div>
						<div class="readin">
							<?php
								the_content();
							?>
						</div>
						<div class="bbg__article-content large">
							
							<h2>Challenges</h2>
							<?php echo $challenges; ?>
							<h2>Strategic Priorities</h2>
							<?php echo $priorities; ?>

							<h2 >Languages Served</h2>
							<table class="usa-table-borderless bbg__jobs__table">
							<tbody>

							<?php while( have_rows('hot_spot_languages') ): the_row(); ?>
								<tr>
									<td><h4><?php the_sub_field('hot_spot_language_name'); ?></h4></td>
									<td></td>
								</tr>
								<?php 
								if( have_rows('hot_spot_language_sites') ): ?>
									<?php 
									while( have_rows('hot_spot_language_sites') ): 
										the_row();
										$link = get_sub_field('hot_spot_site_url');
										$serviceInLanguage = get_sub_field('hot_spot_language_site_name_in_language');
										$serviceInEnglish = get_sub_field('hot_spot_site_name_in_english');
										$serviceName = $serviceInLanguage;
										if ($serviceInEnglish != "") {
											$serviceName .= " &nbsp;&nbsp;&nbsp;($serviceInEnglish)";
										}
									?>
										<tr>
											<td><a target="_blank" href="<?php echo $link; ?>" class="bbg__jobs-list__title"><?php echo $serviceName; ?></a></td>
											<td><?php echo str_replace("http://", "", $link); ?></td>
										</tr>
									<?php endwhile; ?>
								<?php endif; ?>
							<?php endwhile; ?>
							</tbody>
							</table>
							<h2 >Special Programming</h2>
							<?php echo $programming; ?>

						</div><!-- .bbg__article-sidebar -->
						<div class="bbg__article-sidebar large">
							
							<div>
							<?php 
								if( have_rows('hot_spot_press_freedom_numbers') ):
									echo '<h5 class="bbg__label small">Press Freedom</h5>';
									echo '<ul>';
									while ( have_rows('hot_spot_press_freedom_numbers') ) : the_row();
										$countryName = get_sub_field('hot_spot_press_freedom_country_name');
										$freedomIndex = get_sub_field('hot_spot_press_freedom_index');
										echo "<li>$countryName ($freedomIndex)</li>";
									endwhile;
									echo '</ul>';
								else:
								endif;
							?>
							<aside class="bbg__article-sidebar__aside">
							<h6 class="bbg__label small"><a href="/threats-to-press/">Threats to Press</a></h6>
							<ul class="bbg__rss__list">
							 	<li class="bbg__rss__list-link"><a href="https://bbgredesign.voanews.com/blog/2016/06/08/voa-journalists-attacked-in-turkey/">VOA journalists attacked in Turkey</a></li>
							 	<li class="bbg__rss__list-link"><a href="https://bbgredesign.voanews.com/blog/2016/05/25/voice-of-america-journalist-arrested-and-beaten-in-angola/">make this dynamic</a></li>
							</ul>
							</aside>
							<h5 class="bbg__label small">By the numbers</h5>
							<img src="https://placehold.it/300x400" />
							Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
							</div>
							<h5 class="bbg__label small"><a href="https://bbgredesign.voanews.com/our-work/impact-and-results/impact-portfolio/">News from our Networks</a></h5>
							<article class="bbg__article post-26419 page type-page status-publish has-post-thumbnail hentry"><header class="entry-header bbg-portfolio__excerpt-header ">
							<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium " style="margin-bottom: 1rem;"><a tabindex="-1 " href="https://bbgredesign.voanews.com/blog/2016/03/20/the-martis-coverage-reaches-cubans-on-the-island/ "><img class="attachment-small-thumb size-small-thumb wp-post-image " src="https://bbgredesign.voanews.com/wp-content/media/2016/03/Screen-Shot-2016-03-20-at-8.45.01-PM-300x180.png " sizes="(max-width: 300px) 100vw, 300px " srcset="https://bbgredesign.voanews.com/wp-content/media/2016/03/Screen-Shot-2016-03-20-at-8.45.01-PM-300x180.png 300w, https://bbgredesign.voanews.com/wp-content/media/2016/03/Screen-Shot-2016-03-20-at-8.45.01-PM-600x360.png 600w " alt="Two people looking at a computer screen " width="300 " height="180 " /></a></div>
							<p class=" "><a href="https://bbgredesign.voanews.com/blog/2016/03/20/the-martis-coverage-reaches-cubans-on-the-island/ ">The Martí’s coverage reaches Cubans on the island</a></p>
							</header><!-- .entry-header -->
							</article><!-- .bbg-portfolio__excerpt -->
						</div><!-- .bbg__article-sidebar -->
					</article><!-- #post-## -->
				<?php endwhile; // End of the loop. ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

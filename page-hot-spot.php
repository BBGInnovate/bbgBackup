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
$tag = get_field( 'hot_spot_tag', '', true );
$priorities = get_field( 'hot_spot_strategic_priorities', '', true );
$programming = get_field( 'hot_spot_special_programming', '', true );
$mapImage = get_field( 'hot_spot_map_image', '', true );
$mapImageSrc = $mapImage['sizes']['medium'];
$featuredImagesData = get_field( 'hot_spot_rotating_featured_images', '', true );
$randomFeaturedImage = $featuredImagesData[array_rand($featuredImagesData)];
$featuredImageSrc = $randomFeaturedImage['hot_spot_rotating_featured_image']['sizes']['large-thumb'];
$featuredImageBackgroundPosition = $randomFeaturedImage['hot_spot_rotating_featured_image_background_position'];

$listsInclude = get_field( 'sidebar_dropdown_include', '', true);
$pressFreedomIntro = get_field( 'site_setting_press_freedom_intro', 'options', 'false' );

/**** create 'NEWS FROM NETWORKS' array ***/
$nnn_query_args=array(
	'post_type' => array('post'),
	'posts_per_page' => 3,
	'tag' => $tag -> slug,
	'orderby' => 'date',
	'order' => 'DESC'
);
$newsFromNetworks = array();
$custom_query = new WP_Query( $nnn_query_args );
if ($custom_query -> have_posts()) {
	while ( $custom_query -> have_posts() )  {
		$custom_query->the_post();
		$newsFromNetworks[] = array(
			'url' => get_the_permalink(),
			'title' => get_the_title(),
			'id' => get_the_ID(),
			'thumb' => get_the_post_thumbnail( $id, 'small-thumb' )
		);
	}
}
/**** done creating 'NEWS FROM NETWORKS' array ***/

/**** create 'THREATS TO PRESS' array ***/
$ttp_query_args = array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Threats to Press')
	,'tag' => $tag -> slug
	,'posts_per_page' => 3
	,'post_status' => array('publish')
	,'orderby', 'date'
	,'order', 'DESC'
);

$threatsToPress = array();
$ttp_query = new WP_Query( $ttp_query_args );
if ($ttp_query -> have_posts()) {
	
	while ( $ttp_query -> have_posts() )  {
		$ttp_query->the_post();
		$threatsToPress[] = array(
			'url' => get_the_permalink(),
			'title' => get_the_title(),
			'id' => get_the_ID(),
			'thumb' => get_the_post_thumbnail( $id, 'small-thumb' ),
			'excerpt' => get_the_excerpt($id) 
		);
	}
}
/**** done creating 'NEWS FROM NETWORKS' array ***/


include get_template_directory() . "/inc/shared_sidebar.php";

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main bbg__2-column" role="main">
			<div class="usa-grid-full">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
						<div class="usa-grid-full">
							<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner--profile" style="background-image: url(<?php echo $featuredImageSrc; ?>); background-position: <?php echo $featuredImageBackgroundPosition; ?>"></div>
						</div> <!-- usa-grid-full --><!-- .bbg__article-header__thumbnail -->
						<div class="usa-grid">
							<header class="entry-header bbg__article-header">
								<div class="bbg__profile-photo">
									<img src="<?php echo $mapImageSrc; ?>" class="bbg__profile-photo__image">
								</div>
								<div class="bbg__profile-title">
									<h1 class="entry-title bbg__article-header__title"><?php echo get_the_title(); ?></h1>
									<h5 class="entry-category bbg__profile-tagline">
										<a href="<?php echo get_permalink( get_page_by_path( 'hot-spots' ) ); ?>">Hot Spots</a>									</h5><!-- .bbg__label -->
								</div>
							</header>
						</div>
						<div class="usa-grid">
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
										echo $pressFreedomIntro;
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

								<?php 
								if (count($threatsToPress) > 0) {
									$ttpLink = get_permalink( get_page_by_path( 'threats-to-press' ) );
									echo '<aside class="bbg__article-sidebar__aside">';
									echo '<h6 class="bbg__label small"><a href="$ttpLink">Threats to Press</a></h6>';
									$i=0;
									foreach ($threatsToPress as $n) {
										$s = ''; 
										$i++;
										if ($i == 1) {
											$s .= '<article class="' . implode(" ", get_post_class( "bbg__article" )) . '"">';
											$s .=	'<header class="entry-header bbg-portfolio__excerpt-header">';
											$s .=		'<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">';
											$s .=			'<a tabindex="-1" href="' . $n['url'] . '">' . $n['thumb'] . '</a>';
											$s .=		'</div>';
											$s .=		'<p>';
											$s .= '<a href="'.$n['url'] . '"><h4>' . $n['title'] . '</h4></a>';
											if ($n['excerpt'] != '') {
												$s .= $n['excerpt'];
											}
											$s .= '</p><BR>';
											$s .=	'</header><!-- .entry-header -->';
											$s .= '</article><!-- .bbg-portfolio__excerpt -->';
										} else {
											$s .= '<article class="' . implode(" ", get_post_class( "bbg__article" )) . '"">';
											$s .=	'<header class="entry-header bbg-portfolio__excerpt-header">';
											$s .=		'<p class=""><a href="'.$n['url'] . '">' . $n['title'] . '</a></p>';
											$s .=	'</header><!-- .entry-header -->';
											$s .= '</article><!-- .bbg-portfolio__excerpt -->';
										}
										echo $s;
									}
									echo '</aside>';
								}
								
								?>
								<h5 class="bbg__label small">By the numbers</h5>
								<img src="https://placehold.it/300x400" />
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
								</div>

								<?php 
								if (count($newsFromNetworks) > 0) {
									echo '<h5 class="bbg__label small">News from our Networks</h5>';
									foreach ($newsFromNetworks as $n) {
										$s = ''; 
										$s .= '<article class="' . implode(" ", get_post_class( "bbg__article" )) . '"">';
										$s .=	'<header class="entry-header bbg-portfolio__excerpt-header">';
										$s .=		'<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">';
										$s .=			'<a tabindex="-1" href="' . $n['url'] . '">' . $n['thumb'] . '</a>';
										$s .=		'</div>';
										$s .=		'<p class=""><a href="'.$n['url'] . '">' . $n['title'] . '</a></p><BR>';
										$s .=	'</header><!-- .entry-header -->';
										$s .= '</article><!-- .bbg-portfolio__excerpt -->';
										echo $s;
									}
								}
								
								?>
							</div><!-- .bbg__article-sidebar -->
						</div>
					</article><!-- #post-## -->
				<?php endwhile; // End of the loop. ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
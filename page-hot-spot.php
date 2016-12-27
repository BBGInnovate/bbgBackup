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

$pressFreedomIntro = get_field( 'site_setting_press_freedom_intro', 'options', 'false' );

/**** create 'THREATS TO PRESS' array ***/
$ttp_query_args = array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Threats to Press')
	,'posts_per_page' => 2
	,'post_status' => array('publish')
	,'orderby' => 'date'
	,'order' => 'DESC'
	,'tag' => $tag->slug
);

$postIDsUsed=array();
$threatsToPress = array();
$ttp_query = new WP_Query( $ttp_query_args );

if ($ttp_query -> have_posts()) {
	
	while ( $ttp_query -> have_posts() )  {

		$ttp_query->the_post();
		$id = $ttp_query -> post -> ID;
		$postIDsUsed[] = $id; //, array( 'style' => 'max-height:400px; width:100%') 
		
		$threatsToPress[] = array(
			'url' => get_the_permalink(),
			'title' => get_the_title(),
			'id' => $id,
			'thumb' => get_the_post_thumbnail( $id, 'medium-thumb' ),
			'excerpt' => my_excerpt($id)
		);
		
	}
}
 
$fullStr = ''; 
if (count($threatsToPress) > 0) {
	$ttpLink = get_permalink( get_page_by_path( 'threats-to-press' ) );
	//$fullStr .= '<div style="background-color: #F1F1F1; padding: 1rem 2rem; border-radius: 0 3px 3px 3px;" >';
	//$fullStr .= '<p class="bbg__label small"><a href="' . $ttpLink . '">Threats to Press</a></p>'; 
	$i=0;
	foreach ($threatsToPress as $n) {
		$s = ''; 
		$i++;
		$styleStr = '';
		if ($i==1) {
			$styleStr = " style='margin-right:2.35765%; '";
		}
		$s .= '<article ' . $styleStr . ' class="' . implode(" ", get_post_class( "bbg__article bbg-grid--1-2-2" )) . '"">';
		$s .=	'<header class="entry-header bbg-portfolio__excerpt-header">';
		$s .=		'<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">';
		$s .=			'<a tabindex="-1" href="' . $n['url'] . '">' . $n['thumb'] . '</a>';
		$s .=		'</div>';
		$s .=		'<p>'; 
		$s .= '<a href="'.$n['url'] . '"><h4>' . $n['title'] . '</h4></a>';
		if ($n['excerpt'] != '') {
			//$s .= "<span >" . $n['excerpt'] . "</span>";
		}
		$s .= '</p><BR>';
		$s .=	'</header><!-- .entry-header -->';
		$s .= '</article><!-- .bbg-portfolio__excerpt -->';
		$fullStr .= $s;
	}

}
$threatsToPressStr = $fullStr;

/**** done creating 'THREATS TO PRESS' array ***/

/**** create 'NEWS FROM NETWORKS' array ***/
$nnn_query_args=array(
	'post_type' => array('post'),
	'posts_per_page' => 3,
	'tag' => $tag -> slug,
	'orderby' => 'date',
	'order' => 'DESC',
	'post__not_in' => $postIDsUsed

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


get_header(); ?>

<style>
@media screen and (min-width: 600px) {

.bbg-grid--1-2-2:nth-child(2n+1) {
	clear:none;
	margin-right:0;
}

}
</style>

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
									<h1 class="entry-title bbg__article-header__title"><?php echo str_replace("Private:","Draft:",get_the_title()); ?></h1>
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
								
								<?php if( have_rows('hot_spot_freeform_textareas') ): ?>
									<?php while( have_rows('hot_spot_freeform_textareas') ): the_row(); 
										// vars
										$label = get_sub_field('hot_spot_freeform_textarea_label');
										$content = get_sub_field('hot_spot_freeform_textarea_text');
										$content = str_replace("[threatstopress]", $threatsToPressStr, $content);
										echo "<h2>$label</h2>$content";
										?>
									<?php endwhile; ?>
								<?php endif; ?>

							</div><!-- .bbg__article-sidebar -->
							<div class="bbg__article-sidebar large">
								<div>

								<h2 >Languages Served</h2>
								<table class="usa-table-borderless bbg__jobs__table">
								<tbody>

								<?php while( have_rows('hot_spot_languages') ): the_row(); ?>
									<tr>
										<td colspan="3"><h4><?php the_sub_field('hot_spot_language_name'); ?></h4></td>
									</tr>
									<?php 
									if( have_rows('hot_spot_language_sites') ): ?>
										<?php 
										while( have_rows('hot_spot_language_sites') ): 
											the_row();
											$link = get_sub_field('hot_spot_site_url');
											$serviceInLanguage = get_sub_field('hot_spot_language_site_name_in_language');
											$serviceInEnglish = get_sub_field('hot_spot_site_name_in_english');
											$hotSpotNetwork = get_sub_field('hot_spot_site_network');
											$serviceName = $serviceInLanguage;
											if ($serviceInEnglish != "") {
												//$serviceName .= " &nbsp;&nbsp;&nbsp;($serviceInEnglish)";
											}
											$entityLogo = getTinyEntityLogo($hotSpotNetwork);
 
										?>
											<tr>
												<td style="padding:0px" ><img width="20" height="20" style='height:20px !important; width:20px !important; max-width:none;' src="<?php if ($entityLogo) { echo $entityLogo; } ?>" /></td>
												<td nowrap style="padding-left:10px"><a title="<?php echo $serviceInEnglish; ?>"  target="_blank" href="<?php echo $link; ?>" class="bbg__jobs-list__title"><?php echo $serviceName; ?></a></td>
												<td width="99%" ><?php echo str_replace("http://", "", $link); ?></td>
											</tr>
										<?php endwhile; ?>
									<?php endif; ?>
								<?php endwhile; ?>
								</tbody>
								</table>

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

								<!--
								<h5 class="bbg__label small">By the numbers</h5>
								<img src="https://placehold.it/300x400" />
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
								</div>
								-->

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

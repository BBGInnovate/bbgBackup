<?php
/**
 * The template for displaying the Threats to Press archive page.
 * @package bbginnovate
  template name: Threats to Press Archive
 */

$qParams = array(
	'post_type'=> 'threat_to_press',
	'post_status' => 'publish',
	'orderby' => 'post_date',
	'order' => 'desc',
	'posts_per_page' => -1,
	'date_query' => array(
        array(
        	'after' => '2016-01-01 00:00:00',
            'before' => '2016-12-31 23:59:59'
        )
    )
);

$custom_query = new WP_Query( $qParams );

$threats = array();
if ( $custom_query->have_posts() ) :
	while ( $custom_query->have_posts() ) : $custom_query->the_post();
		$id = get_the_ID();
		$country = get_post_meta( $id, 'threats_to_press_country', true );
		$targetNames = get_post_meta( $id, 'threats_to_press_target_names', true );
		$networks = get_post_meta( $id, 'threats_to_press_network', true );
		$coordinates = get_post_meta( $id, 'threats_to_press_coordinates', true );
		$status = get_post_meta( $id, 'threats_to_press_status', true );
		$link = get_post_meta( $id, 'threats_to_press_link', true );
		
		$t = array(
			'country' => $country,
			'name' => $targetNames,
			'date' => get_the_date(),
			'year' => get_the_date('Y'),
			'niceDate' => get_the_date('M d, Y'), 
			'status' => $status,
			'description' => get_the_excerpt(),
			'mugshot' => '',
			'network' => $networks,
			'link' => $link,
			'latitude' => $coordinates['lat'],
			'longitude' => $coordinates['lng'],
			'headline' => get_the_title()
		);
		$threats[] = $t;
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();


$pageContent = "";
$pageTitle = "";
$pageExcerpt = "";
$id = 0;
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageTitle = get_the_title();
		$pageExcerpt = get_the_excerpt();
		$pageContent = apply_filters( 'the_content', $pageContent );
		$pageContent = str_replace( ']]>', ']]&gt;', $pageContent );
		$id = get_the_ID();
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( count( $threats ) ) : ?>

			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg__label--mobile large">Threats to Press</h5>
				</header><!-- .page-header -->
			</div>
			
			<section class="usa-section">
				<div class="usa-grid" style="margin-bottom: 3rem">
					<h2 class="entry-title bbg-blog__excerpt-title--featured"><?php echo $pageTitle; ?></h2>
					<?php
						echo '<h3 class="usa-font-lead">';
						echo $pageContent; // or $pageExcerpt
						echo '</h3>';

						foreach ($threats as $t) {	//4773aa, 112e51
							$imgSrc = '';
							foreach ($t['network'] as $abbreviation) {
								$imgSrc=get_template_directory_uri().'/img/logo_'.$abbreviation.'--circle-200.png'; //	
							}
							
							echo '<article class="bbg-blog__excerpt--list ">';
							echo '<h3 class="entry-title" style="color:#4773aa"><img style="vertical-align:middle;" width="25" height="25" src="' . $imgSrc . '"><span style="margin-left:0.75rem;">' . $t['headline'] . '</span></h3>';
							echo '<div class="entry-meta bbg__excerpt-meta">';
							echo '<span class="posted-on">';
							echo '<time class="entry-date published" >' . $t['niceDate'] . '</time>';
							echo '</span></div>';
							echo '<div class="entry-content bbg-blog__excerpt-content"><p>' . $t['description'] . '</p></div>';
							echo '</article>';
						}

					?>
				</div>
			</section>


			<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



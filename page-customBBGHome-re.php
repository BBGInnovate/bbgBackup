<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes:
 *      - the mission
 *      - a portfolio of recent impact stories,
 *      - recent stories,
 *      - an optional soapbox for senior leadership commentary,
 *      - updates on threats to press around the world
 *      - and a list of the entities.
 *
 * @package bbgRedesign
  template name: Custom BBG Home Re
 */

//helper function used only in this template

function getRandomImpactPostIDs( $used ) {
	/* get two of the most recent 6 impact posts for use on the homepage */
	$qParams = array(
		'post_type'=> 'post',
		'post_status' => 'publish',
		'cat' => get_cat_id('impact'),
		'post__not_in' => $used,
		'posts_per_page' => 12,
		'orderby' => 'post_date',
		'order' => 'desc',
	);
	$custom_query = new WP_Query( $qParams );
	$allIDs = [];
	if ( $custom_query -> have_posts() ) :
		while ( $custom_query -> have_posts() ) : $custom_query -> the_post();
			$allIDs[] = get_the_ID();
		endwhile;
	endif;

	if ( count( $allIDs ) > 2 ) {
		shuffle( $allIDs );
		$ids = [];
		$ids[] = array_pop( $allIDs );
		$ids[] = array_pop( $allIDs );
	} else {
		$ids = $allIDs;
	}

	return $ids;
}

function getRecentPostQueryParams( $numPosts, $used, $catExclude ) {
	$qParams = array(
		'post_type' => array( 'post' ),
		'posts_per_page' => $numPosts,
		'orderby' => 'post_date',
		'order' => 'desc',
		'category__not_in' => $catExclude,
		'post__not_in' => $used,
		/*** NOTE - we could have also done this by requiring quotation category, but if we're using post formats, this is another way */
		'tax_query' => array(
			//'relation' => 'AND',
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-quote',
				'operator' => 'NOT IN'
			)
		)
	);
	return $qParams;
}
function getThreatsPostQueryParams( $numPosts, $used ) {
	$qParams = array(
		'post_type' => array( 'post' ),
		'posts_per_page' => $numPosts,
		'orderby' => 'rand',
		'order' => 'desc',
		'cat' => get_cat_id( 'Threats to Press' ),
		'post__not_in' => $used
	);
	return $qParams;
}

$templateName = 'customBBGHome';

/*** get all custom fields ***/
$siteIntroContent = get_field('site_setting_mission_statement','options','false');
$siteIntroLink = get_field('site_setting_mission_statement_link', 'options', 'false');


//What will go in the corner hero? off (gives random quote), event, callout, advisory
$homepage_hero_corner = get_field( 'homepage_hero_corner', 'option' );
if ( $homepage_hero_corner  == 'event' ) {
	$featuredEvent = get_field( 'homepage_featured_event', 'option' );
} else if ( $homepage_hero_corner == 'advisory' ) {
	$featuredAdvisory = get_field( 'homepage_featured_advisory', 'option' );
} else if ( $homepage_hero_corner == "callout" ) {
	$featuredCallout = get_field('homepage_featured_callout', 'option');
}
$cornerHeroLabel = get_field( 'corner_hero_label', 'option' );
if ( $cornerHeroLabel == '' ) {
	$cornerHeroLabel = 'This week';
}

$homepageBannerType = get_field( 'homepage_banner_type', 'option' );

$featuredPost = get_field('homepage_featured_post', 'option');
$soap = get_field('homepage_soapbox_post', 'option');
$threatsToPressPost = get_field('homepage_threats_to_press_post', 'option');

// ADD CUSTOM FIELDS POSTS TO ARRAY OF POST IDs ALREADY USED ON PAGE
$postIDsUsed = array();
if ( $featuredPost ) {
	$featuredPost = $featuredPost[0];
	$postIDsUsed[] = $featuredPost -> ID;
}
if ( $homepage_hero_corner == 'event' && $featuredEvent ) {
	$postIDsUsed[] = $featuredEvent -> ID;
}
if ( $soap ) {
	$postIDsUsed[] = $soap[0] -> ID;
}

/*** store a handful of page links that we'll use in a few places ***/
$impactPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results' ) );
$impactPortfolioPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results/impact-portfolio' ) );
$threatsPermalink = get_permalink( get_page_by_path( 'threats-to-press' ) );

/*** output the standard header ***/
get_header();

?>
<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			<?php
				/*** output our <style> node for use by the responsive banner ***/
				if ( $homepageBannerType == 'revolution_slider' ) {
					$bannerBackgroundPosition = get_field( 'homepage_banner_background_position', 'option' );
					echo '<section class="usa-section bbg-banner__section" style="position: relative; z-index:9990;">';
					$sliderAlias = get_field( 'homepage_banner_revolution_slider_alias', 'option' );
					echo do_shortcode( '[rev_slider alias="' . $sliderAlias . '"]' );
					echo '</section>';
				} else {
					$useRandomImage = true;
					// $includeBannerLogo = true;
					$bannerCutline = '';
					$bannerAdjustStr = '';

					if ( $homepageBannerType == 'specific_image' ) {
						$includeBannerLogo = get_field( 'homepage_banner_image_include_logo', 'option' );
						$img = get_field( 'homepage_banner_image', 'option' );

						if ( $img ) {
							$attachment_id = $img['ID'];
							$useRandomImage = false;

							$featuredImageCutline='';
							$thumbnail_image = get_posts(
								array(
									'p' => $attachment_id,
									'post_type' => 'attachment'
								)
							);

							if ($thumbnail_image && isset($thumbnail_image[0])) {
								$bannerCutline=$thumbnail_image[0]->post_excerpt;
							}

							$bannerAdjustStr = '';
							$bannerBackgroundPosition = get_field( 'homepage_banner_background_position', 'option' );
							if ( $bannerBackgroundPosition ) {
								$bannerAdjustStr = $bannerBackgroundPosition;
							}
						}
					}

					//deilibarately didn't do an 'else' here in case they checked 'specific_image' without actually selecting one
					if ( $useRandomImage ) {
						$randomImg = getRandomEntityImage();
						$attachment_id = $randomImg['imageID'];
						$bannerCutline = $randomImg['imageCutline'];
						$bannerAdjustStr = $randomImg['bannerAdjustStr'];
					}

					$tempSources= bbgredesign_get_image_size_links( $attachment_id );
					//sources aren't automatically in numeric order.  ksort does the trick.
					ksort( $tempSources );
					$prevWidth=0;

					// Let's prevent any images with width > 1200px from being an output as part of responsive banner
					foreach( $tempSources as $key => $tempSource ) {
						if ( $key > 1900 ) {
							unset( $tempSources[$key] );
						}
					}

					echo "<style>";
					if ( $bannerAdjustStr != "" ) {
						echo "\t.bbg-banner { background-position: $bannerAdjustStr; }";
					}
					$counter=0;
					foreach( $tempSources as $key => $tempSourceObj ) {
						$counter++;
						$tempSource=$tempSourceObj['src'];
						if ( $counter == 1 ) {
							echo "\t.bbg-banner { background-image: url($tempSource) !important; }\n";
						} elseif ( $counter < count($tempSources) ) {
							echo "\t@media (min-width: " . ($prevWidth+1) . "px) and (max-width: " . $key . "px) {\n";
							echo "\t\t.bbg-banner { background-image: url($tempSource) !important; }\n";
							echo "\t}\n";
						} else {
							echo "\t@media (min-width: " . ($prevWidth+1) . "px) {\n";
							echo "\t\t.bbg-banner { background-image: url($tempSource) !important; }\n";
							echo "\t}\n";
						}
						$prevWidth = $key;
					}
					echo "</style>";

					?>
					<section class="usa-section bbg-banner__section" style="position: relative; z-index:9990;">
						<div class="bbg-banner">
							<div class="bbg-banner__gradient"></div>
							<div class="usa-grid bbg-banner__container--home">
								<?php if ( $includeBannerLogo ): ?>
								<img class="bbg-banner__site-logo" src="<?php echo get_template_directory_uri() ?>/img/logo-agency-square.png" alt="BBG logo">
								<?php endif; ?>

								<div class="bbg-social__container">
									<div class="bbg-social">
									</div>
								</div>
							</div>
						</div>

						<div class="bbg-banner__cutline usa-grid">
							<?php //echo $bannerCutline; ?>
							<!-- P TAG IS FOR TESTING ONLY -->
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						</div>
					</section><!-- Responsive Banner -->
					<?php
				}
			?>

			<!-- MISSION -->
            <section class="usa-section usa-grid">
            	<div id="mission">
            		<h3 id="site-intro" class="usa-font-lead">
            			<?php echo $siteIntroContent; ?>
						 <a href="<?php $siteIntroLink; ?>">LEARN MORE »</a>
        			</h3>
            	</div>
            </section>

			<!-- BBG NEWS -->
			<section class="usa-section usa-grid">
				<h6 class="bbg__label"><a href="#">BBG News</a></h6>
				<div id="featured-BBG-post">
					<?php
						// GET FEATURED POST FROM EITHER HOMEPAGE SETTINGS OR MOST RECENT POST
						if ($featuredPost) {
							$qParams=array(
								'post__in' => array($featuredPost->ID),
								'post_status' => array('publish','future')
							);
						} else {
							$qParams = getRecentPostQueryParams(1, $postIDsUsed, $STANDARD_POST_CATEGORY_EXCLUDES);
						}
						query_posts($qParams);

						if (have_posts()) {
							while ( have_posts() ) {
								the_post();
								$counter++;
								$postIDsUsed[] = get_the_ID();
								get_template_part('template-parts/content-excerpt-featured-re', get_post_format());
							};
						}
						wp_reset_query();
					?>
				</div>

				<!-- IMPACT STORIES -->
				<div id="home-impact-story">
					<h6 class="bbg__label"><a href="#">IMPACT STORY</a></h6>
					<?php
						$impactPostIDs = getRandomImpactPostIDs($postIDsUsed);
						$qParams=array(
							'post_type' => array('post'),
							'posts_per_page' => 1,
							'orderby' => 'post_date',
							'order' => 'desc',
							'post__in' => $impactPostIDs
						);
						query_posts( $qParams );
						if ( have_posts() ) :
							while ( have_posts() ) : the_post();
								$includePortfolioDescription = false;
								$postIDsUsed[] = get_the_ID();
								get_template_part( 'template-parts/content-portfolio-re', get_post_format() );
							endwhile;
						endif;
						wp_reset_query();
					?>
				</div>
			</section>

			<!-- SUB STORIES -->
			<section class="usa-section usa-grid">
				<div id="sub-posts">
					<?php
						/* BEWARE: sticky posts add a record */
						$qParams=getRecentPostQueryParams(3, $postIDsUsed, $STANDARD_POST_CATEGORY_EXCLUDES);
						query_posts($qParams);

						if ( have_posts() ) {
							$counter = 0;
							$includeImage = false;

							while ( have_posts() ) : the_post();
								$counter++;
								$postIDsUsed[] = get_the_ID();
								get_template_part( 'template-parts/content-excerpt-list-re', get_post_format() );
							endwhile;
						}
						wp_reset_query();
					?>
				</div>
			</section>

			<?php
				if ($soap) {
					$s = getSoapboxStr($soap);
					echo $s;
				}
			?>

			<!-- THREATS TO PRESS  -->
			<section id="threats-to-journalism" class="usa-section bbg__ribbon">
				<div class="usa-grid">
					<h6 class="bbg__label small"><a href="<?php echo $threatsPermalink; ?>">Threats to Press</a></h6>
				</div>
				<?php
				$threatsUsedPosts = array();
				$qParams = getThreatsPostQueryParams(2, $threatsUsedPosts);

				$threat_article = new WP_Query($qParams);
				if ($threat_article -> have_posts()) {
					$grid_opennings  = '<div class="usa-grid">';
					$grid_opennings .= 	'<div id="threat_padding" class="usa-grid">&nbsp;</div>';
					$grid_opennings .= 		'<div class="usa-grid threat_content">';
					echo $grid_opennings;
					while ($threat_article -> have_posts()) :
						$threat_article -> the_post();
						$teaser = wp_trim_words(get_the_content(), 40);
					?>
					<div class="usa-grid threat-article">
						<div class="threat_image">
							<?php the_post_thumbnail(); ?>
						</div>
						<div class="threat_copy">
							<div class="threat_title">
								<a href="<?php the_permalink(); ?>">
									<h3 class="entry-title bbg-blog__excerpt-title--list"><?php the_title(); ?></h3>
								</a>
							</div>
							<div class="threat_excerpt"><?php echo $teaser; ?></div>
						</div>
					</div>
					<?php
					endwhile;
					echo '</div></div>';
				}
				wp_reset_query();
				?>
			</section>
			
			<!-- NETWORKS BLURB -->
			<section id="entities" class="usa-section bbg__staff">
				<div class="usa-grid">
					<h6 class="bbg__label"><a href="<?php echo get_permalink( get_page_by_path( 'networks' ) ); ?>" title="A list of the BBG broadcasters.">Our networks</a></h6>
					<div class="usa-intro bbg__broadcasters__intro">
						<h3 class="usa-font-lead">Every week, more than <?php echo do_shortcode('[audience]'); ?> listeners, viewers and internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters.</h3>
					</div>
				</div>
			</section>

			<!-- NETWORK ENTITIES -->
			<section id="network-group" class="usa-section">
				<div class="usa-grid">
					<?php echo networks_output(); ?>
				</div>
			</section>

		</main>
	</div>
</div>

<?php get_footer(); ?>


<script type="text/javascript">
function navSlide(){
	var currentScroll = jQuery( "html" );
	//console.log("Currently scrolled to: " + currentScroll.scrollTop());

	var p = jQuery( "#threats-to-journalism" );
	var offset = p.offset();
	//console.log("#threats-to-journalism position: " + offset.top);

	if (currentScroll.scrollTop() > offset.top){
		//console.log("the Threats-to-press section should be at the top of the page");
		jQuery(".bbg__social__container").hide();
	} else {
		//console.log("the Threats-to-press section is below the top of the page");
		jQuery(".bbg__social__container").show();
	}
}

jQuery(window).scroll(navSlide);
</script>
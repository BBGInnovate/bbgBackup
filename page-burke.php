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
  template name: Burke Awards
 */

//helper function used only in this template

$templateName = "customBBGHome";

/*** get all custom fields ***/
$siteIntroContent = get_field('site_setting_mission_statement','options','false');
$siteIntroLink = get_field('site_setting_mission_statement_link', 'options', 'false');
$soap = get_field('homepage_soapbox_post', 'option');

$showFeaturedEvent = get_field('show_homepage_event', 'option');
$featuredEventLabel = get_field('homepage_event_label', 'option');
if ($featuredEventLabel == "") {
	$featuredEventLabel = "This week";
}

$showFeaturedCallout = get_field('show_homepage_featured_callout', 'option');
$featuredCallout = get_field('homepage_featured_callout', 'option');


$featuredEvent = get_field('homepage_featured_event', 'option');
$featuredPost = get_field('homepage_featured_post', 'option');
$threatsToPressPost = get_field('homepage_threats_to_press_post', 'option');

/*** get impact category ***/
//$impactCat = get_category_by_slug('impact');
//$impactPermalink = get_category_link($impactCat->term_id);
$impactPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results' ) );
$impactPortfolioPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results/impact-portfolio' ) );

//$threatsCat=get_category_by_slug('threats-to-press');
//$threatsPermalink = get_category_link($threatsCat->term_id);
$threatsPermalink = get_permalink( get_page_by_path( 'threats-to-press' ) );

/*** add any posts from custom fields to our array that tracks post IDs that have already been used on the page ***/
$postIDsUsed = array();

if ( $featuredPost ) {
	$postIDsUsed[] = $featuredPost -> ID;
}

if ( $showFeaturedEvent && $featuredEvent ) {
	$postIDsUsed[] = $featuredEvent -> ID;
}

if ( $soap ) {
	$postIDsUsed[] = $soap[0] -> ID;
}

if ( $threatsToPressPost ) {
	$postIDsUsed[] = $threatsToPressPost -> ID;
}



/******* BEGIN BURKE AWARDS ****/ 

function getBurkeImage() {
	return array(
		'imageID' => 37332,
		'imageCutline' => 'Burke Awards Logo',
		'bannerAdjustStr' => 'center center'
	);
}
$bannerText = "David Burke Awards"; 
$bannerLogo = "/wp-content/media/2017/07/burkeDemo.jpg";
$siteIntroContent = "The David Burke Awards are named after David W. Burke, founding chairman of the Broadcasting Board of Governors and leader for its first three years. The Burke Awards are presented annually to recognize the courage, integrity, and professionalism of journalists with the BBG.";
$burkeBioLink = "/who-we-are/our-leadership/board/david-w-burke/";
$burkeBioImage = "/wp-content/media/2017/08/Burke-obit-superJumbo.jpg";
/******* END BURKE AWARDS ****/



/*** output the standard header ***/
get_header();

?>
<style>
	.bbg-burke__network {
		font-style: italic;
	}
	.bbg-burke__occupation {
		font-weight: normal;
	}
	.bbg-burke__tagline {
		font-weight: normal;
	}
</style>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			<?php
				/*** output our <style> node for use by the responsive banner ***/
				$data = get_theme_mod('header_image_data');

				$attachment_id = is_object($data) && isset($data->attachment_id) ? $data->attachment_id : false;
				$randomImg= getBurkeImage();
				$bannerCutline="";
				$bannerAdjustStr="";
				if ($randomImg) {
					$attachment_id = $randomImg['imageID'];
					$bannerCutline = $randomImg['imageCutline'];
					$bannerAdjustStr = $randomImg['bannerAdjustStr'];
				}
				if($attachment_id) {
					$tempSources= bbgredesign_get_image_size_links($attachment_id);
					//sources aren't automatically in numeric order.  ksort does the trick.
					ksort($tempSources);
					$counter=0;
					$prevWidth=0;
					// Let's prevent any images with width > 1200px from being an output as part of responsive banner
					foreach( $tempSources as $key => $tempSource ) {
						if ($key > 1900) {
							unset($tempSources[$key]);
						}
					}
					echo "<style>";
					if ($bannerAdjustStr != "") {
						echo "\t.bbg-banner { background-position: $bannerAdjustStr; }";
					}
					foreach( $tempSources as $key => $tempSourceObj ) {
						$counter++;
						$tempSource=$tempSourceObj['src'];
						if ($counter == 1) {
							echo "\t.bbg-banner { background-image: url($tempSource) !important; }\n";
						} elseif ($counter < count($tempSources)) {
							echo "\t@media (min-width: " . ($prevWidth+1) . "px) and (max-width: " . $key . "px) {\n";
							echo "\t\t.bbg-banner { background-image: url($tempSource) !important; }\n";
							echo "\t}\n";
						} else {
							echo "\t@media (min-width: " . ($prevWidth+1) . "px) {\n";
							echo "\t\t.bbg-banner { background-image: url($tempSource) !important; }\n";
							echo "\t}\n";
						}
						$prevWidth=$key;
					}
					echo "</style>";
				}
			?>

			<!-- Responsive Banner -->
			<section class="usa-section bbg-banner__section" style="position: relative; z-index:9990;">
				<div class="bbg-banner">
					<div class="bbg-banner__gradient"></div>
					<div class="usa-grid bbg-banner__container--home">
						<img class="bbg-banner__site-logo" src="<?php echo $bannerLogo; ?>" alt="BBG logo">
						<div class="bbg-banner-box">
							<h1 class="bbg-banner-site-title"><?php echo $bannerText; ?></h1>
						</div>
						<div class="bbg-social__container">
							<div class="bbg-social">
							</div>
						</div>
					</div>
				</div>

				<div class="bbg-banner__cutline usa-grid">
					<?php echo $bannerCutline; ?>
				</div>
			</section><!-- Responsive Banner -->


			<div class="bbg__social__container">
				<?php if (isset($_GET['social'])): ?>
				<div class="bbg__social">
					<h3 class="bbg__social-list__label">FOLLOW US</h3>
					<ul class="bbg__social-list">
						<li class="bbg__social-list__link"><a href="https://www.facebook.com/BBGgov/" title="Like BBG on Facebook" class="bbg-icon-facebook" tabindex="-1"></a></li>
						<li class="bbg__social-list__link"><a href="https://twitter.com/BBGgov" title="Follow BBG on Twitter" class="bbg-icon-twitter" tabindex="-1"></a></li>
						<li class="bbg__social-list__link"><a href="https://www.youtube.com/user/bbgtunein" title="Check out BBG videos on YouTube" class="bbg-icon-youtube" tabindex="-1"></a></li>
					</ul>
				</div>
				<?php endif; ?>
			</div>

			<!-- Site introduction -->
			<section id="mission" class="usa-section usa-grid">
			<?php
				echo '<h3 id="site-intro" class="usa-font-lead">';
				echo $siteIntroContent;
				//echo ' <a href="'.$siteIntroLink.'" class="bbg__read-more">LEARN MORE »</a></h3>';
			?>
			</section><!-- Site introduction -->

			
				
			
			<section id="winners" class="usa-section ">
			<div class="usa-grid">
			<h6 class="bbg__label">Meet the Winners</h6>
			<p>
				Each network nominates a minimum of one Burke award winner every year. 2017 marks the 16th year in which the awards have been given. Below is a random sampling of this year's winners, and you may <a href='/burke_candidates'>view the archive</a> to see a complete listing of nominees and winners for this year as well as years past.
			</p>
			</div>
			<div class="usa-grid">
			<?php
					$counter=0;
					/*** USED FOR AWARDS ****/
					$qParams=array(
						'post_type' => 'burke_candidate'
						,'posts_per_page' => 3
						,'order' => 'ASC'
					);
					$custom_query = new WP_Query( $qParams );
					$counter = 0;
					while ( $custom_query->have_posts() )  {
						$custom_query->the_post();
						$counter++;
						get_template_part( 'template-parts/content-burke', get_post_format() );
					}
			?>
			<div align="right"><a href="/burke_candidates/" class="bbg__kits__intro__more--link">View all nominees and winners »</a></div>
			</div>

			</section>



			<div class="usa-section usa-grid bbg__kits__section" id="page-sections">
			    <section class="usa-grid-full bbg__kits__section--row bbg__ribbon--thin">
			        <div class="usa-grid">
			            <div class="bbg__announcement__flexbox">
			                <div id="lansingPhoto" class="bbg__announcement__photo" style="background-image: url(<?php echo $burkeBioImage; ?>);"></div>
			                <div>
			                    <h6 class="bbg__label">BBG History</h6>
			                    <h2 class="bbg__announcement__headline selectionShareable"><a href="<?php echo $burkeBioLink; ?>">David Burke</a></h2>
			                    <p>David W. Burke was named to the first Broadcasting Board of Governors (BBG) by President Clinton in 1995 and served as its first chairman. <a href="<?php echo $burkeBioLink; ?>" class="bbg__kits__intro__more--link">Learn More »</a></p>
			                </div>
			            </div>
			            <!-- .bbg__announcement__flexbox -->
			        </div>
			        <!-- .usa-grid -->
			    </section>
			</div>


			<!-- Quotation -->
			<section class="usa-section ">
				<div class="usa-grid">
					<?php
						$quote = '';
						$networkColor = '#FF0000';
						$quoteNetwork = 'BBG';
						$quoteText = 'These journalists have exemplified the definition of bravery and courage by risking their lives to report from some of the most dangerous places in the world.';
						$speaker = 'John Lansing';
						$tagline = 'BBG CEO and Director';
						$mugshot = '/wp-content/media/2017/07/john_lansing_ceo-sq.jpg';
						$quote .= '<div class="bbg__quotation">';
							if ( $quoteNetwork != '' ) {
								$quote .= '<div class="bbg__quotation-label" style="background-color:' . $networkColor . '">' . $quoteNetwork . '</div>';
							}
							$quote .= '<h2 class="bbg__quotation-text--large">&ldquo;' . $quoteText . '&rdquo;</h2>';
							$quote .= '<div class="bbg__quotation-attribution__container">';
								$quote .= '<p class="bbg__quotation-attribution">';

								if ( $mugshot != '' ) {
									$quote .= '<img src="' . $mugshot . '" class="bbg__quotation-attribution__mugshot"/>';
								}
								$quote .= '<span class="bbg__quotation-attribution__text">';
								$quote .= '<span class="bbg__quotation-attribution__name">' . $speaker . '</span>';
								$quote .= '<span class="bbg__quotation-attribution__credit">' . $tagline . '</span>';
								$quote .= '</span></p>';
							$quote .= '</div>';
						$quote .= '</div>';
						echo $quote;
					?>
				</div>
			</section><!-- Quotation -->

		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
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

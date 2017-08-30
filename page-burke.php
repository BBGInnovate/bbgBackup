<?php

/**
 * The custom page for the Burke Awards.
 * It includes:
 *      - the lead image
 *      - the blurb
 *      - three randomly selected winners
 *      - david burke ribbon
 *      - a quote from John Lansing
 *
 * @package bbgRedesign
  template name: Burke Awards
 */

/******* BEGIN BURKE AWARDS ****/

function getBurkeImage() {
	return array(
		'imageID' => 37332,
		'imageCutline' => '', //'imageCutline' => 'Burke Awards Logo',
		'bannerAdjustStr' => 'center center'
	);
}
$bannerText = "David Burke Awards";
$bannerLogo = "/wp-content/media/2017/07/burkeDemo.jpg";
$siteIntroContent = "The David Burke Awards are named after David W. Burke, founding chairman of the Broadcasting Board of Governors and leader for its first three years. The Burke Awards are presented annually to recognize the courage, integrity, and professionalism of journalists with the BBG.";
$burkeBioLink = "/who-we-are/our-leadership/board/david-w-burke/";
$burkeBioImage = "/wp-content/media/2017/08/David-Burke-profile.png";
$activeYear = 2016;  //in theory we could let the user pick this

/******* END BURKE AWARDS ****/

/*** output the standard header ***/
get_header();

?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			<?php
				/*** output our <style> node for use by the responsive banner ***/
				$randomImg = getBurkeImage();
				$bannerCutline = "";
				$bannerAdjustStr = "";
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

			<?php

				if ( true || isset ($_GET['slider'] ) ) :
					echo '<section class="usa-section bbg-banner__section" style="position: relative; z-index:9990;">';
					echo do_shortcode( '[rev_slider alias="burke-awards"]' );
					echo '</section>';
				else:
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

				<?php
				endif;

			?>


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
					<h6 class="bbg__label">Meet the winners</h6>
					<div class="bbg-blog__excerpt-content">
						<p>
							Each network nominates a minimum of one Burke award winner every year. 2017 marks the 16<sup>th</sup> year in which the awards have been given. Below is a random sampling of this year’s winners.
							<a href="/burke-candidates/" class="bbg__kits__intro__more--link">Complete list of this year’s winners »</a>

							<!-- and you may <a href='/burke-candidates/'>view the archive</a> to see a complete listing of nominees and winners for this year as well as years past.-->
						</p>
					</div>
				</div>
				<div class="usa-grid">
				<?php
					// BEGIN: Create an array of three random IDs of burke candidate winners from this year
					$counter = 0;
					$qParams = array(
						'post_type' => 'burke_candidate'
						,'meta_query' => array(
							array(
								'key' => 'burke_year_of_eligibility',
								'value' => $activeYear,
								'compare' => '='
							)
						)
					);
					$custom_query = new WP_Query( $qParams );
					$counter = 0;
					$allCandidateIDs = array();
					while ( $custom_query -> have_posts() )  {
						$custom_query -> the_post();
						$allCandidateIDs [] = get_the_ID();
					}
					shuffle( $allCandidateIDs );
					$randomCandidateIDs = array_slice( $allCandidateIDs, 0, min( 3, count($allCandidateIDs) ) );
					wp_reset_query();
					// END: Create an array of three random IDs of burke candidate winners from this year

					// BEGIN: Query and display our three burke candidates

					/*  THIS OLD VERSION OF THE QUERY GETS THREE MOST RECENTLY UPDATED CANDIDATES
						$qParams=array(
						'post_type' => 'burke_candidate'
						,'posts_per_page' => 3
						,'order' => 'DESC'
					);
					*/

					$qParams = array(
						'post_type' => 'burke_candidate',
						'post__in' => $randomCandidateIDs
					);
					$custom_query = new WP_Query( $qParams );
					$counter = 0;
					while ( $custom_query -> have_posts() )  {
						$custom_query -> the_post();
						$counter++;
						//get_template_part( 'template-parts/content-burke', get_post_format() );
					}
					wp_reset_query();
					// END: Create an array of three random IDs of burke candidate winners from this year
					echo do_shortcode('[smartslider3 slider=3]');
				?>
			</section>

<div class="usa-section usa-grid bbg__kits__section" id="page-sections">
			<section class="usa-grid-full bbg__kits__section--row " style="margin-top:-50px;">
			<div align="right"><a href="/burke-awards-archive/" class="bbg__kits__intro__more--link">View full winner archive »</a></div>
			</div>
			</section>
			</div>

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
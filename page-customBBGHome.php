<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Custom BBG Home
 */

//helper function used only in this template
function getRecentPostQueryParams($numPosts, $used, $catExclude) {
	$qParams=array(
		'post_type' => array('post'),
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
function getThreatsPostQueryParams($numPosts, $used) {
	$qParams=array(
		'post_type' => array('post'),
		'posts_per_page' => $numPosts,
		'orderby' => 'post_date',
		'order' => 'desc',
		'cat' => get_cat_id('Threats to Press'),
		'post__not_in' => $used
	);
	return $qParams;
}

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

$featuredBoardMeeting = get_field('homepage_featured_board_meeting', 'option');
$featuredPost = get_field('homepage_featured_post', 'option');
$defaultBoardMeetingImageObj=get_field('site_setting_default_homepage_board_meeting_image', 'option');
$threatsToPressPost = get_field('homepage_threats_to_press_post', 'option');

/*** get impact category ***/
$impactCat = get_category_by_slug('impact');
//$impactPermalink = get_category_link($impactCat->term_id);
$impactPermalink = get_permalink( get_page_by_path( 'our-work/impact-and-results' ) );
$threatsCat=get_category_by_slug('threats-to-press');
$threatsPermalink = get_category_link($threatsCat->term_id);
//$threatsPermalink = "https://bbgredesign.voanews.com/blog/tag/khadija-ismayilova/";

/*** add any posts from custom fields to our array that tracks post IDs that have already been used on the page ***/
$postIDsUsed=array();
if ($featuredPost) {
	$postIDsUsed[]=$featuredPost->ID;
}
if ($featuredBoardMeeting) {
	$postIDsUsed[]=$featuredBoardMeeting->ID;
}
if ($soap) {
	$postIDsUsed[]=$soap->ID;
}
if ($threatsToPressPost) {
	$postIDsUsed[]=$threatsToPressPost->ID;
}

/*** prepare a variable for our default board meeting image string ***/
$defaultBoardMeetingImage="";
if ($defaultBoardMeetingImageObj) {
	if (  isset($defaultBoardMeetingImageObj['sizes']) && 
		  isset($defaultBoardMeetingImageObj['sizes']['medium-thumb'])) {
		$defaultBoardMeetingImage=$defaultBoardMeetingImageObj['sizes']['medium-thumb'];		
	}
}

/*** output the standard header ***/
get_header();

?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			<?php
				/*** output our <style> node for use by the responsive banner ***/
				$data = get_theme_mod('header_image_data');

				$attachment_id = is_object($data) && isset($data->attachment_id) ? $data->attachment_id : false;
				$randomImg= getRandomEntityImage();
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
			<section class="usa-section bbg-banner__section">
				<div class="bbg-banner">
					<div class="bbg-banner__gradient"></div>
					<div class="usa-grid bbg-banner__container--home">
						<a href="<?php echo site_url(); ?>">
							<img class="bbg-banner__site-logo" src="<?php echo get_template_directory_uri() ?>/img/logo-agency-square.png" alt="BBG logo">
						</a>
						<div class="bbg-banner-box">
							<h1 class="bbg-banner-site-title"><?php echo bbginnovate_site_name_html(); ?></h1>
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

			<!-- Site introduction -->
			<section id="mission" class="usa-section usa-grid">
			<?php
				echo '<h3 id="site-intro" class="usa-font-lead">';
				echo $siteIntroContent;
				echo ' <a href="'.$siteIntroLink.'" class="bbg__read-more">LEARN MORE »</a></h3>';
			?>
			</section><!-- Site introduction -->

			<!-- Impact stories + 1 Quotation-->
			<section id="impact-stories" class="usa-section bbg-portfolio">
				<div class="usa-grid">

					<div class="usa-width-two-thirds">

						<h6 class="bbg-label"><a href="<?php echo $impactPermalink; ?>">Impact stories</a></h6>

						<div class="usa-grid-full">
						<?php
							$qParams=array(
								'post_type' => array('post'),
								'posts_per_page' => 2,
								'orderby' => 'post_date',
								'order' => 'desc',
								'cat' => get_cat_id('Impact'),
								'post__not_in' => $postIDsUsed
							);
							query_posts($qParams);
							if ( have_posts() ) :
								while ( have_posts() ) : the_post();
									///$gridClass = "bbg-grid--1-3-3";
									$gridClass = "usa-width-one-half";
									$includePortfolioDescription = FALSE;
									$postIDsUsed[] = get_the_ID();
									get_template_part( 'template-parts/content-portfolio', get_post_format() );
								endwhile;
							endif;
							wp_reset_query();
						?>
						</div>
						<div class="usa-grid-full u--space-below-mobile--large">
							<a href="<?php echo $impactPermalink; ?>">View all impact stories »</a>
						</div><!-- .usa-grid -->
					</div>
					<!-- Quotation -->
					<div class="usa-width-one-third">
					<?php 
						if ($featuredBoardMeeting && $showFeaturedEvent) { 

							$id = $featuredBoardMeeting->ID;
							$labelText = $featuredEventLabel;
							$eventPermalink = get_the_permalink($id);
							$imgSrc = $defaultBoardMeetingImage;
							$featuredImageID = get_post_thumbnail_id($id);	
							if ($featuredImageID) {
								$imgObj = wp_get_attachment_image_src($featuredImageID, 'medium-thumb');
								$imgSrc = $imgObj[0];
							}
							$eventTitle = $featuredBoardMeeting->post_title;
							$excerpt = my_excerpt($id);
					?>

							<article class="bbg-portfolio__excerpt bbg__event-announcement" style="" >
								<h6 class="bbg-label " style=" display: inline-block; margin-bottom: 1rem;"><?php echo $labelText ?></h6>
								<div style="background-color: #F1F1F1; padding: 1rem 2rem; border-radius: 0 3px 3px 3px">
									<header class="entry-header bbg-portfolio__excerpt-header">
										<h3 class="entry-title bbg-portfolio__excerpt-title bbg__event-announcement__title"><a href="<?php echo $eventPermalink; ?>" rel="bookmark"><?php echo $eventTitle; ?></a></h3>		
									</header><!-- .entry-header -->

									<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content bbg__event-announcement__excerpt" style="">
										<p><?php echo $excerpt; ?></p>
									</div><!-- .bbg-portfolio__excerpt-title -->
								</div>
							</article>

					<?php } else { 
						$q=getRandomQuote('allEntities', $postIDsUsed);
						if ($q) {
							$postIDsUsed[] = $q["ID"];
							outputQuote($q, "");
						}

					} ?>
					</div><!-- usa-grid-one-third -->
					</div><!-- .usa-grid-->
			</section><!-- Impact stories + 1 Quotation - #impact-stories .usa-section .bbg-portfolio -->




			<!-- Recent posts (Featured, left 2 headline/teasers, right soapbox/headlines) -->
			<section id="recent-posts" class="usa-section">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'blog' ) ) ?>">BBG News</a></h6>
				</div>
				
				<!-- Featured Post -->
				<div class="usa-grid-full">
				<?php
					/* let's get our featured post, which is either selected in homepage settings or is most recent post */
					if ($featuredPost) {
						$qParams=array(
							'post__in' => array($featuredPost->ID)
						);
					} else {
						$qParams=getRecentPostQueryParams(1,$postIDsUsed,$STANDARD_POST_CATEGORY_EXCLUDES);
					}
					query_posts($qParams);
					if (have_posts()) {
						while ( have_posts() ) : the_post();
							$counter++;
							$postIDsUsed[] = get_the_ID();
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						endwhile;
					}
					wp_reset_query();
				?>
				</div><!-- . usa-grid-full Featured post -->

				<!-- Headlines -->
				<div class="usa-grid bbg__ceo-post"> 
					<div class="usa-width-one-half">
						<?php
							/* BEWARE: sticky posts add a record */
							$maxPostsToShow=9;
							if ($soap) {
								$maxPostsToShow=2;
							}
							$qParams=getRecentPostQueryParams($maxPostsToShow,$postIDsUsed,$STANDARD_POST_CATEGORY_EXCLUDES);
							query_posts($qParams);
							if ( have_posts() ) {
								$counter = 0;
								//If there's no soapbox post, show thumbnails in the left column
								$includeImage = FALSE;
								if (!$soap) {
									$includeImage = TRUE;
								}
								while ( have_posts() ) : the_post();
									$counter++;
									$postIDsUsed[] = get_the_ID();
									$gridClass = "bbg-grid--full-width";
									if ($counter > 2) {
										$includeImage = false;
										$includeMeta=false;
										$includeExcerpt=false;
										if ($counter==3) {
											echo '</div><div class="usa-width-one-half tertiary-stories"><header class="page-header"><h6 class="page-title bbg-label small">More news</h6></header>';
										}
									}
									get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
								endwhile;
							}
							wp_reset_query();
						?>
					</div>


					<?php
						if ($soap) {
							$s = getSoapboxStr($soap);
							echo $s;
						} 
					?>
				</div><!-- headlines -->
			</section><!-- .BBG News -->


			<!-- Threats to Journalism.  -->
			<section id="threats-to-journalism" class="usa-section bbg__ribbon">
				<div class="usa-grid">
					<h6 class="bbg-label small"><a href="<?php echo $threatsPermalink; ?>">Threats to Press</a></h6>
				</div>
				
				<!-- Headlines -->
				<div class="usa-grid"> 
					<div class="bbg-grid--1-2-2">

						<?php
							/* let's get our featured post, which is either selected in homepage settings or is most recent post */
							if ($threatsToPressPost) {
								$qParams=array(
									'post__in' => array($threatsToPressPost->ID)
								);
							} else {
								$qParams=getThreatsPostQueryParams(1,$postIDsUsed);
							}
							query_posts($qParams);
							if (have_posts()) {
								while ( have_posts() ) : the_post();
									$counter++;
									$id = get_the_ID();
									$postIDsUsed[] = $id;
									//$title = get_the_title( sprintf( '<h3 class="entry-title bbg-blog__excerpt-title--list"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
									echo '<article id="post-' . $id . '" '; post_class(); echo '>';
									echo '<div style="position: relative;">';
									echo '<a href="https://bbgredesign.voanews.com/blog/threats-to-journalism/khadija-ismailova/" rel="bookmark">';
									echo '<img src="https://bbgredesign.voanews.com/wp-content/media/2014/12/Khadija-Ismayilova-in-Baku-studio.jpg">';
									echo '</a>';
									echo '<h4 style="position: absolute; bottom: 1rem; left: 0; background-color: #000; border: 1px solid #333; color: #FFF; padding: .5rem;">1 year 5 months 14 days in prison</h4>';
									echo '</div>';
									echo '<header class="entry-header bbg-blog__excerpt-header"><h2 class="entry-title bbg-blog__excerpt-title--list"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h2></header>';
									echo '<div class="entry-content bbg-blog__excerpt-content"><p>';
									echo get_the_excerpt();
									echo '</p></div><!-- .bbg-blog__excerpt-content -->';
									echo '</article><!-- #post-## -->';
								endwhile;
							}
							wp_reset_query();
						?>
					</div>
					<div class="bbg-grid--1-2-2 tertiary-stories">
						<?php
							/* BEWARE: sticky posts add a record */
							$maxPostsToShow=7;
							$qParams=getThreatsPostQueryParams($maxPostsToShow,$postIDsUsed);
							query_posts($qParams);
							if ( have_posts() ) {
								$counter = 0;
								$includeImage = false;
								$includeMeta=false;
								$includeExcerpt=false;
								while ( have_posts() ) : the_post();
									$counter++;
									$postIDsUsed[] = get_the_ID();
									$gridClass = "bbg-grid--full-width";
									get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
								endwhile;
							}
							wp_reset_query();
						?>
					</div>
				</div><!-- headlines -->
			</section><!-- Threats to press section -->




			<!-- Entity list -->
			<section id="entities" class="usa-section bbg-staff">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'broadcasters' ) ); ?>" title="A list of the BBG broadcasters.">Our networks</a></h6>
					<div class="usa-intro bbg__broadcasters__intro">
						<h3 class="usa-font-lead">Every week, more than 226 million listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters</h3>
					</div>
					<?php echo outputBroadcasters('2'); ?>
				</div>
			</section><!-- entity list -->





			<!-- Quotation -->
			<section class="usa-section ">
				<div class="usa-grid">
					<?php
						$q = getRandomQuote('allEntities', $postIDsUsed);
						if ($q) {
							$postIDsUsed[] = $q["ID"];
							outputQuote($q);
						}
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
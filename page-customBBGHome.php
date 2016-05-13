<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Custom BBG Home
 */

$templateName = "customBBGHome";

$siteIntroContent=get_field('site_setting_mission_statement','options','false');
$siteIntroLink=get_field('site_setting_mission_statement_link', 'options', 'false');
$impactCat=get_category_by_slug('impact');
$impactPermalink=get_category_link($impactCat->term_id);		

get_header();

?>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			<?php
				$data = get_theme_mod('header_image_data');
				$attachment_id = is_object($data) && isset($data->attachment_id) ? $data->attachment_id : false;
				if($attachment_id) {
					$tempSources= bbgredesign_get_image_size_links($attachment_id);
					//sources aren't automatically in numeric order.  ksort does the trick.
					ksort($tempSources);
					$counter=0;
					$prevWidth=0;
					// Let's prevent any images with width > 1200px from being an output as part of responsive post cover
					foreach( $tempSources as $key => $tempSource ) {
						if ($key > 1900) {
							unset($tempSources[$key]);
						}
					}
					echo "<style>";
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
				//style="background-image:url(<?php echo get_header_image();
			?>
			<section class="bbg-banner"> 
				<div class="usa-grid bbg-banner__container">
					<a href="<?php echo site_url(); ?>">
						<img class="bbg-banner__site-logo" src="<?php echo get_template_directory_uri() ?>/img/logo-agency-square.png" alt="BBG logo">
					</a>
					<div class="bbg-banner-box">
						<h1 class="bbg-banner-site-title"><?php echo bbginnovate_site_name_html(); ?></h1>
						<?php 
						/*
						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<h3 class="bbg-banner-site-description usa-heading-site-description"><?php echo $description; ?></h3>
						<?php endif; 
						*/
						?>

					</div>

					<div class="bbg-social__container">
						<div class="bbg-social">
						</div>
					</div>
				</div>
			</section>

			


			<!-- Site introduction -->
			<section id="mission" class="usa-section usa-grid">
			<?php
				
				echo '<h3 id="site-intro" class="usa-font-lead">';
				echo $siteIntroContent;
				echo ' <a href="'.$siteIntroLink.'" class="bbg__read-more">LEARN MORE »</a></h3>';
			?>
			</section><!-- Site introduction -->

			<!-- Impact Stories -->
			<section id="projects" class="usa-section bbg-portfolio">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="<?php echo $impactPermalink; ?>">Impact stories</a></h6>

					<div class="usa-grid-full">
					<?php
						$qParams=array(
							'post_type' => array('post'),
							'posts_per_page' => 2,
							'orderby' => 'post_date',
							'order' => 'desc',
							'cat' => get_cat_id('Impact')
						);
						query_posts($qParams);

						if ( have_posts() ) :
							while ( have_posts() ) : the_post();
								$gridClass = "bbg-grid--1-3-3";
								get_template_part( 'template-parts/content-portfolio', get_post_format() );
							endwhile;
						endif;
						wp_reset_query();

					?>

					<!-- Quotation -->
					<?php
						$q=getRandomQuote('allEntities');
						if ($q) {
							outputQuote($q, "bbg-grid--1-3-3");
						}
					?>
					<!-- Quotation -->


					</div><!-- .usa-grid-full -->

					<a href="<?php echo $impactPermalink; ?>">View all impact stories »</a>

				</div><!-- .usa-grid -->
			</section><!-- .bbg-portfolio -->


			<!-- Recent posts -->
			<section id="recent-posts" class="usa-section">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'blog' ) ) ?>">Recent posts</a></h6>
				</div>

				<div class="usa-grid-full">

				<?php
					/* NOTE: if there is a sticky post, we may wind up with an extra item.
					So we hardcode the display code to ignore anything after the 3rd item */
					$maxPostsToShow=3;
					$qParams=array(
						'post_type' => array('post'),
						'posts_per_page' => $maxPostsToShow,
						'orderby' => 'post_date',
						'order' => 'desc',
						'category__not_in' => (array(get_cat_id('Site Introduction'),
													get_cat_id("John's take"),
													get_cat_id('Contact')
											)),
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
					
					query_posts($qParams);

					if ( have_posts() ) :
						$counter=0;
						while ( have_posts() ) : the_post();
							$counter++;
							if ($counter == 1) {
								get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
								echo '<div class="usa-grid">';
							}
							else if ($counter <= $maxPostsToShow) {
								$gridClass = "bbg-grid--1-2-2";
								$includeImage = FALSE;
								//get_template_part( 'template-parts/content-excerpt', get_post_format() );
							}
						endwhile;
						echo '</div><!-- .usa-grid-full -->';
					endif;
					wp_reset_query();
				?>
				</div>
			</section><!-- Recent posts -->


			<!-- CEO Post -->
			<section id="ceo-post" class="usa-section usa-grid bbg__ceo-post">

				<div class="bbg-grid--1-2-2">

				<?php
					/* NOTE: if there is a sticky post, we may wind up with an extra item.
					So we hardcode the display code to ignore anything after the 3rd item */
					$maxPostsToShow=2;
					$qParams=array(
						'post_type' => array('post'),
						'posts_per_page' => $maxPostsToShow,
						'orderby' => 'post_date',
						'order' => 'desc',
						'category__not_in' => (array(get_cat_id('Site Introduction'),
													get_cat_id("John's take"),
													get_cat_id('Contact')
											)),
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
					
					query_posts($qParams);

					if ( have_posts() ) :
						$counter=0;
						while ( have_posts() ) : the_post();
								$gridClass = "bbg-grid--full-width";
								$includeImage = FALSE;
								get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						endwhile;
					endif;
					wp_reset_query();
				?>
				</div>




			<?php
				$soap = get_field('homepage_soapbox_post', 'option');
				if ($soap) {
					$s = "";
					$id=$soap->ID;
					$soapCategory=wp_get_post_categories($id);

					$isCEOPost=false;
					$isSpeech=false;
					$soapHeaderPermalink="";
					$soapHeaderText="";
					$soapPostPermalink=get_the_permalink($id);
					$mugshot = "";
					$mugshotName = "";

					foreach ($soapCategory as $c) {
						$cat = get_category( $c );
						if ($cat->slug=="johns-take") {
							$isCEOPost=true;
							$soapHeaderText="From the CEO";
							$soapHeaderPermalink=get_category_link($cat->term_id);
							$mugshot = "https://bbgredesign.voanews.com/wp-content/media/2016/04/john_lansing_ceo-200x200.jpg";
							$mugshotName = "John Lansing";
						} else if ($cat->slug == "speech") {
							$isSpeech = true;
							$mugshotID = get_post_meta( $id, 'mugshot_photo', true );
							$mugshotName = get_post_meta( $id, 'mugshot_name', true );

							if ($mugshotID) {
								$mugshot = wp_get_attachment_image_src( $mugshotID , 'mugshot');
								$mugshot = $mugshot[0];
							}
						}
					}

					$s .= '<div class="bbg-grid--1-2-2 bbg__voice--featured">';
					if ($soapHeaderPermalink != "") {
						$s .= '<h6 class="bbg-label small"><a href="'.$soapHeaderPermalink.'">'.$soapHeaderText.'</a></h6>';
					}
					
					$s .= '<h2 class="bbg-blog__excerpt-title"><a href="' . $soapPostPermalink. '">';
					$s .= get_the_title($id);
					$s .= '</a></h2>';

					$s .= '<p class="">';

					//if ($isCEOPost) {
					if ($mugshot != "") {
						$s .= '<span class="bbg__mugshot"><img src="' . $mugshot . '" class="bbg__ceo-post__mugshot" />';
						if ($mugshotName != "") {
							$s .= '<span class="bbg__mugshot__caption">' . $mugshotName . '</span>';
						} 
						$s .= '</span>';
					}

					$s .= my_excerpt($id);
					$s .= ' <a href="' . $soapPostPermalink. '" class="bbg__read-more">READ MORE »</a></p>';
					$s .= '</div>';
				} else {
					$s = "";
					
					$someHeadlinesPermalink="http://google.com";
					$s .= '<div class="bbg-grid--1-2-2 ">';
					$s .= '<h6 class="bbg-label small"><a href="'.$someHeadlinesPermalink.'">SOME HEADLINES</a></h6>';
					$s .= '<p>Some headlines will go here</p>';
					$s .= '</div>';
				}
				echo $s;
			?>



			</section><!-- ceo post experiment -->


			<!-- Entity list -->
			<section id="teams" class="usa-section bbg-staff">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'broadcasters' ) ); ?>" title="A list of the BBG broadcasters.">Our broadcasters</a></h6>

					<div class="usa-intro bbg__broadcasters__intro">
						<h3 class="usa-font-lead">Every week, more than 226 million listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters</h3>
					</div>

					<?php echo outputBroadcasters(); ?>
					
					<?php /* <a href="<?php echo get_permalink( get_page_by_path( 'about-the-agency/history/' ) ); ?>">Learn more about the history of USIM »</a> */ ?>
				</div>
			</section><!-- entity list -->

			<!-- Quotation -->
			<section class="usa-section ">
				<div class="usa-grid">
					<?php
						$q=getRandomQuote('allEntities');
						if ($q) {
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
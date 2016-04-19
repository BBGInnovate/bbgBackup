<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Custom BBG Home
 */

$templateName = "customBBGHome";

get_header();

?>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">


			<?php
				if ( get_header_image() != "") {
					/* Check if there's an image set. Ideally we'd tweak the design accorgingly. */
				}
			?>
			<section class="bbg-banner" style="background-image:url(<?php echo get_header_image(); ?>)">
				<div class="usa-grid bbg-banner__container">
					<a href="<?php echo site_url(); ?>">
						<img class="bbg-banner__site-logo" src="<?php echo get_template_directory_uri() ?>/img/logo-agency-square.png" alt="BBG logo">
					</a>
					<div class="bbg-banner-box">
						<h1 class="bbg-banner-site-title"><?php echo bbginnovate_site_name_html(); ?></h1>
						<?php $description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<h3 class="bbg-banner-site-description usa-heading-site-description"><?php echo $description; ?></h3>
						<?php endif; ?>

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
				$qParams=array(
					'post_type' => array('post'),
					'posts_per_page' => 1,
					'cat' => get_cat_id('Site Introduction')
				);
				query_posts($qParams);

				$siteIntroContent="";
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						$siteIntroTitle=get_the_title();
						echo '<h3 id="site-intro" class="usa-font-lead">';
						/* echo '<h2>' . $siteIntroTitle . '</h2>'; */
						echo get_the_content();
						echo '</h3>';
					endwhile;
				endif;
				wp_reset_query();
			?>
			</section><!-- Site introduction -->


			<!-- Portfolio -->
			<section id="projects" class="usa-section bbg-portfolio">
				<div class="usa-grid">
					<h6 class="bbg-label"><a href="/blog/category/press-release/">Around the BBG</a></h6>

					<div class="usa-grid-full">
					<?php
						$qParams=array(
							'post_type' => array('post'),
							'posts_per_page' => 3,
							'orderby' => 'post_date',
							'order' => 'desc',
							'cat' => get_cat_id('Press Release')
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
					</div><!-- .usa-grid-full -->

					<a href="/blog/category/press-release/">View all press releases »</a>

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
													get_cat_id('Profile'),
													get_cat_id("John's take"),
													get_cat_id('Contact')
											))
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
								get_template_part( 'template-parts/content-excerpt', get_post_format() );
							}
						endwhile;
						echo '</div><!-- .usa-grid-full -->';
					endif;
					wp_reset_query();
				?>
				</div>
			</section><!-- Recent posts -->


<section id="teams" class="usa-section bbg-staff">
					<div class="usa-grid">
						<h6 class="bbg-label"><a href="https://bbgredesign.voanews.com/broadcasters/" title="A list of the BBG broadcasters.">Our broadcasters</a></h6>

						<div class="usa-intro bbg__broadcasters__intro">
							<h3 class="usa-font-lead">Every week, more than 226 million listeners, viewers and Internet users around the world turn on, tune in and log onto U.S. international broadcasting programs. The day-to-day broadcasting activities are carried out by the individual BBG international broadcasters</h3>
						</div>

						<div class="usa-grid-full">

								<article class="bbg__entity bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg__entity__icon">
										<a href="/broadcasters/voa/" tabindex="-1">
										<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_voa--circle-200.png);"></div>
										</a>
									</div>

									<div class="bbg__entity__text">
										<h2 class="bbg__entity__name"><a href="/broadcasters/voa/">Voice of America</a></h2>
										<p class="bbg__entity__text-description">VOA produces popular news, information and cultural programs in 45 languages and reaches more than 164 million people around the world every week on television, radio, web and mobile platforms. VOA attracts 80 percent of the total U.S. international media audience.</p>	
									</div><!-- .bbg__entity__text -->

								</article>

								<article class="bbg__entity bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg__entity__icon">
										<a href="/broadcasters/rferl/" tabindex="-1">
										<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_rferl--circle-200.png);"></div>
										</a>
									</div>

									<div class="bbg__entity__text">
										<h2 class="bbg__entity__name"><a href="/broadcasters/rferl/">Radio Free Europe / Radio Liberty</a></h2>
										<p class="bbg__entity__text-description">RFE/RL journalists provide what many people cannot get locally: uncensored news, responsible discussion, and open debate. Its programming focuses on local and regional developments in places where the media are not free or threats to civil society and democracy remain.</p>
									</div><!-- .bbg__entity__text -->

								</article>

								<article class="bbg__entity bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg__entity__icon">
										<a href="/broadcasters/rfa/" tabindex="-1">
										<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_rfa--circle-200.png);"></div>
										</a>
									</div>

									<div class="bbg__entity__text">
										<h2 class="bbg__entity__name"><a href="/broadcasters/rfa/">Radio Free Asia</a></h2>
										<p class="bbg__entity__text-description">RFA journalists provide uncensored, fact-based news to citizens of these countries, among the world’s worst media environments. RFA is funded by a grant from the BBG.</p>
									</div><!-- .bbg__entity__text -->

								</article>

								<article class="bbg__entity bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg__entity__icon">
										<a href="/broadcasters/ocb/" tabindex="-1">
										<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_marti_noticias--circle-200.png);"></div>
										</a>
									</div>

									<div class="bbg__entity__text">
										<h2 class="bbg__entity__name"><a href="/broadcasters/ocb/">Office of Cuban Broadcasting</a></h2>
										<p class="bbg__entity__text-description">OCB oversees Radio and Television Martí at its headquarters in Miami, Florida. The Martís are a multimedia hub of news, information and analysis that provide the people of Cuba with interactive programs seven days a week</p>
									</div><!-- .bbg__entity__text -->

								</article>

								<article class="bbg__entity bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg__entity__icon">
										<a href="/broadcasters/mbn/" tabindex="-1">
										<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url(https://bbgredesign.voanews.com/wp-content/themes/bbgRedesign/img/logo_alhurra--circle-200.png);"></div>
										</a>
									</div>

									<div class="bbg__entity__text">
										<h2 class="bbg__entity__name"><a href="/broadcasters/mbn/">Middle East Broadcasting Network</a></h2>
										<p class="bbg__entity__text-description">MBN is the non-profit news organization that operates Alhurra Television, Radio Sawa and MBN Digital reaching audiences in 22 countries across the Middle East.</p>
									</div><!-- .bbg__entity__text -->

								</article>

								

						</div>
						<a href="https://bbgredesign.voanews.com/about-the-agency/history/">Learn more about the history of USIM »</a>
					</div>
				</section>


		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
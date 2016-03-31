<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Custom Home
 */

$templateName = "customHome";

/*
require(dirname(__FILE__).'/../../fuego/init.php');
use OpenFuego\app\Getter as Getter;
$fuego = new Getter();
siteintroduction
*/

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
							<ul class="bbg-social__list">
								<li class="bbg-social__list__link"><a href="https://github.com/BBGInnovate" title="The ODDI GitHub repo" class="bbg-icon-github"></a></li>
								<li class="bbg-social__list__link"><a href="https://www.youtube.com/channel/UCtDMNCM2Vt_w2M3Irzb-1PQ" title="Check out the ODDI videos on YouTube" class="bbg-icon-youtube"></a></li>
								<li class="bbg-social__list__link"><a href="https://twitter.com/BBGinnovate" title="Follow ODDI on Twitter" class="bbg-icon-twitter"></a></li>
							</ul>
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
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'projects' ) ) ?>">Projects</a></h6>

					<div class="usa-grid-full">
					<?php
						$qParams=array(
							'post_type' => array('post'),
							'posts_per_page' => 3,
							'orderby' => 'post_date',
							'order' => 'desc',
							'cat' => get_cat_id('Project')
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

					<a href="<?php echo get_permalink( get_page_by_path( 'projects' ) ) ?>">Explore entire portfolio »</a>

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
						'category__not_in' => (array(get_cat_id('Project'),get_cat_id('Site Introduction'), get_cat_id('MobileApps Introduction')))
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
				?>
				</div>
			</section><!-- Recent posts -->


			<?php
				/** in general settings we've entered featured categoryIDs as a comma separated list which should be ones that are specified as teams and have users that are specified as their heads */
				$featuredCategoryIDsStr=get_option( 'featuredCategoryIDs' );
				if ($featuredCategoryIDsStr != "") {
			?>
				<!-- Staff -->
				<section id="teams" class="usa-section bbg-staff">
					<div class="usa-grid">
						<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'teams' ) ) ?>" title="A directory of the current ODDI staff.">Our teams</a></h6>

						<div class="usa-intro">
							<h3 class="usa-font-lead">ODDI’s teams of designers, developers and storytellers help drive USIM digital projects.</h3>
						</div>

						<div class="usa-grid-full">

							<?php
								/*
								   we need a way to know which categories are owned by which user - create a quick data structure.
								   there is likely a more efficient way to do that but with <100 users, no harm
								*/

								$categoryHeads=array();
								$blogusers = get_users();
								foreach ( $blogusers as $user ) {
									if ($user->headOfTeam != "") {
										$categoryHeads[$user->headOfTeam]=$user;
									}
								}


								$featuredCategoryIDs = explode( ',', $featuredCategoryIDsStr );
								array_walk( $featuredCategoryIDs, 'intval' );
								$args = array( 'include' => $featuredCategoryIDs, 'orderby' => 'include', 'hide_empty' => false);

								$categories = get_categories($args );
								foreach ( $categories as $category ) {
									$iconName = "bbg-team__icon__".$category->category_nicename;
									//$categoryLink=get_category_link( $category->term_id );
									$categoryLink= add_query_arg('category_id', $category->term_id, get_permalink( get_page_by_path( 'team' ) ));
								?>
								<article class="bbg-team bbg-grid--1-1-1-2">

									<div class="bbg-avatar__container bbg-team__icon">
										<a href="<?php echo $categoryLink; ?>" tabindex="-1">
										<div class="bbg-avatar bbg-team__icon__image <?php echo $iconName ?>" style="background-image: url(<?php echo get_template_directory_uri() ?>/img/icon_team_<?php echo $category->category_nicename; ?>.png);"></div>
										</a>
									</div>

									<div class="bbg-team__text">
										<?php
											$user=$categoryHeads[$category->term_id];
											$authorPath = get_author_posts_url($user->ID);
											echo "<h2 class='bbg-team__name'><a href='$categoryLink'>" . $category->name . "</a></h2>";
											echo "<p class='bbg-team__text-description'>" . $category->description . "</p>";
										?>
									</div><!-- .bbg-team__text -->

								</article>

								<?php } ?>


						</div>
						<a href="<?php echo get_permalink( get_page_by_path( 'staff' ) ) ?>">Meet the full ODDI team »</a>
					</div>
				</section><!-- Staff -->
			<?php
				}
			?>


		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
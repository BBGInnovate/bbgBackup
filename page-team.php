<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Team
 */

/*** SHARING VARS ****/
$teamCategoryID=$_GET["category_id"];
$teamCategory=get_category($teamCategoryID);
$iconName = "bbg-team__icon__".$teamCategory->category_nicename;

$ogTitle=$teamCategory->name . " team page";
$ogDescription=$teamCategory->description; 
/*** END SHARING VARS ****/

$numPortfolioPostsToShow=3;
$numBlogPostsToShow=2;

$blogusers = get_users();
$teamLead=false;
foreach($blogusers as $user) {
	if ($user->headOfTeam== $teamCategoryID) {
		$teamLead=$user;
		break;
	} 
}

$teamPortfolioLink=add_query_arg('category_id', $teamCategoryID, get_permalink( get_page_by_path( 'projects' )));
$fullOddiPorfolioLink=get_permalink( get_page_by_path( 'projects' ));


get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

				<header class="page-header bbg-page__header">
					<div class="bbg-avatar__container bbg-team__icon">
						<div class="bbg-avatar bbg-team__icon__image <?php echo $iconName ?>" style="background-image: url(<?php echo get_template_directory_uri() ?>/img/icon_team_<?php echo $teamCategory->category_nicename; ?>.png);"></div>
					</div>
					<div class="bbg-team__text">
						<h1 class="page-title bbg-team__name"><?php echo $teamCategory->name; ?> Team</h1>
						<h3 class="bbg-team__text-description bbg-page__header-description"><?php echo wptexturize($ogDescription); ?></h3>
						<?php 
							if ($teamLead) {
								//bbg_post_author_bottom_card($teamLead->ID);
								//var_dump($teamLead);
								$authorPath = get_author_posts_url($teamLead -> ID);
								echo '<p>Contact: <a href="' . $authorPath . '">' . $teamLead->display_name . '</a></p>';
							}
						?>
					</div>
				</header><!-- .page-header -->



				<section class="usa-section usa-grid">
					<?php $categoryLink=get_category_link( $teamCategoryID ); ?>

					<h6 class="bbg-label small"><a href="<?php echo $categoryLink; ?>">Recent posts</a></h6>
					<div class="bbg-grid__container">
						<?php 
							$qParams=array(
								'post_type' => array('post'),
								'posts_per_page' => $numBlogPostsToShow,
								'category__in' => [$teamCategoryID],
								'category__not_in' => [get_cat_id('Project')]
							);
							query_posts($qParams);
							$numBlogPostsAvailable=$wp_query->found_posts;

							while ( have_posts() )  {
								the_post();

								$gridClass = "bbg-grid--1-2-2";
								$includeImage = FALSE;
								get_template_part( 'template-parts/content-excerpt', get_post_format() );
							}
						?>
					</div><!-- .bbg-grid__container -->
					<?php if ($numBlogPostsAvailable > $numBlogPostsToShow) { ?>

					<a href="<?php echo $categoryLink; ?>" style="display: block; clear: left;">Read more <?php echo $teamCategory->name; ?> posts »</a>
					<?php } ?>
					
				</section>


				<?php 
					$qParams=array(
						'post_type' => array('post'),
						'posts_per_page' => $numPortfolioPostsToShow,
						'category__and' => [$teamCategoryID, get_cat_id('Project')]
					);
					query_posts($qParams);
					$numProjectsAvailable=$wp_query->found_posts;
				
					if (have_posts()) :
 				?>


						<section class="usa-section usa-grid">
							<h6 class="bbg-label small"><a href="<?php echo $teamPortfolioLink; ?>"><?php echo $teamCategory->name; ?> projects</a></h6>
							<div class="bbg-grid__container">
							
							<?php
								$counter=0;
								while ( have_posts() )  {
									the_post();

									$gridClass = "bbg-grid--1-3-3";
									get_template_part( 'template-parts/content-portfolio', get_post_format() );
								}
							?>
							</div><!--.bbg-grid__containter -->
							<?php if ($numProjectsAvailable > $numPortfolioPostsToShow) { ?>
							<a href="<?php echo $teamPortfolioLink; ?>" style="display:block; clear: left;">Explore the <?php echo $teamCategory->name; ?> portfolio »</a>
							<?php } ?>
						</section>
				<?php
					endif;
				?>

			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



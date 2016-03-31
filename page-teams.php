<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Teams Landing Page
 */

get_header();

?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="content" class="site-content bbg-home-main" role="main">

			<?php
				/** in general settings we've entered featured categoryIDs as a comma separated list which should be ones that are specified as teams and have users that are specified as their heads */
				$featuredCategoryIDsStr=get_option( 'featuredCategoryIDs' );
				if ($featuredCategoryIDsStr != "") {
			?>
				<!-- Teams -->
				<section id="teams" class="usa-section bbg-staff">
					<div class="usa-grid-full">
						<header class="page-header bbg-page__header">
							<div class="usa-intro">
								<h1 class="bbg-page__header-title">Our Teams</h1>
								<h3 class="usa-font-lead">ODDI’s teams of designers, developers and storytellers help drive USIM digital projects.</h3>
							</div>
						</header><!-- .page-header -->
					</div>
					<div class="usa-grid">

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
									<a href='<?php echo $categoryLink; ?>'>
									<div class="bbg-avatar bbg-team__icon__image <?php echo $iconName ?>" style="background-image: url(<?php echo get_template_directory_uri() ?>/img/icon_team_<?php echo $category->category_nicename; ?>.png);"></div>
									</a>
								</div>

								<div class="bbg-team__text">
									<?php
										$user=$categoryHeads[$category->term_id];
										$authorPath = get_author_posts_url($user->ID);
										echo "<h2 class='bbg-team__name'><a href='$categoryLink'>".$category->name."</a></h2>";
										echo "<p class='bbg-team__text-description'>" . $category->description . "</p>";
										// . "<br/> <span style='font-weight: bold;'>Project lead: </span><a href='" . $authorPath . "' class='bbg-staff__author-link'>$user->display_name</a></p>";
									?>
								</div><!-- .bbg-team__text -->

							</article>

							<?php } ?>
					</div>

					<div class="usa-grid">
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
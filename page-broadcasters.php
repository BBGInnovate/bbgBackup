<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Broadcasters
 */

$pageContent ="";
while ( have_posts() ) : the_post();
	$pageContent=get_the_content();
	$pageContent = apply_filters('the_content', $pageContent);
	$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
endwhile;

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

			<?php
				$hideFeaturedImage = get_post_meta( get_the_ID(), "hide_featured_image", true );
				if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
					echo '<div class="usa-grid-full">';
					$featuredImageClass = "";
					$featuredImageCutline="";
					$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
					if ($thumbnail_image && isset($thumbnail_image[0])) {
						$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
					}
					echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
					//echo '<div style="position: absolute;"><h5 class="bbg-label">Label</h5></div>';
					echo the_post_thumbnail( 'large-thumb' );

					echo '</div>';
					echo '</div> <!-- usa-grid-full -->';

					if ($featuredImageCutline != "") {
						echo '<div class="usa-grid">';
							echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
						echo '</div> <!-- usa-grid -->';
					}
				}
			?><!-- .bbg__article-header__thumbnail -->


				<section class="usa-section usa-grid">
					<h6 class="bbg-label"><a href="<?php echo get_permalink( get_page_by_path( 'broadcasters' ) ); ?>" title="A list of the BBG broadcasters.">Our broadcasters</a></h6>
					<div class="bbg-grid__container">

						<?php 

							echo $pageContent;

						?>
					</div><!-- .bbg-grid__container -->
				</section>

				<section id="teams" class="usa-section bbg-staff">
					<div class="usa-grid">

						<div class="usa-grid-full">

								<?php
									$entityParentPage = get_page_by_path('broadcasters');
									$qParams=array(
										'post_type' => array('page'),
										'posts_per_page' => -1,
										'post_parent' => $entityParentPage->ID
										
									);
									$custom_query = new WP_Query($qParams);
									if ($custom_query -> have_posts()) {
										while ( $custom_query -> have_posts() )  {
											$custom_query->the_post();
											$id=get_the_ID();
											$fullName=get_post_meta( $id, 'entity_full_name', true );
											if ($fullName != "") {
												$abbreviation=strtolower(get_post_meta( $id, 'entity_abbreviation', true ));
												$abbreviation=str_replace("/", "",$abbreviation);
												$description=get_post_meta( $id, 'entity_description', true );
												$link=get_permalink( get_page_by_path( "/broadcasters/$abbreviation/" ) );
												$imgSrc=get_template_directory_uri().'/img/logo_'.$abbreviation.'--circle-200.png'; //need to fix this

												echo '<article class="bbg__entity bbg-grid--1-1-1-2">';
												echo '<div class="bbg-avatar__container bbg__entity__icon">';
												echo '<a href="'.$link.'" tabindex="-1">';
												echo '<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url('.$imgSrc.');"></div>';
												echo '</a></div>';
												echo '<div class="bbg__entity__text">';
												echo '<h2 class="bbg__entity__name"><a href="'.$link.'">'.$fullName.'</a></h2>';
												echo '<p class="bbg__entity__text-description">'.$description.'</p>';
												echo '</div>';
												echo '</article>';
											}
											}
											
									}
									wp_reset_postdata();
								?>
						</div>
					</div>
				</section>


			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>



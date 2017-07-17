<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">

			<?php if ( have_posts() ) : ?>
				<div class="usa-grid">
					<header class="page-header">
						<?php
							the_archive_title( '<h5 class="bbg__label--mobile large">', '</h5>' );
						?>
					</header><!-- .page-header -->
				</div>

				<?php /* Start the Loop */
					$counter = 0;
					while ( have_posts() ) : the_post();
						$counter++;
						/*if( $counter == 1 ) {
							echo '<div class="usa-grid-full">';
							echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
						} elseif( $counter==3 ){
							echo '</div><!-- left column -->';
							echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
							echo '<header class="page-header">';
							echo '<h6 class="page-title bbg__label small">More news</h6>';
							echo '</header>';

							//These values are used for every excerpt >=4
							$includeImage = FALSE;
							$includeMeta = FALSE;
							$includeExcerpt=FALSE;
						}

						get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						*/

						//Add a check here to only show featured if it's not paginated.
						if ( (!is_paged() && $counter == 1 || is_category('BBG360')) ){
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						} else {
							if( (!is_paged() && $counter == 2) || (is_paged() && $counter == 1) ){
								echo '</div>';
								echo '<div class="usa-grid">';
									echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							} elseif( (!is_paged() && $counter == 4) || (is_paged() && $counter == 3)){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
									echo '<header class="page-header">';
									$moreLabel = "More News";

									if (is_category('Board Meetings')) {
										$moreLabel = "More Board Meetings";
									}
										echo '<h6 class="page-title bbg__label small">' . $moreLabel . '</h6>';
									echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt = FALSE;
							}
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						}

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
					?>

					<?php endwhile; ?>

					<?php the_posts_navigation(); ?>
				</div><!-- .usa-grid -->

				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full" style="margin-top: 3rem;">

			<?php if ( have_posts() ) : ?>


			<div class="usa-grid">
				<header class="page-header">
					<h6 class="bbg-label--mobile large">Blog</h6>
				</header><!-- .page-header -->
			</div>




				<?php if ( is_home() && ! is_front_page() ) : ?>
					<!--
					<header class="page-header">
						<h6 class="page-title screen-reader-text bbg-label large"><?php single_post_title(); ?></h6>
					</header>
					-->
				<?php endif; ?>


				<?php /* Start the Loop */
					$counter = 0;
				?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php

						$counter++;


						//Add a check here to only show featured if it's not paginated.
						if (  (!is_paged() && $counter==1) ){
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						} else {
							if( (!is_paged() && $counter == 2) || (is_paged() && $counter==1) ){
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							} elseif( (!is_paged() && $counter == 4) || (is_paged() && $counter==3)){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								echo '<h6 class="page-title bbg-label small">More news</h6>';
								echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt=FALSE;
							}
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						}
					?>
				<?php endwhile; ?>
					</div><!-- .bbg-grid right column -->


			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

			</div><!-- .usa-grid -->
			<div class="usa-grid">
				<?php the_posts_navigation(); ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * The template for displaying Author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="usa-grid-full">

			<?php if ( have_posts() ) : ?>

				<?php get_template_part( 'author-bio' ); ?>

				<?php /* Start the Loop */
					$counter = 0;
				?>
				<div>
				<section class="usa-section usa-grid">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$counter = $counter + 1;
						$gridClass = "";
						if ($counter < 2) {
							$gridClass = "bbg-grid--1-2-2";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						} elseif ($counter == 2){
							$gridClass = "bbg-grid--1-2-2";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
							echo '</section>';
							echo '<section class="usa-section usa-grid">';
						} else {
							$gridClass = "";
							$includeMeta = FALSE;
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						}

					?>
				<?php endwhile; ?>
					</section>
				</div>

			<?php else : ?>
				<?php get_template_part( 'author-bio' ); ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

				<?php the_posts_navigation(); ?>

			</div><!-- .usa-grid-full -->
		</div>
		<!-- #content -->
	</section><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
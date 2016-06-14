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
			<div class="usa-grid-full">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h6 class="bbg-label large">News + Information</h6>
					<?php
						//the_archive_title( '<h6 class="bbg-label large">', '</h6>' );
					?>
				</header><!-- .page-header -->


				<?php /* Start the Loop */ 
					$counter = 0;
				?>



				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$counter++;
						/*

							if( $counter == 1 ) {
								echo '<div class="usa-grid-full">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							} elseif( $counter==3 ){
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
							*/
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

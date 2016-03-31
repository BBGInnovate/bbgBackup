<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("bbg-search-results--list"); ?> >
	<header class="entry-header bbg-blog__excerpt-header">
		<?php the_title( sprintf( '<h3 class="entry-title bbg-search-results__title--list"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta bbg__excerpt-meta">
			<?php bbginnovate_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .bbg-blog__excerpt-header -->

	<div class="entry-summary bbg-search-results__excerpt-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer bbg-search-results__excerpt-categories">
		<?php search_excerpt_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
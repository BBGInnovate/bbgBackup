<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php bbginnovate_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php
			if (has_post_thumbnail()) {
				echo '<div class="single-post-thumbnail clear usa-single_post_thumbnail">';
				echo the_post_thumbnail('large-thumb');
				echo '</div>';
			}
		?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_excerpt();
			/*
			the_content( sprintf(
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'bbginnovate' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
			*/
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php bbginnovate_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

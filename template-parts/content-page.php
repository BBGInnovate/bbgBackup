<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>

	<?php echo bbginnovate_post_categories(); ?>
	<!-- .bbg-label -->

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<ul class="bbg-post-share-menu">
		<li class="bbg-post-share-menu-tool email"><a href="#"><span class="bbg-share-icon email"></span><span class="bbg-share-text ">Email</span></a></li>
		<li class="bbg-post-share-menu-tool facebook"><a href="#"><span class="bbg-share-icon facebook"></span><span class="bbg-share-text ">Share</span></a></li>
		<li class="bbg-post-share-menu-tool twitter"><a href="#"><span class="bbg-share-icon twitter"></span><span class="bbg-share-text ">Tweet</span></a></li>
	</ul>

	<div class="entry-content bbg__article-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer bbg-post-footer 1234">
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'bbginnovate' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

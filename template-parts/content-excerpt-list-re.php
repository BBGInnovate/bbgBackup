<?php
/**
 * Template part for displaying excerpts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

// DEFINE POST LINKS
$link = sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) );
$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', esc_url( get_permalink() ) );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<header>
		<h3><a href="<?php echo esc_url( get_permalink() );?>" rel="bookmark"><?php echo get_the_title(); ?></a></h3>
	</header>

	<?php if ( 'post' === get_post_type() ) : ?>
		<?php if ($includeMeta) { ?>
			<div class="entry-meta bbg__excerpt-meta">
				<?php bbginnovate_posted_on(); ?>
			</div>
		<?php } ?>
	<?php endif; ?>

	<div>
		<?php
			$post_content = wp_trim_words(get_the_content(), 18);
			echo $post_content;

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div>
</article>
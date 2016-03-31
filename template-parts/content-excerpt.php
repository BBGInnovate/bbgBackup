<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */


//You can set the grid and breakpoints
global $gridClass;
if (! isset ($gridClass)) {
	$gridClass="";
}
$classNames="bbg-blog__excerpt ".$gridClass;

//The image is included by default 
global $includeImage;
if (! isset ($includeImage)) {
	$includeImage=TRUE;
}

//The byline meta info is displayed by default 
global $includeMeta;
if (! isset ($includeMeta)) {
	$includeMeta=TRUE;
}

$link = sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) );
$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', esc_url( get_permalink() ) );
?>



<article id="post-<?php the_ID(); ?>" <?php post_class($classNames); ?> >
	<header class="entry-header bbg-blog__excerpt-header">

		<?php if ($includeImage) { ?>
		<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
			<?php
				echo $linkImage;

				/* Set a default thumbnail image in case one isn't set */
				$thumbnail = '<img src="' . get_template_directory_uri() . '/img/portfolio-project-default.png" alt="This is a default image." />';

				if (has_post_thumbnail()) {
					$thumbnail = the_post_thumbnail('medium-thumb');
				}
				echo $thumbnail;
			?>
			</a>
		</div>
		<?php } ?>


		<?php the_title( sprintf( '<h3 class="entry-title bbg-blog__excerpt-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>


		<?php if ($includeMeta) { ?>
		<div class="entry-meta bbg__excerpt-meta">
			<?php bbginnovate_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php } ?>

		<?php endif; ?>

	</header><!-- .bbg-blog__excerpt-header -->

	<div class="entry-content bbg-blog__excerpt-content">
		<?php
			the_excerpt();
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .bbg-blog__excerpt-content -->

</article><!-- #post-## -->

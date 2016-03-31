<?php
/**
 * Template part for displaying a featured excerpt. 
 * Large full width photo and large excerpt text.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

//The byline meta info is displayed by default 
global $includeMetaFeatured;
if (! isset ($includeMetaFeatured)) {
	$includeMetaFeatured=TRUE;
}

$postPermalink=esc_url( get_permalink() );
if (isset($_GET['category_id'])) {
	$postPermalink=add_query_arg('category_id', $_GET['category_id'], $postPermalink);
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class("bbg-blog__excerpt--featured usa-grid-full"); ?>>
	<header class="entry-header bbg-blog__excerpt-header--featured usa-grid-full">

		<?php 
			$link = sprintf( '<a href="%s" rel="bookmark">', $postPermalink );
			$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink );
			$linkH2 = '<h2 class="entry-title bbg-blog__excerpt-title--featured">'.$link;
			echo $linkImage;

			if (has_post_thumbnail()) {
				echo '<div class="single-post-thumbnail clear usa-single_post_thumbnail bbg__excerpt-header__thumbnail--large">';
				echo the_post_thumbnail('large-thumb');
				echo '</div>';
			}
		?>
		</a>
<div class="usa-grid">
		<?php the_title( sprintf( $linkH2, $postPermalink ), '</a></h2>' ); ?>


		<?php if ($includeMetaFeatured){ ?>
		<div class="entry-meta bbg__excerpt-meta bbg__excerpt-meta--featured">
			<?php bbginnovate_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php }?>
</div>

	</header><!-- .bbg-blog__excerpt-header--featured -->

	<div class="entry-content bbg-blog__excerpt-content--featured usa-grid">
		<h3 class="usa-font-lead">
			<?php echo get_the_excerpt(); ?>
		</h3>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
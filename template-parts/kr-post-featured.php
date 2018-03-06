<?php
/**
 * Template part for displaying a featured excerpt.
 * Large full width photo and large excerpt text.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

$postPermalink = esc_url( get_permalink() );

if (isset($_GET['category_id'])) {
	$postPermalink = add_query_arg('category_id', $_GET['category_id'], $postPermalink);
}

$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("usim-blog-group"); ?>>
	<?php
		$link = sprintf( '<a href="%s" rel="bookmark">', $postPermalink );
		$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink );
		$linkH2 = '<h2 class="entry-title bbg-blog__excerpt-title--featured">'.$link;

		// FEATURED VIDEO ? VIDEO STILL : IMAGE
		$hideFeaturedImage = FALSE;
		if ($videoUrl != "") {
			echo featured_video($videoUrl);
			$hideFeaturedImage = TRUE;
		} 
		elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
			$featuredImageClass = "";
			$featuredImageCutline= "";
			$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));

			if ($thumbnail_image && isset($thumbnail_image[0])) {
				$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
			}
			echo $linkImage;

			$post_thumb_div  = '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
			$post_thumb_div .= 	the_post_thumbnail( 'large-thumb' );
			$post_thumb_div .= 	'</a>';
			$post_thumb_div .= '</div>';
			echo $post_thumb_div;
		}
			echo '<div class="post-date">';
			bbginnovate_posted_on();
			echo '</div>';
	?>
	<header class=""><?php the_title( $linkH2, '</a></h2>' ); ?></header>
	<?php
		$trimmed_content = wp_trim_words(get_the_content(), 40);
		$contentTag  = '<p id="featured-post-excerpt">';
		$contentTag .= $trimmed_content;
		$contentTag .= '</p>';
		echo $contentTag;
	?>
</article>
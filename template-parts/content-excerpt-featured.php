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


/*** the only way you should ever have a future post status here is if a future event is featured on the homepage */
if (get_post_status() == 'future') {
	global $post;
	$my_post = clone $post;
	$my_post->post_status = 'published';
	$my_post->post_name = sanitize_title($my_post->post_name ? $my_post->post_name : $my_post->post_title, $my_post->ID);
	$postPermalink = get_permalink($my_post);
}

if (isset($_GET['category_id'])) {
	$postPermalink=add_query_arg('category_id', $_GET['category_id'], $postPermalink);
}

//Add featured video
$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class("bbg-blog__excerpt--featured usa-grid-full"); ?>>
	<header class="entry-header bbg-blog__excerpt-header--featured usa-grid-full">
		<?php
		$link = sprintf( '<a href="%s" rel="bookmark">', $postPermalink );
		$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink );
		$linkH2 = '<h2 class="entry-title bbg-blog__excerpt-title--featured kr-re-feat-post-title">'.$link;

		//If a featured video is set, include it.
		//ELSE if a featured image is set, include it.
		$hideFeaturedImage = FALSE;
		if ($videoUrl!="") {
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

			$post_thumb_div = '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
			$post_thumb_div .= 	the_post_thumbnail( 'large-thumb' );
			$post_thumb_div .= 	'</a>';
			$post_thumb_div .= '</div>';
			echo $post_thumb_div;
		}
		if ($includeMetaFeatured) {
			bbginnovate_posted_on();
		}
		the_title( $linkH2, '</a></h2>' );
		?>
	</header>
</article><!-- #post-## -->
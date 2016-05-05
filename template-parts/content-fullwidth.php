<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */


//Add featured video
$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>

	<?php
		//If a featured video is set, include it.
		//ELSE if a featured image is set, include it.
		$hideFeaturedImage = FALSE;
		if ($videoUrl!="") {
			echo featured_video($videoUrl);
			$hideFeaturedImage = TRUE;
		} elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
			echo '<div class="usa-grid-full">';
			$featuredImageClass = "";
			$featuredImageCutline="";
			$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
			if ($thumbnail_image && isset($thumbnail_image[0])) {
				$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
			}
			echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
			//echo '<div style="position: absolute;"><h5 class="bbg-label">Label</h5></div>';
			echo the_post_thumbnail( 'large-thumb' );

			echo '</div>';
			echo '</div> <!-- usa-grid-full -->';

			if ($featuredImageCutline != "") {
				echo '<div class="usa-grid">';
					echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
				echo '</div> <!-- usa-grid -->';
			}
		}
	?><!-- .bbg__article-header__thumbnail -->

	<div class="usa-grid">

	<?php /* echo bbginnovate_post_categories(); */ ?>
	<!-- .bbg-label -->
	<?php if($post->post_parent) {
		//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
		$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
		$parent_link = get_permalink($post->post_parent);
	?>
	<h5 class="entry-category bbg-label"><a href="<?php echo $parent_link; ?>" title="A list of the BBG broadcasters."><?php echo $parent->post_title; ?></a></h6>

	<?php } ?>


		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	</div>



	<div class="usa-grid">
		<?php the_content(); ?>
	</div><!-- .usa-grid -->



	<div class="usa-grid">
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
	</div><!-- .usa-grid -->
</article><!-- #post-## -->

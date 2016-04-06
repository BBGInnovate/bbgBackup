<?php
/**
 * The template for displaying standard single project posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bbgRedesign
 */
/* we go through the loop once and reset it in order to get some vars for our og tags */
if ( have_posts() ) {
	the_post();


	$metaAuthor = get_the_author();
	$metaAuthorTwitter = get_the_author_meta( 'twitterHandle' );
	$ogTitle = get_the_title();

	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];

	$socialImageID = get_post_meta( $post->ID, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}

	$ogDescription = get_the_excerpt(); //get_the_excerpt()
	rewind_posts();
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>
			<div class="usa-grid">
			<?php
				/* START CONTACT CARDS */
				$contactPostIDs = get_post_meta( $post->ID, 'contact_post_id',false );
				if (count($contactPostIDs) > 0) {
					$qParamsContactCard=array(
						'post__in' => $contactPostIDs
					);
					$custom_query = new WP_Query( $qParamsContactCard );
					if ( $custom_query->have_posts() ) :
						echo "<h1>Related contact data</h1>";
						while ( $custom_query->have_posts() ) : $custom_query->the_post();
							//now let's get the custom fields associated with our related contac tposts
							$email = get_post_meta( get_the_ID(), 'email',true );
							$fullname=get_post_meta( get_the_ID(), 'fullname',true );
							$phone=get_post_meta( get_the_ID(), 'phone',true );
							$bio=get_the_content();

							echo "email: $email<BR>";
							echo "fullname: $fullname<BR>";
							echo "phone: $phone<BR>";
							echo "bio: $bio<BR>";

						endwhile;
					endif;
					wp_reset_postdata();
					wp_reset_query();
				}
				/* END CONTACT CARDS */
			?>
			</div>

			<div class="bbg__article-footer usa-grid">
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( !in_category('Project') &&(comments_open() || get_comments_number())):
						comments_template();
					endif;
				?>
			</div>
		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

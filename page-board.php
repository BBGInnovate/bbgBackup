<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Board
 */

/***** BEGIN PROJECT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$pageContent="";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent=get_the_content();
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Board Member')
	,'post_status' => array('publish')
);
$custom_query = new WP_Query($qParams);

$b="";
while ( $custom_query->have_posts() )  {
	$custom_query->the_post();

	$active=get_post_meta( get_the_ID(), 'active', true );
	if ($active){
		$occupation=get_post_meta( get_the_ID(), 'occupation', true );
		$email=get_post_meta( get_the_ID(), 'email', true );
		$phone=get_post_meta( get_the_ID(), 'phone', true );
		$twitterProfileHandle=get_post_meta( get_the_ID(), 'twitter_handle', true );
		$profilePhotoID=get_post_meta( get_the_ID(), 'profile_photo', true );
		$profilePhoto = "";

		if ($profilePhotoID) {
			$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
			$profilePhoto = $profilePhoto[0];
		}
		$b.=  '<div class="bbg__board-member__profile">';
		$b.=  '<a href="' . get_the_permalink() . '">';
			$b.=  '<div class="bbg__board-member__photo-container">';
				$b.=  '<img src="' . $profilePhoto . '" class="bbg__board-member__photo" alt="Photo of BBG Governor '. get_the_title() .'"/>';
			$b.=  '</div>';
			$b.=  '<h3 class="bbg__board-member__name">' . get_the_title() . '</h3>';
			$b.= get_the_excerpt();
		$b.=  '</a>';
		$b.=  '</div><!-- .bbg__board-member__profile -->';

		
	}
}
$pageContent=str_replace("[board list]", $b, $pageContent);


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">
				<header class="page-header">
					<h6 class="bbg-label--mobile large">
						Board Members
					</h6>
				</header><!-- .page-header -->
			</div>
			<div class="usa-grid-full">
				<div class="usa-grid">
				<?php
					if ($pageContent != "") {
						echo $pageContent;
					}
				?>
				</div><!-- .usa-grid -->
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>



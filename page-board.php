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
$pageTitle="";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent=get_the_content();
		$pageTitle=get_the_title();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();

$boardPage=get_page_by_title('The Board');
$thePostID=$boardPage->ID;

$qParams=array(
	'post_type' => array('page')
	,'post_status' => array('publish')
	,'post_parent' => $thePostID
	,'orderby' => 'meta_value'
	,'meta_key' => 'last_name'
	,'order' => 'ASC'
	,'posts_per_page' => 100
);
$custom_query = new WP_Query($qParams);

//Default adds a space above header if there's no image set
$featuredImageClass = " bbg__article--no-featured-image";

$boardStr="";
$chairpersonStr="";
$secretaryStr="";
while ( $custom_query->have_posts() )  {
	$custom_query->the_post();

	$active=get_post_meta( get_the_ID(), 'active', true );
	if ( ($pageTitle=="The Board" && $active) || ($pageTitle=="Former Governors" && (!isset('active') || $active=="" || !$active ))  ) {
		$isChairperson=get_post_meta( get_the_ID(), 'chairperson', true );
		$isSecretary=get_post_meta( get_the_ID(), 'secretary_of_state', true );
		//$occupation=get_post_meta( get_the_ID(), 'occupation', true );
		$email=get_post_meta( get_the_ID(), 'email', true );
		$phone=get_post_meta( get_the_ID(), 'phone', true );
		$twitterProfileHandle=get_post_meta( get_the_ID(), 'twitter_handle', true );
		$profilePhotoID=get_post_meta( get_the_ID(), 'profile_photo', true );
		$profilePhoto = "";

		if ($profilePhotoID) {
			$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
			$profilePhoto = $profilePhoto[0];
		}

		$profileName = get_the_title();
		$occupation = "";
		if ($isChairperson) {
			$occupation =  '<span class="bbg__profile-excerpt__occupation">Chairman of the Board</span>';
		} else if ($isSecretary) {
			$occupation =  '<span class="bbg__profile-excerpt__occupation">Ex officio board member</span>';
		}


		$b =  '<div class="bbg__profile-excerpt bbg-grid--1-2-2">';
			$b.=  '<h3 class="bbg__profile-excerpt__name">';
				$b.=  '<a href="' . get_the_permalink() . '">' . $profileName . '</a>';
			$b.=  '</h3>';
			$b.=  '<a href="' . get_the_permalink() . '">';
				$b.=  '<div class="bbg__profile-excerpt__photo-container">';
					$b.=  '<img src="' . $profilePhoto . '" class="bbg__profile-excerpt__photo" alt="Photo of BBG Governor '. get_the_title() .'"/>';
				$b.=  '</div>';
			$b.=  '</a>';
			$b.= '<p>' . $occupation . get_the_excerpt() . '</p>';
		$b.=  '</div><!-- .bbg__profile-excerpt -->';

		if ($isChairperson) {
			$chairpersonStr=$b;
		} else if ($isSecretary) {
			$secretaryStr=$b;
		} else {
			$boardStr.=$b;
		}
	}
}
$boardStr = '<div class="usa-grid-full">' . $chairpersonStr . $boardStr . $secretaryStr . '</div>';
$pageContent = str_replace("[board list]", $boardStr, $pageContent);
wp_reset_postdata();

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>



			<?php
				$hideFeaturedImage = get_post_meta( get_the_ID(), "hide_featured_image", true );
				if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
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
			</article>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>



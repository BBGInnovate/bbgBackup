<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Senior Management
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


$boardPage=get_page_by_title('Senior Management');
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

$boardStr="";
$ceoStr="";
$granteeStr="";
while ( $custom_query->have_posts() )  {
	$custom_query->the_post();

	$active=get_post_meta( get_the_ID(), 'active', true );
	if ($active){
		$isCEO=get_post_meta( get_the_ID(), 'ceo', true );
		$isGrantee=get_post_meta( get_the_ID(), 'grantee_leadership', true );
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

		$profileName = get_the_title(); // . ', ' . $occupation;

		$b =  '<div class="bbg__profile-excerpt bbg-grid--1-2-2">';
			$b.=  '<h3 class="bbg__profile-excerpt__name">'; 
				$b.=  '<a href="' . get_the_permalink() . '">' . $profileName . '</a>';
			$b.=  '</h3>';
			$b.=  '<a href="' . get_the_permalink() . '">';
				$b.=  '<div class="bbg__profile-excerpt__photo-container">';
					$b.=  '<img src="' . $profilePhoto . '" class="bbg__profile-excerpt__photo" alt="Photo of '. $profileName .'"/>';
				$b.=  '</div>';
			$b.=  '</a>';
			$b.=  '<p class="bbg__profile-excerpt__text">';
				$b.=  '<span class="bbg__profile-excerpt__occupation">'. $occupation . '</span>';
				$b.=  get_the_excerpt();
			$b.=  '</p>';
		$b.=  '</div><!-- .bbg__profile-excerpt__profile -->';

		if ($isCEO) {
			$ceoStr=$b;
		} else if ($isGrantee) {
			$granteeStr.=$b;
		} else {
			$boardStr.=$b;
		}
	}
}
$boardStr = '<div class="usa-grid-full">' . $ceoStr . $boardStr . '</div><h1>Grantee Leadership</h1><div class="usa-grid-full">' . $granteeStr . '</div>';
$pageContent = str_replace("[management list]", $boardStr, $pageContent);


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">
				<header class="page-header">
					<h6 class="bbg-label--mobile large">
						Senior Management
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



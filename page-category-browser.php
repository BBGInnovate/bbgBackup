<?php
/**
 * The template for displaying the BBG Portfolio.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Category Browser
 */


$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}


$pageContent = "";
$pageTitle = "";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageTitle = get_the_title();
		//$pageContent = apply_filters('the_content', $pageContent);
   		//$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();


/***** BEGIN PROJECT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$paginationLabel=  get_post_meta( get_the_ID(), 'category_browser_pagination_label', true );
$category_browser_type = get_post_meta( get_the_ID(), 'category_browser_type', true );
$burkeYear =  get_post_meta( get_the_ID(), 'category_browser_burke_year', true);


// $hasIntroFeature = FALSE;
// if ( $videoUrl != "" ) {
// 	$hasIntroFeature = true;
// } elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
// 	$hasIntroFeature = true;
// }

$numPostsFirstPage=7;
// if ($hasIntroFeature) {
// 	$numPostsFirstPage = 6;
// }
$numPostsSubsequentPages=6;


$postsPerPage=$numPostsFirstPage;
$offset=0;
if ($currentPage > 1) {
	$postsPerPage=$numPostsSubsequentPages;
	$offset=$numPostsFirstPage + ($currentPage-2)*$numPostsSubsequentPages;
}

$hasTeamFilter=false;
$mobileAppsPostContent="";


if ($category_browser_type == "Page Children") {
	
	/*** USED FOR APPS LANDING PAGE ****/
	$qParams = array (
		'post_type' => array('page'),
		'posts_per_page' => -1,
		'post_parent' => get_the_ID(),
		'order' => 'DESC'
	);
	if ( $pageTitle == "Burke Awards Archive" ) {
		$qParams['post_status'] = array( 'publish','private','pending','draft' );
		$qParams['orderby'] = 'menu_order';
		$qParams['order'] = 'ASC';
	}

} else if ($category_browser_type == "Custom Post Type") { 
	/*** USED FOR AWARDS AND BURKE CANDIDATES ****/
	$categoryBrowsePostType=  get_post_meta( get_the_ID(), 'category_browser_post_type', true );

	/*** categoryBrowsePostType ***/ 
	$qParams=array(
		'post_type' => array($categoryBrowsePostType)
		,'posts_per_page' => $postsPerPage
		,'offset' => $offset
		,'order' => 'DESC'
	);
	if ($categoryBrowsePostType == 'burke_candidate') {
		// $qParams['meta_key'] = 'burke_award_info_0_burke_ceremony_year';
		// $qParams['orderby'] = 'meta_value';
		
		$qParams['meta_query'] = array(
		    'relation' => 'OR',
		    array('key' => 'burke_award_info_0_burke_ceremony_year','compare' => '=','value' => $burkeYear),
		    array('key' => 'burke_award_info_1_burke_ceremony_year','compare' => '=','value' => $burkeYear),
		    array('key' => 'burke_award_info_2_burke_ceremony_year','compare' => '=','value' => $burkeYear)
		);
	}
} else {
	$categoryToBrowse =  get_field( 'category_browser_category', get_the_ID(), true);
	$projectCatObj = get_category_by_slug($categoryToBrowse->slug); 
	
	$awardYear = get_query_var('awardyear','');
	$entity = get_query_var('entity','');

	$qParams=array(
		'post_type' => array('post')
		,'cat' => $projectCatObj->term_id
		,'posts_per_page' => $postsPerPage
		,'offset' => $offset
		,'post_status' => array('publish')

	);

	if ($awardYear != '' || $entity != '') {
		$meta_query = array (
			'relation' => 'AND'
		);

		if ($awardYear != '') {
			$meta_query[] = array( 
				'key' => 'standardpost_award_year',
				'value' => $awardYear,
				'compare' => '='
			); 
		} 
		if ($entity != '') {
		
			$meta_query[] = array( 
				'key' => 'standardpost_award_recipient',
				'value' => $entity,
				'compare' => '='
			); 

		}
		$qParams['meta_query'] = $meta_query;
	}
}


/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );

$totalPages=1;
if ($custom_query->found_posts > $numPostsFirstPage) {
	$totalPages = 1 + ceil( ($custom_query->found_posts - $numPostsFirstPage)/$numPostsSubsequentPages);
}

//query_posts($qParams);


/*** SHARING VARS ****/
/*
$teamCategoryID=$_GET["cat"];
$teamCategory=get_category($teamCategoryID);
$portfolioDescription=$teamCategory->description;
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">

			<?php if ( $custom_query->have_posts() ) : ?>

				<header class="page-header">
					<?php the_title( '<h5 class="bbg__label--mobile large">', '</h5>' ); ?>
					<?php echo $pageTagline; ?>
				</header><!-- .page-header -->
			</div>

			<?php
				// if ( $videoUrl != "" ) {
				// 	echo featured_video($videoUrl);
				// } elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
				// 	echo '<div class="usa-grid-full">';
				// 		$featuredImageClass = "";
				// 		$featuredImageCutline = "";
				// 		$thumbnail_image = get_posts( array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment') );
				// 		if ( $thumbnail_image && isset($thumbnail_image[0]) ) {
				// 			$featuredImageCutline = $thumbnail_image[0]->post_excerpt;
				// 		}

				// 		$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

				// 		echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner" style="background-image: url(' . $src[0] . '); background-position: ' . $bannerAdjustStr . '">';
				// 		echo '</div>';
				// 	echo '</div> <!-- usa-grid-full -->';
				// }
			?><!-- .bbg__article-header__thumbnail -->

			<div class="usa-grid-full">
				<?php
					$counter=0;
					while ( $custom_query->have_posts() )  {
						$custom_query->the_post();
						$counter=$counter+1;
						if ( $counter == 1 && $currentPage==1 ) { //  && !$hasIntroFeature
							$includeMetaFeatured = FALSE;
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
							echo '<div class="usa-grid">';
						} elseif ( $counter == 1 && $currentPage != 1 ) {
							echo '<div class="usa-grid">';
							$gridClass = "bbg-grid--1-2-3";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						} else {
							$gridClass = "bbg-grid--1-2-3";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						}
					}
					echo '</div><!-- .usa-grid -->';

					echo '<div class="usa-grid">';
					/*
					$args = array(
						'prev_text'          => __( 'Older projects' ),
						'next_text'          => __( 'Newer projects' ),
						'screen_reader_text' => __( 'Project navigation' )
					);*/

					echo '<nav class="navigation posts-navigation" role="navigation">';
					echo '<h2 class="screen-reader-text">Event navigation</h2>';
					echo '<div class="nav-links">';
					$nextLink=get_next_posts_link('Older ' . $paginationLabel, $totalPages);
					$prevLink=get_previous_posts_link('Newer ' . $paginationLabel);
					if ($nextLink != "") {
						echo '<div class="nav-previous">';
						echo $nextLink;
						echo '</div>';
					}

					if ($prevLink != "") {
						echo '<div class="nav-next">';
						echo $prevLink;
						echo '</div>';	
					}
					
					echo '</div>';
					echo '</nav>';
					echo '</div><!-- .usa-grid -->';

				?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>


			</div><!-- .usa-grid-full -->
			<?php 
				//we are not applying the_content filter, so shortcodes won't be processed and paragraph tags won't be added 
				echo $pageContent; 
			?>


		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>



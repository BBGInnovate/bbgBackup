<?php
/**
 * The template for displaying upcoming + previous events.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Events
 */

/***** BEGIN EVENT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$featuredEvent = get_field('homepage_featured_event', 'option');
$showFeaturedEvent = get_field('show_homepage_event', 'option');

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$postIDsUsed=array();

$numPostsFirstPage=10;//12
$numPostsSubsequentPages=9;//11


$postsPerPage=$numPostsFirstPage;
$offset=0;
if ($currentPage > 1) {
	$postsPerPage=$numPostsSubsequentPages;
	$offset=$numPostsFirstPage + ($currentPage-2)*$numPostsSubsequentPages;
}

$hasTeamFilter=false;


/**** QUERY TO GET FIRST POST - EITHER FEATURED OR FIRST REVERSE CHRON ***/
if ($showFeaturedEvent && $featuredEvent && has_category('event',$featuredEvent)) {
	$qParamsFirst=array(
		'p' => $featuredEvent->ID
		,'post_status' => array('publish','future')
	);
} else {
	$qParamsFirst=array(
		'post_type' => array('post')
		,'cat' => get_cat_id('Event')
		,'posts_per_page' => 1
		,'post_status' => array('publish')
	);
}
 
$featured_event_query = new WP_Query( $qParamsFirst );
while ( $featured_event_query->have_posts() ) {
	$featured_event_query->the_post(); 
	$postIDsUsed[] = get_the_ID();
}

/**** QUERY PAST EVENTS FOR MAIN PAGE LOOP ***/
$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Event')
	,'posts_per_page' => $postsPerPage
	,'offset' => $offset
	,'post_status' => array('publish')
	,'post__not_in' => $postIDsUsed
);
$past_events_query_args= $qParams;
$past_events_query = new WP_Query( $past_events_query_args );

$totalPages=1;
if ($past_events_query->found_posts > $numPostsFirstPage) {
	$totalPages = 1 + ceil( ($past_events_query->found_posts - $numPostsFirstPage)/$numPostsSubsequentPages);
}
/**** END QUERY PAST EVENTS FOR MAIN PAGE LOOP ***/

/**** QUERY FUTURE EVENTS + BUILD STRING  ***/
$qParamsUpcoming = array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Event')
	,'posts_per_page' => $postsPerPage
	,'offset' => $offset
	,'post_status' => array('future')
	,'order' => 'ASC'
	,'post__not_in' => $postIDsUsed
);
$future_events_query_args = $qParamsUpcoming;
$future_events_query = new WP_Query( $future_events_query_args );

$upcomingEvents = "";
if (!is_paged()) {
	
	if ($future_events_query->have_posts()) {
		$upcomingEvents .= '<div class="bbg__infobox">';
		$upcomingEvents .= '<header class="page-header">';
		$upcomingEvents .= '<h6 class="page-title bbg__label">Upcoming events</h6>';
		$upcomingEvents .= '</header>';
		while ( $future_events_query->have_posts() ) {
			$future_events_query->the_post(); 
			//we're not using get_template_part because of how future permalinks work 
			$upcomingEvents .= '<article id="post-' .get_the_ID() . '" class="' . implode(" ", get_post_class()) . '">';
			global $post;
			$my_post = clone $post;
			$my_post->post_status = 'published';
			$my_post->post_name = sanitize_title($my_post->post_name ? $my_post->post_name : $my_post->post_title, $my_post->ID);
			$permalink = get_permalink($my_post);
			$upcomingEvents .= "<a href='$permalink'>" . get_the_title() . "</a>";
			$upcomingEvents .= '</article>';
		}
		$upcomingEvents .= '</div>';
	}
}
wp_reset_query();
wp_reset_postdata();
/**** END  FUTURE EVENTS  ***/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<!-- BEGIN HEADER RED LABEL -->
			<div class="usa-grid">
				<header class="page-header">
					<h6 class="bbg__label--mobile large">
						<?php if ($hasTeamFilter) {
							echo "" . $teamCategory->cat_name. " events";
						} else {
							echo 'Events';
						}
						?>
					</h6>
				</header><!-- .page-header -->
			</div>
			<!-- END HEADER -->

			<!-- BEGIN FEATURED/FIRST EVENT -->
			<div class="usa-grid-full">
				<?php 
					if ( !is_paged() ) {
						while ( $featured_event_query->have_posts() ) {
							$featured_event_query->the_post(); 
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						}
					}
				?>
			</div><!-- .usa-grid-full -->
			<!-- END FEATURED/FIRST EVENT -->


			<!-- BEGIN PAST EVENTS -->
			<div class="usa-grid">
			<div class="bbg-grid--1-1-1-2 secondary-stories">

			<?php 
			echo '<div class="u--hide-medium-large">' . $upcomingEvents . '</div>';
				$counter = 0;
				while ( $past_events_query->have_posts() ) {
					$past_events_query->the_post(); 
					$includeDate = TRUE;
					$includeMeta = FALSE;
					$counter++;

					if( (!is_paged() && $counter == 3) || (is_paged() && $counter==2)){
						echo '</div><!-- left column -->';
						echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';

						echo '<div class="u--show-medium-large">' . $upcomingEvents . '</div>';

						echo '<header class="page-header">';
						echo '<h6 class="page-title bbg__label small">Past events</h6>';
						echo '</header>';

						//These values are used for every excerpt >=4
						$includeImage = FALSE;
						//$includeMeta = FALSE;
						$includeExcerpt = FALSE;
						//$includeDate = TRUE;
					}
					get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
				}
			?>



			</div><!-- right column -->
			</div><!-- .usa-grid-full -->
			<!-- END PAST EVENTS -->

			<!-- BEGIN NAVIGATION  -->
			<div class="usa-grid">
			<?php 
				echo '<nav class="navigation posts-navigation" role="navigation">';
				echo '<h2 class="screen-reader-text">Event navigation</h2>';
				echo '<div class="nav-links">';
				$nextLink=get_next_posts_link('Older Events', $totalPages);
				$prevLink=get_previous_posts_link('Newer Events');
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
			?>
			</div>
			<!-- END NAVIGATION  -->
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

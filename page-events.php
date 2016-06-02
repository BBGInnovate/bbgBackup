<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Events
 */

/***** BEGIN PROJECT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage=12;
$numPostsSubsequentPages=11;

$postsPerPage=$numPostsFirstPage;
$offset=0;
if ($currentPage > 1) {
	$postsPerPage=$numPostsSubsequentPages;
	$offset=$numPostsFirstPage + ($currentPage-2)*$numPostsSubsequentPages;
}

$hasTeamFilter=false;
$mobileAppsPostContent="";

$qParams=array(
	'post_type' => array('post')
	,'cat' => get_cat_id('Event')
	,'posts_per_page' => $postsPerPage
	,'offset' => $offset
	,'post_status' => array('publish')
);

/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$past_events_query_args= $qParams;
$past_events_query = new WP_Query( $past_events_query_args );

$totalPages=1;
if ($past_events_query->found_posts > $numPostsFirstPage) {
	$totalPages = 1 + ceil( ($past_events_query->found_posts - $numPostsFirstPage)/$numPostsSubsequentPages);
}

$portfolioDescription="some portfolio description could go here based on the category description?";

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">

			<?php if ( $past_events_query->have_posts() ) : ?>

				<header class="page-header">
					<h6 class="bbg-label--mobile large">
						<?php if ($hasTeamFilter) {
							echo "" . $teamCategory->cat_name. " events";
						} else {
							echo 'Events';
						}

						?>
					</h6>
				</header><!-- .page-header -->
			</div>
			
			<div class="usa-grid-full">

				<?php /* Start the Loop */ 
					$counter = 0;
				?>



				<?php /* Start the Loop */ ?>
				<?php while ( $past_events_query->have_posts() ) : $past_events_query->the_post(); ?>

					<?php
						$counter++;
													//Add a check here to only show featured if it's not paginated.
						if (  (!is_paged() && $counter==1) ){
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
						} else {
							if( (!is_paged() && $counter == 2) || (is_paged() && $counter==1) ){
								echo '</div>';
								echo '<div class="usa-grid">';
								echo '<div class="bbg-grid--1-1-1-2 secondary-stories">';
							} elseif( (!is_paged() && $counter == 4) || (is_paged() && $counter==3)){
								echo '</div><!-- left column -->';
								echo '<div class="bbg-grid--1-1-1-2 tertiary-stories">';
								echo '<header class="page-header">';
								echo '<h6 class="page-title bbg-label small">More events</h6>';
								echo '</header>';

								//These values are used for every excerpt >=4
								$includeImage = FALSE;
								$includeMeta = FALSE;
								$includeExcerpt=FALSE;
							}
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						}
					?>

				<?php endwhile; ?>

				</div><!-- .usa-grid -->
				
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



			<?php endif; ?>
			</div><!-- .usa-grid-full -->
			<div class="usa-grid-full">
			<?php
				if (!is_paged()) {
					echo '<section style="margin-top:20px;" class="usa-section bbg-portfolio">';
					echo '<header class="page-header">';
					echo '<h6 class="page-title bbg-label small">Upcoming events</h6>';
					echo '</header>';

					$qParamsUpcoming = array(
						'post_type' => array('post')
						,'cat' => get_cat_id('Event')
						,'posts_per_page' => $postsPerPage
						,'offset' => $offset
						,'post_status' => array('future')
						,'order' => 'ASC'
					);
					$future_events_query_args = $qParamsUpcoming;
					$future_events_query = new WP_Query( $future_events_query_args );
					while ( $future_events_query->have_posts() ) {
						$future_events_query->the_post(); 
						$counter++;
						//These values are used for every excerpt >=4
						$includeImage = FALSE;
						$includeMeta = FALSE;
						$includeExcerpt = FALSE;
						//get_template_part( 'template-parts/content-excerpt-list', get_post_format() );

						//I'm not using get_template_part because of how future permalinks, but we could make that work if we needed

						echo '<article id="post-' .get_the_ID() . '" ' . get_post_class($classNames) . '>';
						global $post;
						$my_post = clone $post;
						$my_post->post_status = 'published';
						$my_post->post_name = sanitize_title($my_post->post_name ? $my_post->post_name : $my_post->post_title, $my_post->ID);
						$permalink = get_permalink($my_post);
						echo "<a href='$permalink'>" . get_the_title() . "</a>";
						echo '</article>';




					}
					echo '</section>';
				}
			?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php
/**
 * The template for displaying Author archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 */

// Set author occupation
$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
$theAuthorID = $curauth -> ID;
$m = get_user_meta( $theAuthorID );
$occupation = "";
if ( isset( $m['occupation'] ) ) {
	$occupation = $m['occupation'][0];
}
$twitterHandle = "";
if ( isset( $m['twitterHandle'] ) ) {
	$twitterHandle = $m['twitterHandle'][0];
}

$isCEO = false;
if ( stristr($occupation, "ceo") ) {
	$isCEO = true;
}

$showPodcasts = false;
if ( isset($_GET['showPodcasts'] )) {
	$showPodcasts = true;
}
$tweets = [];
$profilePageID = "";
$latestTweetsStr = "";
$featuredPostID = 0;
if ( isset( $m['author_profile_page'] ) ) {
	$profilePageID =  $m['author_profile_page'][0];

	$tweets = get_field( 'profile_related_author_page_tweets', $profilePageID, true);
	$featuredPostID = get_field( 'profile_related_author_page_featured_post', $profilePageID, true);
	if ( count( $tweets )) {
		$randKey = array_rand( $tweets );
		$latestTweetsStr = $tweets[$randKey]['profile_related_author_page_tweet'];
		/* THE HTML OF A TWEET SHOULD LOOK LIKE THIS
		$latestTweetsStr = '<blockquote class="twitter-tweet" data-theme="light"><p lang="en" dir="ltr">Our Impact Model measures 40+ indicators beyond audience size to hold our activities accountable. <a href="https://twitter.com/hashtag/BBGannualReport?src=hash">#BBGannualReport</a> <a href="https://t.co/r8geNg47OP">https://t.co/r8geNg47OP</a> <a href="https://t.co/e6T3Zea443">pic.twitter.com/e6T3Zea443</a></p>&mdash; BBG (@BBGgov) <a href="https://twitter.com/BBGgov/status/881886454485528576">July 3, 2017</a></blockquote> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
	*/
	}
}



get_header(); ?>

<div id="content" class="site-content" role="main">
	<section class="usa-section usa-grid">
	<?php
		if ( have_posts() ) :
			/* BEGIN AUTHOR BIO - LITTLE HEADER  AT TOP WITH THUMBNAIL */
			echo '<div class="usa-grid-full">';
				get_template_part( 'author-bio' );
			echo '</div>';
			/* END AUTHOR BIO - LITTLE HEADER  AT TOP WITH THUMBNAIL */
		if ($isCEO) {
			
			$postIDsUsed = [];
			
			
			/**** BEGIN FETCH FEATURED POSTID ****/
			
			if ( $featuredPostID == 0 ) {	//only fetch a featured post ID if one is not selected.
				$qParams = array(
					'post_type' => array( 'post' ),
					'posts_per_page' => 1,
					'orderby' => 'post_date',
					'order' => 'desc',
					'post__not_in' => $postIDsUsed,
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field' => 'slug',
							'terms' => array( 'from-the-ceo'),
							'operator' => 'AND'
						)
					)
				);
				query_posts( $qParams );
				if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						$featuredPostID = get_the_ID();
					endwhile;
				}
				wp_reset_query();
			}
			$postIDsUsed []= $featuredPostID;
			/**** END FETCH FEATURED POSTID ****/

			/**** BEGIN FETCH FEATURED SECOND AND THIRD POSTIDS ****/
			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 6,
				'orderby' => 'post_date',
				'order' => 'desc',
				'post__not_in' => $postIDsUsed,
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => array( 'from-the-ceo','blog'),
						'operator' => 'AND'
					)
				)
			);

			$secondPostID = 0;
			$morePostIDs = [];
			$counter = 0;
			query_posts( $qParams );

			if ( have_posts() ) {
				while ( have_posts() ) : the_post();
					$counter++;
					if ( $counter == 1 ) {
						$secondPostID = get_the_ID();
					} else if ($counter == 2) {
						$thirdPostID = get_the_ID();
					}
					$postIDsUsed []= get_the_ID();
				endwhile;
			}
			wp_reset_query();
			/**** END FETCH FEATURED SECOND AND THIRD POSTIDS ****/

			$ceoLink = '/category/from-the-ceo';

			/* BEGIN FIRST ROW */
			echo '<div class="usa-grid-full">';
				echo '<h6 class="bbg__label"><a href="' . $ceoLink . '">From the CEO</a></h6>';
				/* BEGIN FIRST POST - ONLY COLUMN OF FIRST ROW */
				query_posts( array( 'post__in' => array( $featuredPostID ) ));
				if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
					endwhile;
				}
				wp_reset_query();
				/* END FIRST POST - ONLY COLUMN OF FIRST ROW */
			echo '</div>';
			/* END FIRST ROW */

			// set link to CEO blog
			$blogLink = '/category/from-the-ceo+blog/';

			/* BEGIN SECOND ROW */
			echo '<section class="usa-section">';
				echo '<div class="usa-grid">';
					echo '<div class="usa-width-two-thirds">';
						echo '<h6 class="bbg__label"><a href="' . $blogLink . '">Blog</a></h6>';
						echo '<div class="usa-grid-full">';
							/* BEGIN FIRST BLOG POST -  COLUMN 1 OF SECOND ROW */
							echo '<article class="bbg-portfolio__excerpt usa-width-one-half">';
								query_posts( array( 'post__in' => array ( $secondPostID ) ));
								if ( have_posts() ) {
									while ( have_posts() ) : the_post();
										$gridClass = "bbg-grid--1-1-1";
										get_template_part( 'template-parts/content-portfolio', get_post_format() );
									endwhile;
								}
								wp_reset_query();
							echo '</article>';
							/* END FIRST BLOG POST - COLUMN 1 OF SECOND ROW */

							/* BEGIN SECOND BLOG POST -  COLUMN 2 OF SECOND ROW */
							echo '<article class="bbg-portfolio__excerpt usa-width-one-half">';
								// echo '<h6>&nbsp;</h6>';
								query_posts( array( 'post__in' => array ( $thirdPostID ) ));
								if ( have_posts() ) {
									while ( have_posts() ) : the_post();
										$gridClass = "bbg-grid--1-1-1";
										get_template_part( 'template-parts/content-portfolio', get_post_format() );
									endwhile;
								}
								wp_reset_query();
								///echo '<div align="right"><a href="' . $blogLink . '" class="bbg__kits__intro__more--link">Read more blog posts »</a></div>';
							echo '</article>';
							/* END SECOND BLOG POST - COLUMN 2 OF SECOND ROW */
						echo '</div>';
						
						echo '<div class="usa-grid-full u--space-below-mobile--large" style="text-align:right;">';
							//echo '<a href="' . $blogLink . '" class="bbg__kits__intro__more--link">Read more posts from John\'s blog about his work in support of BBG\'s mission »</a>';
						echo '<a href="' . $blogLink . '" class="bbg__kits__intro__more--link">More blog posts »</a>'; //<strong>Media Diplomacy</strong>
						echo '</div>';

					echo '</div>';
					/* BEGIN TWEET - COLUMN 3 OF SECOND ROW */
					echo '<div class="usa-width-one-third">';
						echo '<h6 class="bbg__label"><a target="_blank" href="https://twitter.com/' . $twitterHandle . '">Featured Tweet</a></h6>';
						echo '<div class="bbg__quotation" style="margin-top:0; padding:0;">';
							echo $latestTweetsStr;
						echo '</div>';
					echo '</div>';
					/* END TWEET - COLUMN 3 OF SECOND ROW */
				echo '</div>';
			echo '</section>';
			/* END SECOND ROW */
		?>
	</section>

	<?php
		/* TRANSCRIPTS */
		$remarksLink = '/ceo-speeches-and-remarks/ ';
		$ceoImage = '/wp-content/media/2017/07/lansingspeaks.jpg';

	?>
	
	<style>
		#lansingPhoto {
			background-position: center top;
		}
		@media screen and (min-width: 900px) {
		  #lansingPhoto {
			background-position: center top;
		  }
		}
	</style>


	
	<div class="usa-section usa-grid bbg__kits__section" id="page-sections">
	    <section class="usa-grid-full bbg__kits__section--row bbg__ribbon--thin">
	        <div class="usa-grid">
	            <div class="bbg__announcement__flexbox">
	                <div id="lansingPhoto" class="bbg__announcement__photo" style="background-image: url(/wp-content/media/2017/07/lansingspeaks.jpg);"></div>
	                <div>
	                    <h6 class="bbg__label">On the record</h6>
	                    <h2 class="bbg__announcement__headline selectionShareable"><a href="/ceo-speeches-and-remarks">Speeches and Remarks</a></h2>
	                    <p>View transcripts of CEO Lansing’s remarks and statements at each of his appearances since he joined the BBG in September 2015. <a href="<?php echo $remarksLink; ?>" class="bbg__kits__intro__more--link">View All »</a></p>
	                </div>
	            </div>
	            <!-- .bbg__announcement__flexbox -->
	        </div>
	        <!-- .usa-grid -->
	    </section>
	</div>


	<?php
		
			// OPEN THE CONTAINER FOR THE OFIRST ROW AFTER RIBBON 
			echo '<div class="usa-grid">';

			// BEGIN FIRST COLUMN OF FIRST ROW AFTER RIBBON - STATEMENTS 
			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 1,
				'orderby' => 'post_date',
				'order' => 'desc',
				'post__not_in' => $postIDsUsed,
				'tax_query' => array(
					array( 
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => array('statement', 'from-the-ceo'),
						'operator' => 'AND'
					)
				)
			);


			$containerClass = 'usa-width-one-half';
			if ($showPodcasts) {
				$containerClass = 'usa-width-one-third';
			}

			$statementsLink = "/category/from-the-ceo+statement/";
			echo '<div class="' . $containerClass . '">';
				echo "<h6 class='bbg__label'><a target='_blank' href='" . $statementsLink . "'>Statements</a></h6>";

				query_posts( $qParams );
				if (have_posts()) {
					while ( have_posts() ) : the_post();
						$postIDsUsed []= get_the_ID();
						get_template_part( 'template-parts/content-portfolio', get_post_format() );
					endwhile;
				}
				wp_reset_query();
			echo '<div align="right"><a href="' . $statementsLink . '" class="bbg__kits__intro__more--link">More statements »</a></div>';
			echo '</div>';
			// END FIRST COLUMN OF FIRST ROW AFTER RIBBON - STATEMENTS 

			// BEGIN SECOND COLUMN OF FIRST ROW AFTER RIBBON - OP-EDS 
			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 1,
				'orderby' => 'post_date',
				'order' => 'desc',
				'post__not_in' => $postIDsUsed,
				'tax_query' => array(
					array( 
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => array('op-ed', 'from-the-ceo'),
						'operator' => 'AND'
					)
				)
			);

			$opEdLink = "/category/from-the-ceo+op-ed/";
			echo '<div class="' . $containerClass . '">';
				echo "<h6 class='bbg__label'><a target='_blank' href='" . $opEdLink . "'>Op-Eds</a></h6>";
				query_posts( $qParams );
				if (have_posts()) {
					while ( have_posts() ) : the_post();
						$postIDsUsed []= get_the_ID();
						get_template_part( 'template-parts/content-portfolio', get_post_format() );
					endwhile;
				}
				wp_reset_query();
			echo '<div align="right"><a href="' . $opEdLink . '" class="bbg__kits__intro__more--link">More op-eds »</a></div>';
			echo '</div>';
			// END SECOND COLUMN OF FIRST ROW AFTER RIBBON - STATEMENTS

			// BEGIN THIRD COLUMN OF FIRST ROW AFTER RIBBON - PODCASTS
			if ( $showPodcasts ) {

				$qParams = array(
					'post_type' => array( 'post' ),
					'posts_per_page' => 1,
					'orderby' => 'post_date',
					'order' => 'desc',
					'post__not_in' => $postIDsUsed,
					'tax_query' => array(
						array( 
							'taxonomy' => 'category',
							'field' => 'slug',
							'terms' => array('podcasts', 'from-the-ceo'),
							'operator' => 'AND'
						)
					)
				);

				$podcastsLink = "/category/from-the-ceo+podcasts/";
				echo '<div class="' . $containerClass . '">';
					echo "<h6 class='bbg__label'><a target='_blank' href='" . $opEdLink . "'>Podcasts</a></h6>";
					query_posts( $qParams );
					if (have_posts()) {
						$counter = 0;
						while ( have_posts() ) : the_post();
							$postIDsUsed []= get_the_ID();
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						endwhile;
					}
					wp_reset_query();
				echo '<div align="right"><a href="' . $podcastsLink . '" class="bbg__kits__intro__more--link">More podcasts »</a></div>';
				echo '</div>';
			}
			// END THIRD COLUMN OF FIRST ROW AFTER RIBBON - PODCASTS






			echo '</div>';
		} else {
			// BEGIN REGULAR NON-CEO AUTHOR PAGE
			while ( have_posts() ) : the_post();
				$counter = $counter + 1;
				$gridClass = "";
				if ($counter < 2) {
					$gridClass = "bbg-grid--1-2-2";
					get_template_part( 'template-parts/content-portfolio', get_post_format() );
				} elseif ($counter == 2){
					$gridClass = "bbg-grid--1-2-2";
					get_template_part( 'template-parts/content-portfolio', get_post_format() );
					echo '</section>';
					echo '<section class="usa-section usa-grid">';
				} else {
					$gridClass = "";
					$includeMeta = FALSE;
					get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
				}
			endwhile;
			// END REGULAR NON-CEO AUTHOR PAGE
		}
	endif;	//if ( have_posts() ) :
	?>

</div>
<?php get_footer();

	

 ?>
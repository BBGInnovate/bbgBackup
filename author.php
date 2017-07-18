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
if (stristr($occupation, "ceo")) {
	$isCEO = true;
}
$latestTweetsStr = '<a data-chrome="noheader nofooter noborders transparent noscrollbar" data-tweet-limit="1" class="twitter-timeline" href="https://twitter.com/'.$twitterHandle.'" data-screen-name="'.$twitterHandle.'" >Tweets by @'.$twitterHandle.'</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
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

			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 6,
				'orderby' => 'post_date',
				'order' => 'desc',
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => array( 'from-the-ceo','blog'),
						'operator' => 'AND'
					)
				)
			);

			$featuredPostID = 0;
			$secondPostID = 0;
			$morePostIDs = [];
			$counter = 0;
			query_posts( $qParams );
			
			if ( have_posts() ) {
				while ( have_posts() ) : the_post();
					$counter++;
					if ( $counter == 1 ) {
						$featuredPostID = get_the_ID();
					} else if ($counter == 2) {
						$secondPostID = get_the_ID();
					} else if ($counter == 3) {
						$thirdPostID = get_the_ID();
					}
				endwhile;
			}
			wp_reset_query();

			/* BEGIN FIRST ROW */
			echo '<div class="usa-grid-full">';

				echo '<h6 class="bbg__label"><a href="/category/from-the-ceo+blog/">Blog</a></h6>';
				
				/* BEGIN FIRST POST - ONLY COLUMN OF FIRST ROW */
				query_posts( array( 'post__in' => array( $featuredPostID ) ) );
				if (have_posts()) {
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
					endwhile;
				}
				wp_reset_query();
				/* END FIRST POST - ONLY COLUMN OF FIRST ROW */
			echo '</div>';
			/* END FIRST ROW */
			
			/* BEGIN SECOND ROW */
			echo '<div class="usa-grid">';
				/* BEGIN SECOND POST -  COLUMN 1 OF SECOND ROW */
				echo "<div class='usa-width-one-third'>";
					query_posts( array( 'post__in' => array ( $secondPostID) ) );
					if (have_posts()) {
						while ( have_posts() ) : the_post();
							$includeImage = true;
							$includeMeta=false;
							$includeExcerpt=true;
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						endwhile;
					}
					wp_reset_query();
				echo "</div>";
				/* END SECOND POST - COLUMN 1 OF SECOND ROW */

				/* BEGIN SECOND POST -  COLUMN 2 OF SECOND ROW */
				echo "<div class='usa-width-one-third'>";
					query_posts( array( 'post__in' => array ( $thirdPostID) ) );
					if (have_posts()) {
						while ( have_posts() ) : the_post();
							$includeImage = true;
							$includeMeta=false;
							$includeExcerpt=true;
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						endwhile;
					}
					wp_reset_query();
				echo "</div>";
				/* END SECOND POST - COLUMN 1 OF SECOND ROW */

				/* BEGIN TWEET - COLUMN 2 OF SECOND ROW */
				echo "<div class='usa-width-one-third'>";
				echo "<h6 class='bbg__label'><a target='_blank' href='https://twitter.com/$twitterHandle'>On Twitter</a></h6>";
				echo "<div class='bbg__quotation' style='margin-top:0rem; padding:1rem;'>";
				echo $latestTweetsStr;
				echo "</div>";
				echo "</div>";
				/* END TWEET - COLUMN 2 OF SECOND ROW */
			echo '</div>';
			/* END SECOND ROW */

			/* TRANSCRIPTS */
			?>
			</section>
			<section class="bbg__kits__section">
				<section class="usa-grid-full bbg__kits__section--row bbg__ribbon--thin">
				    <div class="usa-grid">
				        <div class="bbg__announcement__flexbox">
				            <div class="bbg__announcement__photo" style="background-image: url(https://bbgredesign.voanews.com/wp-content/media/2017/07/lansingspeaks.jpg);"></div>
				            <div>
				                <!-- <h6 class="bbg__label">Transcripts</h6> -->
				                <BR>
				                <h2 class="bbg__announcement__headline selectionShareable"><a href="#">Statements and Remarks</a></h2>
				                <p>View transcripts of CEO Lansing's remarks and statements at each of his appearances since he joined the BBG in September 2015.
									<a href="https://bbgredesign.voanews.com/statements-and-remarks/" class="bbg__kits__intro__more--link">Read more »</a>
				                </p>
				            </div>
				        </div><!-- .bbg__announcement__flexbox -->
				    </div><!-- .usa-grid -->
				</section>
			</section>
	<?php 
		}//if ($isCEO) {
			echo '<div class="usa-grid">';
			
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => array('john-lansing'),
					'operator' => 'IN'
				),
				array(
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => array('statement'),
					'operator' => 'IN'
				)
			);

			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 5,
				'orderby' => 'post_date',
				'order' => 'desc',
				'tax_query' => $tax_query
			);

			
			echo "<div class='usa-width-one-half'>";
				echo "<h6 class='bbg__label'><a target='_blank' href='/category/from-the-ceo+statements/'>Statements</a></h6>";
				query_posts( $qParams );
				if (have_posts()) {
					$counter = 0;
					while ( have_posts() ) : the_post();
						$counter++;
						if ( $counter == 1 ) {
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						} else {
							$includeImage = false;
							$includeMeta=false;
							$includeExcerpt=false;
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );	
						}
					endwhile;
				}
				wp_reset_query();
			echo '</div>';






			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => array('john-lansing'),
					'operator' => 'IN'
				),
				array(
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => array('op-ed'),
					'operator' => 'IN'
				)
			);

			$qParams = array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 5,
				'orderby' => 'post_date',
				'order' => 'desc',
				'tax_query' => $tax_query
			);

			
			echo "<div class='usa-width-one-half'>";
				echo "<h6 class='bbg__label'><a target='_blank' href='/category/from-the-ceo+op-ed/'>Op-Eds</a></h6>";
				query_posts( $qParams );
				if (have_posts()) {
					$counter = 0;
					while ( have_posts() ) : the_post();
						$counter++;
						if ( $counter == 1 ) {
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						} else {
							$includeImage = false;
							$includeMeta=false;
							$includeExcerpt=false;
							get_template_part( 'template-parts/content-excerpt-list', get_post_format() );	
						}
					endwhile;
				}
				wp_reset_query();
			echo '</div>';
		




			echo '</div>';



	endif;	//if ( have_posts() ) :
	?>
	
</div>
<?php get_footer();

						// else {
						// 	while ( have_posts() ) : the_post();
						// 		$counter = $counter + 1;
						// 		$gridClass = "";
						// 		if ($counter < 2) {
						// 			$gridClass = "bbg-grid--1-2-2";
						// 			get_template_part( 'template-parts/content-portfolio', get_post_format() );
						// 		} elseif ($counter == 2){
						// 			$gridClass = "bbg-grid--1-2-2";
						// 			get_template_part( 'template-parts/content-portfolio', get_post_format() );
						// 			echo '</section>';
						// 			echo '<section class="usa-section usa-grid">';
						// 		} else {
						// 			$gridClass = "";
						// 			$includeMeta = FALSE;
						// 			get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
						// 		}
						// 	endwhile;
						// }

 ?>
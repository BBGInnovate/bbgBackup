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
$latestTweetsStr = '<blockquote class="twitter-tweet" data-theme="light"><p lang="en" dir="ltr">Our Impact Model measures 40+ indicators beyond audience size to hold our activities accountable. <a href="https://twitter.com/hashtag/BBGannualReport?src=hash">#BBGannualReport</a> <a href="https://t.co/r8geNg47OP">https://t.co/r8geNg47OP</a> <a href="https://t.co/e6T3Zea443">pic.twitter.com/e6T3Zea443</a></p>&mdash; BBG (@BBGgov) <a href="https://twitter.com/BBGgov/status/881886454485528576">July 3, 2017</a></blockquote> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
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

			$blogLink = '/category/from-the-ceo+blog/';

			/* BEGIN SECOND ROW */
			echo '<section class="usa-section">';
				echo '<div class="usa-grid-full';
					echo '<div class="usa-width-two-thirds">';
						echo '<h6 class="bbg__label"><a href="' . $blogLink . '">Blog</a></h6>';
						/* BEGIN SECOND POST -  COLUMN 1 OF SECOND ROW */
						echo '<article class="bbg-portfolio__excerpt usa-width-one-half">';
							query_posts( array( 'post__in' => array ( $secondPostID) ) );
							if ( have_posts() ) {
								while ( have_posts() ) : the_post();
									$gridClass = "bbg-grid--1-1-1";
									get_template_part( 'template-parts/content-portfolio', get_post_format() );
								endwhile;
							}
							wp_reset_query();
						echo '</article>';
						/* END SECOND POST - COLUMN 1 OF SECOND ROW */

						/* BEGIN SECOND POST -  COLUMN 2 OF SECOND ROW */
						echo '<article class="bbg-portfolio__excerpt usa-width-one-half">';
							// echo '<h6>&nbsp;</h6>';
							query_posts( array( 'post__in' => array ( $thirdPostID) ) );
							if (have_posts()) {
								while ( have_posts() ) : the_post();
									$gridClass = "bbg-grid--1-1-1";
									get_template_part( 'template-parts/content-portfolio', get_post_format() );
								endwhile;
							}
							wp_reset_query();
							echo '<div align="right"><a href="' . $blogLink . '" class="bbg__kits__intro__more--link">More Blogs »</a></div>';
						echo '</article>';
						/* END SECOND POST - COLUMN 2 OF SECOND ROW */

						/* BEGIN TWEET - COLUMN 3 OF SECOND ROW */
						echo '<article class="usa-width-one-third">';
							echo '<h6 class="bbg__label"><a target="_blank" href="https://twitter.com/$twitterHandle">On Twitter</a></h6>';
							echo '<div class="bbg__quotation" style="margin-top:0; padding:0;">';
								echo $latestTweetsStr;
							echo '</div>';
						echo '</div>';
						/* END TWEET - COLUMN 3 OF SECOND ROW */
					echo '</div>';
				echo '</div>';
			echo '</section>';
			/* END SECOND ROW */
		?>
	</section>

	<?php
		/* TRANSCRIPTS */
		$remarksLink = 'https://bbgredesign.voanews.com/statements-and-remarks/';
		$ceoImage = 'https://bbgredesign.voanews.com/wp-content/media/2017/07/lansingspeaks.jpg';

	?>

	<section class="bbg__kits__section">
		<section class="usa-grid-full bbg__kits__section--row bbg__ribbon--thin">
		    <div class="usa-grid">
		        <div class="bbg__announcement__flexbox">
		            <div class="bbg__announcement__photo" style="background-image: url(<?php echo $ceoImage; ?>)"></div>
		            <div>
		                <h6 class="bbg__label">On the Record</h6>
		                <h2 class="bbg__announcement__headline selectionShareable"><a href="<?php echo $remarksLink; ?>">Transcribed Remarks</a></h2>
		                <p>View transcripts of CEO Lansing’s remarks and statements at each of his appearances since he joined the BBG in September 2015.
							<br/><br/><a href="<?php echo $remarksLink; ?>" class="bbg__kits__intro__more--link">View All »</a>
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
				'posts_per_page' => 1,
				'orderby' => 'post_date',
				'order' => 'desc',
				'tax_query' => $tax_query
			);

			$statementsLink = "/category/from-the-ceo+statements/";
			echo '<div class="usa-width-one-half">';
				echo "<h6 class='bbg__label'><a target='_blank' href='<?php echo $statementsLink; ?>'>Statements</a></h6>";

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
			echo '<div align="right"><a href="' . $statementsLink . '" class="bbg__kits__intro__more--link">More Statements »</a></div>';
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
				'posts_per_page' => 1,
				'orderby' => 'post_date',
				'order' => 'desc',
				'tax_query' => $tax_query
			);

			$opEdLink = "/category/from-the-ceo+op-ed/";
			echo "<div class='usa-width-one-half'>";
				echo "<h6 class='bbg__label'><a target='_blank' href='<?php echo $opEdLink; ?>'>Op-Eds</a></h6>";
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
			echo '<div align="right"><a href="' . $opEdLink . '" class="bbg__kits__intro__more--link">More Op-Eds »</a></div>';
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
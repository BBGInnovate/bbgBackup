<?php
/**
 * The template for displaying all single Burke profile posts. *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post *
 * @package bbgRedesign
 */
/* we go through the loop once and reset it in order to get some vars for our og tags */

if ( have_posts() ) {
	the_post();
	// $metaAuthor = get_the_author();
	$metaKeywords = strip_tags( get_the_tag_list('', ', ', '') );
	/**** CREATE OG TAGS ***/
	$ogDescription = get_the_excerpt();
	$ogTitle = get_the_title();
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'Full' );
	$ogImage = $thumb['0'];
	$socialImageID = get_post_meta( $post -> ID, 'social_image', true );
	if ( $socialImageID ) {
		$socialImage = wp_get_attachment_image_src( $socialImageID, 'Full' );
		$ogImage = $socialImage[0];
	}

	/**** CREATE $bannerAdjustStr *****/
	$bannerPosition = get_post_meta( get_the_ID() , 'adjust_the_banner_image', true );
	$bannerPositionCSS = get_field( 'adjust_the_banner_image_css', '', true );
	$bannerAdjustStr = "";
	if ( $bannerPositionCSS ) {
		$bannerAdjustStr = $bannerPositionCSS;
	}
	else
	if ( $bannerPosition ) {
		$bannerAdjustStr = $bannerPosition;
	}

	rewind_posts();
}

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	<?php
		while ( have_posts() ):
			the_post();

		// Default adds a space above header if there's no image set
		$featuredImageClass = " bbg__article--no-featured-image";

		// the title/headline field, followed by the URL and the author's twitter handle
		$twitterText = "";
		$twitterText.= "Profile of " . html_entity_decode( get_the_title() );
		$twitterText.= " by @bbggov " . get_permalink();
		$twitterURL = "//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
		$fbUrl = "//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );
	?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>
			<div class="usa-grid">
				<h5 class="bbg__label--mobile large">
					<a href="<?php echo network_home_url()?>burke-awards/burke-honorees">Burke Awards honorees</a>
				</h5>
			</div>

			<?php
				$hideFeaturedImage = get_post_meta( get_the_ID() , "hide_featured_image", true );
				if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
					echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline = "";
						$thumbnail_image = get_posts( array(
							'p' => get_post_thumbnail_id( get_the_ID() ),
							'post_type' => 'attachment'
						));
						if ( $thumbnail_image && isset( $thumbnail_image[0] ) ) {
							$featuredImageCutline = $thumbnail_image[0] -> post_excerpt;
						}

						$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , array( 700, 450 ) , false, '' );

						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner--profile" style="background-image: url(' . $src[0] . '); background-position: ' . $bannerAdjustStr . '">';
						echo '</div>';
					echo '</div> <!-- usa-grid-full -->';
				}
			?><!-- .bbg__article-header__thumbnail -->

			<div class="usa-grid">
			<?php
				$burkeProfileObj = get_field( 'burke_award_info' );
				$numRows = count ( $burkeProfileObj );
				// create variable to sort the award array by year
				$orderByYear = array();
				// populate order
				foreach( $burkeProfileObj as $i => $row ) {
					$orderByYear[ $i ] = $row[ 'burke_ceremony_year' ];
				}

				// use multisort to reorder the award array based on the year (reverse chrono)
				array_multisort( $orderByYear, SORT_DESC, $burkeProfileObj );
				// var_dump( $burkeProfileObj );

				echo '<header class="entry-header bbg__article-header' . $featuredImageClass . '">';
					echo '<div class="bbg__profile-title" style="max-width: none;">';
						the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' );
						echo "<!-- .bbg__article-header__title -->";
						// output variables: ceremony year + win status
						echo '<h5 class="entry-category bbg__profile-tagline">';
							// check if the repeater field has rows of data
							foreach ( array_values($burkeProfileObj) as $i => $profile ) {
								$burkeYear = $profile[ "burke_ceremony_year" ];
								// check if this profile won for this year
								$burkeWin = $profile[ "burke_is_winner" ];
								if ( !$burkeWin ) { // if not a winner, add nominee tag
									$burkeStatus = " nominee";
								} else { // else add winner tag
									$burkeStatus = " winner";
								}

								echo $burkeYear . $burkeStatus;
								if ( $numRows > 1 && $i + 1 < $numRows ) {
									echo " | ";
								}
							}
						echo "</h5><!-- .bbg__label -->";
					echo "</div>";
				echo "</header><!-- .bbg__article-header -->";
			?>
				<div class="bbg__article-sidebar--left">
					<h3 class="bbg__sidebar-label bbg__contact-label">Share </h3>
					<ul class="bbg__article-share">
						<li class="bbg__article-share__link facebook">
							<a href="<?php echo $fbUrl; ?>">
								<span class="bbg__article-share__icon facebook"></span>
							</a>
						</li>
						<li class="bbg__article-share__link twitter">
							<a href="<?php echo $twitterURL; ?>">
								<span class="bbg__article-share__icon twitter"></span>
							</a>
						</li>
					</ul>
				</div>

				<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">
					<?php the_content(); ?>
				</div><!-- .entry-content -->

				<div class="bbg__article-sidebar">
					<?php
						foreach ( array_values($burkeProfileObj) as $i => $profile ) {
							$i = $i + 1; // add a number to the item key to start at 1
							$b = ""; // create a variable for the award content

							echo "<div id='award-$i'>";
								$burkeWin = $profile[ "burke_is_winner" ];
									if ( !$burkeWin ) { // if not a winner, add nominee title
										$b .= '<h3 class="bbg__label small bbg__sidebar__download__label">Nominee</h3>';
									} else { // else add winner title
										$b .= '<h3 class="bbg__label small bbg__sidebar__download__label">Winner</h3>';
									}
								$b .=  '<h5 class="bbg__sidebar-label">' . $profile[ "burke_ceremony_year" ] . '</h5>' ;

								// populate network and check for service and concatenate into 1 variable
								$burkeNetwork = $profile[ "burke_network" ];
								$burkeService = $profile[ "burke_service" ];
								if ( $burkeService ) {
									$b .= " of " . $burkeNetwork . "’s " . $burkeService;
								} else {
									$b .= " of " . $burkeNetwork;
								}

								$burkeOccupation = $profile[ "burke_occupation" ];


								$burkeRecognition = $profile[ "burke_reason" ];
								$burkeWinningWork = $profile[ "burke_sample_works" ];
								$burkeAcceptanceURL = $profile[ "burke_acceptance_video_url" ];

								echo $b;
							echo "</div>";
						}
					?>
<!-- class="bbg__sidebar__primary-headline"
 -->					<?php wp_reset_postdata();?>
				</div><!-- .bbg__article-sidebar -->
			</div><!-- .usa-grid -->
		</article><!-- #post-## -->

	<?php endwhile; // End of the loop. ?>

	</main><!-- #main -->
</div><!-- #primary -->

	<section class="usa-grid">
		<?php get_sidebar(); ?>
	</section>
<?php get_footer(); ?>
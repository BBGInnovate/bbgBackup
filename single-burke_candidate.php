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
					<?php the_content();
						echo "<!-- Acceptance Videos -->";
						$v = "";
						// output acceptance video
						foreach ( array_values($burkeProfileObj) as $i => $profileVid ) {
							$burkeVidURL = $profileVid[ "burke_acceptance_video_url" ];
							if ( $burkeVidURL ) {
								if ( $numRows > 1 ) {
									$v .= "<h3>Watch acceptance videos</h3>";
								} else {
									$v .=  "<h3>Watch acceptance video</h3>";
								}
								// output valid URL
								$v .= apply_filters( 'the_content', $burkeVidURL );
							}
						}
						echo $v;
					?>
				</div><!-- .entry-content -->

				<div class="bbg__article-sidebar">
					<h3 class='bbg__label'>Nominations</h3>
					<?php
						foreach ( array_values($burkeProfileObj) as $i => $profile ) {
							$i = $i + 1; // add a number to the item key to start at 1
							$b = ""; // create a variable for all the award content

							echo "<div id='award-$i' class='bbg__sidebar__primary'>";
								// output year and winner label
								$b .=  '<h3 class="bbg__label small">' . $profile[ "burke_ceremony_year" ];
									if ( $profile[ "burke_is_winner" ] ) {
										$b .= ' <span class="usa-label bbg__label--burke">Winner</span>';
									}
								$b .= '</h3>';
								// output reason for award
								$b .= "<p class='bbg__article-sidebar__emphasis--italic'>" . $profile[ "burke_reason" ] . "</p>";
								$b .= "<p>";
									// output work title if available
									if ( $profile[ "burke_occupation" ] ) {
										$b .= "<strong>Honoree:</strong> " . $profile[ "burke_occupation" ] . "<br/>";
									}
									// convert network name to uppercase
									$burkeNetwork = strtoupper( $profile[ "burke_network" ] );
										// add backward slash to RFERL
										if ( $burkeNetwork == "RFERL" ) {
											$burkeNetwork = "RFE/RL";
										}
									$burkeService = $profile[ "burke_service" ];
									// output network and service name (if latter is available)
									if ( $burkeService ) {
										$b .= "<strong>Recognized by:</strong> " . $burkeNetwork . "’s " . $burkeService . "<br/>";
									} else {
										$b .= "<strong>Recognized by:</strong> " . $burkeNetwork . "<br/>";
									}
								$b .= "</p>";
								// set variable for sample work URL repeater
								$burkeWorkObj = $profile[ "burke_sample_works" ];
								// output links to work
								if ( $burkeWorkObj ) {
									$b .= "<h4>Award-winning work</h4>";
									$b .= "<ul>";
										// loop through URLs of award-winning work
										foreach( $burkeWorkObj as $burkeWorkURL ) {
											// var_dump( $burkeWorkURL );
											$b .= '<li class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $burkeWorkURL[ "burke_sample_works_link" ] . '">' . $burkeWorkURL[ "burke_sample_works_title" ] . ' »</a></li>';
										}
									$b .= "</ul>";
								}
								// output related profiles
								$burkeRelated = $profile [ "burke_associated_profiles" ];
								// var_dump($burkeRelated);
								if ( $burkeRelated ) {
									$b .= "<h4>Related Burke honorees</h4>";
									$b .= "<ul class='usa-unstyled-list'>";
										// loop through URLs of award-winning work
										foreach( $burkeRelated as $burkeRelProfile ) {
											// var_dump( $burkeRelProfile->guid );
											$b .= '<li class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $burkeRelProfile->guid . '">' . $burkeRelProfile->post_title . ' »</a></li>';
										}
									$b .= "</ul>";
								}

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
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

//Add shared sidebar
include get_template_directory() . "/inc/shared_sidebar.php";

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
					<?php
						// loop through all award entries
						foreach ( array_values( $burkeProfileObj ) as $i => $profile ) {
							$i = $i + 1; // add a number to the item key to start at 1
							$b = ""; // create a variable for all the award content
							// populate variables
							$burkeTitle = $profile[ "burke_occupation" ]; // check for full title
							$burkeNetwork = strtoupper( $profile[ "burke_network" ] ); // convert network name to uppercase
							if ( $burkeNetwork == "RFERL" ) { // add backward slash to RFERL
								$burkeNetwork = "RFE/RL";
							}
							// check for language service
							$burkeService = "";
							if ( $profile[ "burke_service" ] ) {
								$burkeService = $profile[ "burke_service" ] . ' Service';
							}

							// output all award details
							echo "<div id='award-$i' class='bbg__sidebar__primary'>";
								// output year and winner label
								$b .=  '<h3 class="bbg__label">' . $profile[ "burke_ceremony_year" ];
									if ( $profile[ "burke_is_winner" ] ) {
										$b .= ' Winner';
									} else {
										$b .= ' Nominee';
									}
								$b .= '</h3>';

								$b .= '<p>';
									// output honoree details
									if ( $burkeTitle ) {
										// output bold title
										$b .= '<strong class="bbg__label--burke">' . $burkeTitle . '</strong><br/>';
										// output network + service (if set)
										if ( $burkeService ) {
											$b .= $burkeNetwork . ', ' . $burkeService;
										} else {
											$b .= $burkeNetwork;
										}
									} else {
										// output network + service (if set)
										if ( $burkeService ) {
											$b .= '<strong class="bbg__label--burke">' . $burkeNetwork . ', ' . $burkeService . '</strong>';
										} else {
											$b .= '<strong class="bbg__label--burke">' . $burkeNetwork . '</strong>';
										}
									}

								$b .= "</p>";

								// output reason for award
								$b .= "<p class='bbg__article-sidebar__emphasis--italic'>" . $profile[ "burke_reason" ] . "</p>";

								// output related profiles
								$burkeRelated = $profile [ "burke_associated_profiles" ];
								// var_dump($burkeRelated);
								if ( $burkeRelated ) {
									$b .= "<h4>Recognized with</h4>";
									$b .= "<ul class='usa-unstyled-list'>";
										// loop through URLs of award-winning work
										foreach( $burkeRelated as $burkeRelProfile ) {
											// var_dump( $burkeRelProfile->guid );
											$b .= '<li class="bbg__sidebar__primary-headline"><a target="_blank" href="' . get_post_permalink( $burkeRelProfile -> ID ) . '">' . $burkeRelProfile->post_title . ' »</a></li>';
										}
									$b .= "</ul>";
								}

								// set variable for sample work URL repeater
								$burkeWorkObj = $profile[ "burke_sample_works" ];
								// create variables to separate link types
								$workLinks = array();
								$otherLinks = array();
								// output links to work
								if ( $burkeWorkObj ) {
									$links = count( $burkeWorkObj );
									// populate order
									for ( $l = 0; $l + 1 <= $links; $l++ ) {
										// check link type
										$linkType = $burkeWorkObj[$l][ 'burke_sample_works_type' ];
										// add to link type array
										if ( $linkType == 'work' ) {
										 	$workLinks[] = array (
									 						'type' => $linkType,
									 						'title' => $burkeWorkObj[$l][ 'burke_sample_works_title' ],
									 						'url' => $burkeWorkObj[$l][ 'burke_sample_works_link' ]
									 						);
										} elseif ( $linkType == 'related' ) {
											$otherLinks[] = array (
									 						'type' => $linkType,
									 						'title' => $burkeWorkObj[$l][ 'burke_sample_works_title' ],
									 						'url' => $burkeWorkObj[$l][ 'burke_sample_works_link' ]
									 						);
										}
									}
									// output work links header
									if ( count( $workLinks ) == 1 && $workLinks[0]['url'] ) { // if only one singular
										$b .= "<h4>Award-winning work</h4>";
										$b .= '<p class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $workLinks[0]["url"] . '">' . $workLinks[0]["title"] . ' »</a></p>';

									} elseif ( count( $workLinks ) > 1 ) { // if 1+ pluralize
										$b .= "<h4>Award-winning works</h4>";
										$b .= "<ul style='margin-bottom:1rem;'>";
											// loop through URLs of award-winning work
											foreach( $workLinks as $workURL ) {
												// var_dump( $burkeWorkURL );
												$b .= '<li class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $workURL[ "url" ] . '">' . $workURL[ "title" ] . ' »</a></li>';
											}
										$b .= "</ul>";
									}
									// output other links header
									if ( count( $otherLinks ) == 1 && $otherLinks[0]['url'] ) { // if only one singular
										$b .= "<h4>Related link</h4>";
										$b .= '<p class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $otherLinks[0]["url"] . '">' . $otherLinks[0]["title"] . ' »</a></p>';

									} elseif ( count( $otherLinks ) > 1 ) { // if 1+ pluralize
										$b .= "<h4>Related links</h4>";
										$b .= "<ul style='margin-bottom:1rem;'>";
											// loop through URLs of award-winning work
											foreach( $otherLinks as $otherURL ) {
												// var_dump( $burkeWorkURL );
												$b .= '<li class="bbg__sidebar__primary-headline"><a target="_blank" href="' . $otherURL[ "url" ] . '">' . $otherURL[ "title" ] . ' »</a></li>';
											}
										$b .= "</ul>";
									}
								}

								echo $b;
							echo "</div>";
						}
					?>
					<!-- class="bbg__sidebar__primary-headline" -->
					<section id="added-sidebar" class="usa-grid-full">
						<?php
							echo "<!-- Additional sidebar content -->";
							if ( $includeSidebar ) {
								echo $sidebar;
							}

							echo $sidebarDownloads;
						?>
					</section>
					<?php wp_reset_postdata();?>
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
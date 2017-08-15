<?php
/**
 * Custom template for displaying informational kits — Press Room, Congressional.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 * @author Gigi Frias <gfrias@bbg.gov>
   template name: Info Kits
 */

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageName = get_the_title();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

/******* CREATE AN ARRAY FOR AWARDS INFO ******/
$awards = array();
$awardCategoryObj = get_category_by_slug( 'award' );
if ( is_object($awardCategoryObj) ) {
	// set up award category query parameters
	$awardCategoryID = $awardCategoryObj -> term_id;
	$awardParams = array(
		'post_type' => array( 'award' ),
		'posts_per_page' => 1,
		'orderby' => 'date',
		'order' => 'DESC'
	);
	// execute awards query
	$award_query = new WP_Query( $awardParams );
	if ( $award_query -> have_posts() ) {
		while ( $award_query -> have_posts() ) : $award_query -> the_post();
			$id = get_the_ID();

			$awardYears  = get_post_meta( $id, 'standardpost_award_year' );
			$awardTitle = get_post_meta( $id, 'standardpost_award_title', true );
			$orgTerms = get_field( 'standardpost_award_organization', $id );
		    $organizations = array();
		    $organizations[] = $orgTerms -> name;
			$entity = get_post_meta( $id, 'standardpost_award_recipient' );
			$description = get_post_meta( $id, 'standardpost_award_description' );

			$awards[] = array(
				'id' => $id,
				'url' => get_permalink( $id ),
				'title' => get_the_title( $id ),
				'excerpt' => get_the_excerpt(),
				'awardYears' => $awardYears,
				'awardTitle' => $awardTitle,
				'organizations' => $organizations,
				'recipients' => $entity
			);
		endwhile;
	}
	wp_reset_postdata();
}
/******* DONE CREATING AN ARRAY FOR AWARDS INFO ******/

/******* GRAB THE MOST RECENT MEDIA ADVISORY THAT HAS AN EXPIRATION DATE IN THE FUTURE. WE CHECK LATEST 5 ******/
$advisory = false;
// Get all posts with advisory category
$mediaAdvisoryCategoryObj = get_category_by_slug( 'media-advisory' );

if ( $pageName != "Office of Congressional Affairs" && is_object( $mediaAdvisoryCategoryObj ) ) {
	// set up award category query parameters
	$mediaAdvisoryCategoryID = $mediaAdvisoryCategoryObj -> term_id;
	$mediaParams = array(
		'post_type' => array( 'post' ),
		'posts_per_page' => 5,
		'category__and' => array( $mediaAdvisoryCategoryID ),
		'orderby' => 'date',
		'order' => 'DESC'
	);
	$todayDateObj = new DateTime("now");

	// execute advisory query
	$foundAdvisory = false;

	$media_query = new WP_Query( $mediaParams );

	if ( $media_query -> have_posts() ) {
		while ( $media_query -> have_posts() ) : $media_query -> the_post();
			if ( !$foundAdvisory ) {
				$id = get_the_ID();
				$expiryDate = get_field( 'media_advisory_expiration_date', $id );
				$expiryDateObj = DateTime::createFromFormat(
					"m/d/Y h:i",
					$expiryDate . " 00:00"
				);
				if ( $expiryDateObj > $todayDateObj ) {
					$foundAdvisory = true;
					$thumb =  get_the_post_thumbnail( $id, 'medium-thumb' );
					$advisory = array(
						'id' => $id,
						'url' => get_permalink(),
						'title' => get_the_title(),
						'excerpt' => get_the_excerpt(),	//leave this one last- it changes the post context
						'thumb' => $thumb,
						'expiryDate' => $expiryDate
					);
				}
			}
		endwhile;
	}
	wp_reset_postdata();
}

$numNews = 4;
if ( $advisory ) {
	$numNews = 1;
}

/****** PREPARE QUERY PARAMS PRESS RELEASES ********/
$prCategoryObj = get_category_by_slug( 'press-release' );
$prCategoryID = $prCategoryObj -> term_id;

$qParamsPressReleases = array(
	'post_type' => array( 'post' ),
	'posts_per_page' => $numNews,
	'category__and' => array( $prCategoryID ),
	'orderby', 'date',
	'order', 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => 'post-format-quote',
			'operator' => 'NOT IN'
		)
	),
	// 'category__not_in' => get_cat_id( 'Award' ) // exclude Awards posts ** not necessary with new awards workflow including custom Award post type
);
// set variable for PR category link to All network highlights page
$prCategoryLink = get_permalink( get_page_by_path( 'news/network-highlights' ) );
/******* DONE CREATING AN ARRAY FOR PRESS RELEASES ******/

/****** PREPARE QUERY PARAMS CONGRESSIONAL NEWS ********/
$qParamsCongressional = array(
	'post_type' => array( 'post' ),
	'posts_per_page' => $numNews,
	'tag' => 'office-of-congressional-affairs',
	'orderby', 'date',
	'order', 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => 'post-format-quote',
			'operator' => 'NOT IN'
		)
	),
	'category__not_in' => get_cat_id( 'Award' ) // exclude Awards posts
);
// set variable for PR category link to main news page
$congCategoryLink = get_permalink( get_page_by_path( 'news' ) );
/******* DONE CREATING AN ARRAY FOR CONGRESSIONAL NEWS ******/

include get_template_directory() . "/inc/constant-contact_sign-up.php";

get_header(); ?>

<style>
	@media screen and ( min-width: 600px ) {
		/*** JBF: this CSS is here because something is wrong with the grid at 1-2-2 ***/
		.bbg-grid--1-2-2:nth-child(2n+1) {
			clear: none;
			margin-right: 0;
		}
	}
</style>

<script type="text/javascript">
	/* show/hide the constant contact signup form */
	function toggleForm() {
		btnSignup = document.getElementById( 'btnSignup' );

		if ( btnSignup.style.display == 'none' ) {
			btnSignup.style.display = '';
			ccForm = document.getElementsByName( 'embedded_signup' );
			ccForm[0].style.display = 'none';
		} else {
			btnSignup.style.display = 'none';
			ccForm = document.getElementsByName( 'embedded_signup' );
			ccForm[0].style.display = '';
		}
	}

	jQuery( document ).ready( function() {
		/* click handler for the show/hide of constant contact form */
		jQuery( '#btnClose' ).click( function() {
		  toggleForm();
		});
	});
</script>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid">
				<header class="page-header">
					<?php the_title( '<h5 class="bbg__label--mobile large">', '</h5>' ); ?>
				</header><!-- .page-header -->
			</div>

			<!-- SET UNIVERSAL VARIABLES -->
			<?php
				// access site-wide variables
				global $post;
				// set locale for number filters
				setlocale( LC_ALL, 'en_US.UTF-8' ); // currency

			    /* BBG settings variables */
			    // Load url for mission page
			    $missionURL = get_field( 'site_setting_mission_statement_link', 'options', 'false' );

			    if ( $missionURL ) {
			    	// get mission page ID
			    	$missionID = url_to_postid( $missionURL );
			    	// load excerpt from page
					$mission = my_excerpt( $missionID );
					// apply content filters to excerpt
					$mission = apply_filters( 'the_content', $mission );
					$mission = str_replace( ']]>', ']]&gt;', $mission );
					// remove last few characters from excerpt
					$mission = substr($mission, 0, -5);
					// add link to url at end of excerpt
					$mission = $mission . ' <a href="' . $missionURL . '" class="bbg__kits__intro__more--link">Network missions »</a></p>';
			    } else {
			    	// get mission from BBG settings "mission statement" variable
			    	$mission = get_field( 'site_setting_mission_statement', 'options', 'false' );
			    	// apply content filters to text
					$mission = apply_filters( 'the_content', $mission );
					$mission = str_replace( ']]>', ']]&gt;', $mission );
			    }

			    // numbers
			    $networks = get_field( 'site_setting_total_networks', 'options', 'false' ) . " networks";
			    $languages = get_field( 'site_setting_total_languages', 'options', 'false' ) . " languages";
			    $countries = get_field( 'site_setting_total_countries', 'options', 'false' ) . " countries";
			    $audience = get_field( 'site_setting_unduplicated_audience', 'options', 'false' ) . " million";
			    $affiliates = get_field( 'site_setting_total_affiliates', 'options', 'false' );
					$affiliates = number_format( $affiliates ) . " affiliates"; // format number and append value desc
			    $transmittingSites = get_field( 'site_setting_transmitting_sites', 'options', 'false' );
					$transmittingSites = number_format( $transmittingSites ) . " transmitting sites"; // format number and append value desc
			    $programming = get_field( 'site_setting_weekly_programming', 'options', 'false' );
					$programming = number_format( $programming ) . " hours"; // format number and append value desc

				/* Contact information */
				$phone = get_field( 'agency_phone', 'options', 'false' );
				$phone_link = str_replace( array('(',') ','-'), '' , $phone );

				$phoneMedia = get_field( 'agency_phone_press', 'options', 'false' );
				$phoneMedia_link = str_replace( array('(',') ','-'), '' , $phoneMedia );

				$phoneCongress = get_field( 'agency_phone_congress', 'options', 'false' );
				$phoneCongress_link = str_replace( array('(',') ','-'), '' , $phoneCongress );

				$email = get_field( 'agency_email', 'options', 'false' );
				$emailPress = get_field( 'agency_email_press', 'options', 'false' );
				$emailCongress = get_field( 'agency_email_congress', 'options', 'false' );

				$street = get_field( 'agency_street', 'options', 'false' );
				$city = get_field( 'agency_city', 'options', 'false' );
				$state = get_field( 'agency_state', 'options', 'false' );
				$zip = get_field( 'agency_zip', 'options', 'false' );

				/* Format all contact information */
				// Pick which phone number to display based on page title
				if ( $pageName == "Press room" && $phoneMedia != "" )  {
					$phone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel:' . $phoneMedia_link . '">' . $phoneMedia . '</a></li>';
				} elseif ( $pageName == "Office of Congressional Affairs" && $phoneCongress != "" )  {
					$phone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel:' . $phoneCongress_link . '">' . $phoneCongress . '</a></li>';
				} else {
					$phone = '<li itemprop="telephone" aria-label="telephone"><span class="bbg__list-label">Tel: </span><a href="tel:' . $phone_link . '">' . $phone . '</a></li>';
				}

				// Pick which email address to display based on page title
				if ( $pageName == "Press room" && $emailPress != "" ) {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $emailPress . '" title="Contact us">' . $emailPress . '</a></li>';
				} elseif ( $pageName == "Office of Congressional Affairs" && $emailCongress != "" ) {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $emailCongress . '" title="Contact us">' . $emailCongress . '</a></li>';
				} else {
					$email = '<li><span class="bbg__list-label">Email: </span><a itemprop="email" aria-label="email" href="mailto:' . $email . '" title="Contact us">' . $email . '</a></li>';
				}

				if ($street != "" && $city != "" && $state != "" && $zip != "") {
					$address = $street . '<br/>' . $city . ', ' . $state . ' ' . $zip;

					//Strip spaces for url-encoding.
					$street = str_replace(" ", "+", $street);
					$city = str_replace(" ", "+", $city);
					$state = str_replace(" ", "+", $state);
					$mapLink = 'https://www.google.com/maps/place/' . $street . ',+' . $city . ',+' . $state . '+' . $zip . '/';

					$address = '<p itemprop="address" aria-label="address"><a href="'. $mapLink . '">' . $address . '</a></p>';
				}

				if ( $address != "" || $phone != "" || $email != "" || $phoneMedia != "" || $emailPress != "" ){
					$includeContactBox = TRUE;
				}

				// get all contact cards for dropdown
				$allContacts = get_field( 'kits_network_contacts' );

				$contactPostIDs = get_post_meta( $post -> ID, 'contact_post_id', true );
			?>

			<!-- PART 1 **** News + media advisories + contact & inquiries **** -->
			<article class="bbg__article bbg__kits__section">
				<div class="usa-grid">
					<?php
						$includeInfoBox = get_post_meta( $post -> ID, 'kits_include_info_box', true );
						if ( $includeInfoBox ) {
							$link = get_post_meta( $post -> ID, 'kits_info_box_link', true );
							$title = get_post_meta( $post -> ID, 'kits_info_box_title', true );
							$text = get_post_meta( $post -> ID, 'kits_info_box_text', true );

							$s = '';
							$s .= '<section class="usa-section">';
							$s .= '<div class="usa-alert usa-alert-info">';
							$s .= '<div class="usa-alert-body">';
							$s .= '<h3 class="usa-alert-heading">';
							if ( $link == "" ) {
								$s .= $title;
							} else {
								$s .= '<a href="' . $link . '">' . $title . '</a>';
							}
							$s .= '</h3>';
							$s .= '<p class="usa-alert-text">';
							$s .= $text;
							$s .= '</p>';
							$s .= '</div>';
							$s .= '</div>';
							$s .= '</section>';
							echo $s;
						}
					?>

					<div class="entry-content bbg__article-content large">
						<?php
						echo '<!-- Recent news section -->';
						echo '<section id="recent-posts" class="usa-section bbg__home__recent-posts">';

							if ( !$advisory && $pageName == "Press room" ) {
								echo '<h2>Recent press releases</h2>';
							} elseif ( !$advisory && $pageName == "Office of Congressional Affairs" ) {
								echo '<h2>Recent highlights</h2>';
							}

							echo '<div class="bbg__kits__recent-posts">';
								echo '<div class="usa-width-one-half bbg__secondary-stories">';

								if ( $advisory ) {
									echo '<h2>Latest press release</h2>';
								}

								/**** START FETCH related news based on page title ****/
								if ( $pageName == "Press room" ) {
									// Run query of press releases
									query_posts( $qParamsPressReleases );
								} elseif ( $pageName == "Office of Congressional Affairs" ) {
									// Run query of congressional affairs tag
									query_posts( $qParamsCongressional );
								}

								if ( have_posts() ) {
									$counter = 0;
									$includeImage = TRUE;

									while ( have_posts() ) : the_post();
										$counter++;
										$postIDsUsed[] = get_the_ID();
										$includeMeta = false;
										$gridClass = "bbg-grid--full-width";
										$includeExcerpt = false;

										if ( $counter > 1 ) {
											$includeImage = false;
											$includeMeta = false;
											if ( $counter == 2 ) {
												echo '</div><div class="usa-width-one-half tertiary-stories">';
											}
										}
										if ( $counter == 1 ) {
											$includePortfolioDescription = false;
											get_template_part( 'template-parts/content-portfolio', get_post_format() );
										} else {
											get_template_part( 'template-parts/content-excerpt-list', get_post_format() );
										}

									endwhile;

									echo "<br/><a href='$prCategoryLink' class='bbg__kits__intro__more--link'>View more »</a>";
								}
								wp_reset_query();
								echo '</div>';

								if ( $advisory && $pageName == "Press room" ) {
									echo '<div class="usa-width-one-half tertiary-stories">';
										echo '<h3 class="entry-title bbg-blog__excerpt-title"><span class="usa-label bbg__label--advisory">Media Advisory</span><br/><a href="' . $advisory['url'] . '">' . $advisory['title'] . '</a></h3>';
										echo '<div class="entry-content bbg-blog__excerpt-content">';
											echo '<p>' . $advisory['excerpt'] . '</p>';
										echo '</div>';
									echo '</div>';
								}
								echo '</div><!-- headlines -->';

							echo '</section><!-- .BBG News -->';
						?>
					</div>

					<!-- Contact card (tailored to audience based on page title) -->
					<div class="bbg__article-sidebar large">
						<?php if ( $includeContactBox ) { ?>
							<aside>
								<div class="bbg__contact-card">
									<div class="bbg__contact-card__text">
										<?php
											// Contact information
											// $pageName = str_replace( "Private: ", "", $pageName ); // hide "private" label on staging

											if ( $pageName == "Press room" ) {
												echo '<h3>Office of Public Affairs</h3>';
											} elseif ( $pageName == "Office of Congressional Affairs" ) {
												echo '<h3>Office of Congressional Affairs</h3>';
											} else {
												echo '<h3>' . $pageName . 'Contact information</h3>';
											}
											echo $address;
											echo '<ul class="usa-unstyled-list">';
												echo $phone;
												echo $email;
											echo '</ul>';

											echo '<!-- Social media profiles -->';
											// check that budget repeater field exists
											$allSocial = get_field( 'agency_social_media_profiles', 'options', 'false' );

											echo '<div class="bbg__kits__social">';
												if ( $allSocial ) {
													// loop through repeater rows
													foreach ( $allSocial as $socials ) {
														// populate variables for each row
														$socialPlatform = $socials['social_media_platform'];
														$socialProfile = $socials['social_media_profile_name'];
														$socialURL = $socials['social_media_url'];

														echo '<a class="bbg__kits__social-link usa-link-' . strtolower( $socialPlatform ) . '" href="' . $socialURL . '" role="img" aria-label="' . $socialPlatform  . '"></a>';
													}
												}
											echo '</div>';

											echo '<div class="bbg__kits__contacts">';
												renderContactSelect($contactPostIDs);
											echo '</div>';
;
											echo "<button id='btnSignup' onclick=\" toggleForm();  \" class='usa-button-outline bbg__kits__inquiries__button--half' style='width:100%; margin-top:2rem;' data-enabled='enabled'>Sign up to receive updates</button>";

											echo $signupForm;
										?>
									</div>
								</div>
							</aside>
						<?php } ?>
					</div>
				</div>
			</article>
			<!-- END PART 1 **** News + media advisories + contact & inquiries **** -->

			<!-- PART 2 **** BBG by the numbers + CEO ribbon + latest award/featured page + Featured reports **** -->
			<div class="usa-section usa-grid bbg__kits__section" id="page-sections">
		        <!-- 2A **** 3-COL ROW: BBG by the numbers -->
		        <section class="usa-grid-full bbg__kits__section--row">
		        	<h2 class="entry-title">BBG by the numbers</h2>
		        	<div class="usa-grid-full bbg__kits__section--tiles">
						<!-- DISTRIBUTION tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">International operations</h3>
							<p class="bbg__kits__section--tile__list"><span class="bbg__kits__section--tile__list--serif"><?php echo $networks; ?></span> and a system of <span class="bbg__kits__section--tile__list--sans"><?php echo $affiliates; ?></span> and over <span class="bbg__kits__section--tile__list--sans"><?php echo $transmittingSites; ?></span> distribute <span class="bbg__kits__section--tile__list--sans"><?php echo $programming; ?></span> of original content globally each week.</p>
		        		</article>

		        		<!-- AUDIENCE tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Global audience</h3>
							<p class="bbg__kits__section--tile__list">A worldwide unduplicated audience of <span class="bbg__kits__section--tile__list--serif"><?php echo $audience; ?></span> from more than <span class="bbg__kits__section--tile__list--sans"><?php echo $countries; ?></span> tune in weekly in <span class="bbg__kits__section--tile__list--sans"><?php echo $languages; ?></span>.</p>
		        		</article>

		        		<!-- BUDGET tile -->
		        		<article class="bbg-grid--1-3-3 bbg__kits__section--tile">
		        			<h3 class="bbg__kits__section--tile__title-bar">Annual budget</h3>
		        			<table class="bbg__kits__section--tile__table--borderless">
								<tbody>
								<?php
									// check that budget repeater field exists
									$allBudgets = get_field( 'site_setting_annual_budgets', 'options', 'false' );

									if( $allBudgets ) {
										//build a new array with the key and value
										foreach($allBudgets as $key => $value) {
											//still going to sort by firstname
											$budget[$key] = $value['fiscal_year'];
										}
										// sort multi-dimensional array by new array
										array_multisort( $budget, SORT_DESC, $allBudgets );

										// create a variable to limit number of budget years
										$maxYears = 0;

										// loop through repeater rows
										foreach( $allBudgets as $budget ) {
											// populate variables for each row
											$budgetFY = 'FY' . $budget['fiscal_year'];
											$budgetStatus = $budget['status'];
											$budgetAmount = $budget['dollar_amount'];

											echo '<!-- ' . $budgetFY . ' budget -->';
											echo '<tr>';
												// fiscal year column
												echo '<th scope="row">' . $budgetFY . ' <span class="bbg__file-size">(' . $budgetStatus  . ')</span></th>';
												// amount column
												echo '<td class="bbg__kits__section--tile__list--sans">' . money_format( '%.1n', $budgetAmount ) . 'M</td>';
											echo '</tr>';

											if ( $maxYears++ == 3 ) break; // change max number here to set new limit — current max is 4 (starting from 0)
										}
									}
								?>
								</tbody>
							</table>
		        		</article>
		        	</div>
		        </section>
				<!-- END 2A **** BBG by the numbers -->

				<!-- 2B **** Flexible content rows -->
		        <?php
				/**** FETCH AND RETURN DATA ROWS ****/

				// check if the flexible content field has rows of data
				if ( have_rows('kits_flexible_page_rows') ):
					// set basic variables
					$counter = 0;
					$pageTotal = 1; // for setting grid based on odd/even count
					$containerClass = "bbg__kits__child ";

					/* @Check if number of pages is odd or even
					*  Return BOOL (true/false) */
					function checkNum( $pageTotal ) {
						return ( $pageTotal%2 ) ? TRUE : FALSE;
					}

					// loop through all the rows
					while ( have_rows('kits_flexible_page_rows') ) : the_row();
						$counter++;

						$sectionClasses = "usa-grid-full bbg__kits__section--row";

						// change section class for ribbon rows
						if ( get_row_layout() == 'kits_ribbon_page' ) {
							$sectionClasses .= " bbg__ribbon--thin";
						}

						echo '<!-- ROW ' . $counter . '-->'; // output counter to keep track of rows
						echo '<section class="' . $sectionClasses . '">'; // output row-specific class

						if ( get_row_layout() == 'kits_ribbon_page' ):
						/*** BEGIN DISPLAY OF ENTIRE RIBBON ROW ***/
							// populate variables from row data
							$labelText = get_sub_field( 'kits_ribbon_label' );
							$labelLink = get_sub_field( 'kits_ribbon_label_link' );
							$headlineText = get_sub_field( 'kits_ribbon_headline' );
							$headlineLink = get_sub_field( 'kits_ribbon_headline_link' );
							$summary = get_sub_field( 'kits_ribbon_summary' );
							$imageURL = get_sub_field( 'kits_ribbon_image' );
							$imageDownload = get_sub_field( 'kits_ribbon_download_photo' );

							// allow shortcodes in intro text
							$summary = apply_filters( 'the_content', $summary );
							$summary = str_replace( ']]>', ']]&gt;', $summary );

							echo '<div class="usa-grid">';
								echo '<div class="bbg__announcement__flexbox">';
									// output image
									if ( $imageURL ) {
										echo "<div class='bbg__announcement__photo' style='background-image: url($imageURL);'>";
											//if download checked add button
											if ( $imageDownload ) {
												echo '<button class="usa-button bbg__kits__button" type="button"><span class="fa fa-download"></span> high-res photo</button>';
											}
										echo '</div>';
									}

									echo '<div>';
										// output label (with link if set)
										if ( $labelLink ) {
											echo "<h6 class='bbg__label'><a href='" . get_permalink( $labelLink ) . "'>$labelText</a></h6>";
										} else {
											echo "<h6 class='bbg__label'>$labelText</h6>";
										}

										// output headline (with link if set)
										if ( $headlineLink ) {
											echo "<h2 class='bbg__announcement__headline'><a href='" . get_permalink( $headlineLink ) . "'>$headlineText</a></h2>";
										} else {
											echo "<h2 class='bbg__announcement__headline'>$headlineText</h2>";
										}

										// output summary text
										echo $summary;
									echo '</div>';
								echo '</div><!-- .bbg__announcement__flexbox -->';
							echo '</div><!-- .usa-grid -->';
						/*** END DISPLAY OF ENTIRE RIBBON ROW ***/

						elseif ( get_row_layout() == 'kits_downloads_files' ):
						/*** BEGIN DISPLAY OF DOWNLOAD LINKS ROW ***/
							$downloadsLabel = get_sub_field( 'kits_downloads_label' );

							// output label if set
							if ( $downloadsLabel ) {
								echo "<h2 class='bbg__label'>$downloadsLabel</h2>";
							}

							echo "<div class='usa-grid-full bbg__kits__section--tiles'>";
								// set variable object
								$downloadFiles = get_sub_field( 'kits_downloads_file' );

								// count the number of files
								$countFiles = count ( $downloadFiles );

								// check number of files function return
								if ( checkNum($countFiles) === TRUE ) {
									// if TRUE: number is odd, set 3 column grid
									$containerClass = 'bbg-grid--1-3-3';
								} else {
									// if FALSE: number is even, set 2 column grid
									$containerClass = 'bbg-grid--1-2-2';
								}

								if ( $downloadFiles ) {
									// Loop through all the grandchild pages (objects in variable)
									foreach ( $downloadFiles as $file ) {
										// Define all variables
										$fileImageObject = $file['downloads_file_image'];
										// var_dump( $fileImageObject );
										// retrieve ID from image object and load large thumbnail size
										$thumbSrc = wp_get_attachment_image_src( $fileImageObject['ID'] , 'large-thumb' );

									    $supportPageTitle = $file['kits_related_page_name'];

									    // Related page array
									    $supportPage = $file['kits_related_page'];
									    	// page data
									    	if ( $supportPageTitle ) {
									    		$pageHeadline = $supportPageTitle;
									    	} else {
										    	$pageHeadline = get_the_title( $supportPage -> ID );
									    	}
									    	$pageURL = get_permalink( $supportPage -> ID );
											$pageExcerpt = my_excerpt( $supportPage -> ID );
											$pageExcerpt = apply_filters( 'the_content', $pageExcerpt );
											$pageExcerpt = str_replace( ']]>', ']]&gt;', $pageExcerpt );

										$fileTitle = $file['downloads_link_name'];

									    // Files object array
										$fileObj = $file['downloads_file'];
											// file data
											// var_dump( $fileObj );
											if ( $fileTitle ) {
												$fileName = $fileTitle;
											} else {
												$fileName = $fileObj['title'];
											}
											$fileID = $fileObj['ID'];
											$fileURL = $fileObj['url'];
											$file = get_attached_file( $fileID );
											$fileExt = strtoupper( pathinfo( $file, PATHINFO_EXTENSION ) );
											$fileSize = formatBytes( filesize( $file ) );

										// Output variables in HTML format
										echo "<article class='$containerClass bbg__kits__section--tile'>";
											echo "<header class='bbg__kits__section--tile__header'>";
												// Output page data
												if ( $supportPage ) {
													echo "<h3 class='bbg__kits__section--tile__title'>" . "<a href='" . $pageURL . "'>" . $pageHeadline . "</a></h3>";
												} else {
													echo "<h3 class='bbg__kits__section--tile__title'>" . "<a href='" . $fileURL . "' target='_blank'>" . $fileName . "</a></h3>";
												}
											echo "</header>";

											// Output file image
											if ( $thumbSrc ) {
												echo '<a href="' . $fileURL . '" target="_blank">';
													echo '<div class="bbg__kits__section--tile__thumb" style="background-image: url(' . $thumbSrc[0] . ');"></div>';
												echo '</a>';
												// echo $thumbSrc[0];
											}

											echo $pageExcerpt;

											// Output file title/name
											echo "<p class='bbg__kits__section--tile__downloads'><a href='" . $fileURL . "' target='_blank'>" . $fileName ."</a> <span class='bbg__file-size'>(" . $fileExt . ", " . $fileSize . ")</span></p>";
										echo '</article>';
									}
								}
							echo '</div>';
						/*** END DISPLAY OF DOWNLOAD LINKS ROW ***/

						elseif ( get_row_layout() == 'kits_recent_awards' ) :
							$counter = 0;

							// loop through all the awards
							foreach ( $awards as $a ) {
								$counter++;
								$styleStr = '';

								// add margin on the right for the fist left-hand column
								if ( $counter == 1 ) {
									$styleStr = " style='margin-right:2.35765%; '";
								}

								// populate all awards content variables
								$id = $a['id'];
								$url = $a['url'];
								$title = $a['title'];
								$awardYears = $a['awardYears'];
								$awardTitle = $a['awardTitle'];
								$awardCategoryLink = get_category_link( $awardCategoryObj -> term_id );

								// output HTML
								$s = '<div class="usa-section usa-grid-full bbg__kits__section">';
									$s .= '<section class="usa-grid-full bbg__kits__section--row">';

										// left column content
										$s .= '<div ' . $styleStr . ' class="bbg-grid--1-2-2 usa-width-one-half bbg__post-excerpt bbg__award__excerpt">';
											$s .= '<h2 class="entry-title">Recent Awards</h2>';
											$s .= '<h3 class="bbg__award-excerpt__title"><a href="' . $url . '">' . $title . '</a></h3>';
											$s .= '<h4>' . join( $awardYears ) . ' ' . join( $organizations ) . '</h4>';
											// $s .= '<p class="bbg__award-excerpt__org">' . $awardTitle . '</p>';
											$s .= "<a href='$awardCategoryLink'class='bbg__kits__intro__more--link'>View all awards »</a>";
										$s .= '</div>';
							}

							// create object variable for right-hand column focus page
							$focusPageObj = get_sub_field('kits_recent_awards_focus_page');
								// populate page content variables from object variable
								$focusPageTitle = get_the_title( $focusPageObj -> ID );
								$focusPageURL = get_the_permalink( $focusPageObj -> ID );
								$focusPageExcerpt = my_excerpt( $focusPageObj -> ID );
								$focusPageExcerpt = apply_filters( 'the_content', $focusPageExcerpt );
								$focusPageExcerpt = str_replace( ']]>', ']]&gt;', $focusPageExcerpt );

										// right column content
										$s .= '<div class="bbg-grid--1-2-2 usa-width-one-half bbg__post-excerpt bbg__award__excerpt">';
											$s .= '<h2 class="entry-title">' . $focusPageTitle . '</h2>';
											$s .= '<p>' . $focusPageExcerpt . '</p>';
											$s .= '<a href="' . $focusPageURL . '" class="bbg__kits__intro__more--link">Read more »</a>';
										$s .= '</div>';
									$s .= '</section>';
								$s .= '</div>';
							echo $s;
						endif;
						echo '</section>';
					endwhile;
					echo '<!-- END ROWS -->';
				endif;
				?>
				<!-- END 2B **** Flexible content rows -->

			</div> <!-- End id="page-sections" -->

<?php get_footer(); ?>
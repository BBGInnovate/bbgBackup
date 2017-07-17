<?php
/**
 * The template for displaying Author bios.
 *
 * @package bbgRedesign
 */
?>

<?php

	$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );

	/**** BEGIN PREPARING AUTHOR vars ****/
	$theAuthorID = $curauth -> ID;
	$authorName = $curauth -> display_name;
	$addSeparator = FALSE;

	$authorEmail = "";
	if ( $curauth -> isActive == "on" ) {
		//$authorEmail = '<a href="mailto:'. $curauth->user_email .'">'. $curauth->user_email .'</a>';
		// $addSeparator = TRUE;
	}

	$website = "";
	if ( isset( $curauth -> user_url ) ) {
		$website = $curauth -> user_url;
	}

	// Set author profile photo
	$avatar = get_avatar( $theAuthorID , apply_filters( 'change_avatar_css', 100 ) );

	$m = get_user_meta( $theAuthorID );

	// Set author profile page
	$profilePageURL = "";
	if ( isset( $m['author_profile_page'] ) ) {
		$profilePageURL = esc_url( get_page_link( $m['author_profile_page'][0] ) );
	}

	// Set author Twitter handle
	$twitterHandle = "";
	$twitterLink = "";
	if ( isset( $m['twitterHandle'] ) ) {
		$twitterHandle = $m['twitterHandle'][0];
	}

	// Set author occupation
	$occupation = "";
	if ( isset( $m['occupation'] ) ) {
		$occupation = $m['occupation'][0];
	}

	// Set author short bio
	// Now hidden for new template (July 2017/GF)
	/*$description = "";
	if ( isset( $m['description'] ) ) {
		$description = $m['description'][0];
	}*/
	/**** DONE PREPARING AUTHOR vars ****/
?>

<div class="usa-section">
	<div class="usa-grid clearAll bbg__page-header" >

		<header class="page-header usa-width-two-thirds">
			<!-- Display author profile photo -->
			<div class="bbg__avatar__container bbg__team__icon">
				<?php
					echo '<a href="' . $profilePageURL . '">' . $avatar . '</a>';
				?>
			</div>

			<div class="bbg__staff__author__text">
				<!-- Author's full name -->
				<?php
					echo '<h1 class="bbg__staff__author-name"><a href="' . $profilePageURL . '">' . $authorName . '</a></h1>';
				?>

				<div class="bbg__staff__author-description">
					<?php
						// Author's occupation
						echo '<div class="bbg__staff__author-occupation">' . $occupation . '</div>';

						// Author's Twitter handle
						if ( $twitterHandle && $twitterHandle != '' ) {
							$twitterHandle = str_replace( "@", "", $twitterHandle );
							$twitterLink = '<a href="//www.twitter.com/' . $twitterHandle . '"><i class="fa fa-twitter"></i> @' . $twitterHandle . '</a> ';

							if ( $addSeparator ) {
								$twitterLink = '<span class="u--seperator"></span> ' . $twitterLink;
							}
						}

						// Author's URL
						/*if ( $website && $website != '' ) {
							echo '<span class="u--seperator"></span> <a href="' . $website . '">' . $website . '</a>';
						}*/
					?>

					<!-- Author's email and Twitter handle -->
					<div class="bbg__staff__author-contact">
						<?php echo $authorEmail . $twitterLink; ?>
					</div>

					<!-- Author's short bio -->
					<!-- <div class="bbg__staff__author-bio">
						<?php echo $description; ?>
					</div> -->

				</div><!-- .author-description -->
			</div><!-- .bbg__staff__author-text -->
		</header><!-- .bbg__page-header -->
	</div><!-- .usa-grid -->
</div>
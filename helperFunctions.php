<?php 
	function outputUser($user, $mode, $postCounts) {
		//$authorPath = site_url() .'/blog/author/' . esc_html( $user->user_nicename );
		$authorPath=get_author_posts_url($user->ID);
		$authorName = esc_html( $user->display_name );
		$authorOccupation = esc_html( $user->occupation );
		$authorEmail = esc_html( $user->user_email );
		$twitterHandle = esc_html( $user->twitterHandle );
		$authorDescription = esc_html( $user->description );
		$theauthorid = esc_html( $user->ID );
		$twitterLink = "";


		$authorEmailLink = '';
		$addSeparator = FALSE;

		//Disabling the website url for now
		//$website = esc_html( $user->user_url );

		$count = 0;
		$number_of_posts = 3;

		if ( $user->isActive=="on" ) {

	?>
	<div <?php post_class("bbg-grid--1-2-3 bbg-staff__author "); ?>>

		<?php 
			if ($mode=="home") { 
				//Not currently using this on the homepage.
			} elseif ($mode=="staff") { 
		?>


		<div class="bbg-avatar__container--small">
			<?php if ($mode != "staff" || $postCounts[$user->ID] > 0): ?>
				<a href="<?php echo $authorPath ?>">
					<?php echo get_avatar( $user->user_email , apply_filters( 'change_avatar_css', 150) ); ?>
				</a>
			<?php else: 
				echo get_avatar( $user->user_email , apply_filters( 'change_avatar_css', 150) );
				endif;
			?>
		</div>

		<div class="bbg-staff__author__text">
			
			<h3 class="bbg-staff__author-name">
				<?php if ($mode != "staff" || $postCounts[$user->ID] > 0): ?>
					<a href="<?php echo $authorPath ?>" class="bbg-staff__author-link"><?php echo $authorName; ?></a>
				<?php else: 
					echo $authorName;
					endif; 
				?>
			</h3>
			
			<?php if ( $authorOccupation!="" ) { ?>
				<div class="bbg-staff__author-occupation"><?php echo $authorOccupation; ?></div>
			<?php } ?>


				<?php 
					if ( $twitterHandle && $twitterHandle != '' ) {
						$twitterHandle = str_replace("@", "", $twitterHandle);
						$twitterLink = '<a href="//www.twitter.com/' . $twitterHandle. '" class="bbg-staff__author__contact-link twitter">@' . $twitterHandle . '</a> ';

						if ( $addSeparator ) {
							$twitterLink = '<span class="u--seperator"></span> ' . $twitterLink;
						}
					}
					echo '<div class="bbg-staff__author-contact">' . $authorEmailLink . $twitterLink . '</div>';
				?>
				<div class="bbg-staff__author-description">
					<div class="bbg-staff__author-bio">
						<?php echo $authorDescription; ?>
					</div>
					<div class='clearAll'></div>
				</div>

				<?php
					}
				?>

			<!-- .author-description -->
		</div><!-- .bbg-author-text -->
	</div>
<?php
		}
	}
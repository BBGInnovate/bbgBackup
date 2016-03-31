<?php
/**
 * The Template for displaying the staff.
 *
 * @package bbgRedesign
 * template name: Staff
 */


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
		$teamLeader = $user->headOfTeam;


		$authorEmailLink = '';
		$addSeparator = FALSE;
		if ( isset($teamLeader) && $teamLeader!="" ){
			//$authorEmail = '<a href="mailto:'. $curauth->user_email .'">'. $curauth->user_email .'</a>';
			$authorEmailLink = '<a href="mailto:' . $authorEmail . '" class="bbg-staff__author__contact-link email">'.$authorEmail . '</a>';

			$addSeparator = TRUE;
		}

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

get_header();

?>
<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="content" class="site-content" role="main">
			<section class="usa-section usa-grid-full bbg-staff__roster">
				<header class="bbg-page__header usa-grid">
					<h1 class="bbg-page__header-title">STAFF</h1>
					<h3 class="bbg-page__header-description">ODDI’s designers, developers and storytellers help drive USIM digital projects.</h3>
				</header>
				<div class="usa-grid">
					<?php
						$blogusers = get_users();
						$ids=array();
						foreach($blogusers as $user) {
							array_push($ids,$user->ID);
						}
						$postCounts=count_many_users_posts($ids);
						// Loop through the users to create the staff profiles
						foreach ( $blogusers as $user ) {
							outputUser($user,"staff",$postCounts);
						}
					?>
				</div><!-- .usa-grid -->
			</section>
		</main>
	</div><!-- #primary .content-area -->

	<div id="secondary" class="widget-area" role="complementary"></div><!-- #secondary .widget-area -->

</div><!-- #main .site-main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

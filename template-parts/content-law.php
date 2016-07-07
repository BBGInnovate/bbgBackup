<?php
/**
 * Template part for displaying laws in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */


//Experimenting with adding the social share code to Pages
//the title/headline field, followed by the URL and the author's twitter handle
$twitterText = "";
$twitterText .= html_entity_decode( get_the_title() );
$twitterHandle = get_the_author_meta( 'twitterHandle' );
$twitterHandle = str_replace( "@", "", $twitterHandle );
if ( $twitterHandle && $twitterHandle != '' ) {
	$twitterText .= " by @" . $twitterHandle;
} else {
	$authorDisplayName = get_the_author();
	if ( $authorDisplayName && $authorDisplayName!='' ) {
		$twitterText .= " by " . $authorDisplayName;
	}
}
$twitterText .= " " . get_permalink();
$hashtags="";
//$hashtags="testhashtag1,testhashtag2";

///$twitterURL="//twitter.com/intent/tweet?url=" . urlencode(get_permalink()) . "&text=" . urlencode($ogDescription) . "&hashtags=" . urlencode($hashtags);
$twitterURL="//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
$fbUrl="//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );


include get_template_directory() . "/inc/shared_sidebar.php";

<article id="post-<?php the_ID(); ?>">
	<div class="usa-grid">
		<?php echo bbginnovate_post_categories(); ?>

		<!-- .bbg__label -->
		<header class="entry-header">
			<?php if($post->post_parent) {
				//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
				$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
				$parent_link = get_permalink($post->post_parent);
				?>
				<h5 class="bbg__label"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>
			<?php } ?>

			<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
		</header><!-- .entry-header -->


		<div class="usa-grid-full">
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
			</div><!-- .bbg__article-sidebar--left -->

			<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">

				<?php
					$pageHeadline = get_field('headline');

					if ( $pageHeadline ) {
						echo "<h2 class='act-title'>" . $pageHeadline . "</h2>";
					}
					echo "<section class='usa-grid-full'>";
						the_content();
					echo "</section>";


				?>
			</div><!-- .entry-content -->
			<?php
			// Right sidebar
			echo "<div class='bbg__article-sidebar'>";
				echo "<!-- Sidebar content -->";
					if ( $includeSidebar && $sidebarTitle != "" ) {
						echo $sidebar;
					}
			echo "</div><!-- .bbg__article-sidebar -->";
			?>
		</div>

		<footer class="entry-footer bbg-post-footer 1234">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'bbginnovate' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	</div><!-- .usa-grid -->
</article><!-- #post-## -->

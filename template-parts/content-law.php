<?php
/**
 * Template part for displaying laws in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

/*** Sidebar content ***/
	$includeSidebar = get_post_meta( get_the_ID(), 'sidebar_include', true );

	if ( $includeSidebar ) {
		// check if the flexible content field has rows of data
		$sidebar = "";
		$s = "";

		if ( have_rows('sidebar_items') ):
			$sidebarTitle = get_post_meta( get_the_ID(), 'sidebar_title', true );

			if ( $sidebarTitle != "" ) {
				$s = "<h5 class='bbg-label small bbg__sidebar__download__label'>" . $sidebarTitle ."</h5>";
			}

			while ( have_rows('sidebar_items') ) : the_row();

				if ( get_row_layout() == 'sidebar_download_file' ) {

					$sidebarDownloadTitle = get_sub_field( 'sidebar_download_title' );
					$sidebarDownloadThumbnail = get_sub_field( 'sidebar_download_thumbnail' );
					$sidebarDownloadLink = get_sub_field( 'sidebar_download_link' );
					$sidebarDownloadDescription = get_sub_field( 'sidebar_download_description', false);

					$sidebarImage = "";
					if ($sidebarDownloadThumbnail && $sidebarDownloadThumbnail != "") {
						$sidebarImage = "<img src='" . $sidebarDownloadThumbnail . "' class='bbg__sidebar__download__thumbnail' alt='Thumbnail image for download' />";
					}

					$sidebarDescription = "";
					if ($sidebarDownloadDescription && $sidebarDownloadDescription != "") {
						$sidebarDescription = "<p class='bbg__sidebar__download__description'>" . $sidebarDownloadDescription . "</p>";
					}

					$sidebarDownload = "";
					$sidebarDownload = "<a href='" . $sidebarDownloadLink . "'>" . $sidebarImage . "</a><h5 class='bbg__sidebar__download__title'><a href='" . $sidebarDownloadLink . "'>" . $sidebarDownloadTitle . "</a></h5>" . $sidebarDescription;

					$s .= "<div class='bbg__sidebar__download'>" . $sidebarDownload . "</div>";
				} else if (get_row_layout() == 'sidebar_quote'){

					$sidebarQuotationText = get_sub_field( 'sidebar_quotation_text', false);
					$sidebarQuotationSpeaker = get_sub_field( 'sidebar_quotation_speaker' );
					$sidebarQuotationSpeakerTitle = get_sub_field( 'sidebar_quotation_speaker_title' );

					$s .= '<div><h5>"' . $sidebarQuotationText . '"</h5><p>' . $sidebarQuotationSpeaker . ', ' . $sidebarQuotationSpeakerTitle ."</p></div>";
				}
			endwhile;

			$sidebar .= $s;
		endif;
	}

?>

<article id="post-<?php the_ID(); ?>">
	<div class="usa-grid">
		<?php echo bbginnovate_post_categories(); ?>

		<!-- .bbg-label -->
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="bbg__article-sidebar--left">
			<p></p>
		</div><!-- .bbg__article-sidebar--left -->

		<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">

			<?php
				$pageHeadline = get_field('headline');

				if ( $pageHeadline ) {
					echo "<h2 class='act-title'>" . $pageHeadline . "</h2>";
				}
				echo "<div class='usa-grid>'"
					// echo "<section class='usa-grid-full'>";
						the_content();
					// echo "</section>";

					// Right sidebar
					echo "<div class='bbg__article-sidebar'>";
					echo "<!-- Sidebar content -->";
						if ( $includeSidebar && $sidebarTitle != "" ) {
							echo $sidebar;
						}
					echo "</div><!-- .bbg__article-sidebar -->";
				echo "</div>";
			?>
		</div><!-- .entry-content -->

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

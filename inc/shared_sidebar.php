<?php
/**
 * Include sidebar
 * show single downloads, quotations, external links, internal links, & photos
 * @var [boolean]
 */
$includeSidebar = get_post_meta( get_the_ID(), 'sidebar_include', true );
if ( $includeSidebar ) {

	// check if the flexible content field has rows of data
	$sidebar = "";
	$s = "";

	$sidebarTitle = get_post_meta( get_the_ID(), 'sidebar_title', true );
	if ( $sidebarTitle != "" ) {
		$s .= "<h5 class='bbg__label small bbg__sidebar__download__label'>" . $sidebarTitle ."</h5>";
	}

	$sidebarDescription = get_post_meta( get_the_ID(), 'sidebar_description', true );
	if ( $sidebarDescription != "" ) {
		$sidebarDescription = apply_filters('the_content', $sidebarDescription);
		$sidebarDescription = str_replace(']]>', ']]&gt;', $sidebarDescription);
		$s .= $sidebarDescription;
	}

	if ( have_rows('sidebar_items') ):
		while ( have_rows('sidebar_items') ) : the_row();
			if ( get_row_layout() == 'sidebar_download_file' ) {
			/* START DOWNLOAD FILES */
				$sidebarDownloadTitle = get_sub_field( 'sidebar_download_title' );
				$sidebarDownloadThumbnail = get_sub_field( 'sidebar_download_thumbnail' );
				$sidebarDownloadLinkObj = get_sub_field( 'sidebar_download_link' );
				$sidebarDownloadDescription = get_sub_field( 'sidebar_download_description', false);

				$fileID = $sidebarDownloadLinkObj['ID'];
				$sidebarDownloadLink = $sidebarDownloadLinkObj['url'];
				$file = get_attached_file( $fileID );
				$ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
				$filesize = formatBytes(filesize($file));

				$sidebarImage = "";
				if ($sidebarDownloadThumbnail && $sidebarDownloadThumbnail != "") {
					$sidebarImage = "<img src='" . $sidebarDownloadThumbnail . "' class='bbg__sidebar__download__thumbnail' alt='Thumbnail image for download' />";
				}

				$sidebarDescription = "";
				if ($sidebarDownloadDescription && $sidebarDownloadDescription != ""){
					$sidebarDescription = "<p class='bbg__sidebar__download__description'>" . $sidebarDownloadDescription . "</p>";
				}

				$sidebarDownload = "";
				$sidebarDownload = "<a href='" . $sidebarDownloadLink . "'>" . $sidebarImage . "</a><h5 class='bbg__sidebar__download__title'><a href='" . $sidebarDownloadLink . "'>" . $sidebarDownloadTitle . "</a> <span class='bbg__file-size'>($ext, $filesize)</span></h5>" . $sidebarDescription;

				$s .= "<div class='bbg__sidebar__download'>" . $sidebarDownload . "</div>";
			/* END DOWNLOAD FILES */
			} else if (get_row_layout() == 'sidebar_quote'){
			/* START QUOTATIONS */
				$sidebarQuotationText = get_sub_field( 'sidebar_quotation_text', false);
				$sidebarQuotationSpeaker = get_sub_field( 'sidebar_quotation_speaker' );
				$sidebarQuotationSpeakerTitle = get_sub_field( 'sidebar_quotation_speaker_title' );

				$s .= '<div class="bbg__quotation"><h5 class="bbg__quotation-text--large">“' . $sidebarQuotationText . '”</h5><p class="bbg__quotation-attribution__text"><span class="bbg__quotation-attribution__name">' . $sidebarQuotationSpeaker . ',</span><span class="bbg__quotation-attribution__credit"> ' . $sidebarQuotationSpeakerTitle ."</span></p></div>";
			/* END QUOTATIONS */
			} else if (get_row_layout() == 'sidebar_external_link'){
			/* START EXTERNAL LINKS */
				$sidebarLinkTitle = get_sub_field( 'sidebar_link_title', false);
				$sidebarLinkLink = get_sub_field( 'sidebar_link_link' );
				$sidebarLinkImage = get_sub_field( 'sidebar_link_image' );
				$sidebarLinkDescription = get_sub_field( 'sidebar_link_description', false);

				$sidebarDescription = "";
				if ($sidebarLinkDescription && $sidebarLinkDescription != ""){
					$sidebarDescription = "<p>" . $sidebarLinkDescription . "</p>";
				}

				$sidebarImage = "";
				if ($sidebarLinkImage && $sidebarLinkImage != ""){
					$sidebarImage = '<a href="' . $sidebarLinkLink . '"><img class="bbg__sidebar__primary-image" src="' . $sidebarLinkImage . '"/></a>';
				}

				$s .= '<div>' . $sidebarImage . '<h5 class="bbg__sidebar__primary-headline"><a href="' . $sidebarLinkLink . '">' . $sidebarLinkTitle . '</a></h5>' . $sidebarDescription . '</div>';
			/* END EXTERNAL LINKS */
			} else if (get_row_layout() == 'sidebar_internal_link') {
			/* START INTERNAL LINKS */
				$sidebarInternalTitle = get_sub_field( 'sidebar_internal_title', false);
				$sidebarInternalLocation = get_sub_field( 'sidebar_internal_location' );
				$sidebarInternalDescription = get_sub_field( 'sidebar_internal_description', false);

				// get data out of WP object
				$url = get_permalink( $sidebarInternalLocation->ID ); // Use WP object ID to get permalink for link
				$title = $sidebarInternalLocation->post_title; // WP object title

				$sidebarSectionTitle = "";
				// Set text for the internal link
				if ($sidebarInternalTitle && $sidebarInternalTitle != "") {
					// User-defined title
					$sidebarSectionTitle = "<p>" . $sidebarInternalTitle . "</p>";
				} else {
					// WP object title (set above)
					$sidebarSectionTitle = "<p>" . $title . "</p>";
				}

				$sidebarDescription = "";
				// Set text for description beneath link
				if ($sidebarInternalDescription && $sidebarInternalDescription != "") {
					// User-defined description
					$sidebarDescription = "<p>" . $sidebarInternalDescription . "</p>";
				}

				$s .= '<div><h5 class="bbg__sidebar__primary-headline"><a href="' . $url . '">' . $sidebarSectionTitle . '</a></h5>' . $sidebarDescription . '</div>';
			/* END INTERNAL LINKS */
			} else if (get_row_layout() == 'sidebar_photo'){
			/* START PHOTOS */
				$sidebarPhotoImage = get_sub_field( 'sidebar_photo_image' );
				$sidebarPhotoTitle = get_sub_field( 'sidebar_photo_title', false);
				$sidebarPhotoCaption = get_sub_field( 'sidebar_photo_caption', false);

				$sidebarImage = "";
				if ($sidebarPhotoImage && $sidebarPhotoImage != ""){
					$sidebarPhotoImageSrc = $sidebarPhotoImage['sizes']['medium'];
					$sidebarImage = '<img class="" src="' . $sidebarPhotoImageSrc . '"/>';
				}
				/*
				helpful for debugging
				var_dump($sidebarPhotoImage);
				foreach ($sidebarPhotoImage as $key=>$value) {
					echo "$key -> $value<BR>";
					if ($key == 'sizes') {
						var_dump($value);
					}
				}
				var_dump($sidebarPhotoImage['sizes']);
				*/

				$sidebarImageTitle = "";
				if ($sidebarPhotoTitle && $sidebarPhotoTitle != ""){
					$sidebarImageTitle = "<h5>" . $sidebarPhotoTitle . "</h5>";
				}

				$sidebarDescription = "";
				if ($sidebarPhotoCaption && $sidebarPhotoCaption != ""){
					$sidebarDescription = "<p>" . $sidebarPhotoCaption . "</p>";
				}

				$s .= '<div>' . $sidebarImage . $sidebarImageTitle . $sidebarDescription . '</div>';
			/* END PHOTOS */
			}
		endwhile;
		// Add all content types to the sidebar variable
		$sidebar .= $s;
	endif;
}

/**
 * Sidebar drop-down for multiple downloads (2-col pages)
 * @var [boolean]
 */
$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

if ( $listsInclude ) {

	$dropdownTitle = get_field( 'sidebar_dropdown_title' );

	if ( have_rows('sidebar_dropdown_content') ) {
		$s = "<h5 class='bbg__label small bbg__sidebar__download__label'>" . $dropdownTitle ."</h5>";

		while ( have_rows('sidebar_dropdown_content') ) : the_row();

			if ( get_row_layout() == 'file_downloads' ){
				$s .= "<div class='bbg__sidebar__download'>";
					$sidebarDownloadsTitle = get_sub_field( 'sidebar_downloads_title' );
					$sidebarDownloadsDefault = get_sub_field( 'sidebar_downloads_default' );
					$sidebarDownloadsRows = get_sub_field( 'sidebar_downloads' );
					$sidebarDownloadsTotal = count( $sidebarDownloadsRows );

					// $s .= "<h5 class='bbg_label'>" . $sidebarDownloadsTitle . "</h5>" ;

					if ( $sidebarDownloadsTotal >= 4 ) {
						$s .= '<form>';
							$s .= '<label for="options" style="display: inline-block; font-size: 2rem; font-weight: bold; margin-top: 0;">' . $sidebarDownloadsTitle . '</label>';
							$s .= '<select name="file_download_list" id="file_download_list" style="display: inline-block;">';
								$s .= '<option>' . $sidebarDownloadsDefault . '</option>';

								foreach( $sidebarDownloadsRows as $row ) {
									// Download option name
									$sidebarDownloadsLinkName = $row['sidebar_download_title'];
									// Download file info
									$sidebarDownloadsLinkObj = $row['sidebar_download_file'];
										// file details
										$fileLink = $sidebarDownloadsLinkObj['url'];
										$fileID = $sidebarDownloadsLinkObj['ID'];
										$file = get_attached_file( $fileID );
										$ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
										$size = formatBytes(filesize($file));

										if ( $sidebarDownloadsLinkName == "" | !$sidebarDownloadsLinkName ) {
											$name = $sidebarDownloadsLinkObj['title'];
											$sidebarDownloadsLinkName = $name;
										}

									$s .= '<option value="' . $fileLink . '">' . $sidebarDownloadsLinkName . ' <span class="bbg__file-size">(' . $ext . ', ' . $size . ')</span>' . '</option>';
								}

							$s .= '</select>';
						$s .= '</form>';

						$s .= '<button class="usa-button" id="downloadFile" style="width: 100%;">Download</button>';
					} else {
						$sidebarDownloadsTitle = get_sub_field( 'sidebar_download_title' );
						$sidebarDownloadsRows = get_sub_field( 'sidebar_downloads' );

						$s .= "<ul class='bbg__article-sidebar__list--labeled'>";
							foreach( $sidebarDownloadsRows as $row ) {
								// Download option name
								$sidebarDownloadsLinkName = $row['sidebar_download_title'];
								// Download file info
								$sidebarDownloadsLinkObj = $row['sidebar_download_file'];
									// file details
									$fileLink = $sidebarDownloadsLinkObj['url'];
									$fileID = $sidebarDownloadsLinkObj['ID'];
									$file = get_attached_file( $fileID );
									$ext = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
									$size = formatBytes(filesize($file));

									if ( $sidebarDownloadsLinkName == "" | !$sidebarDownloadsLinkName ) {
										$name = $sidebarDownloadsLinkObj['title'];
										$sidebarDownloadsLinkName = $name;
									}

								$s .= "<li><h5 class='bbg__sidebar__download__title'><a href='" . $fileLink . "'>" . $sidebarDownloadsLinkName . "</a> <span class='bbg__file-size'>(" . $ext . ", " . $size . ")</span>" . "</h5></li>";
							}
						$s .= "</ul>";
					}
				$s .= "</div>";
			} elseif ( get_row_layout() == 'sidebar_dropdown_internal_links' ) {
				$s .= "<div class='bbg__sidebar__download'>";
					$sidebarInternalTitle = get_sub_field( 'sidebar_internal_title' );
					$sidebarInternalDefault = get_sub_field( 'sidebar_internal_default' );
					$sidebarInternalRows = get_sub_field( 'sidebar_internal_objects' );

					$s .= "<h5 class='bbg_label'>" . $sidebarInternalTitle . "</h5>" ;

					$s .= "<ul class='bbg__article-sidebar__list--labeled'>";
						foreach( $sidebarInternalRows as $link ) {
							// Internal link option name
							$sidebarInternalLinkName = $link['internal_links_title'];
							// Internal WP object
							$sidebarInternalLinkObj = $link['internal_links_url'];
								// link details
								$url = get_permalink( $sidebarInternalLinkObj->ID ); // Use WP object ID to get permalink for link

								if ( $sidebarInternalLinkName == "" | !$sidebarInternalLinkName ) {
									$title = $sidebarInternalLinkObj->post_title; // WP object title
									$sidebarInternalLinkName = $title;
								}

							$s .= "<li><h5 class='bbg__sidebar__download__title'><a href='" . $url . "'>" . $sidebarInternalLinkName . "</a></h5></li>";
						}
					$s .= "</ul>";
				$s .= "</div>";
			}
		endwhile;
		// Add all content types to the sidebar variable
		$sidebarDownloads = $s;
	}

// echo $sidebar;

}

?>
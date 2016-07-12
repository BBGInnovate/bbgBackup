<?php
//Include sidebar of single downloads, links, photos, quotations
$includeSidebar = get_post_meta( get_the_ID(), 'sidebar_include', true );
if ( $includeSidebar ) {

	// check if the flexible content field has rows of data
	$sidebar = "";
	$s = "";

	if( have_rows('sidebar_items') ):

		$sidebarTitle = get_post_meta( get_the_ID(), 'sidebar_title', true );
		if ($sidebarTitle != ""){
			$s = "<h5 class='bbg__label small bbg__sidebar__download__label'>" . $sidebarTitle ."</h5>";
		}

		$sidebarContent = get_post_meta( get_the_ID(), 'sidebar_description', true );
		if ( $sidebarContent != "" ) {
			$sidebarContent = apply_filters('the_content', $sidebarContent);
   			$sidebarContent = str_replace(']]>', ']]&gt;', $sidebarContent);
			$s .= $sidebarContent;
		}

		while ( have_rows('sidebar_items') ) : the_row();

			if ( get_row_layout() == 'sidebar_download_file' ){

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
				$sidebarDownload = "<a href='" . $sidebarDownloadLink . "'>" . $sidebarImage . "</a><h5 class='bbg__sidebar__download__title'><a href='" . $sidebarDownloadLink . "'>" . $sidebarDownloadTitle . "</a>  ($ext, $filesize) </h5>" . $sidebarDescription;

				$s .= "<div class='bbg__sidebar__download'>" . $sidebarDownload . "</div>";

			} else if (get_row_layout() == 'sidebar_quote'){

				$sidebarQuotationText = get_sub_field( 'sidebar_quotation_text', false);
				$sidebarQuotationSpeaker = get_sub_field( 'sidebar_quotation_speaker' );
				$sidebarQuotationSpeakerTitle = get_sub_field( 'sidebar_quotation_speaker_title' );

				$s .= '<div class="bbg__quotation"><h5 class="bbg__quotation-text--large">“' . $sidebarQuotationText . '”</h5><p class="bbg__quotation-attribution__text"><span class="bbg__quotation-attribution__name">' . $sidebarQuotationSpeaker . ',</span><span class="bbg__quotation-attribution__credit"> ' . $sidebarQuotationSpeakerTitle ."</span></p></div>";

			} else if (get_row_layout() == 'sidebar_external_link'){

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

			} else if (get_row_layout() == 'sidebar_internal_link') {

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
			} else if (get_row_layout() == 'sidebar_photo'){

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
					$sidebarImageTitle = "<h5 class=''>" . $sidebarPhotoTitle . "</h5>";
				}

				$sidebarDescription = "";
				if ($sidebarPhotoCaption && $sidebarPhotoCaption != ""){
					$sidebarDescription = "<p class=''>" . $sidebarPhotoCaption . "</p>";
				}

				$s .= '<div class="">' . $sidebarImage . $sidebarImageTitle . $sidebarDescription . '</div>';
			}
		endwhile;

		$sidebar .= $s;
	endif;
}

// Sidebar drop-down for multiple downloads
$sidebarInclude = get_field( 'sidebar_downloads_include', '', true);
$sidebarDownloads = "";
if( $sidebarInclude ) {
	$downloadsTitle = get_field( 'sidebar_downloads_title' );
	$optionDefault = get_field ( 'sidebar_downloads_default' );
	$rows = get_field( 'sidebar_downloads' );
	if ( $rows ) {
		$s = '<form style="">';
		$s .= '<label for="options" style="display: inline-block; font-size: 2rem; font-weight: bold; margin-top: 0;">' . $downloadsTitle . '</label>';
		$s .= '<select name="file_download_list" id="file_download_list" style="display: inline-block;">';
		$s .= '<option>' . $optionDefault . '</option>';

		foreach( $rows as $row ) {
			$s .= '<option value="' . $row['sidebar_download_file'] .'">' . $row["sidebar_download_title"] . '</option>';
		}

		$s .= '</select>';
		$s .= '</form>';

		$s .= '<button class="usa-button" id="downloadFile" style="width: 100%;">Download</button>';
		$sidebarDownloads = $s;
	}
}
?>
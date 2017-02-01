<?php
/**
 * Include sidebar
 * show single downloads, quotations, external links, internal links, & photos
 * @var [boolean]
 */
$includeSidebar = get_post_meta( get_the_ID(), 'sidebar_include', true );
$sidebar = "";
$sidebarDownloads = "";
if ( $includeSidebar ) {

	// check if the flexible content field has rows of data

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

					$sidebarImageSrc = $sidebarLinkImage['sizes']['medium'];
					$sidebarImage = '<a target="blank" href="' . $sidebarLinkLink . '"><img class="bbg__sidebar__primary-image" src="' . $sidebarImageSrc . '"/></a>';
				}

				$s .= '<div>' . $sidebarImage . '<h5 class="bbg__sidebar__primary-headline"><a target="blank" href="' . $sidebarLinkLink . '">' . $sidebarLinkTitle . '</a></h5>' . $sidebarDescription . '</div>';
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
					$sidebarDescription = "<p class='bbg__sidebar__photo-caption'>" . $sidebarPhotoCaption . "</p>";
				}

				$s .= '<div>' . $sidebarImage . $sidebarImageTitle . $sidebarDescription . '</div>';
			/* END PHOTOS */
			} else if (get_row_layout() == 'sidebar_accordion'){
				$s = "";
				$accordionTitle = get_sub_field('sidebar_accordion_title');
				if ($accordionTitle != "") {
					$s .= "<h5 class='bbg__label small bbg__sidebar__download__label'>$accordionTitle</h5>";
				}
				if( have_rows('sidebar_accordion_items') ):
					$s .= '<style>
					div.usa-accordion-content {
						padding:1.5rem !important;
					}
					</style>';
					
					$s .= '<div class="usa-accordion bbg__committee-list"><ul class="usa-unstyled-list">';
					$i = 0;
					while ( have_rows('sidebar_accordion_items') ) : the_row();
						$i++;
						$itemLabel = get_sub_field('sidebar_accordion_item_label');
						$itemText = get_sub_field('sidebar_accordion_item_text');
						$s .= '<li>';
						$s .= '<button class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-faq-' . $i . '">' . $itemLabel . '</button>';
						$s .= '<div id="collapsible-faq-' . $i . '" aria-hidden="true" class="usa-accordion-content">';
						$s .= $itemText;
						$s .= '</div>';
						$s .= '</li>';
						endwhile;
						$s .= '</ul></div>';  
				endif;
			} else if (get_row_layout() == 'sidebar_related_award'){
				$relatedPosts = get_sub_field('sidebar_related_award_post');
				if (is_array($relatedPosts) && count($relatedPosts) > 0) {
					$label = "About the Award";
					if (count($relatedPosts) > 1) {
						$label .= "s";
					}

					$s .= '<h5 class="bbg__label small bbg__sidebar__download__label">' . $label . '</h5>';
					$s .='<div class="bbg__sidebar__primary">';
					$counter =0;	
					foreach ($relatedPosts as $relatedPost) {
						$counter++;
						if ($counter > 1) {
							$s .= "<br />";
						}
						$s .= getAwardInfo($relatedPost -> ID, false);	
					}
					$s .=  '</div>';	
				}
			} else if (get_row_layout() == 'sidebar_twitter_widget'){
				//create widgets @ https://twitter.com/settings/widgets
				$widgetID = get_sub_field('sidebar_twitter_widget_id');
				//$widgetLabel = get_sub_field('sidebar_twitter_widget_label');

				$widgetHashtag = get_sub_field('sidebar_twitter_widget_hashtag');
				$widgetAuthor = get_sub_field('sidebar_twitter_widget_author');

				if ($widgetHashtag || $widgetAuthor) {
					if ($widgetAuthor) {
						$widgetLink = "https://twitter.com/$widgetAuthor";
						$widgetLinkLabel = '@' . $widgetAuthor;
					} else {
						$widgetLink = "https://twitter.com/hashtag/$widgetHashtag";
						$widgetLinkLabel = '#' . $widgetHashtag;
					}
					$s .= '<h5 class="bbg__label small bbg__sidebar__download__label">Follow on Twitter</h5>';
					$s .= '<ul class="bbg__article-share ">';
					$s .= '<li class="bbg__article-share__link twitter">';
					$s .= '<a href="' . $widgetLink . '" title="Follow on Twitter"><span class="bbg__article-share__icon twitter"></span><span class="">' . $widgetLinkLabel . '</span></a>';
					$s . = '</li>';
					$s .= '</ul>';

					if ($widgetAuthor) {
						$s .= '<a data-tweet-limit="2"  data-chrome="noheader nofooter noborders transparent noscrollbar" class="twitter-timeline" data-dnt="true" data-theme="light" href="https://twitter.com/' . $widgetAuthor . '">Tweets by ' . $widgetAuthor . '</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
					} else if ($widgetHashtag) {
						$s .= '<a data-chrome="noheader" class="twitter-timeline"  href="https://twitter.com/hashtag/' . $widgetHashtag . '" data-widget-id="' . $widgetID . '">#' . $widgetHashtag . ' Tweets</a>';
						$s .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?';
						$s .= "'http':'https'";
						$s .= ';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
					}
				}
			}
		endwhile;
		// Add all content types to the sidebar variable

	endif;
	$sidebar = $s;
}

/**
 * Sidebar drop-down for multiple downloads (2-col pages)
 * @var [boolean]
 */
$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

if ( $listsInclude ) {

	$dropdownTitle = get_field( 'sidebar_dropdown_title' );

	if ( have_rows('sidebar_dropdown_content') ) {

		$s = "";
		if ($dropdownTitle && $dropdownTitle != "") {
			$s = "<h5 class='bbg__label small bbg__sidebar__download__label'>" . $dropdownTitle ."</h5>";
		}


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

						$s .= '<button class="usa-button downloadFile" id="downloadFile" style="width: 100%;">Download</button>';
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
				if (count($sidebarInternalRows) < 5) {
					$s .= "<h5 class='bbg_label'>" . $sidebarInternalTitle . "</h5>" ;
					$s .= "<ul class='bbg__article-sidebar__list--labeled'>";
					foreach( $sidebarInternalRows as $link ) {
						$sidebarInternalLinkName = $link['internal_links_title'];
						$sidebarInternalLinkObj = $link['internal_links_url'];
						$url = get_permalink( $sidebarInternalLinkObj->ID );
						if ( $sidebarInternalLinkName == "" | !$sidebarInternalLinkName ) {
							$title = $sidebarInternalLinkObj->post_title; // WP object title
							$sidebarInternalLinkName = $title;
						}
						$s .= "<li><h5 class='bbg__sidebar__download__title'><a href='" . $url . "'>" . $sidebarInternalLinkName . "</a></h5></li>";
					}
					$s .= "</ul>";
				} else {
					$s .= '<form >';
					$s .= '<label for="options" style="display: inline-block; font-size: 2rem; font-weight: bold; margin-top: 0;">' . $sidebarInternalTitle . '</label>';
					$s .= '<select name="internal_links_list" class="internal_links_list" style="display: inline-block;">';
					$s .= '<option>Select a link</option>';
					foreach( $sidebarInternalRows as $link ) {
						$sidebarInternalLinkName = $link['internal_links_title'];
						$sidebarInternalLinkObj = $link['internal_links_url'];
						$url = get_permalink( $sidebarInternalLinkObj->ID );
						if ( $sidebarInternalLinkName == "" | !$sidebarInternalLinkName ) {
							$title = $sidebarInternalLinkObj->post_title; // WP object title
							$sidebarInternalLinkName = $title;
						}
						$s .= '<option value="' . $url . '">' . $sidebarInternalLinkName . '</option>';
					}
					$s .= '</select>';
					$s .= '</form>';
					$s .= '<button class="usa-button internalLink" style="width: 100%;">Go</button>';
				}
				$s .= "</div>";
			}
		endwhile;
		// Add all content types to the sidebar variable
		$sidebarDownloads = $s;
	}

// echo $sidebar;

}

?>
<?php
// Sidebar multiple downloads drop-down
/*$sidebarInclude = get_field( 'sidebar_downloads_include', '', true);
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
}*/
?>
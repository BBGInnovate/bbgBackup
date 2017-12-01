<?php

/** Basid function to get user’s role **/
function get_current_user_role() {
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$role = ( array ) $user -> roles;
		return $role[0];
	} else {
		return false;
	}
} // may be executed and echoed for confirmation

function hide_from_users() {
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$role = ( array ) $user -> roles;
		$userRole = $role[0];

		// var_dump( $userRole );

		if ( $userRole == "enhanced_contributor" ) {
			function remove_my_post_metaboxes() {
				remove_meta_box( 'authordiv','post','normal' ); // Author Metabox
				remove_meta_box( 'formatdiv','post','normal' ); // Format Metabox
				remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox
				remove_meta_box( 'commentstatusdiv','post','normal' ); // Comments Status Metabox
				remove_meta_box( 'commentsdiv','post','normal' ); // Comments Metabox
				remove_meta_box( 'postcustom','post','normal' ); // Custom Fields Metabox
				remove_meta_box( 'slugdiv','post','normal' ); // Slug Metabox
				remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback Metabox
				remove_meta_box( 'tagsdiv-{$organizations}','post','normal' ); // Trackback Metabox
				remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' ); // Slider Revolution Metabox
				// remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );  // Recent Drafts from Dashboard
			}
			add_action('admin_menu','remove_my_post_metaboxes');
		}
	} else {
		return false;
	}
}

hide_from_users();
?>
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

		var_dump( $userRole );

		if ( $userRole == "" ) {

		}
	} else {
		return false;
	}
}

hide_from_users();
?>
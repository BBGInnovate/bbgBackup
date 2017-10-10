<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bbgRedesign
 */
/* we go through the loop once and reset it in order to get some vars for our og tags */

$includeFile = "single-standard.php";


if ( in_category( 'Profile' ) ) {
	$includeFile = "single-profile.php";
} else if ( in_category( 'Board Meetings' ) || in_category( 'Event' ) ) {
	$includeFile = "single-meeting.php";
} else if ( is_singular( 'burke_candidate' ) ) { // find out if post is custom type
	$includeFile = "single-burke_candidate.php";
}

include( $includeFile );
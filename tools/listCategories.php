<?php 
	
	/*
    Author: Joe Flowers
    Description: I wrote this at some point to make it easy to list all of the categories we have on the site
	*/

	die();
	require ('../../../../wp-load.php');
	$term_query = new WP_Term_Query( array( 'taxonomy' => 'category' ) );
	if ( ! empty( $term_query->terms ) ) {
		foreach ( $term_query ->terms as $term ) {
			echo $term->name . "<BR>";
			echo $term->description . "<BR>";
			echo "<BR>";
			
		}
	}
?>
<?php 
	// Add shortcode reference for the BBG mission
	function impact_shortcode( $atts ) {
	    return "<p>Render impact shortcode here.</p>";
	}
	add_shortcode( 'impact', 'impact_shortcode' );
?>
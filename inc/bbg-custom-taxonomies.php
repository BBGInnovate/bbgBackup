<?php 
add_action( 'init', 'cptui_register_my_taxes' );
function cptui_register_my_taxes() {
	$labels = array(
		"name" => __( 'Organizations', 'bbgRedesign' ),
		"singular_name" => __( 'Organization', 'bbgRedesign' ),
		);

	$args = array(
		"label" => __( 'Organizations', 'bbgRedesign' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "Organizations",
		"show_ui" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'organizations', 'with_front' => true ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "organizations", array( "post", "awards" ), $args );

	$labels = array(
		"name" => __( 'Recipients', 'bbgRedesign' ),
		"singular_name" => __( 'Recipient', 'bbgRedesign' ),
		);

	$args = array(
		"label" => __( 'Recipients', 'bbgRedesign' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Recipients",
		"show_ui" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'recipients', 'with_front' => true ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "recipients", array( "post", "awards" ), $args );

// End cptui_register_my_taxes()
}
?>
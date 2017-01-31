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
		"show_ui" => false,
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


/************ BEGIN THREATS TO PRESS ************/

/************ Register the post type ************/
function register_custom_post_types() {
	$labels = array(
		'name'               => 'Threats to Press',
		'singular_name'      => 'Threat to Press',
		'menu_name'          => 'Threats to Press',
		'name_admin_bar'     => 'Threats to Press',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Threat to Press',
		'new_item'           => 'New Threat to Press',
		'edit_item'          => 'Edit Threat to Press',
		'view_item'          => 'View Recipe',
		'all_items'          => 'Threats to Press',
		'search_items'       => 'Search Threats to Press',
		'parent_item_colon'  => 'Parent Threats to Press:',
		'not_found'          => 'No Threats to Press found.',
		'not_found_in_trash' => 'No Threats to Press found in Trash.'
	);

	$args = array( 
		'labels'      => $labels,
		'public'                => false,
		'show_ui'	        => true,
		'show_in_admin_bar'     => true
		//,'menu_position' => 5
		//'show_in_menu' => 'edit.php',
	);
	register_post_type( 'threat_to_press', $args );
}
add_action( 'init', 'register_custom_post_types' );




/************ Add a few columns to the admin view ************/
add_filter('manage_threat_to_press_posts_columns', 'ttp_table_head');
function ttp_table_head( $defaults ) {
    $defaults['threats_to_press_country']  = 'Country';
    $defaults['threats_to_press_network']    = 'Network(s)';
    $defaults['threats_to_press_link']    = 'Link';
    return $defaults;
}


add_action( 'manage_threat_to_press_posts_custom_column', 'ttp_table_content', 10, 2 );
function ttp_table_content( $column_name, $post_id ) {
	if ($column_name == 'threats_to_press_country') {
		$country = get_field_object('threats_to_press_country', $post_id, true );
		echo $country['choices'][$country['value']];
	}
	if ($column_name == 'threats_to_press_target_names') {
		$targetName = get_post_meta( $post_id, 'threats_to_press_target_names', true );
		echo $targetName;
	}
	if ($column_name == 'threats_to_press_network') {
		//$network = get_post_meta( $post_id, 'threats_to_press_network', true );
		//echo implode(",", $network);
		$field = get_field_object('threats_to_press_network');
		$networks = $field['value'];
		if( $networks ) {
			$counter=0;
			foreach( $networks as $n ) {
				$counter++;
				if ($counter > 1) {
					echo ", ";
				}
				echo $field['choices'][ $n ];
			}
		}
	}
	if ($column_name == 'threats_to_press_link') {
		$link = get_post_meta( $post_id, 'threats_to_press_link', true );
		if ($link != "") {
			echo "<a href='$link' target='_blank'><span class='dashicons dashicons-admin-links'></span></a>";
		}
	}
}

/**** make the country sortable ****/
add_filter( 'manage_edit-threat_to_press_sortable_columns', 'sortableThreatsCols' );
function sortableThreatsCols( $sortable_columns ) {
   $sortable_columns[ 'threats_to_press_country' ] = 'threats_to_press_country';
   return $sortable_columns;
}
add_action( 'pre_get_posts', 'ttp_orderby' );
function ttp_orderby( $query ) {
    if( ! is_admin() ) {
        return;
    }
    $orderby = $query->get( 'orderby');
    if( 'threats_to_press_country' == $orderby ) {
        $query->set('meta_key','threats_to_press_country');
        $query->set('orderby','meta_value');
    }
}

?>
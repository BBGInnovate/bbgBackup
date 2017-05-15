<?php 

/************ Add a few columns to the admin view ************/
add_filter('manage_threat_to_press_posts_columns', 'ttp_table_head');
function ttp_table_head( $defaults ) {
    $defaults['threats_to_press_status']  = 'Status';
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
	if ($column_name == 'threats_to_press_status') {
		$status = get_field_object('threats_to_press_status', $post_id, true );
		echo $status['choices'][$status['value']];
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
  // $sortable_columns[ 'threats_to_press_status' ] = 'threats_to_press_status';
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
    if( 'threats_to_press_status' == $orderby ) {
        $query->set('meta_key','threats_to_press_status');
        $query->set('orderby','meta_value');
    }
}

?>
<?php 


function getNetworkExcerptJS() {
	/* used on map container */
	$entityParentPage = get_page_by_path('broadcasters');
	$qParams=array(
		'post_type' => array('page'),
		'posts_per_page' => -1,
		'post_parent' => $entityParentPage->ID
	);
	
	$e = array();
	$custom_query = new WP_Query($qParams);
	if ($custom_query -> have_posts()) {
		while ( $custom_query -> have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$fullName=get_post_meta( $id, 'entity_full_name', true );
			if ($fullName != "") {
				$abbreviation=strtolower(get_post_meta( $id, 'entity_abbreviation', true ));
				$abbreviation=str_replace("/", "",$abbreviation);
				$description=get_post_meta( $id, 'entity_description', true );
				$link=get_permalink( get_page_by_path( "/broadcasters/$abbreviation/" ) );
				$imgSrc=get_template_directory_uri().'/img/logo_'.$abbreviation.'--circle-200.png'; //need to fix this
				$e[$abbreviation] = array(
					'description' => $description,
					'fullName' => $fullName
				);
			}
		}
	}
	wp_reset_postdata();

	$s = "<script type='text/javascript'>\n";
	$s .= "entities=" . json_encode(new ArrayValue($e), JSON_PRETTY_PRINT, 10) . ";";
	$s .="</script>";
	
	return $s; 
}
	
function outputBroadcasters($cols) {
	$entityParentPage = get_page_by_path('broadcasters');
	$qParams=array(
		'post_type' => array('page'),
		'posts_per_page' => -1,
		'post_parent' => $entityParentPage->ID,
		'orderby' => 'meta_value_num',
		'meta_key' => 'entity_year_established',
		'order' => 'ASC'
	);
	$columnsClass = "";
	if ($cols == 2){
		$columnsClass = " bbg-grid--1-1-1-2";
	}

	$s = '';
	$s .= '<div class="usa-grid-full">';
	$custom_query = new WP_Query($qParams);
	if ($custom_query -> have_posts()) {
		while ( $custom_query -> have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$fullName=get_post_meta( $id, 'entity_full_name', true );
			if ($fullName != "") {
				$abbreviation=strtolower(get_post_meta( $id, 'entity_abbreviation', true ));
				$abbreviation=str_replace("/", "",$abbreviation);
				$description=get_post_meta( $id, 'entity_description', true );
				$link=get_permalink( get_page_by_path( "/broadcasters/$abbreviation/" ) );
				$imgSrc=get_template_directory_uri().'/img/logo_'.$abbreviation.'--circle-200.png'; //need to fix this

				$s .= '<article class="bbg__entity'. $columnsClass .'">';
				$s .=  '<div class="bbg-avatar__container bbg__entity__icon">';
				$s .=  '<a href="'.$link.'" tabindex="-1">';
				$s .=  '<div class="bbg-avatar bbg__entity__icon__image" style="background-image: url('.$imgSrc.');"></div>';
				$s .=  '</a></div>';
				$s .=  '<div class="bbg__entity__text">';
				$s .=  '<h2 class="bbg__entity__name"><a href="'.$link.'">'.$fullName.'</a></h2>';
				$s .=  '<p class="bbg__entity__text-description">'.$description.'</p>';
				$s .=  '</div>';
				$s .=  '</article>';
			}
		}
	}
	$s .= '</div>';
	wp_reset_postdata();
	return $s;
}

function broadcasters_list_shortcode($atts) {
	return outputBroadcasters($atts['cols']);
}
add_shortcode('broadcasters_list', 'broadcasters_list_shortcode');

?>
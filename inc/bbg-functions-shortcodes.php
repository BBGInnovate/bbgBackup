<?php 
	// Add shortcode reference for the BBG mission
	function mission_shortcode( $atts ) {
	    $a = shortcode_atts( array(
	        'org' => 'Broadcasting Board of Governors',
	    ), $atts );

	    return "<p>The mission of the {$a['org']} is to inform, engage, and connect people around the world in support of freedom and democracy.</p>";
	}

	add_shortcode( 'mission', 'mission_shortcode' );

	// Add shortcode reference for BBG-wide audience numbers
	function audience_shortcode( $atts ) {
		// access site-wide variables
		global $post;

	    // set variables based on custom fields
	    $total = get_field('site_setting_unduplicated_audience','options','false');
		$tv = get_field('site_setting_tv_audience','options','false');
		$radio = get_field('site_setting_radio_audience','options','false');
		$internet = get_field('site_setting_internet_audience','options','false');
		// set a default audience variable
		$selectedAudienceType = 'total';
		// change audience variable if passed from shortcode
		if (isset($atts['type'])) {
			$selectedAudienceType = $atts['type'];
		}
	    // set shortcode attributes to custom field values
	    $split = shortcode_atts( array(
	        'total' => '226',
	        'tv' => '142',
	        'radio' => '102',
	        'internet' => '32',
	    ), array(
	        'total' => $total,
	        'tv' => $tv,
	        'radio' => $radio,
	        'internet' => $internet,
	    ), 'audience' );

	    return $split[$selectedAudienceType] . " million";
	}

	add_shortcode( 'audience', 'audience_shortcode' );


	// Add shortcode reference for the number of supported languages
	function languages_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $number_of_languages = get_field('site_setting_total_languages','options','false');

		// $number_of_languages = 61;
		return $number_of_languages . " languages";
	}
	add_shortcode('languages', 'languages_shortcode');

	// Add shortcode reference for the number of total countries
	function countries_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $number_of_countries = get_field('site_setting_total_countries','options','false');

		//$number_of_countries = 100;
		return $number_of_countries . " countries";
	}
	add_shortcode('countries', 'countries_shortcode');


	function outputEmployeeProfiles($type) {
		$qParams=array(
			'post_type' => array('post')
			,'post_status' => array('publish')
			,'posts_per_page' => 6
			,'cat' => get_cat_id('Employee'),
		);
		$custom_query = new WP_Query($qParams);

		$epStr = '<section class="usa-section">';
		$epStr .= '<h5 class="bbg-label small">Employees</h5>';
		$epStr .= '<p class="" style="font-family: sans-serif;">This is a description that goes here and here.</p>';
		$epStr .= '<div class="usa-grid-full">';
		while ( $custom_query->have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$active=get_post_meta( $id, 'active', true );
			$e = "";
			if ($active){
				$occupation=get_post_meta( $id, 'occupation', true );
				$twitterProfileHandle=get_post_meta( $id, 'twitter_handle', true );
				$profilePhotoID=get_post_meta( $id, 'profile_photo', true );
				$profilePhoto = "";
				if ($profilePhotoID) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
					$profilePhoto = $profilePhoto[0];
				}
				$firstName=get_post_meta( $id, 'first_name', true );
				$lastName=get_post_meta( $id, 'last_name', true );
				$profileName = $firstName . " " . $lastName;
				$permalink=get_the_permalink();
				$e = '';
				$e .= '<div class="bbg__employee-profile__excerpt">';
				
				$e .= '<a href="'.$permalink.'"><img src="'.$profilePhoto.'"/></a>';
				$e .= '<h4><a href="'.$permalink.'">'.$profileName.'</h4>';
				$e .= '<h6>'.$occupation.'</h6>';
				$e .= '</div>';
				$epStr .= $e;
			}
		}

		$epStr .= '</div>';
		$epStr .= '</section>';
		return $epStr;
	}

	function employee_profile_list_shortcode() {
		return outputEmployeeProfiles();
	}
	add_shortcode('employee_profile_list', 'employee_profile_list_shortcode');

?>
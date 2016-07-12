<?php
	// Add shortcode reference for the BBG mission
	function mission_shortcode( $atts ) {
	    $a = shortcode_atts( array(
	        'org' => 'Broadcasting Board of Governors',
	    ), $atts );

	    return "<p>The mission of the {$a['org']} is to inform, engage, and connect people around the world in support of freedom and democracy.</p>";
	}

	add_shortcode( 'mission', 'mission_shortcode' );

	// Add shortcode reference for the "About the BBG"
	function about_shortcode( $atts ) {
		/*$a = shortcode_atts( array(
			'org' => 'Broadcasting Board of Governors',
		), $atts );*/

		$aboutBBG = get_field('site_setting_boilerplate_bbg','options','true');
		echo $aboutBBG;

		if ( $aboutBBG = "" ) {
			$aboutBBG = "The Broadcasting Board of Governors is an independent federal agency supervising all U.S. government-supported, civilian international media. Its mission is to inform, engage and connect people around the world in support of freedom and democracy. BBG networks include the Voice of America, Radio Free Europe/Radio Liberty, the Middle East Broadcasting Networks (Alhurra TV and Radio Sawa), Radio Free Asia, and the Office of Cuba Broadcasting (Radio and TV Martí). BBG programming has a measured audience of 226 million in more than 100 countries and in 61 languages.";
		}

		$about = "<h4>About the BBG</h4>";
			$about .= "<div class='bbg__tagline'>";
				$about .= $aboutBBG;
			$about .= "</div>";

	    return $about;
	}

	add_shortcode( 'about', 'about_shortcode' );

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




	// Add shortcode reference for BBG boilerplate
	/*
	function boilerplate_bbg_shortcode() {
		// access site-wide variables
		//global $post;

		// set variables based on custom fields
		$aboutBBG = get_field('site_setting_boilerplate_bbg','options','false');

		if ($aboutBBG) {
			$aboutBBG = "<h3>About the BBG</h3><p class='bbg__tagline'>" . $aboutBBG . "</p>";
		}

		// $number_of_languages = 61;
		return $aboutBBG;
	}
	add_shortcode('about-bbg', 'boilerplate_bbg_shortcode');
	*/



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

?>
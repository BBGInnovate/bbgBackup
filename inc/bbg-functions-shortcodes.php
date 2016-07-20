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

		// access site-wide variables
		global $post;

		$aboutBBG = get_field('site_setting_boilerplate_bbg','options','false');
		//echo $aboutBBG;

		if ( !$aboutBBG || $aboutBBG == "" ) {
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

	function appselector_shortcode($atts) {
		$formType = "news";
		if (isset($atts['type'])) {
			$formType = $atts['type'];
		}
		$s = "";
		if ($formType == 'news') {
			$s .= '<p>Find the version of the app that\'s best for you:<p>
					<form class="bbg__article__form--inline">
					<legend class="bbg__article-content__form-title"></legend>
					<fieldset id="appSelect-os" class="usa-fieldset-inputs usa-sans"><label class="bbg__article__form-radio__label" for="appSelect-os">Select your OS:</label>
					<div><input id="ios" name="os" type="radio" value="iOS" /><label for="ios">Apple iOS</label></div>
					<div><input id="android" name="os" type="radio" value="Android" /><label for="android">Android</label></div></fieldset>
					</form><form id="appSelect-smartphone-iOS" class="usa-form-large bbg__article__form style=">
					<fieldset>
					<div id="entities-iOS" class="usa-input-grid bbg__article__form-select--main"><label for="entity-iOS">Network</label><select name="entity">
					<option disabled="disabled" selected="selected" value="">Select a Network</option>
					</select></div>
					<input class="usa-input-grid-large" name="btnGoIOS" type="button" value="Go to download site »" /></fieldset>
					</form><form id="appSelect-smartphone-android" class="usa-form-large bbg__article__form style=">
					<fieldset>
					<div id="entities" class="usa-input-grid bbg__article__form-select--main"><label for="entity">Network</label><select id="entity" name="entity">
					<option disabled="disabled" selected="selected" value="">Select a Network</option>
					</select></div>
					<div id="stores" class="usa-input-grid bbg__article__form-select--main"><label for="stores">Store</label><select id="store" name="store">
					<option disabled="disabled" selected="selected" value="">Select a Store</option>
					</select></div>
					<div id="languages" class="usa-input-grid bbg__article__form-select--main"><label for="language">App language</label><select id="language" name="language">
					<option disabled="disabled" selected="selected" value="">Select a language</option>
					</select></div>
					<input class="usa-input-grid-large" name="btnGo" type="button" value="Go to download site »" /></fieldset>
					</form>
				';
		} else if ($formType == 'java') {
			$s .= '
				<form id="appSelect-java" class="usa-form-large bbg__article__form">
				<legend class="bbg__article-content__form-title">Find the version of the app that\'s best for you:</legend>
				<fieldset>
				<div id="entities" class="usa-input-grid bbg__article__form-select--main"><label for="entity" class="">Network</label><select id="entity" name="entity">
				<option value="" disabled selected>Select a network</option>
				</select></div>
				<div id="stores" class="usa-input-grid bbg__article__form-select--main"><label for="stores" class="">Store</label><select id="store" name="store">
				<option value="" disabled selected>Select a store</option>
				</select></div>
				<div id="languages" class="usa-input-grid bbg__article__form-select--main"><label for="language" class="">App language</label><select id="language" name="language">
				<option value="" disabled selected>Select a language</option>
				</select></div>
				<input class="usa-input-grid-large" name="btnGo" type="button" value="Go to download site »" /></fieldset>
				</form>';
		} else if ($formType == 'sawa') {
			$s .= '
				<form id="appSelect-sawa" class="usa-form-large bbg__article__form">
				<legend class="bbg__article-content__form-title">Find the version of the app that\'s best for you:</legend>
				<fieldset>
				<div id="osList" class="usa-input-grid bbg__article__form-select--main"><label class="" for="os">OS</label><select id="os" name="os">
				<option disabled="disabled" selected="selected" value="">Select an OS</option>
				</select></div>
				<div id="stores" class="usa-input-grid bbg__article__form-select--main"><label class="" for="stores">Store</label><select id="store" name="store">
				<option disabled="disabled" selected="selected" value="">Select a store</option>
				</select></div>
				<div id="languages" class="usa-input-grid bbg__article__form-select--main"><label class="" for="language">App language</label><select id="language" name="language">
				<option disabled="disabled" selected="selected" value="">Select a language</option>
				</select></div>
				<input class="usa-input-grid-large" name="btnGo" type="button" value="Go to download site »" /></fieldset>
				</form>';
		} else if ($formType == 'streamer') {
			$s .= '
				<form id="appSelect-streamer" class="usa-form-large bbg__article__form">
				<legend class="bbg__article-content__form-title">Find the version of the app that\'s best for you:</legend>
				<fieldset>
				<div id="osList" class="usa-input-grid bbg__article__form-select--main"><label for="os" class="usa-sr-only">OS</label>
				<select id="os" name="os">
				<option value="" disabled selected>Select an OS</option>
				</select></div>
				<div id="stores" class="usa-input-grid bbg__article__form-select--main"><label for="stores" class="usa-sr-only">Store</label>
				<select id="store" name="store">
				<option value="" disabled selected>Select a store</option>
				</select></div>
				<div id="languages" class="usa-input-grid bbg__article__form-select--main"><label for="language" class="usa-sr-only">App language</label>
				<select id="language" name="language">
				<option value="" disabled selected>Select a language</option>
				</select></div>
				<input name="btnGo" type="button" value="Download site" class="usa-input-grid-large" />
				</fieldset>
				</form>';
		}

	}
	add_shortcode('appselector', 'appselector_shortcode');


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
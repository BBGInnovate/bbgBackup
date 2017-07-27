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
		$atts = shortcode_atts( array(
			'name' => 'BBG',
		), $atts );

		$orgName = 'bbg';
		$boilerplate = '';

		if ( ! empty( $atts['name'] ) ) {
	        $orgName = strtolower( $atts['name'] );
	    }

        if ( $orgName === 'bbg' ) {
			$fullName = "the BBG";
			$boilerplate = get_field( 'site_setting_boilerplate_bbg', 'options', 'false') ; // Load BBG description from 'BBG Settings' custom field
			$boilerplate = apply_filters('the_content', $boilerplate); // allowing shortcodes in the boilerplate entry

			// Set default description for "About BBG" in case field is empty
			if ( ! $boilerplate || $boilerplate == "" ) {
				$boilerplate = "The Broadcasting Board of Governors is an independent federal agency supervising all U.S. government-supported, civilian international media. Its mission is to inform, engage and connect people around the world in support of freedom and democracy. BBG networks include the Voice of America, Radio Free Europe/Radio Liberty, the Middle East Broadcasting Networks (Alhurra TV and Radio Sawa), Radio Free Asia, and the Office of Cuba Broadcasting (Radio and TV Martí). BBG programming has a measured audience of [audience] in more than [countries] and in [languages].";
			}
        } else if ( $orgName == "ct" ) {
        	$fullName = "Current Time";
			$boilerplate = "<p>Current Time is a 24/7 Russian-language television and digital network aimed at providing fact-based news and information to Russian speakers in Eurasia and beyond. The network, <strong>launched by RFE/RL with support from VOA</strong>, provides audiences with an alternative to Kremlin-sponsored media when it comes to fractious or underreported issues such as Crimea, the war in Ukraine, and Russia sanctions, as well as evergreen social issues like corruption, labor migration, and minority rights.</p><p>Current Time Television currently broadcasts to <strong>14 countries</strong> including Russia, Ukraine, Moldova, the Baltics, parts of Central Asia, and Israel. Current Time’s dynamic digital platforms have built an audience among young news consumers as well. Current Time’s video products were viewed more than 160 million times in the first five months of 2017—with half of the views coming from inside Russia itself.";
		} else {
        	$entityParentPage = get_page_by_path('networks');

			$qParams = array(
				'post_type' => array('page'),
				'posts_per_page' => -1,
				'post_parent' => $entityParentPage->ID,
				'orderby' => 'meta_value_num',
				'meta_key' => 'entity_year_established',
				'order' => 'ASC'
			);

			$custom_query = new WP_Query($qParams);
			if ( $custom_query -> have_posts() ) {
				while ( $custom_query -> have_posts() )  {
					$custom_query -> the_post();
					$id = get_the_ID();

					$entityAbbr = get_post_meta( $id, 'entity_abbreviation', true );
					$abbreviation = strtolower( get_post_meta( $id, 'entity_abbreviation', true ) );
					$abbreviation = str_replace( "/", "", $abbreviation );

					if ( $abbreviation == $orgName ) {
						$fullName = $entityAbbr;
						$boilerplate = get_post_meta( $id, 'entity_boilerplate_description', true );
						$boilerplate = apply_filters('the_content', $boilerplate); // allowing shortcodes in the boilerplate entry
					}
				}
			}
			wp_reset_postdata();
        }

		$about = "<h4>About " . $fullName . "</h4>";
		$about .= "<div class='bbg__tagline'>";
			$about .= $boilerplate;
		$about .= "</div>";

	    return $about;
	}

	add_shortcode( 'about', 'about_shortcode' );

	/**
	 * Add shortcode reference for BBG-wide audience numbers
	 * @param  Array $split includes the total audience number + a split by media type
	 * @return Text
	 * Returns the audience numbers based on the parameter from the custom field set on 'BBG settings'
	 */
	function audience_shortcode( $atts ) {
		// access site-wide variables
		global $post;

	    // set variables based on custom fields
	    $total = get_field( 'site_setting_unduplicated_audience', 'options', 'false' );
		$tv = get_field( 'site_setting_tv_audience', 'options', 'false' );
		$radio = get_field( 'site_setting_radio_audience', 'options', 'false' );
		$internet = get_field( 'site_setting_internet_audience', 'options', 'false' );
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

	/**
	 * Add shortcode reference for the number of supported languages
	 * @return Text
	 * Returns total number of languages from custom field set in 'BBG Settings'
	 */
	function languages_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
		$number_of_languages = get_field( 'site_setting_total_languages', 'options', 'false' );

		// $number_of_languages = 61;
		return $number_of_languages . " languages";
	}
	add_shortcode('languages', 'languages_shortcode');

	/**
	 * Add shortcode reference for the number of total countries
	 * @return Text
	 * Returns total number of countries covered from custom field set in 'BBG settings'
	 */
	function countries_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $number_of_countries = get_field( 'site_setting_total_countries', 'options', 'false' );

		//$number_of_countries = 100;
		return $number_of_countries . " countries";
	}
	add_shortcode('countries', 'countries_shortcode');

	/**
	 * Add shortcode reference for the number of total affiliates
	 * @return Text
	 * Returns total number of affiliates from custom field set in 'BBG settings'
	 */
	function affiliates_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $number_of_affiliates = get_field( 'site_setting_total_affiliates', 'options', 'false' );

		return $number_of_affiliates . " global affiliates";
	}
	add_shortcode('affiliates', 'affiliates_shortcode');

	/**
	 * Add shortcode reference for the number of programming hours per week
	 * @return Text
	 * Returns estimated number of programming hours distributed from custom field set in 'BBG settings'
	 */
	function programming_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $hours_of_programming = get_field( 'site_setting_total_countries', 'options', 'false' );

		return $hours_of_programming . " hours of original programming each week";
	}
	add_shortcode('programming', 'programming_shortcode');

	/**
	 * Add shortcode reference for the most recent requested budget.
	 * Comes from custom field set in 'BBG settings'.
	 * Used on the Rumors, Myths and Untruths page.
	 * @return number rounded to nearest integer
	 */
	function budget_shortcode() {
		// access site-wide variables
		global $post;

		// set variables based on custom fields
	    $budget = get_field( 'site_setting_annual_budgets', 'options', 'false' );

	    /**** sort the budgets so that they are in order of fiscal year descending ***/
	    function cmpFiscalYear($a, $b) {
		    return ($b['fiscal_year'] - $a['fiscal_year']);
		}
	    usort($budget, "cmpFiscalYear");

	    $latestBudget=0;
	    for ($i=0; $i < count($budget); $i++) {
	    	if ($latestBudget == 0 && ($budget[$i]['status'] == 'Requested')) {
	    		$latestBudget = round($budget[$i]['dollar_amount'],0);
	    	}
	    }
		return $latestBudget;
	}

	add_shortcode('currentCBJ', 'budget_shortcode');

	/**
	 * Shortcode for selecting/downloading apps
	 * @param  $formType
	 * @return Returns a form for downloading the correct type of apps
	 */
	function appselector_shortcode($atts) {
		$formType = "news";
		if (isset($atts['type'])) {
			$formType = $atts['type'];
		}
		$s = "";
		if ($formType == 'news') {
			$s .= '<p>Find the version of the app that’s best for you:<p>
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
				<legend class="bbg__article-content__form-title">Find the version of the app that’s best for you:</legend>
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
				<legend class="bbg__article-content__form-title">Find the version of the app that’s best for you:</legend>
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
				<legend class="bbg__article-content__form-title">Find the version of the app that’s best for you:</legend>
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
		return $s;

	}
	add_shortcode('appselector', 'appselector_shortcode');

	/**
	 * Add shortcode reference for BBG-wide audience numbers
	 * @param  Array $split includes the total audience number + a split by media type
	 * @return Text
	 * Returns numbers set on the entity pages
	 */
	function entityfastfact_shortcode( $atts ) {
		// access site-wide variables
		global $post;

		$atts = shortcode_atts( array(
			'entity' => '',
			'type' => ''
		), $atts );

		$returnVal = '';
		if ( ! empty( $atts['entity'] ) ) {
			$entity = $atts['entity'];
		}
		if ( ! empty( $atts['type'] ) ) {
			$fastFactType = $atts['type'];
		}

		if ($entity != "" && $fastFactType != "") {
			//entity_budget, entity_employees, entity_languages, entity_primary_language, entity_audience, entity_mobile_apps_link
			$entityParentPage = get_page_by_path('networks');

			$qParams = array(
				'post_type' => array('page'),
				'posts_per_page' => -1,
				'post_parent' => $entityParentPage->ID
			);

			$custom_query = new WP_Query($qParams);
			if ( $custom_query -> have_posts() ) {
				while ( $custom_query -> have_posts() )  {
					$custom_query -> the_post();
					$id = get_the_ID();

					$abbreviation = strtolower( get_post_meta( $id, 'entity_abbreviation', true ) );
					$abbreviation = str_replace( "/", "", $abbreviation );

					if ( $abbreviation == $entity ) {
						$returnVal = get_post_meta( $id, 'entity_' . $fastFactType, true );
					}
				}
			}
			wp_reset_postdata();
		}
		return $returnVal;

	}

	add_shortcode( 'entityfastfact', 'entityfastfact_shortcode' );

	function transcript_list_shortcode($atts) {
		
		//if you want to only show X transcripts, use the 'limit' variable
		$limit = 0;
		if ( isset( $atts['limit'] ))  {
	    	$limit = $atts['limit'];
	    }

	    //for pagination
	    $transcriptPage = 1;
	    if ( isset ( $_GET['transcriptPage'] )) {
	    	$transcriptPage = $_GET['transcriptPage'];
	    }
	    
	    //BEGIN: MODIFY TAXONOMY QUERY AS NEEDED TO ACCOMMODATE SUBCATEGORIES
		$tax_query = array(
			'relation' => 'AND',
			array (
				'taxonomy' => 'media_category',
				'field' => 'slug',
				'terms' => array('transcript'),
				'operator' => 'IN'
			)
		);

		if ( isset ( $atts['subcategory'] )) {
			$tax_query []= array (
				'taxonomy' => 'media_category',
				'field' => 'slug',
				'terms' => array($atts['subcategory']),
				'operator' => 'IN'
			);
	    }
	    //END: MODIFY TAXONOMY QUERY AS NEEDED TO ACCOMMODATE SUBCATEGORIES

		$the_query = new WP_Query( array(
			'post_type' => 'attachment',
    		'post_status' => 'inherit',	//for some reason, this is required
			'posts_per_page' => $limit,
			'paged'          => $transcriptPage, 
			'tax_query' => $tax_query,
			'meta_key'			=> 'transcript_appearance_date',
			'orderby'			=> 'meta_value',
			'order'				=> 'DESC'
		) );

		$str = "";
		while ( $the_query->have_posts() ) :
		    $the_query->the_post();
			$link = get_permalink();
			$link = wp_get_attachment_url( get_post_thumbnail_id() );
			$title = get_the_title();
			$excerpt = get_the_content();

			$date = get_field('transcript_appearance_date', get_the_ID(), true);
			$relatedPost = get_field('transcript_related_post', get_the_ID(), true);
			$p = false;
			if ( is_array( $relatedPost)) {
				$p = $relatedPost[0];
			}
			$str .= '<article class="bbg-blog__excerpt--list">';
			$str .= '<header class="entry-header bbg-blog__excerpt-header">';
			$str .= '<h3 class="entry-title bbg-blog__excerpt-title--list  selectionShareable"><a target="_blank" href="' . $link . '" rel="bookmark">' . $title . '</a></h3>';
			$str .= '</header><!-- .bbg-blog__excerpt-header -->';
			$str .= '<div class="entry-content bbg-blog__excerpt-content">';
			if ($p) {
				$str .= '<div style="margin-bottom: 1rem;" ><a href="' . get_permalink( $p -> ID ) . '">' . $p->post_title .'</a></div>';	
			}
			$str .= '<div class="posted-on bbg__excerpt-meta" ><time class="entry-date published updated">' . $date . '</time></div>';
			$str .= '<p>' . $excerpt . '</p>';
			$str .='</div><!-- .bbg-blog__excerpt-content -->';		
			$str .= '</article>';
		endwhile;

		wp_reset_postdata();		
		return $str;
	}

	add_shortcode('transcript_list', 'transcript_list_shortcode'); 


?>
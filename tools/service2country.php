<?php 

/*
    Author: Joe Flowers
    Description: The map data for worldwide ops is represented as follows:
    	custom post type "country"
    	custom taxonomy called "Language Services"
    	custom field "website name" and "website url" on each language service taxonomy term
    	
    	To keep the data model simple and in a format that could be managed with WordPress's built in taxonomy and custom field capabilities, we elected to duplicate language services when they have more than one website. For instance, many RFERL services have sites in both their native language as well as Russian.

        Data source: We asked the Office of Policy and Research to provide us a list of countries that each of our five networks target, and also which language services are used to target each country. 
*/

require ('../../../../wp-load.php');
//switch_to_blog(2);
die();

$serviceCountryStr = "Martinoticias	Cuba
Alhurra TV	Algeria,Bahrain,Chad,Comoros,Djibouti,Egypt,Iraq,Jordan,Kuwait,Lebanon,Libya,Mauritania,Morocco,Oman,Palestinian Territories,Qatar,Saudi Arabia,Somalia,Sudan,Syria,Tunisia,United Arab Emirates,Yemen
Radio Sawa	Algeria,Bahrain,Chad,Comoros,Djibouti,Egypt,Iraq,Jordan,Kuwait,Lebanon,Libya,Mauritania,Morocco,Oman,Palestinian Territories,Qatar,Saudi Arabia,Somalia,Sudan,Syria,Tunisia,United Arab Emirates,Yemen
Afia Darfur	Chad,South Sudan
RFA Burmese	Myanmar [Burma]
RFA Cantonese	China,China (Taiwan)
RFA Khmer	Cambodia
RFA Korean	North Korea
RFA Lao	Laos
RFA Mandarin	China,China (Taiwan)
RFA Tibet	China,China (Taiwan)
RFA Uyghur	China,China (Taiwan)
RFA Vietnamese	Vietnam
RFERL Afghan (Dari)	Afghanistan
RFERL Afghan (Pashto)	Afghanistan
RFERL Armenian	Armenia
RFERL Azerbaijani	Azerbaijan
RFERL Azerbaijani (Russian)	Azerbaijan
RFERL Balkan	Bosnia and Herzegovina,Kosovo,Macedonia,Montenegro,Serbia
RFERL Balkan (Albanian)	Bosnia and Herzegovina,Kosovo,Macedonia,Montenegro,Serbia
RFERL Balkan (Macedonian)	Bosnia and Herzegovina,Kosovo,Macedonia,Montenegro,Serbia
RFERL Belarus	Belarus
RFERL Current Time	Estonia,Georgia,Israel,Kazakhstan,Kyrgyzstan,Latvia,Lithuania,Moldova,Russia,Tajikistan,Ukraine
Radio Farda	Iran
RFERL Georgian	Georgia
RFERL Kazakh	Kazakhstan
RFERL Kazakh (Russian)	Kazakhstan
RFERL Kyrgyz	Kyrgyzstan
RFERL Kyrgyz (Russian)	Kyrgyzstan
Gandhara (English to AfPak)	Afghanistan,Pakistan
Radio Mashaal	Afghanistan,Pakistan
RFERL Moldovan	Moldova
RFERL Moldovan (Russian)	Moldova
RFERL North Caucasus (Avar)	Russia
RFERL North Caucasus (Chechen)	Russia
RFERL North Caucasus (Russian)	Russia
RFERL Russian	Russia
RFERL Tajik	Kyrgyzstan,Russia,Tajikistan
RFERL Tajik (Russian)	Kyrgyzstan,Russia,Tajikistan
RFERL Tatar-Bashkir	Russia
RFERL Tatar-Bashkir (Russian)	Russia
RFERL Turkmen	Afghanistan,Iran,Russia,Turkey,Turkmenistan,Uzbekistan
RFERL Ukrainian	Ukraine
RFERL Ukrainian (Russian)	Ukraine
RFERL Ukrainian (Crimean Tatar)	Ukraine
RFERL Uzbek	Afghanistan,Kazakhstan,Kyrgyzstan,Russia,Tajikistan,Turkey,Turkmenistan,Uzbekistan
VOA Afghan (Dari)	Afghanistan
VOA Afghan (Pashto)	Afghanistan
VOA Albanian	Albania,Kosovo,Macedonia,Montenegro
VOA Armenian	Armenia
VOA Azerbaijani	Azerbaijan
VOA Bambara	Mali
VOA Bangla	Bangladesh
VOA Bosnian	Bosnia and Herzegovina
VOA Burmese	Myanmar [Burma]
VOA Cantonese	China,China (Taiwan),Hong Kong
VOA Central Africa	Burundi,Congo - Kinshasa,Rwanda
VOA Creole	Haiti
VOA Deewa	Afghanistan,Pakistan
VOA French to Africa	Algeria,Benin,Burkina Faso,Burundi,Cameroon,Central African Republic,Chad,Congo - Brazzaville,Congo - Kinshasa,Gabon,Guinea,Ivory Coast,Madagascar,Mali,Mauritania,Morocco,Niger,Rwanda,Senegal,Togo,Tunisia
VOA Georgian	Georgia
VOA Hausa	Burkina Faso,Cameroon,Chad,Ghana,Niger,Nigeria
VOA Horn of Africa (Afaan Oromoo)	Eritrea,Ethiopia
VOA Horn of Africa (Amharic)	Eritrea,Ethiopia
VOA Horn of Africa (Tigrigna)	Eritrea,Ethiopia
VOA Indonesian	Indonesia
VOA Khmer	Cambodia
VOA Korean	North Korea
VOA Kurdish	Iran,Iraq,Syria,Turkey
VOA Lao	Laos
VOA Macedonian	Macedonia
VOA Mandarin	China,China (Taiwan)
VOA Persian	Iran
VOA Portuguese to Africa	Angola,Cape Verde,Guinea-Bissau,Mozambique,São Tomé and Príncipe
VOA Russian	Georgia,Kazakhstan,Kyrgyzstan,Latvia,Lithuania,Moldova,Russia,Tajikistan,Ukraine
VOA Serbian	Bosnia and Herzegovina,Kosovo,Montenegro,Serbia
VOA Somali	Kenya,Somalia
VOA Spanish	Argentina,Bolivia,Chile,Colombia,Costa Rica,Cuba,Dominican Republic,Ecuador,El Salvador,Guatemala,Honduras,Mexico,Nicaragua,Panama,Paraguay,Peru,Uruguay,Venezuela
VOA Swahili	Burundi,Congo - Kinshasa,Kenya,Rwanda,Tanzania,Uganda
VOA Thai	Thailand
VOA Tibetan	China,China (Taiwan)
VOA Turkish	Turkey
VOA Ukrainian	Ukraine
VOA Urdu	Pakistan
VOA Uzbek	Afghanistan,Kazakhstan,Kyrgyzstan,Tajikistan,Turkmenistan,Uzbekistan
VOA Vietnamese	Vietnam
VOA Zimbabwe	Botswana,South Africa,Zambia,Zimbabwe";

$serviceCountryLines = explode("\n", $serviceCountryStr);
$counter = 0;
$errors = array();
foreach( $serviceCountryLines as $str ) {
	$counter++;
	if ($counter < 999) {
		$o = explode("\t",$str);
		$serviceName = $o[0];
		$terms = get_terms( array(
			'taxonomy' => 'language_services',
			'hide_empty' => false,
			'name' => $serviceName
		) );
		$error = false;
		if (!count($terms)) {
			$errors []= "Language Service missing: $serviceName";
			$error = true;
		}
		if (!$error) {
			$countryList = $o[1];
			$countryArray = explode(",", $countryList);
			foreach ($countryArray as $countryName) {
				echo $serviceName . " " . $countryName . "<BR>";
				$sql = "SELECT ID FROM $wpdb->posts WHERE post_title = '".$countryName."' and post_type='country' and post_status='publish' ";
				$mypostids = $wpdb->get_col($sql);

				if (!count($mypostids)) {
					$errors []= "Country missing: $countryName";
					$error = true;
				}

				if (!$error) {
					$langServiceTermID = $terms[0]->term_id;
					$langServiceTermName = $terms[0]->name;
					$networkTermID = $terms[0]->parent;
					$countryPostID = $mypostids[0];	
					echo $countryName . "=" . $countryPostID . " ------- " . $langServiceTermName . "=" . $langServiceTermID . " with network " . $networkTermID . " <BR>";
					wp_set_post_terms( $countryPostID, array($networkTermID,$langServiceTermID), "language_services", true );
				}
			}
		}
	}
}

	echo "<pre>";
	var_dump($errors);
	echo "</pre>";


?>
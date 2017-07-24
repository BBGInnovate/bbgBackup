<?php 

	/*
        Author: Joe Flowers
        Date: 2/28/2017
        Description: 
			We use the 'country' custom post type to track every country that is represented on our coverage map. The purposes of this script is to populate the list of countries, as well as to store the 'Ammap' code for each country.  The Ammap code is a two letter code used by our mapping library - https://www.amcharts.com/demos/#javascript-maps - to represent each country

		Data Source: We asked the Office of Policy and Research to provide us a list of countries that each of our five networks target
			(VOA, RFERL, OCB, RFA, MBN). Each country in the aggregate list is represented below.

    */

require ('../../../../wp-load.php');
//switch_to_blog(2);
die();
$countriesStr = "Afghanistan	AF
Albania	AL
Algeria	DZ
Angola	AO
Argentina	AR
Armenia	AM
Azerbaijan	AZ
Bahrain	
Bangladesh	BD
Belarus	BY
Benin	BJ
Bolivia	BO
Bosnia and Herzegovina	BA
Botswana	BW
Burkina Faso	BF
Burundi	BI
Cambodia	KH
Cameroon	CM
Cape Verde	
Central African Republic	CF
Chad	TD
Chile	CL
China	CN
Colombia	CO
Comoros	
Congo - Brazzaville	CG
Congo - Kinshasa	CD
Costa Rica	CR
Cuba	CU
Djibouti	DJ
Dominican Republic	DO
Ecuador	EC
Egypt	EG
El Salvador	SV
Equatorial Guinea	GQ
Eritrea	ER
Estonia	EE
Ethiopia	ET
Gabon	GA
Gambia	GM
Georgia	GE
Ghana	GH
Guatemala	GT
Guinea	GN
Guinea-Bissau	GW
Haiti	HT
Honduras	HN
Hong Kong	
India	IN
Indonesia	ID
Iran	IR
Iraq	IQ
Israel	IL
Ivory Coast	CI
Jordan	JO
Kazakhstan	KZ
Kenya	KE
Kosovo	XK
Kuwait	KW
Kyrgyzstan	KG
Laos	LA
Latvia	LV
Lebanon	LB
Lesotho	LS
Liberia	LR
Libya	LY
Lithuania	LT
Macedonia	MK
Madagascar	MG
Malawi	MW
Mali	ML
Mauritania	MR
Mauritius	
Mexico	MX
Moldova	MD
Montenegro	ME
Morocco	MA
Mozambique	MZ
Myanmar [Burma]	MM
Namibia	NA
Nicaragua	NI
Niger	NE
Nigeria	NG
North Korea	KP
Oman	OM
Pakistan	PK
Palestinian Territories	PS
Panama	PA
Paraguay	PY
Peru	PE
Philippines	PH
Qatar	QA
Russia	RU
Rwanda	RW
Sao Tome and Principe	
Saudi Arabia	SA
Senegal	SN
Serbia	RS
Seychelles	
Sierra Leone	SL
Singapore	
Somalia	SO
South Africa	ZA
South Sudan	SS
Sudan	SD
Swaziland	SZ
Syria	SY
Taiwan	TW
Tajikistan	TJ
Tanzania	TZ
Thailand	TH
Togo	TG
Tunisia	TN
Turkey	TR
Turkmenistan	TM
Uganda	UG
Ukraine	UA
United Arab Emirates	AE
Uruguay	UY
Uzbekistan	UZ
Venezuela	VE
Vietnam	VN
Yemen	YE
Zambia	ZM
Zimbabwe	ZW";

$countries = explode("\n", $countriesStr);
$countries = array_reverse($countries);  //we create them in reverse order so that a default sort will show titles in proper order
echo "<pre>";
var_dump($countries);
echo "</pre>";
$counter = 0;

foreach( $countries as $str ) {
	$counter++;
	if ($counter < 999) {

		$countryObj = explode("\t",$str);
		$countryName = $countryObj[0];
		$countryCode = $countryObj[1];

		echo $countryName . "<BR>";	

		$post_information = array(
			'post_title' => $countryName,
			'post_content' => '',
			'post_type' => 'country',
			'post_status' => 'publish'
		);
		
		$post_id = wp_insert_post( $post_information );
		echo "created id " . $post_id . "<BR>";
		flush();
		update_field('field_58b4b1001b5df', $countryCode, $post_id); //threats_to_press_country = field_5890db9048521
		usleep(1100000); //we do this to make sure the timestamps are created in our desired order
	}

}



?>
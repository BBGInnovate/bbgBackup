<?php 


 require ('../../../../wp-load.php');
 switch_to_blog(2);

$serviceCountryStr = "Martinoticias	http://www.martinoticias.com/
Alhurra TV	http://www.alhurra.com/
Radio Sawa	http://www.radiosawa.com/
RFA Burmese	http://www.rfa.org/burmese/
RFA Cantonese	http://www.rfa.org/cantonese
RFA Khmer	http://www.rfa.org/khmer
RFA Korean	http://www.rfa.org/korean
RFA Lao	http://www.rfa.org/laos
RFA Mandarin	http://www.rfa.org/mandarin
RFA Tibet	http://www.rfa.org/tibetan/
RFA Uyghur	http://www.rfa.org/uyghur
RFA Vietnamese	http://www.rfa.org/vietnamese
RFERL Afghan (Dari)	http://da.azadiradio.com/
RFERL Afghan (Pashto)	http://pa.azadiradio.com/
RFERL Armenian	http://www.azatutyun.am/
RFERL Azerbaijani	http://www.azadliq.org/
RFERL Azerbaijani (Russian)	https://www.radioazadlyg.org/
RFERL Balkan	www.slobodnaevropa.org
RFERL Balkan (Albanian)	www.evropaelire.org
RFERL Balkan (Macedonian)	www.slobodnaevropa.mk
RFERL Belarus	http://www.svaboda.org/
RFERL Current Time	http://www.currenttime.tv/
Radio Farda	http://www.radiofarda.com/
RFERL Georgian	http://www.tavisupleba.org/
RFERL Kazakh	http://www.azattyq.org/
RFERL Kazakh (Russian)	http://rus.azattyq.org/
RFERL Kyrgyz	http://www.azattyk.kg/
RFERL Kyrgyz (Russian)	http://rus.azattyk.org/
Gandhara (English to AfPak) http://gandhara.rferl.org
Radio Mashaal http://www.mashaalradio.com
RFERL Moldovan	http://www.europalibera.org/
RFERL Moldovan (Russian)	http://www.europalibera.org/p/5065.html
RFERL North Caucasus (Avar)	http://www.radioerkenli.com/
RFERL North Caucasus (Chechen)	http://www.radiomarsho.com/
RFERL North Caucasus (Russian)	http://www.kavkazr.com/
RFERL Russian	http://www.svoboda.org/
RFERL Tajik	http://www.ozodi.org/
RFERL Tajik (Russian)	http://rus.ozodlik.org/
RFERL Tatar-Bashkir	http://www.azatliq.org/
RFERL Tatar-Bashkir (Russian)	http://www.idelreal.org/
RFERL Turkmen	http://www.azathabar.com/
RFERL Ukrainian	http://www.radiosvoboda.org/
RFERL Ukrainian (Russian)	http://ru.krymr.com/
RFERL Ukrainian (Crimean Tatar)	ktat.krymr.com
RFERL Uzbek	http://www.ozodlik.org/
VOA Afghan (Dari)	http://www.darivoa.com/
VOA Afghan (Pashto)	http://www.pashtovoa.com/
VOA Albanian	http://www.zeriamerikes.com/
VOA Armenian	http://www.amerikayidzayn.com/
VOA Azerbaijani	http://www.amerikaninsesi.org/
VOA Bambara	http://www.voaafrique.com/p/5384.html
VOA Bangla	http://www.voabangla.com/
VOA Bosnian	http://ba.voanews.com/
VOA Burmese	http://burmese.voanews.com/
VOA Cantonese	http://www.voacantonese.com/
VOA Central Africa	http://www.radiyoyacuvoa.com/
VOA Creole	http://www.voanouvel.com/
VOA Deewa	http://www.voadeewaradio.com/
VOA French to Africa	http://www.voaafrique.com
VOA Georgian	http://www.amerikiskhma.com/
VOA Hausa	http://www.voahausa.com/
VOA Horn of Africa (Afaan Oromoo)	http://www.voaafaanoromoo.com/
VOA Horn of Africa (Amharic)	http://amharic.voanews.com/
VOA Horn of Africa (Tigrigna)	http://tigrigna.voanews.com/
VOA Indonesian	http://www.voaindonesia.com/
VOA Khmer	http://www.khmer.voanews.com/
VOA Korean	http://www.voakorea.com/
VOA Kurdish	http://www.dengiamerika.com/
VOA Lao	http://lao.voanews.com/
VOA Macedonian	http://mk.voanews.com/
VOA Mandarin	http://www.voachinese.com/
VOA Persian	http://ir.voanews.com/
VOA Portuguese to Africa	http://www.voaportugues.com/
VOA Russian	http://www.golos-ameriki.ru/
VOA Serbian	http://www.glasamerike.net/
VOA Somali	http://www.voasomali.com/
VOA Spanish	http://www.voanoticias.com/
VOA Swahili	http://www.voaswahili.com/
VOA Thai	http://www.voathai.com/
VOA Tibetan	http://www.voatibetan.com/
VOA Turkish	http://www.amerikaninsesi.org/
VOA Ukrainian	http://ukrainian.voanews.com/
VOA Urdu	http://www.urduvoa.com/
VOA Uzbek	http://www.amerikaovozi.com/
VOA Vietnamese	http://www.voatiengviet.com/
VOA Zimbabwe	http://www.voazimbabwe.com/";

$serviceWebsiteLines = explode("\n", $serviceCountryStr);
$counter = 0;
$errors = array();
foreach( $serviceWebsiteLines as $str ) {
	$counter++;
	if ($counter < 999) {
		$error = false;

		$o = explode("\t",$str);
		$serviceName = $o[0];
		$url = $o[1];

		echo $serviceName . " " . $url . "<BR>";
	
		$terms = get_terms( array(
			'taxonomy' => 'language_services',
			'hide_empty' => false,
			'name' => $serviceName
		) );

		if (!count($terms)) {
			$errors []= "Language Service missing: $serviceName";
			$error = true;
		}

		if (!$error) {
		 	$langServiceTermID = $terms[0]->term_id;
		 	$langServiceTermName = $terms[0]->name;
		 	echo "setting " . $langServiceTermName . " to " . $url . "<BR>";
		 	update_term_meta($langServiceTermID, "language_service_site_url", $url);
		 	update_term_meta($langServiceTermID, "language_service_site_name", $langServiceTermName);
		 }

	}
}

echo "<h3>Errors?</h3><pre>";
var_dump($errors);
echo "</pre>";


?>
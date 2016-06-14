<?php
	/****** UTILITY FUNCTIONS - KEEP UP TOP ****/
	function fileExpired($filepath, $minutesToExpire) {
		$expired = false;
		if ( !file_exists( $filepath ) ) {
			$expired = true;
		} else {
			$secondsDiff = time() - filemtime( $filepath );
			$minutesDiff = $secondsDiff/60;
			if ($minutesDiff > 30) {
				$expired = true;
			}
		}
		return  $expired;
	}
	function fetchUrl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	function getJSONStr($url, $id) {
		$jsonFilepath = get_template_directory() . "/" . $id . ".json";
		if ( fileExpired($jsonFilepath, 0) ) { 	//1440 min = 1 day
			$result=fetchUrl($url);
			file_put_contents($jsonFilepath, $result);
		} else {
			$result=file_get_contents($jsonFilepath);
		}

		return $result;
	}

	//http://bbgredesign.voanews.com/api.php?endpoint=api/countries/?region_coutry=1
	$test = getJsonStr('http://api.bbg.gov/api/countries/?region_country=1', 'region1');
	echo $test;
?>

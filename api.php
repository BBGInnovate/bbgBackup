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
		$id = str_replace("/", "_", $id);
		$id = str_replace("?", "_", $id);
		$id = str_replace("&", "_", $id);
		$id = str_replace("=", "_", $id);
		$jsonFilepath = "/var/www/wordpress/wp-content/themes/bbgRedesign" . "/" . $id . ".json";
		if ( fileExpired($jsonFilepath, 10) ) { 	//1440 min = 1 day
			$result=fetchUrl($url);
			file_put_contents($jsonFilepath, $result);
		} else {
			$result=file_get_contents($jsonFilepath);
		}
		return $result;
	}

	//http://bbgredesign.voanews.com/api.php?endpoint=api/countries/?region_coutry=1
	///$endpoint = str_replace("&", "?", $_GET['endpoint']);
	$endpoint = $_GET['endpoint'];
	$targetUrl = 'http://api.bbg.gov/' . $endpoint;
	header('Content-Type: application/json');
	$result=getJSONStr($targetUrl, $endpoint);
	echo $result; 
?>

<?php

	/** 

	api.bbg.gov is firewalled to only allow certain IP addresses to reach it.  We allow clients to call this file and it 
	serves as a proxy with some caching enabled.  For example. this will translate a call from

	https://www.bbg.gov/wp-content/themes/bbgRedesign/api.php?endpoint=api/groups/?country=Russia

	to
	
	http://api.bbg.gov/api/groups/?country=Russia	

	***/


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
		//TODO: ideally this would use get_template_directory() from wordpress, but we're not in the wordpress context
		// /Users/josephflowers/apacheapps/sasspress/wp-content/themes/bbgRedesign
		// /var/www/wordpress/wp-content/themes/bbgRedesign
		$jsonFilepath = "/Users/josephflowers/apacheapps/sasspress/wp-content/themes/bbgRedesign" . "/external-feed-cache/" . $id . ".json";
		if ( fileExpired($jsonFilepath, 1440) ) { 	//1440 min = 1 day
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
	header("Access-Control-Allow-Origin: *");

	$result=getJSONStr($targetUrl, $endpoint);
	echo $result; 
?>

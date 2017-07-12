<?php 
	
	/**
	FROM: http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 * @param Array   $cats     taxonomy term objects to sort
	 * @param Array   $into     result array to put them in
	 * @param integer $parentId the current parent ID to put them in
	 */
	function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0) {
	    foreach ($cats as $i => $cat) {
	        if ($cat->parent == $parentId) {
	            $into[$cat->term_id] = $cat;
	            unset($cats[$i]);
	        }
	    }

	    foreach ($into as $topCat) {
	        $topCat->children = array();
	        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
	    }
	}

	function convertSmartQuotes($str) {
		//http://stackoverflow.com/questions/20025030/convert-all-types-of-smart-quotes-with-php
		$chr_map = array(
			// Windows codepage 1252
			"\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
			"\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
			"\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
			"\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
			"\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
			"\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
			"\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
			"\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark

			// Regular Unicode     // U+0022 quotation mark (")
			// U+0027 apostrophe     (')
			"\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
			"\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
			"\xE2\x80\x98" => "'", // U+2018 left single quotation mark
			"\xE2\x80\x99" => "'", // U+2019 right single quotation mark
			"\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
			"\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
			"\xE2\x80\x9C" => '"', // U+201C left double quotation mark
			"\xE2\x80\x9D" => '"', // U+201D right double quotation mark
			"\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
			"\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
			"\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
			"\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
		);
		$chr = array_keys  ($chr_map); // but: for efficiency you should
		$rpl = array_values($chr_map); // pre-calculate these two arrays
		$str = str_replace($chr, $rpl, html_entity_decode($str, ENT_QUOTES, "UTF-8"));
		return $str;
	}

	/****** UTILITY FUNCTIONS - KEEP UP TOP ****/
	function fileExpired($filepath, $minutesToExpire) {
		$expired = false;
		if ( !file_exists( $filepath ) ) {
			$expired = true;
		} else {
			$secondsDiff = time() - filemtime( $filepath );
			$minutesDiff = $secondsDiff/60;
			if ($minutesDiff > $minutesToExpire) {
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
	function getFeed($url,$id) {
		$feedFilepath = get_template_directory() . "/external-feed-cache/" . $id . ".xml";
		if ( fileExpired($feedFilepath,60)) { //one hour expiration
			$feedStr=fetchUrl($url);
			file_put_contents($feedFilepath, $feedStr);
		} else {
			$feedStr=file_get_contents($feedFilepath);
		}
		$xml = simplexml_load_string($feedStr);
		$json = json_encode($xml,JSON_PRETTY_PRINT);
		$json=json_decode($json);
		return $json;
	}
	function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true) {
	    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
	    $enc = preg_replace_callback(
	        '/"(.*?)"/s',
	        function ($field) {
	            return urlencode(utf8_encode($field[1]));
	        },
	        $enc
	    );
	    $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
	    return array_map(
	        function ($line) use ($delimiter, $trim_fields) {
	            $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
	            return array_map(
	                function ($field) {
	                    return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
	                },
	                $fields
	            );
	        },
	        $lines
	    );
	}
	function getCSV($url,$id,$expirationMinutes) {
		$feedFilepath = get_template_directory() . "/external-feed-cache/" . $id . ".csv";
		if ( $expirationMinutes<=0 || fileExpired($feedFilepath,$expirationMinutes)) {
			$feedStr=fetchUrl($url);
			file_put_contents($feedFilepath, $feedStr);
		} else {
			$feedStr=file_get_contents($feedFilepath);
		}
		$csv = parse_csv($feedStr);

		return $csv;
	}
	function formatBytes($bytes) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1000));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1000, $pow);
		// $bytes /= (1 << (10 * $pow));

		$precision = 2;
		if ($pow < 2) {
			$precision = 0;
		}

		return round($bytes, $precision) . ' ' . $units[$pow];
	}

	class ArrayValue implements JsonSerializable {
		//****** HELPER CLASS FOR SERIALIZING PHP TO JSON
		public function __construct(array $array) {
			$this->array = $array;
		}

		public function jsonSerialize() {
			return $this->array;
		}
	}
	
	function buildLabel($classNames) {
		$labelText = "";
		$label = "";
		if (stristr($classNames, "category-remarks")) {
			$labelText = "REMARKS";
		} elseif (stristr($classNames, "category-statement")) {
			$labelText = "STATEMENT";
		} elseif (stristr($classNames, "category-appearance")) {
			$labelText = "APPEARANCE";
		} elseif (stristr($classNames, "category-oped")) {
			$labelText = "OP-ED";
		} elseif (stristr($classNames, "category-event")) {
			$labelText = "EVENT";
		}
		if ( $labelText != "" ) {
			$label = "<div><a class='bbg__label_inverted'>" . $labelText . "</a></div>";
		}
		return $label;
	}
	/****** END OF UTILITY FUNCTIONS - KEEP UP TOP ****/

?>
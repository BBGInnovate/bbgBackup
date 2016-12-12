<?php 

/**
 * Affiliate data parsing for BBG.gov
 * This script accepts JSON data from the affiliates endpoint in the CRM as a file upload, parses out the relevant data, and writes it to a file to be read by the web serer
 */

/* these are a few values from the CRM that map to human readable names */
$BBG_ACTIVE_STATUS_MAGIC_NUMBER = 864930000;
$platforms =array(
	"864930000" => "Radio",
	"864930001" => "TV",
	"864930002" => "Web", //this is 'Newspaper' in the CRM but we want them to be treated as Web
	"864930003" => "TV",  //this is 'Satellite' in the CRM but we want them to be treated as Web
	"864930004" => "Web", 
	"864930005" => "Web", //this is 'Mobile' in the CRM but we want them to be treated as Web
	"864930006" => "Other"
);

/* 
   When somebody in the field selects "other", they are given a free form text entry field.  
   $otherPlatformsKey maps each of these to either Web, Radio, or TV 
*/
$otherPlatformsKey = array( 
	"Audio text" => "Web",
	"Blog" => "Web",
	"Cable" => "TV",
	"Cable Radio" => "Radio",
	"Cable TV" => "TV",
	"Cable, IPTV" => "TV",
	"Communication Company" => "Web",
	"Consulting" => "Web",
	"Content Distributor" => "Web",
	"Content Provider" => "Web",
	"Digital Audio Broadcasting" => "Radio",
	"Education" => "Web",
	"Educational" => "Web",
	"Humanitarian" => "Web",
	"Institute" => "Web",
	"Internet radio" => "Radio",
	"Internet TV" => "Web",
	"IP TV" => "TV",
	"Magazine" => "Web",
	"Media Consultant" => "Web",
	"Media Training Academy" => "Web",
	"Multiple" => "Web",
	"n/a" => "Web",
	"Newspaper" => "Web",
	"Press" => "Web",
	"Print Media" => "Web",
	"Promotion Performer" => "Web",
	"Publications" => "Web",
	"Publisher (Book format with DVD inserts)" => "Web",
	"Radio and TV"  => "TV",
	"Tablet in Taxi"  => "Web",
	"Terrestrial" => "Web",
	"Theater" => "Web",
	"TV (Digital Video Broadcasting)" => "TV",
	"University" => "Web",
	"Weekly Tabloid" => "Web"
);

$errorMsg = false;
if (isset($_FILES['fileToUpload'])) {
	$target_dir = "/external-feed-cache/";
	$target_file = getcwd() . $target_dir . basename($_FILES["fileToUpload"]["name"]);
	if(isset($_POST["submit"])) {
		
		/*** move the temporary file that they just uploaded to an actual location on disk **/
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		} else {
			$errorMsg = "There was an error uploading affiliate data.  Please try again.";
		}

		/* read the contents of the file into a string */
		$string = file_get_contents($target_file);

		/* turn it into a json object and grab the 'value' node which has all of the real data*/
		$jsonObj = json_decode($string, true);

		if (!$jsonObj) {
			$errorMsg = "There was an error interpreting the affiliate data as JSON.  Please make sure it's the right format and try again.";
		}

		if (!$errorMsg) {


			$affiliates = $jsonObj['value'];

			/* $s will be the string that we build up */
			$s = '';
			$s .= "[\n";
			$activeCounter = 0;
			$invalidCounter = 0;
			$inactiveCounter = 0;
			$invalids = array();

			/* loop through the JSON data object, which has many fields we don't need, and write them to a less verbose format we can use */
			foreach ($affiliates as $a) {

				$affiliateName = $a['bbg_affiliatename'];
				$specificAffiliateName = $a['bbg_specificaffiliatename'];
				
				/*** VERY IMPORTANT: We very deliberately round the location of our affiliates to a single decimal place to avoid exposing them to harm from malicious actors ****/
				$latitude = round($a['bbg_latitude'],1);
				$longitude = round($a['bbg_longitude'],1);

				$city = $a['bbg_city'];
				$country = '';
				$url = $a['bbg_url'];
				$smurl = $a['bbg_socialmediaurl'];
				
				$freq = $a['bbg_frequency'];
				if ($freq == "") {
					$freq = "null";
				}

				$platform = $a['bbg_platform'];

				$status = $a['statuscode'];
				$bbgStatus = $a['bbg_status'];
				
				$isActive = false;
				$isInvalid = false;
				if ($status == 1 && $bbgStatus==$BBG_ACTIVE_STATUS_MAGIC_NUMBER) {
					$isActive = true;
				}

				/* from time to time, poorly formed latitude or longitude are entered.  We avoid those items */
				if (abs($latitude) > 1000 || abs($longitude) > 1000) {
					$isInvalid = true;
				}
				if ($isInvalid) {
					$invalidCounter++;
				} else if ($isActive) {
					$activeCounter++;
				} else {
					$inactiveCounter++;
				}

				$platform = $platforms["$platform"];
				if ($platform == "Other") {
					$platformOtherFreeText = $a['bbg_platformother'];
					if (isset($otherPlatformsKey[$platformOtherFreeText])) {
						$platform = $otherPlatformsKey[$platformOtherFreeText];
					} else {
						$platform = "Web";
					}
				}

				$newAffiliate = array(
					"$specificAffiliateName",
					$latitude,
					$longitude,
					"$city",
					"$country",
					$freq,
					"$url",
					"$smurl",
					"$platform",
					"$platformOtherFreeText"
				);

				$specificNameObj = split("-", $specificAffiliateName);
				$parsedPlatform = "";
				if ($platform == "Other") {
					$parsedPlatform = "\t" . $specificNameObj[count($specificNameObj)-1];	
				}
				$specificAffiliateName = str_replace('"', "'", $specificAffiliateName);
				$affiliateName = str_replace('"', "'", $affiliateName);
				
				if (!$isInvalid && $isActive) {
					if ($activeCounter > 1) {
						$s .= ",";
					}
					$s .= "\t[\n";
					$s .= "\t\t" . "\"$affiliateName\"" . ",\n";
					$s .= "\t\t" .$latitude . ",\n";
					$s .= "\t\t" .$longitude . ",\n";
					$s .= "\t\t" ."\"$city\"" . ",\n";
					$s .= "\t\t" ."\"$country\"" . ",\n";
					$s .= "\t\t" .$freq . ",\n";
					$s .= "\t\t" ."\"$url\"" . ",\n";
					$s .= "\t\t" ."\"$smurl\"" . ",\n";
					$s .= "\t\t" ."\"$platform\"" . ",\n";
					$s .= "\t\t" ."\"$platformOtherFreeText\"" . "\n";
					$s .= "\t\t" ."]" . "\n";
				} else if ($isInvalid) {
					$invalids[] = $affiliateName;
				}
				
			}
			$s .= "]";
			$target_file = getcwd() . $target_dir . "affiliates.json";
			file_put_contents($target_file, $s);

			$result = "";
			$result .= "<div class='alert alert-success'>
						<strong>Success!</strong> You successfully updated the affiliate data.<BR><BR>
						";
			$result .= "<strong>Active affiliates:</strong> $activeCounter<BR><strong>Inactive affiliates:</strong> $inactiveCounter<BR>";

			if ($invalids) {
				$result .= "<BR>The following affiliates had bad lat/lon data:<BR> ---" . implode('<BR>---', $invalids);
			} 

			$result .= "</div>";
		}
	}
}
 
?>

<html>
<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
	
<p class="help-block"></p>

	<div class="container">
	<div class="jumbotron">
        <h1>Affiliate Data Entry for BBG.gov</h1>
        <p>To use this, first browse the appropriate location in the CRM and save the text file locally.  Then, browse to the file containing the JSON and upload it here.  It is usually ~ 13MB in size.</p>
    </div>
	
    Please check <a target='_blank' href='https://bbgredesign.voanews.com/our-work/worldwide-operations/affiliates/'>the affiliate map</a> after you update the data.<BR><BR>

		<!-- Example row of columns -->
		<div class="row">
			<?php 
				if ($errorMsg) {
					echo "<div class='alert alert-danger'>$errorMsg</div>";
				} else if ($result) {
					echo $result;
				}
			?>
	        <div class="col-md-12">
				<form enctype="multipart/form-data" action="parseAffiliates.php" method="post">
					<div class="form-group">
						<label for="fileToUpload">File input</label>
						<input type="file" id="fileToUpload" name="fileToUpload">
					</div>
					<button type="submit" name="submit" class="btn btn-default">Upload</button>
				</form>
			</div>
		</div>
	
</body>
<?php 
	//Grab the list of congressional committee members from the Sunlight Foundation's API
	function getCongressionalCommittee($committeeID, $committeeTitle) {
		$committeeFilepath = get_template_directory() . "/committeecache$committeeID.json";
		if ( fileExpired($committeeFilepath, 1440) ) { 	//1440 min = 1 day
			//use http://tryit.sunlightfoundation.com/congress to try out the api
			$url="https://congress.api.sunlightfoundation.com/committees?apikey=" . SUNLIGHT_API_KEY . "&committee_id=$committeeID&fields=members";
			$result=fetchUrl($url);
			file_put_contents($committeeFilepath, $result);
		} else {
			$result=file_get_contents($committeeFilepath);
		}
		$c2 = json_decode($result, true);
		$members=$c2["results"][0]["members"];

		$minority=array();
		$majority=array();
		foreach($members as $m) {
			if ($m['side']=='majority') {
				$majority[]=$m;
			} else {
				$minority[]=$m;
			}
		}

	$s='<div class="usa-accordion bbg__committee-list">';
	$s.='<ul class="usa-unstyled-list">';
	$s.='<li>';
	$s.='<button class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-'.$committeeID.'">';
	$s.=$committeeTitle;
	$s.='</button>';
	$s.='<div id="collapsible-'.$committeeID.'" aria-hidden="true" class="usa-accordion-content">';




		$s.="<section class='usa-grid-full'>";
		$s.= "<div class='bbg-grid--1-1-1-2'>";
		$s.= "<strong>MAJORITY</strong> (".$majority[0]['legislator']['party'].")";
		$s.= "<ul class='usa-unstyled-list'>";
		foreach ($majority as $m) {
			$firstName=$m['legislator']['first_name'];
			$lastName=$m['legislator']['last_name'];
			$state=$m['legislator']['state'];
			$title='';
			if (isset($m['title'])) {
				$title=' <em>— '.$m['title'].'</em>';
			}
			$s.= "<li>$firstName $lastName, $state $title</li>";
		}
		$s.= "</ul>";
		$s.= "</div><!-- .bbg-grid -->";
		$s.= "<div class='bbg-grid--1-1-1-2'>";
		$s.=  "<strong>MINORITY</strong> (".$minority[0]['legislator']['party'].")";
		$s.= "<ul class='usa-unstyled-list'>";
		foreach ($minority as $m) {
			$firstName=$m['legislator']['first_name'];
			$lastName=$m['legislator']['last_name'];
			$state=$m['legislator']['state'];
			$title='';
			if (isset($m['title'])) {
				$title=' <em>— '.$m['title'].'</em>';
			}
			$s.= "<li>$firstName $lastName, $state $title</li>";
		}
		$s.= "</ul>";
		$s.= "</div><!-- .bbg-grid -->";
		$s.= "</section><!-- .usa-grid -->";


	$s.= '</div>';
	$s.= '</li>';
	$s.= '</ul>';
	$s.= '</div>';

		return $s;
	}

	// Add shortcode reference to Innovation Series on old posts and pages
	function congressional_committee_shortcode($atts) {
		return getCongressionalCommittee($atts['id'], $atts['title']);
	}
	add_shortcode('congressional_committee', 'congressional_committee_shortcode');

?>
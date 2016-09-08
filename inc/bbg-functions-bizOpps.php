<?php 

	function getBizOpps() {
		$bizOppsFilepath = get_template_directory() . "/external-feed-cache/biz.json";
		if (fileExpired($bizOppsFilepath, 1440)  ) {  //1440 min = 1 day
			$searchParams = array(
				'documents_to_search' => 'active',
				'notice_type' => 'COMBINE'
			);

			$baseUrl = 'https://www.fbo.gov';
			$wsdlUrl = $baseUrl . '/ws/fbo_api.php?wsdl';
			$username = FBO_USERNAME;
			$pw = FBO_PW;
			
			try {
				// BASIC AUTH
				$client = new SoapClient($wsdlUrl,array('login'=> $username, 'password'=>$pw));

				//SOAP HEADER AUTH
				// $client = new SoapClient($wsdlUrl);
				// $header = new SoapHeader($baseUrl,'AuthenticationData',array('username'=>$username,'password'=>$pw));
				// $client->__setSoapHeaders(array($header));

				$response = $client->getList($searchParams);
				if ($response->success) {
					$postings = array();
					foreach ($response->data as $d) {
						$p = array();
						$p["id"] = $d -> notice_id;
						$p["baseType"] = $d -> base_type;
						$p["currentType"] = "";
						if (property_exists($d, 'current_type')) {
							$p["currentType"] = $d -> current_type;
						}
						$p["lastPostedDate"] = $d -> last_posted_date;
						$p["solutionNumber"] = $d -> solnbr;
						$p["subject"] = $d -> subject;
						$postings[] = $p;
					}
					$result = json_encode($postings);
					file_put_contents($bizOppsFilepath, $result);
				}
			} catch (Exception $e){
				// echo "error ";
				// var_dump($e);
			}
		}

		$result=file_get_contents($bizOppsFilepath);
		$opps = json_decode($result, true);
		return $opps;
	}
	function outputBizOpps() {
		$opps = getBizOpps();
		$fboSearchLink = 'https://www.fbo.gov/index?s=agency&mode=form&tab=notices&id=50e78d8b5e1bacce4caf0f645e07e253';
		$s = "";
		///$s .= "View all business opportunities at <a target='_blank' href='$fboSearchLink'>fbo.gov</a>.<BR>";\

		array_splice($opps, 5); //show the most recent 5 

		if (count($opps)==0) {
			
		} else {
			//$s = "<p class='bbg__article-sidebar__tagline'>Includes postings from the International Broadcasting Bureau, Voice of America and Office of Cuba Broadcasting. All federal job opportunities are available on <a target='_blank' href='$jobSearchLink'>USAjobs.gov</a></p>";
			$s .= '<table class="usa-table-borderless bbg__jobs__table">';
			$s .= '<thead><tr><th scope="col">Title</th><th scope="col" width="95">Posted On</th></tr></thead>';
			$s .= '<tbody>';

			for ($i = 0; $i < count($opps); $i++) {
				$o = $opps[$i];
				//var_dump($j);
				$id = $o['id'];
				$title = $o['subject'];
				$lastPostedDateObj = DateTime::createFromFormat(
					DateTime::ISO8601,
					$o['lastPostedDate']
				);
				$lastPostedDate = $lastPostedDateObj->format('n/j/Y');
				$solutionNumber = $o['solutionNumber'];
				$link = 'https://www.fbo.gov/index?s=opportunity&mode=form&id=' . $id . '&tab=core&_cview=0';
				$s .= '<tr><td><a target="_blank" href="' . $link . '" class="bbg__jobs-list__title">' . $title . '</a></td><td>' . $lastPostedDate . '</td></tr>';
			}
			$s .= '</tbody></table>';
		}
		return $s;
	}

	// Add shortcode to output the jobs list
	function bizopps_shortcode() {
		return outputBizOpps();
	}
	add_shortcode('bizopps', 'bizopps_shortcode');

?>
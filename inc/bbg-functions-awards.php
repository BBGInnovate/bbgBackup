<?php
	function getAwardInfo($awardPostID, $isAwardDetailPage) {
		//$isAwardDetailPage=true means we're on the landing page for a post that has been categorized as an award
		//$isAwardDetailPage=false means that we're this information on the sidebar of a post where a user picked a related award in the sidebar

		$award = "";
		$awardTitle = get_post_meta( $awardPostID, 'standardpost_award_title', true );
		$awardYear = get_post_meta( $awardPostID, 'standardpost_award_year', true );
		$awardRecipient = get_post_meta( $awardPostID, 'standardpost_award_recipient', true );
		$awardWinningWork = get_post_meta( $awardPostID, 'standardpost_award_winning_work', true );
		$awardWinner = get_post_meta( $awardPostID, 'standardpost_award_winner', true );
		$awardLink = get_post_meta( $awardPostID, 'standardpost_award_link', true );

		$awardOrganization = get_field( 'standardpost_award_organization', $awardPostID, true);
		$awardOrganization = $awardOrganization -> name;
		$awardLogo = get_post_meta( $awardPostID, 'standardpost_award_logo', true );
		$awardOrgUrl = get_post_meta( $awardPostID, 'standardpost_award_org_url', true );
		$awardDescription = get_post_meta( $awardPostID, 'standardpost_award_description', true );
		$awardPermalink = get_permalink($awardPostID);

		// $awardLogoImage = "";
		// if ( $awardLogo ){
		// 	$awardLogoImage = wp_get_attachment_image_src( $awardLogo , 'small-thumb-uncropped');
		// 	$awardLogoImage = $awardLogoImage[0];
		// 	// $awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__sidebar__primary-image"/>';
		// 	$awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__profile-excerpt__photo"/>';
		// }

		$award .='<div class="bbg__sidebar__primary">';
		$awardYearAndTitle = $awardYear . ' ' . $awardTitle;

		if ($isAwardDetailPage) {
			
			$award .= '<p>';

			/*** TITLE ***/
			$award .= '<strong>Name: </strong>' . $awardYearAndTitle . '<br />';
			
			/*** BEGIN AWARD WINNING WORK ***/
			if ($awardWinningWork) {
				$award .= '<strong>Project: </strong>';
				if ($awardLink) {
					$award .= '<a target="_blank" href="' . $awardLink .'">' . $awardWinningWork;
				}  else {
					$award .= $awardWinningWork;
				}
				$award .= '<br/>';
			}
			/*** END AWARD WINNING WORK ***/
			
			/*** AWARD WINNER ***/
			if ($awardWinner) {
				$award .= '<strong>Winner: </strong>' . $awardWinner . '<br/>';
			}

			/*** NETWORK ***/
			$award .= '<strong>Network: </strong>' . $awardRecipient . '<br/>';
			
			/*** BEGIN ORG ***/
			$award .= '<strong>Presented by: </strong>';
			if ($awardOrgUrl != "") {
				$award .= '<a target="_blank" href="' . $awardOrgUrl .'">' . $awardOrganization . '</a>';
			} else {
				$award .= $awardOrganization;
			}
			/*** END ORG ***/

			$award .= '</p>';
			
		} else {

			/*** BEGIN TITLE ***/
			$award .= '<h4 class="bbg__sidebar__primary-headline">';
			if ( $awardPermalink && $awardPermalink != "" ){
				$award .= '<a href="' . $awardPermalink .'">' . $awardYearAndTitle . '</a>';
			} else {
				$award .= $awardYearAndTitle;
			}
			$award .= '</h4>';
			/*** END TITLE ***/

			$award .= "<p>";
			/*** AWARD WINNER ***/
			if ($awardWinner) {
				$award .= '<strong>Winner: </strong>' . $awardWinner . '<br/>';
			}

			/*** NETWORK ***/
			$award .= '<strong>Network: </strong>' . $awardRecipient . '<br />';
			
			/*** BEGIN AWARD WINNING WORK ***/
			if ($awardWinningWork) {
				$award .= '<strong>Project: </strong>';
				if ($awardLink) {
					$award .= '<a target="_blank" href="' . $awardLink .'">' . $awardWinningWork;
				}  else {
					$award .= $awardWinningWork;
				}
				$award .= '<br/>';
			}
			/*** END AWARD WINNING WORK ***/

			/*** BEGIN ORG ***/
			$award .= '<strong>Presented by: </strong>';
			if ($awardOrgUrl != "") {
				$award .= '<a href="' . $awardOrgUrl .'">' . $awardOrganization . '</a>';
			} else {
				$award .= $awardOrganization;
			}
			$award .= '</p>';
			/*** END ORG ***/
		}
			
		$award .= '</div>';
		return $award;
	}
?>
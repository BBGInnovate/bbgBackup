<?php 
	
	function outputBoardMembers($showActive) {
		//$showActive should be a 0 or 1 passed in a 'active' in the shortcode
		$boardPage=get_page_by_title('The Board');
		$thePostID=$boardPage->ID;

		$formerCSS="";
		$formerGovernorsLink = "";

		if ($showActive==0) {
			$formerCSS=" bbg__former-member";
		}

		$qParams=array(
			'post_type' => array('page')
			,'post_status' => array('publish')
			,'post_parent' => $thePostID
			,'order' => 'ASC'
			,'orderby' => 'meta_value'
			,'meta_key' => 'last_name'
			,'posts_per_page' => 100
		);
		$custom_query = new WP_Query($qParams);

		//Default adds a space above header if there's no image set
		$featuredImageClass = " bbg__article--no-featured-image";

		$boardStr="";
		$chairpersonStr="";
		$secretaryStr="";

		while ( $custom_query->have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$active=get_post_meta( $id, 'active', true );
			if (!isset($active) || $active=="" || !$active) {
				$active=0;
			}
			if (  (get_the_title() != "Special Committees") && ($showActive==$active)) {
				$isChairperson=get_post_meta( $id, 'chairperson', true );
				$isSecretary=get_post_meta( $id, 'secretary_of_state', true );
				//$occupation=get_post_meta( $id, 'occupation', true );
				$email=get_post_meta( $id, 'email', true );
				$phone=get_post_meta( $id, 'phone', true );
				$twitterProfileHandle=get_post_meta( $id, 'twitter_handle', true );
				$profilePhotoID=get_post_meta( $id, 'profile_photo', true );
				$profilePhoto = "";

				if ($profilePhotoID) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
					$profilePhoto = $profilePhoto[0];
				}

				$profileName = get_the_title();
				$occupation = "";
				if ($isChairperson) {
					$occupation =  '<span class="bbg__profile-excerpt__occupation">Chairman of the Board</span>';
				} else if ($isSecretary) {
					$occupation =  '<span class="bbg__profile-excerpt__occupation">Ex officio board member</span>';
				}


				$b =  '<div class="bbg__profile-excerpt bbg-grid--1-2-2">';
					$b.=  '<h3 class="bbg__profile-excerpt__name">';
						$b.=  '<a href="' . get_the_permalink() . '">' . $profileName . '</a>';
					$b.=  '</h3>';

					//Only show a profile photo if it's set.
					if ($profilePhoto!=""){
						$b.=  '<a href="' . get_the_permalink() . '">';
							$b.=  '<div class="bbg__profile-excerpt__photo-container">';
								$b.=  '<img src="' . $profilePhoto . '" class="bbg__profile-excerpt__photo' . $formerCSS . '" alt="Photo of BBG Governor '. get_the_title() .'"/>';
							$b.=  '</div>';
						$b.=  '</a>';
					}

					$b.= '<p>' . $occupation . get_the_excerpt() . '</p>';
				$b.=  '</div><!-- .bbg__profile-excerpt -->';

				if ($isChairperson) {
					$chairpersonStr=$b;
				} else if ($isSecretary) {
					$secretaryStr=$b;
				} else {
					$boardStr.=$b;
				}
			}
		}
		$boardStr = '<div class="usa-grid-full">' . $chairpersonStr . $boardStr . $secretaryStr . '</div>' . $formerGovernorsLink;

		return $boardStr;
	}
	function board_member_list_shortcode($atts) {
		return outputBoardMembers($atts['active']);
	}
	add_shortcode('board_member_list', 'board_member_list_shortcode');


	function outputSeniorManagement($type) {
		$boardPage=get_page_by_title('Senior Management');
		$thePostID=$boardPage->ID;

		$qParams=array(
			'post_type' => array('page')
			,'post_status' => array('publish')
			,'post_parent' => $thePostID
			,'orderby' => 'meta_value'
			,'meta_key' => 'last_name'
			,'order' => 'ASC'
			,'posts_per_page' => 100
		);
		$custom_query = new WP_Query($qParams);

		$boardStr="";
		$ceoStr="";
		$granteeStr="";

		while ( $custom_query->have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$active=get_post_meta( $id, 'active', true );
			if ($active){
				$isCEO=get_post_meta( $id, 'ceo', true );
				$isGrantee=get_post_meta( $id, 'grantee_leadership', true );
				$occupation=get_post_meta( $id, 'occupation', true );
				$email=get_post_meta( $id, 'email', true );
				$phone=get_post_meta( $id, 'phone', true );
				$twitterProfileHandle=get_post_meta( $id, 'twitter_handle', true );
				$profilePhotoID=get_post_meta( $id, 'profile_photo', true );
				$profilePhoto = "";

				if ($profilePhotoID) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
					$profilePhoto = $profilePhoto[0];
				}

				$profileName = get_the_title(); // . ', ' . $occupation;

				$b =  '<div class="bbg__profile-excerpt bbg-grid--1-2-2">';
					$b.=  '<h3 class="bbg__profile-excerpt__name">';
						$b.=  '<a href="' . get_the_permalink() . '" title="Read a full profile of ' . $profileName . '">' . $profileName . '</a>';
					$b.=  '</h3>';

					//Only show a profile photo if it's set.
					if ($profilePhoto!=""){
						$b.=  '<a href="' . get_the_permalink() . '" title="Read a full profile of ' . $profileName . '">';
							$b.=  '<div class="bbg__profile-excerpt__photo-container">';
								$b.=  '<img src="' . $profilePhoto . '" class="bbg__profile-excerpt__photo" alt="Photo of '. $profileName .', ' . $occupation .'"/>';
							$b.=  '</div>';
						$b.=  '</a>';
					}

					$b.=  '<p class="bbg__profile-excerpt__text">';
						$b.=  '<span class="bbg__profile-excerpt__occupation">'. $occupation . '</span>';
						$b.=  get_the_excerpt();
					$b.=  '</p>';
				$b.=  '</div><!-- .bbg__profile-excerpt__profile -->';

				if ($isCEO) {
					$ceoStr=$b;
				} else if ($isGrantee) {
					$granteeStr.=$b;
				} else {
					$boardStr.=$b;
				}
			}
		}
		$s = '';
		$s .= '<div class="usa-grid-full">';
		if ($type=='ibb') {
			$s .=  $ceoStr . $boardStr;
		} else if ($type=='broadcast') {
			$s .= $granteeStr;
		}
		$s .= '</div>';

		return $s;
	}

	function senior_management_list_shortcode($atts) {
		return outputSeniorManagement($atts['type']);
	}
	add_shortcode('senior_management_list', 'senior_management_list_shortcode');

?>
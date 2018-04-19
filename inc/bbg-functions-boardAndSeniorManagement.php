<?php

	function outputBoardMembers( $showActive ) {
		//$showActive should be a 0 or 1 passed in a 'active' in the shortcode
		$boardPage = get_page_by_title( 'The Board' );
		$thePostID = $boardPage -> ID;

		$formerCSS = "";
		$formerGovernorsLink = "";

		if ( $showActive == 0 ) {
			$formerCSS = " bbg__former-member";
		}

		$qParams = array(
			'post_type' => array( 'page' )
			,'post_status' => array( 'publish' )
			,'post_parent' => $thePostID
			,'order' => 'ASC'
			,'orderby' => 'meta_value'
			,'meta_key' => 'last_name'
			,'posts_per_page' => 100
		);
		$custom_query = new WP_Query( $qParams );

		//Default adds a space above header if there's no image set
		$featuredImageClass = " bbg__article--no-featured-image";

		$boardStr = "";
		$chairpersonStr = "";
		$secretaryStr = "";
		$underSecretaryStr = "";
		$actingStr = "";

		while ( $custom_query -> have_posts() )  {
			$custom_query -> the_post();
			$id = get_the_ID();
			$active = get_post_meta( $id, 'active', true );
			if ( !isset($active) || $active == "" || !$active ) {
				$active = 0;
			}
			if ( ( get_the_title() != "Special Committees" ) && ( $showActive == $active ) ) {
				$isChairperson = get_post_meta( $id, 'chairperson', true );
				$isSecretary = get_post_meta( $id, 'secretary_of_state', true );
				$isUnderSecretary = get_post_meta( $id, 'under_secretary_of_state', true );
				$isActing = get_post_meta( $id, 'acting', true );

				//$occupation=get_post_meta( $id, 'occupation', true );
				$email = get_post_meta( $id, 'email', true );
				$phone = get_post_meta( $id, 'phone', true );
				$twitterProfileHandle = get_post_meta( $id, 'twitter_handle', true );
				$profilePhotoID = get_post_meta( $id, 'profile_photo', true );
				$profilePhoto = "";

				if ( $profilePhotoID ) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot' );
					$profilePhoto = $profilePhoto[0];
				}
				$profileName = get_the_title();
				if (!$isUnderSecretary) {
					$occupation = '<span class="bbg__profile-excerpt__occupation">';
				} else {
					$occupation = '<span class="bbg__profile-excerpt__occupation" style="display: inline; padding-left: 0.75em; font-size: 1.6rem; font-weight: 400;">';
				}
				if ( $isActing && !$isUnderSecretary ) {
					$occupation .= 'Acting ';
				} else if ( $isActing && $isUnderSecretary ) {
					$occupation .= 'Acting ' . get_field('occupation', $id);
				}

				if ( $isChairperson ) {
					$occupation .= 'Chairman of the Board';
				} else if ( $isSecretary ) {
					$occupation .= 'Ex officio board member';
				}
				$occupation .= '</span>';

				$b  = '<div class="bbg__profile-excerpt bbg-grid--1-2-2"';
				if ($isUnderSecretary) {
					$b .= ' style="clear: both; border-top: 1px solid #f1f1f1; margin-top: -1.5em"';
				}
				$b .= '>';
				if (!$isUnderSecretary) {
					$b .= 	'<h3 class="bbg__profile-excerpt__name">';
					$b .= 		'<a href="' . get_the_permalink() . '">' . $profileName . '</a>';
					$b .= 	'</h3>';
				}

				//Only show a profile photo if it's set.
				if ( ($profilePhoto != "")){
					$b .= '<a href="' . get_the_permalink() . '">';
					$b .= 	'<img src="';
					$b .= 		$profilePhoto;
					$b .= 		'" class="bbg__profile-excerpt__photo';
					$b .= 		$formerCSS;
					$b .= 		'" alt="Photo of BBG Governor ';
					$b .= 		get_the_title();
					$b .= 		'"';
					if ($isUnderSecretary) {
						$b .= ' style="width: 15%"';
					}
					$b .= 	' />';
					$b .= '</a>';
				}
				if (!$isUnderSecretary) {
					$b .= 	'<p>' . $occupation . get_the_excerpt() . '</p>';
				} else {
					$b .= 	'<h3 class="bbg__profile-excerpt__name" style="display:inline">';
					$b .= 		'<a href="' . get_the_permalink() . '">' . $profileName . '</a>' . $occupation;
					$b .= 	'</h3>';
					$b .= 	'<p>' . get_the_excerpt() . '</p>';
				}
				$b .= '</div><!-- .bbg__profile-excerpt -->';

				if ( $isChairperson ) {
					$chairpersonStr = $b;
				} else if ( $isSecretary ) {
					$secretaryStr = $b;
				} else if ($isUnderSecretary) {
					$underSecretaryStr = $b;
				} else {
					$boardStr .= $b;
				}
			}
		}
		$boardStr = '<div class="usa-grid-full">' . $chairpersonStr . $boardStr . $secretaryStr . $underSecretaryStr . '</div>' . $formerGovernorsLink;

		return $boardStr;
	}
	function board_member_list_shortcode( $atts ) {
		return outputBoardMembers( $atts['active'] );
	}
	add_shortcode( 'board_member_list', 'board_member_list_shortcode' );

	function outputSeniorManagement( $type ) {
		$boardPage = get_page_by_title( 'Management Team' );
		$thePostID = $boardPage -> ID;

		if ( $type == 'ibb' ) {
			$ids = get_field( "senior_management_management_team_ordered", $thePostID, true );
		} else if ( $type == 'broadcast' ) {
			$ids = get_field( "senior_management_network_leaders_ordered", $thePostID, true );
		}

		$peopleStr = "";

		foreach ( $ids as $id ) {
			$active = get_post_meta( $id, 'active', true );

			if ( $active ){
				$isGrantee = get_post_meta( $id, 'grantee_leadership', true );
				$occupation = get_post_meta( $id, 'occupation', true );
				$isActing = get_post_meta( $id, 'acting', true );
				$email = get_post_meta( $id, 'email', true );
				$phone = get_post_meta( $id, 'phone', true );
				$twitterProfileHandle = get_post_meta( $id, 'twitter_handle', true );
				$profilePhotoID = get_post_meta( $id, 'profile_photo', true );
				$profilePhoto = "";
				$actingTitle = "";

				if ( $isActing ) {
					$actingTitle = 'Acting ';
				}

				if ( $profilePhotoID ) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
					$profilePhoto = $profilePhoto[0];
				}

				$profileName = get_the_title($id);

				$b  = '<div class="bbg__profile-excerpt bbg-grid--1-2-2">';
				$b .= 	'<h3 class="bbg__profile-excerpt__name">';
				$b .= 		'<a href="' . get_the_permalink($id) . '" title="Read a full profile of ' . $profileName . '">' . $profileName . '</a>';
				$b .= 	'</h3>';

				//Only show a profile photo if it's set.
				if ( $profilePhoto != "" ){
					$b .= '<a href="' . get_the_permalink($id) . '" title="Read a full profile of ' . $profileName . '">';
					$b .= 	'<img src="' . $profilePhoto . '" class="bbg__profile-excerpt__photo" alt="Photo of ' . $profileName . ', ' . $occupation . '"/>';
					$b .= '</a>';
				}

				$b .= 	'<p class="bbg__profile-excerpt__text">';
				$b .= 		'<span class="bbg__profile-excerpt__occupation">' . $actingTitle . $occupation . '</span>';
				$b .= 		my_excerpt($id);
				$b .= 	'</p>';
				$b .= '</div><!-- .bbg__profile-excerpt__profile -->';

				$peopleStr .= $b;
			}
		}
		$s  = '';
		$s .= '<div class="usa-grid-full">';
		$s .= 	$peopleStr;
		$s .= '</div>';

		return $s;
	}

	function senior_management_list_shortcode( $atts ) {
		return outputSeniorManagement( $atts['type'] );
	}

	add_shortcode( 'senior_management_list', 'senior_management_list_shortcode' );
?>
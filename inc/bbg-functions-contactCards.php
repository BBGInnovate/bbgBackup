<?php
	function acf_load_contact_card_choices( $field ) {
	    //http://stackoverflow.com/questions/4452599/how-can-i-reset-a-query-in-a-custom-wordpress-metabox#comment46272169_7845948
	    //note that wp_reset_postdata doesn't work here, so we have to store a reference to post and put it back when we're done.  documented wordpress "bug"

		global $post;
		$post_original = $post;
	    $field['choices'] = array();
	    $qParamsContact = array(
			'post_type' => array('contact_card')
			// ,'cat' => get_cat_id('contact')
			,'posts_per_page' => 100
		);
		$custom_query = new WP_Query( $qParamsContact );
		$choices = array();
		while ( $custom_query->have_posts() )  {
			$custom_query->the_post();
			$choices[] = array(
				"post_id" => get_the_ID(),
				"title" => get_the_title()
			);
		}
		usort( $choices, 'sortByTitle' );
		foreach ( $choices as $choice ) {
			$field['choices'][ $choice["post_id"]] = $choice["title"];
		}

		$post = $post_original;
		// return the field
		return $field;
	}

	add_filter('acf/load_field/name=contact_post_id', 'acf_load_contact_card_choices');

	function renderContactCard( $postIDs ) {
		if ( is_array($postIDs) && count($postIDs) > 0 ) {
			$qParamsContactCard = array(
				'post__in' => $postIDs,
				'ignore_sticky_posts' => true
			);
			$custom_query = new WP_Query( $qParamsContactCard );
			if ( $custom_query->have_posts() ) :
				echo '<div class="usa-grid-full bbg__contact-box">';
				echo '<h3 class="bbg__contact-box__title">Find out more</h3>';
				while ( $custom_query->have_posts() ) : $custom_query->the_post();
					//now let's get the custom fields associated with our related contact posts
					$id = get_the_ID();
					$email = get_post_meta( $id, 'email', true );
					$fullname = get_post_meta( $id, 'fullname', true );
					$phone = get_post_meta( $id, 'phone', true );
					$bio = get_the_content( $id );
					$office = get_post_meta( $id, 'office', true );
					$jobTitle = get_post_meta( $id, 'job_title', true );

					if ($jobTitle != ""){
						$office = $jobTitle . ", " . $office;
					}

					echo '<div class="bbg__contact__card">';
					echo '<p>Contact ' . $fullname . '<br/>';
					echo $office . '</p>';
					echo '<ul class="bbg__contact__card-list">';
					echo '<li class="bbg__contact__link email"><a href="mailto:' . $email . '" title="Email ' . $fullname . '"><span class="bbg__contact__icon email"></span><span class="bbg__contact__text">' . $email . '</span></a></li>';
					echo '<li class="bbg__contact__link phone"><span class="bbg__contact__icon phone"></span><span class="bbg__contact__text">' . $phone . '</span></li>';
					echo '</ul></div>';
				endwhile;
				echo '</div>';
			endif;
			wp_reset_postdata();
		}
	}

	function renderContactSelect( $postIDs ) {
		if ( is_array($postIDs) && count($postIDs) > 0 ) {
			$custom_query = new WP_Query( array(
				'post__in' => $postIDs,
				'ignore_sticky_posts' => true,
				// 'posts_per_page' => 1,
				'meta_key' => 'organization',
				'orderby' => 'meta_value',
				'order' => 'ASC'
			) );
			if ( $custom_query->have_posts() ) :
				echo '<select name="entity_sites" id="entity_sites">';
					echo '<option>Contact our networks</option>';
					while ( $custom_query->have_posts() ) : $custom_query->the_post();

						//now let's get the custom fields associated with our related contact posts
						$id = get_the_ID();
						$organization = get_post_meta( $id, 'organization', true );
							// trim numbers from organization value
							$organization = trim($organization, "123456-");
						$fullname = get_post_meta( $id, 'fullname', true );
						$jobTitle = get_post_meta( $id, 'job_title', true );
						$email = get_post_meta( $id, 'email', true );
						// $office = get_post_meta( $id, 'office', true );

						/*echo '<pre>';
						var_dump( $organization );
						echo '</pre>';*/
						if ( $jobTitle ) {
							echo '<option value="mailto:' . $email . '">' . $organization . ': ' . $fullname . ', ' . $jobTitle . '</option>';
						} else {
							echo '<option value="mailto:' . $email . '">' . $organization . ': ' . $fullname . '</option>';
						}

					endwhile;
				echo '</select><button class="usa-button" id="entityUrlGo">Go</button>';
				echo '</div>';
			endif;
			wp_reset_postdata();
		}
	}
?>
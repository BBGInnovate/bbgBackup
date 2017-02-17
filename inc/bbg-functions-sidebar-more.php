<?php
	function getAccordion() {
		$accordion = "";
		if( have_rows('accordion_items') ):
			$accordion .= '<style>
			div.usa-accordion-content {
				padding:1.5rem !important;
			}
			</style>';
			$accordion .= '<div class="usa-accordion bbg__committee-list"><ul class="usa-unstyled-list">';
		    $i = 0;
		    while ( have_rows('accordion_items') ) : the_row();
		        $i++;
		        $itemLabel = get_sub_field('accordion_item_label');
		        $itemText = get_sub_field('accordion_item_text');
				$accordion .= '<li>';
				$accordion .= '<button class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-faq-' . $i . '">' . $itemLabel . '</button>';
				$accordion .= '<div id="collapsible-faq-' . $i . '" aria-hidden="true" class="usa-accordion-content">';
				$accordion .= $itemText;
				$accordion .= '</div>';
				$accordion .= '</li>';
		    endwhile;
		    $accordion .= '</ul></div>';
		endif;
		return $accordion;
	}

	function getInterviewees() {
		    // set the interviewees label field variable
			$intervieweesLabel = get_field('interviews_label');
			// create list variable
			$intervieweesList = "";

		    while ( have_rows('interview_names') ) : the_row();
		    	// open a new div + output label + open list
				$intervieweesList .= '<div><h3 class="bbg__sidebar-label">' . $intervieweesLabel . '</h3><ul class="usa-unstyled-list">';

				if ( get_row_layout() == 'interview_names_internal' ) {
					// set variable for WP object array
					$profileObjects = get_sub_field( 'interviewee_internal' );

					// loop through all the items in the array
					foreach ( $profileObjects as $profile ) {
						// get data out of WP object
						$url = get_permalink( $profile -> ID ); // Use WP object ID to get permalink for link
						$name = $profile -> post_title; // WP object title
						$title = $profile -> occupation; // custom field

						// output list item
						$intervieweesList .= '<li><h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name"><a href="' . $url . '">' . $name . '</a></h5><span class="bbg__profile-excerpt__occupation">' . $title . '</span></li>';
					}

				} elseif ( get_row_layout() == 'interview_names_external' ) {
					// set variable for names array
					$extInterviewees = get_sub_field( 'interviewee_external' );

					// loop through all the items in the array
					foreach ( $extInterviewees as $extName ) {
						// set variables from custom fields
						$externalName = $extName['interviewee_name'];
						$externalTitle = $extName['interviewee_title'];
						$externalURL = $extName['interviewee_url'];

						// output list item
						$intervieweesList .= '<li><h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name"><a href="' . $externalURL . '">' . $externalName . '</a></h5><span class="bbg__profile-excerpt__occupation">' . $externalTitle . '</span></li>';
					}
				}

				// close list and div
		    endwhile;

			$intervieweesList .= '</ul></div>';

		return $intervieweesList;
	}
?>
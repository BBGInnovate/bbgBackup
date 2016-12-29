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
?>
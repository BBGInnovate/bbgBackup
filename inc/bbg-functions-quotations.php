<?php
	function getAllQuotes( $entity, $idsToExclude ) {
		//	allEntities or rfa, rferl, voa, mbn, ocb
		if ( $entity == 'allEntities' ) {
			$qParams = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'order' => 'DESC',
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => array( 'post-format-quote' )
					)
				),
				'post__not_in' => $idsToExclude
			);
		} else {
			$qParams = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'order' => 'DESC',
				'tax_query' => array(
					'relation' => 'AND',
	                array(
				        'taxonomy' => 'post_format',
				        'field'    => 'slug',
				        'terms'    => array( 'post-format-quote' ),
	                ),
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => array( $entity )
					),
				),
				'post__not_in' => $idsToExclude
			);
		}

		$quotes = array();
		$custom_query = new WP_Query($qParams);
		while ( $custom_query -> have_posts() )  {
			$custom_query -> the_post();
			$id = get_the_ID();
			$speaker = get_post_meta( $id, 'quotation_speaker', true );
			$quoteTagline = get_post_meta( $id, 'quotation_tagline', true );
			//$quoteDate = get_post_meta( $id, 'quotation_date', true );
			$quoteDate = get_field( 'quotation_date', $id, true );

			$quoteMugshotID = get_post_meta( get_the_ID(), 'quotation_mugshot', true );
			$quoteMugshot = '';

			if ( $quoteMugshotID ) {
				$quoteMugshot = wp_get_attachment_image_src( $quoteMugshotID , 'mugshot' );
				$quoteMugshot = $quoteMugshot[0];
			}

			// populate array with quotation posts
			$quotes[] = array(
				'ID' => $id,
				'url' => get_permalink( $id ),
				'quoteNetwork' => get_the_category( $id ),
				'quoteDate' => $quoteDate,
				'speaker' => $speaker,
				'quoteText' => get_the_content(),
				'quoteTagline' => $quoteTagline,
				'quoteMugshot' => $quoteMugshot
			);
		}
		wp_reset_postdata();
		return $quotes;
	}
	function getRandomQuote( $entity, $idsToExclude ) {
		//	allEntities or rfa, rferl, voa, mbn, ocb
		$allQuotes = getAllQuotes( $entity, $idsToExclude );
		$returnVal = false;
		if ( count( $allQuotes ) ) {
			$randKey = array_rand( $allQuotes );
			$returnVal = $allQuotes[$randKey];
		}
		return $returnVal;
	}

	function outputQuote( $q, $class = '' ) {
		$quoteDate = $q['quoteDate'];
		$ID = $q['ID'];
		$url = $q['url'];
		$speaker = $q['speaker'];
		$quoteText = $q['quoteText'];
		$tagline = $q['quoteTagline'];
		if ( $tagline != '' ) {
			$tagline = '' . $tagline;
		}
		$mugshot = $q['quoteMugshot'];

		$catArray = $q['quoteNetwork'];

		foreach ( (get_the_category()) as $cat ) {
			// $catName = $cat.cat_name;
			// var_dump($cat);
			$quoteNetwork = $cat->cat_name . ' ';
		}
		// $quoteNetwork = ;
		// var_dump($catName);

		$quote = '';
		$quote .= '<div class="bbg__quotation $class">';
			$quote .= '<div class="bbg__quotation-label">' . $quoteNetwork . '</div>';
			$quote .= '<h2 class="bbg__quotation-text--large">&ldquo;' . $quoteText . '&rdquo;</h2>';
			$quote .= '<div class="bbg__quotation-attribution__container">';
				$quote .= '<p class="bbg__quotation-attribution">';

				if ( $mugshot != '' ) {
					$quote .= '<img src="$mugshot" class="bbg__quotation-attribution__mugshot"/>';
				}
				$quote .= '<span class="bbg__quotation-attribution__text">';
				$quote .= '<span class="bbg__quotation-attribution__name">' . $speaker . '</span>';
				$quote .= '<span class="bbg__quotation-attribution__credit">' . $tagline . '</span>';
				$quote .= '</span></p>';
			$quote .= '</div>';
		$quote .= '</div>';
		echo $quote;
	}
?>
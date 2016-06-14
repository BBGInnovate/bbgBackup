<?php 
	function outputInterns() {
		$qParams=array(
			'post_type' => array('post'),
			'posts_per_page' => 5,
			'category__and' =>  array(
									get_cat_id("Intern Testimonial")
								),
			'orderby', 'date',
			'order', 'DESC'
		);
		$custom_query = new WP_Query($qParams);
		$s = "";
		while ( $custom_query->have_posts() )  {
			$custom_query->the_post();
			$id = get_the_ID();
			$permalink = get_the_permalink();
			$profilePhotoID = get_post_meta( $id, 'intern_profile_photo', true );
			$internName = get_post_meta( $id, 'intern_name', true );
			$internSchool = get_post_meta( $id, 'intern_school', true );
			$profilePhoto = "";

			if ($profilePhotoID) {
				$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
				$profilePhoto = $profilePhoto[0];
			}
			$s .= '<div class="bbg__profile__intern">';
			$s .= '<span class="bbg__profile__intern__job-label">';
			$s .= '<a href="' . $permalink . '">' . get_the_title() . '</a>';
			$s .= '</span>';
			if ($profilePhoto != "") {
				//title="Lindsay Matthews" alt="Lindsay Matthews"
				$s .= '<img class="bbg__mugshot"  src="'.$profilePhoto.'"  />';
			}
			$s .= get_the_excerpt();
			if ($internSchool != "") {
				$s .= "<strong>—$internName,</strong> $internSchool";
			} else {
				$s .= "<strong>—$internName</strong>";
			}
			$s .= "</div>";
		}

		return $s;
	}
	function intern_list_shortcode() {
		return outputInterns();
	}
	add_shortcode('intern_list', 'intern_list_shortcode');

?>
<?php 

	/*===================================================================================
	 * Manually Adding Custom Styles to WordPress Visual Editor
	 * based on http://www.wpbeginner.com/wp-tutorials/how-to-add-custom-styles-to-wordpress-visual-editor
	 * =================================================================================*/

	// Add 'styleselect' drop-down menu on the second row of the buttons
	function wpb_mce_buttons_2($buttons) {
		array_unshift($buttons, 'styleselect');
		return $buttons;
	}
	// Attach callback to 'wpb_mce_buttons_2'
	add_filter('mce_buttons_2', 'wpb_mce_buttons_2');


	 // Callback function to filter the MCE settings
	function my_mce_before_init_insert_formats( $init_array ) {

	// Define the style_formats array
		$style_formats = array(
			// Each array child is a style with it's own settings
			array(
				'title' => 'Subheads',
				'block' => 'h3',
				'classes' => 'bbg__article-content__subhead',
				'wrapper' => false,
			),
			array(
				'title' => 'Section Title',
				'block' => 'h4',
				'classes' => 'bbg__article-content__section-title',
				'wrapper' => false,
			),
			array(
				'title' => 'Related Links',
				'block' => 'span', // Block or inline-block element to wrap highlighted content
				'classes' => 'bbg__related-links', // Name(s) of classes to add to the element
				'wrapper' => true,

			),
			array(
				'title' => 'Pullquotes',
				'block' => 'blockquote',
				'classes' => 'pullquote',
				'wrapper' => false,
			),
			array(
				'title' => 'BBG tagline',
				'block' => 'span',
				'classes' => 'bbg-tagline',
				'wrapper' => true,
			),
			array(
				'title' => 'Extra-wide images',
				'block' => 'div',
				'classes' => 'bbg__article-content__image--extra-large',
				'wrapper' => false,
			)

		);
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array;

	}
	// Attach callback to 'tiny_mce_before_init'
	add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );

	/**
	 * Add function to include additional editor stylesheets (if needed)
	 * only needed if different from default: 'editor-style.css'
	 * stylesheet location is relative to the theme root
	 */
	function my_theme_add_editor_styles() {
		add_editor_style( 'editor-styles.css' );
	}
	add_action( 'init', 'my_theme_add_editor_styles' );

?>
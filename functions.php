<?php
/**
 * bbgRedesign functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bbgRedesign
 */

require "config_bbgWPtheme.php";

if ( ! function_exists( 'bbginnovate_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bbginnovate_setup() {
		/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bbginnovate, use a find and replace
	 * to change 'bbginnovate' to the name of your theme in all the template files.
	 */
		load_theme_textdomain( 'bbginnovate', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
		add_theme_support( 'title-tag' );

		/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'large-thumb', 1040, 624, true );
		add_image_size( 'medium-thumb', 600, 360, true );
		add_image_size( 'small-thumb', 300, 180, true );
		//add_image_size( 'largest', 1200, 9999 ); // new size at our max breaking point
		add_image_size( 'gigantic', 1900, 9999 ); // for some huge monitors
		add_image_size( 'mugshot', 200, 200, true );

		function my_custom_sizes( $sizes ) {
			/*  NOTE: the $sizes array here is simply an associative array.  It doesn't provide actual dimensions.
				We are hardcoding that Mugshot goes second now (and thumbnail first) ... a more robust solution
				could leverage something like https://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
			*/
			/*
			$newArray=array( 'mugshot' =>'Mugshot');
			foreach ($sizes as $key => $value) {
				$newArray[$key]=$value;
			}
			$reorderedSizes=array_swap("mugshot","thumbnail",$newArray);
			*/
			return array_merge( $sizes, array(
		        'mugshot' => __('Mugshot'),
		        'large-thumb' => __('Extra Large'),
		    ) );

			return $reorderedSizes;
		}
		add_filter( 'image_size_names_choose', 'my_custom_sizes' );


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
				'primary' => esc_html__( 'Primary', 'bbginnovate' ),
				'menu-side' => esc_html__( 'Menu Side', 'bbginnovate-side-menu' ),
			) );

		/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
		add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );

		/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
		add_theme_support( 'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'bbginnovate_custom_background_args', array(
					'default-color' => 'ffffff',
					'default-image' => '',
				) ) );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}
endif; // bbginnovate_setup
add_action( 'after_setup_theme', 'bbginnovate_setup' );


/* Add an html version of the site title */
function bbginnovate_site_name_html(){
	$html_site_name = get_bloginfo( 'name' );

	//SITE_TITLE_MARKUP is defined in config_bbgWPtheme.php
	if (defined('SITE_TITLE_MARKUP')) {
		$html_site_name = SITE_TITLE_MARKUP;
	}
	return $html_site_name;
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bbginnovate_content_width() {
	//$GLOBALS['content_width'] = apply_filters( 'bbginnovate_content_width', 600 );
}
add_action( 'after_setup_theme', 'bbginnovate_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bbginnovate_widgets_init() {
	/* This is the sidebar that came with _s theme */
	register_sidebar( array(
			'name'          => esc_html__( 'Sidebar 1', 'underscores' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

	/* This is the new sidebar for bbginnovate theme */
	register_sidebar( array(
			'name'          => esc_html__( 'Sidebar 2', 'bbginnovate' ),
			'id'            => 'sidebar-2',
			'description'   => 'This sidebar incorporates the side menu by USDS (https://playbook.cio.gov/designstandards/sidenav/)',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
}
add_action( 'widgets_init', 'bbginnovate_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bbginnovate_scripts() {
	wp_enqueue_style( 'bbginnovate-style', get_stylesheet_uri() );

	// wp_enqueue_style( 'bbginnovate-style-fonts-google', get_template_directory_uri() . "/css/google-fonts.css" );

	wp_enqueue_style( 'bbginnovate-style-fonts', get_template_directory_uri() . "/css/bbg-fonts.css" );

	wp_enqueue_script( 'bbginnovate-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'bbginnovate-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	//wp_enqueue_script( 'usa-web-design-standards', get_template_directory_uri() . '/js/start.js', array(), '20130115', true );

	//wp_enqueue_script( 'bbgWPtheme', get_template_directory_uri() . '/js/bbgWPtheme.js', array( 'jquery' ));
	wp_enqueue_script( 'bbginnovate-bbgredesign', get_template_directory_uri() . '/js/bbgredesign.js', array('jquery'), '20160223', true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'bbginnovate-accordion', get_template_directory_uri() . '/js/components/accordion.js', array(), '20160223', true );
	wp_enqueue_script( 'bbginnovate-18f', get_template_directory_uri() . '/js/18f.js', array(), '20160223', true );

	wp_enqueue_script( 'bbginnovate-bbginnovate', get_template_directory_uri() . '/js/bbginnovate.js', array(), '20160223', true );
}
add_action( 'wp_enqueue_scripts', 'bbginnovate_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * Extend Walker function to add usa-sidenav-sub_list class to submenu navigation items
 */
class themeslug_walker_nav_menu extends Walker_Nav_Menu {
	// add classes to ul sub-menus
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1 ); // because it counts the first submenu as 0
		$classes = array(
			'sub-menu',
			'usa-sidenav-sub_list',
			//'usa-current',
			//( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
			( $display_depth >=2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth
		);
		$class_names = implode( ' ', $classes );

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	//
	// add main/sub classes to li's and links
	function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// depth dependent classes
		$depth_classes = array(
			( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >=2 ? 'sub-sub-menu-item' : '' ),
			//( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
		$current_class_names = '';
		// if the class array includes "current-menu-item" or "current_page_item" then set variable to "usa-current"
		if( in_array('current-menu-item', $classes) || in_array('current_page_item', $classes) ){
			 $current_class_names = 'usa-current ';
		}

		// build html
		$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $current_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . ' ' . $current_class_names . '"';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Add Twitter handle to author metadata using built-in wp hook for contact methods
 * Reference: http://www.paulund.co.uk/how-to-display-author-bio-with-wordpress
 */
function bbg_extendAuthorContacts( $c ) {
$c['twitterHandle'] = 'Twitter Handle';
return $c;
}
add_filter( 'user_contactmethods', 'bbg_extendAuthorContacts', 10, 1 );


/**
 * Add Twitter handle to author metadata using built-in wp hook for contact methods
 * Reference: http://www.paulund.co.uk/how-to-display-author-bio-with-wordpress
 */
add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
	$class = str_replace("class='avatar", "class='avatar usa-avatar bbg-avatar", $class) ;

	//Adding a second version because we're using WP User Avatar plugin and it uses double quotes
	$class = str_replace('class="avatar', 'class="avatar usa-avatar bbg-avatar', $class) ;
	return $class;
}


/**
* Removing labels from archive.php pages (e.g. "Category: XYZ")
*/
add_filter( 'get_the_archive_title', function ($title) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' ;
	}
	return $title;
});



/**
 * Add non-standard metadata to author
 * Reference: http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
 */

add_action( 'show_user_profile', 'bbg_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'bbg_show_extra_profile_fields' );
function bbg_show_extra_profile_fields( $user ) {
	$isActive=esc_attr( get_the_author_meta( 'isActive', $user->ID ));
	$isActiveChecked= ($isActive=="on") ? "checked" : "";
	$headOfTeam=esc_attr( get_the_author_meta( 'headOfTeam', $user->ID ));


?>

	<h3>Additional Profile Information</h3>

	<table class="form-table">

		<tr>
			<th><label for="occupation">Occupation</label></th>

			<td>
				<input type="text" name="occupation" id="occupation" value="<?php echo esc_attr( get_the_author_meta( 'occupation', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Occupation.</span>
			</td>
		</tr>
		<tr>
			<th><label for="isActive">Active</label></th>

			<td>
				<input type="checkbox" name="isActive" id="isActive" <?php echo $isActiveChecked; ?>  />
				<span class="description">User is active</span>
			</td>
		</tr>
		<tr>
			<th><label for="isActive">Head of Team</label></th>
			<td><select name="headOfTeam"><option value="">None</option>
				<?php
					$categories = get_terms('category', 'hide_empty=0' );
					foreach ( $categories as $category ) {
						$optionSelected=($headOfTeam==$category->term_id) ? "selected" : "";
						$term = get_option( "taxonomy_" . $category->term_id );
						if ($term['isTeamName'] == 1 ) {
							printf( '<option %1$s value="%2$s">%3$s</option>',
								$optionSelected,
								esc_attr( $category->term_id ),
								esc_html( $category->name )
						    );
						}
					}
				?>
				</select>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'occupation', $_POST['occupation'] );
	update_usermeta( $user_id, 'isActive', $_POST['isActive'] );
	update_usermeta( $user_id, 'headOfTeam', $_POST['headOfTeam'] );
}

/*=========== ADD USERID TO USER LIST SO THAT IT'S EASY TO SET THE RANKING GENERAL SETTING *===========*/
add_filter('manage_users_columns', 'pippin_add_user_id_column');
function pippin_add_user_id_column($columns) {
	$new = array();
	foreach($columns as $key => $title) {
		if ($key=='username') {
			$new['user_id'] = 'ID';
			$new['isActive'] = 'Active';
		}
		$new[$key] = $title;
	}
	return $new;
}

add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'user_id' == $column_name ) {
		return $user_id;
	}
	if ('isActive' == $column_name) {
		return ($user->isActive == "on") ? "<span class='adminYes'>Yes</span>": "<span class='adminNo'>No</span>";
	}
    return $value;
}

function admin_css() {
   echo '<style type="text/css">
           th#user_id {width:50px;}
           th#isActive {width:100px;}
           .adminYes {color:green;}
           .adminNo {color:red;}
         </style>';
}

add_action('admin_head', 'admin_css');

// add a custom field to the 'GENERAL' section of wordpress with our list of featured userIDs
function featuredUserIDs_callback( $args ) {
	$val = get_option( 'featuredUserIDs' );
	if (! $val ) {
		$val = '';
	}
	$html = '<input type="text" id="featuredUserIDs" name="featuredUserIDs" value="' . $val . '" size="35" class="regular-text" />';
	$html .= '<p class="description" >(comma separated list of id\'s for users on homepage - get them from <a target="_blank" href="users.php">Users</a></label>)';
	echo $html;
}
function featuredCategoryIDs_callback( $args ) {
	$val = get_option( 'featuredCategoryIDs' );
	if (! $val ) {
		$val = '';
	}
	$html = '<input type="text" id="featuredCategoryIDs" name="featuredCategoryIDs" value="' . $val . '" size="35" class="regular-text" />';
	$html .= '<p class="description" >(comma separated list of id\'s for categories on homepage - get them from term_id in query strings on <a target="_blank" href="edit-tags.php?taxonomy=category">Categories</a></label>)';
	echo $html;
}

function oddi_settings_api_init() {
	/*
	add_settings_field(
		'featuredUserIDs',
		'Featured User IDs',
		'featuredUserIDs_callback',
		'general'
	);
	register_setting('general','featuredUserIDs');
	*/
	add_settings_field(
		'featuredCategoryIDs',
		'Featured Category IDs',
		'featuredCategoryIDs_callback',
		'general'
	);
	register_setting('general','featuredCategoryIDs');
}

add_action( 'admin_init', 'oddi_settings_api_init' );

function enqueueAdminStyles() {
	wp_enqueue_style( 'bbginnovate_admin_css', get_template_directory_uri() . '/bbgredesign_admin.css', array(), '20160403' );
}

add_action( 'admin_enqueue_scripts', 'enqueueAdminStyles' );
add_action( 'admin_init', 'oddi_settings_api_init' );



/*===================================================================================
 * ADD CATEGORY METADATA - http://php.quicoto.com/add-metadata-categories-wordpress/
 * We needed the ability to
 * =================================================================================*/


function xg_edit_featured_category_field( $term ){
    $term_id = $term->term_id;
    $term_meta = get_option( "taxonomy_$term_id" );
?>
    <tr class="form-field">
        <th scope="row">
            <label for="term_meta[isTeamName]"><?php echo _e('Is Team Name') ?></label>
            <td>
            	<select name="term_meta[isTeamName]" id="term_meta[isTeamName]">
                	<option value="0" <?=($term_meta['isTeamName'] == 0) ? 'selected': ''?>><?php echo _e('No'); ?></option>
                	<option value="1" <?=($term_meta['isTeamName'] == 1) ? 'selected': ''?>><?php echo _e('Yes'); ?></option>
            	</select>
            </td>
        </th>
    </tr>
<?php
}
function xg_save_tax_meta( $term_id ){
    if ( isset( $_POST['term_meta'] ) ) {
		$term_meta = array();
		$term_meta['isTeamName'] = isset ( $_POST['term_meta']['isTeamName'] ) ? intval( $_POST['term_meta']['isTeamName'] ) : '';
		update_option( "taxonomy_$term_id", $term_meta );
	}
} // save_tax_meta

add_action( 'category_edit_form_fields', 'xg_edit_featured_category_field' );
add_action( 'edited_category', 'xg_save_tax_meta', 10, 2 );


/*===================================================================================
 * CUSTOM PAGINATION LOGIC - we show X posts on front page but more posts on 'older post' pages
 * the next several functions are for adding that functionality and also making it available in wordpress settings
 * =================================================================================*/

add_action('pre_get_posts', 'bbginnovate_query_offset', 1 );
function bbginnovate_query_offset(&$query) {
	/* note that is_home really means 'blog index page' ... which is not necessarily the homepage */

	if ( $query->is_main_query() && !is_admin() && ($query -> is_home() || $query->is_archive() )) {
		$tax_query = array(
			//'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => array(
					get_cat_id('Site Introduction'),
					get_cat_id("John's take"),
					get_cat_id('Contact')
				),
				'operator' => 'NOT IN',
			)
		);
		$query->set( 'tax_query', $tax_query );
	}
	if ( ! ($query->is_home() &&  $query->is_main_query()) ) {
		return;
	}
}

/*===================================================================================
 * CUSTOM YOUTUBE EMBED LOGIC - Always make youtube emebeds responsive
 * see http://tutorialshares.com/youtube-oembed-urls-remove-showinfo/
 * =================================================================================*/

function custom_youtube_settings($code){
	if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
		//$return = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&showinfo=0&rel=0&autohide=1", $code);

		//remove the width/height attributes
		$return = preg_replace(
			array('/width="\d+"/i', '/height="\d+"/i'),
				array('',''),
			$code);
		//wrap in a responsive div
		$return="<div class='bbg-embed-shell'><div class='embed-container'>" . $return . "</div></div>";
	} else {
		$return = $code;
	}
	return $return;
}
add_filter('embed_handler_html', 'custom_youtube_settings');
add_filter('embed_oembed_html', 'custom_youtube_settings');


	/*
	add_filter( 'pre_update_option_blogname', 'blogname_with_html', 10, 2 );
	function blogname_with_html( $value, $old_value ) {
		return "saved something";
	}
	*/

/*===================================================================================
 * CUSTOM POST CATEGORY LIST LOGIC
 * =================================================================================*/
if ( ! function_exists( 'bbginnovate_post_categories' ) ) :
	/**
	 * Returns categories for current post with separator.
	 * Optionally returns only a single category.
	 *
	 * @since bbginnovate 1.0
	 */
	function bbginnovate_post_categories() {
		$separator='';
		$categories = get_the_category();
		$output     = '';
		$selectedCategory=false;
		if ( $categories ) {

			/* team categories take priority */
			foreach ( $categories as $category ) {
				$term = get_option( "taxonomy_" . $category->term_id );
				if ( $term['isTeamName'] == 1 ) {
					$selectedCategory=$category;
					break;
				}
			}
			/* followed by any non-project category */
			if ( !$selectedCategory ) {
				foreach ( $categories as $category ) {
					if ( $category->name != "Project" ) {
						$selectedCategory = $category;
						break;
					}
				}
			}
			/* followed by project ... probably don't need a loop here but it's fien */
			if ( !$selectedCategory ) {
				foreach ( $categories as $category ) {
					$selectedCategory = $category;
				}
			}
			if ($selectedCategory) {
				$output .= '<h5 class="entry-category bbg-label"><a href="' . get_category_link( $selectedCategory->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'bbginnovate' ), $selectedCategory->name ) ) . '">' . $selectedCategory->cat_name . '</a></h5>' . $separator;
			}
		}
		return $output;
	}
endif;

/*===================================================================================
 * CUSTOM POST EXCERPTS LOGIC
 * =================================================================================*/
if ( ! function_exists( 'bbg_first_sentence_excerpt' ) ):
	/**
	 * Return the post excerpt. If no excerpt set, generates an excerpt using the first sentence.
	 * Based on same function from the independent publisher theme http://independentpublisher.me/
	 */
	function bbg_first_sentence_excerpt( $text = '' ) {
		global $post;
		$content_post = get_post( $post->ID );

		// Only generate a one-sentence excerpt if there is no excerpt set and One Sentence Excerpts is enabled
		if ( ! $content_post->post_excerpt ) {

			// The following mimics the functionality of wp_trim_excerpt() in wp-includes/formatting.php
			// and ensures that no shortcodes or embed URLs are included in our generated excerpt.
			$text           = get_the_content( '' );
			$text           = strip_shortcodes( $text );
			$text           = apply_filters( 'the_content', $text );
			$text           = str_replace( ']]>', ']]&gt;', $text );
			$excerpt_length = 150; // Something long enough that we're likely to get a full sentence.
			$excerpt_more   = ''; // Not used, but included here for clarity

			$startIndex = 0;

			$firstP_openPosition = strpos( $text, "<p" );
			if ( $firstP_openPosition !== false ) {
				$firstP_closePosition = strpos( $text, ">", $firstP_openPosition );
				if ( $firstP_closePosition !== false ) {
					$startIndex = $firstP_closePosition +1;
				}
			}
			$endIndex=strpos($text, "</p>")+4;
			$strLength=$endIndex-$startIndex;
			$text = substr($text, $startIndex, $strLength);
			//$text ='<p>' . strip_tags($text) . '</p>';
			$text = strip_tags($text);
			/*** ODDI CUSTOM - REMOVE ONE SENTENCE LOGIC
			$text           = wp_trim_words( $text, $excerpt_length, $excerpt_more ); // See wp_trim_words() in wp-includes/formatting.php

			// Get the first sentence
			// This looks for three punctuation characters: . (period), ! (exclamation), or ? (question mark), followed by a space
			$strings = preg_split( '/(\.|!|\?)\s/', strip_tags( $text ), 2, PREG_SPLIT_DELIM_CAPTURE );

			// $strings[0] is the first sentence and $strings[1] is the punctuation character at the end
			if ( ! empty( $strings[0] ) && ! empty( $strings[1] ) ) {
				$text = $strings[0] . $strings[1];
			}

			$text = wpautop( $text );
			****/
		}

		return $text;
	}
endif;

add_filter( 'get_the_excerpt', 'bbg_first_sentence_excerpt' );

/*===================================================================================
 * CUSTOM AUTHOR BOX CONTENT LOGIC
 * =================================================================================*/
if ( ! function_exists( 'bbg_post_author_bottom_card' ) ) :
	/**
	 * Outputs post author info for display on bottom of single posts
	 *
	 */
	//$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );

	function bbg_post_author_bottom_card($theAuthorID) {
		$curauth = get_userdata( $theAuthorID );

		/**** BEGIN PREPARING AUTHOR vars ****/
		$authorPath = get_author_posts_url($curauth -> ID);
		$authorName = $curauth -> display_name;
		$avatar = get_avatar( $theAuthorID , apply_filters( 'change_avatar_css', 150 ) );
		//$website = $curauth -> user_url;
		//$website = str_replace('http://', '', $website);
		$website = '';
		//$authorEmail = $curauth -> user_email;
		$authorEmail = "";

		$teamLeader = $curauth->headOfTeam;
		$addSeparator = FALSE;
		if ( isset($teamLeader) && $teamLeader!="" ){
			$authorEmail = '<a href="mailto:'. $curauth->user_email .'">'. $curauth->user_email .'</a>';
			$addSeparator = TRUE;
		}



		$m = get_user_meta( $theAuthorID );
		$twitterHandle = "";
		$twitterLink = "";
		if ( isset( $m['twitterHandle'] ) ) {
			$twitterHandle = $m['twitterHandle'][0];
		}

		$occupation = "";
		if ( isset( $m['occupation'] ) ) {
			$occupation = $m['occupation'][0];
		}
		$description = "";
		if ( isset( $m['description'] ) ) {
			$description = $m['description'][0];
		}
		/**** DONE PREPARING AUTHOR vars ****/
		?>

		<!-- <div class="usa-grid"> -->
			<div class="bbg__article-author">

				<div class="bbg-avatar__container">
					<?php echo $avatar; ?>
				</div>

				<div class="bbg__author__text">

					<h2 class="bbg-staff__author-name">
						<a href="<?php echo $authorPath ?>" class="bbg__author-link"><?php echo $authorName; ?></a>
					</h2><!-- .bbg-staff__author-name -->

					<div class="bbg__author-description">
						<?php echo '<div class="bbg__author-occupation">' . $occupation . '</div>'; ?>

						<div class="bbg__author-bio">
							<?php echo $description; ?>
						</div>

					</div><!-- .bbg-staff__author-description -->

					<div class="bbg__author-contact">
						<?php
							if ( $twitterHandle && $twitterHandle != '' ) {
								$twitterHandle = str_replace( "@", "", $twitterHandle );
								$twitterLink = '<span class="bbg__author-contact__twitter"><a href="//www.twitter.com/' . $twitterHandle. '">@' . $twitterHandle . '</a></span>';

								if ( $addSeparator ) {
									$twitterLink = '<span class="u--seperator"></span> ' . $twitterLink;
								}
							}
							echo $authorEmail . $twitterLink;
						?>
					</div> <!-- .bbg-staff__author-contact -->

				</div><!-- .bbg-staff__author__text -->
		</div><!-- .bbg__article-author -->
		<?php
		do_action( 'bbg_post_author_bottom_card' );
	}
endif;

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


/**
 * Search results category-only footer
 * prints meta information for the categories
 */
if ( ! function_exists( 'search_excerpt_footer' ) ) :

function search_excerpt_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'bbginnovate' ) );
		if ( $categories_list && bbginnovate_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'bbginnovate' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
	}
}

endif;


// Add shortcode reference to Innovation Series on old posts and pages
function innovation_series_shortcode() {
	return '<p class="bbg-tagline">This post originally appeared on <a href="http://www.innovation-series.com/" target="_blank">Innovation Series</a>.</p>';
}
add_shortcode('tagline', 'innovation_series_shortcode');

// Add shortcode reference for the number of supported languages
function languages_shortcode() {
	$number_of_languages = 61;
	return $number_of_languages . " languages";
}
add_shortcode('languages', 'languages_shortcode');


//Grab the list of congressional committee members from the Sunlight Foundation's API
function getCommittee($committeeID, $committeeTitle) {
	$committeeFilepath = get_template_directory() . "/committeecache$committeeID.json";
	if (!file_exists($committeeFilepath)) {
		//use http://tryit.sunlightfoundation.com/congress to try out the api
		$url="https://congress.api.sunlightfoundation.com/committees?apikey=".SUNLIGHT_API_KEY . "&committee_id=$committeeID&fields=members";
		$result=fetchUrl($url);
		file_put_contents($committeeFilepath, $result);
	} else {
		$result=file_get_contents($committeeFilepath);
	}
	$c2 = json_decode($result, true);
	$members=$c2["results"][0]["members"];

	$minority=array();
	$majority=array();
	foreach($members as $m) {
		if ($m['side']=='majority') {
			$majority[]=$m;
		} else {
			$minority[]=$m;
		}
	}

$s='<div class="usa-accordion bbg__committee-list">';
$s.='<ul class="usa-unstyled-list">';
$s.='<li>';
$s.='<button class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-'.$committeeID.'">';
$s.=$committeeTitle;
$s.='</button>';
$s.='<div id="collapsible-'.$committeeID.'" aria-hidden="true" class="usa-accordion-content">';




	$s.="<section class='usa-grid-full'>";
	$s.= "<div class='bbg-grid--1-1-1-2'>";
	$s.= "<strong>MAJORITY</strong> (".$majority[0]['legislator']['party'].")";
	$s.= "<ul class='usa-unstyled-list'>";
	foreach ($majority as $m) {
		$firstName=$m['legislator']['first_name'];
		$lastName=$m['legislator']['last_name'];
		$state=$m['legislator']['state'];
		$title='';
		if (isset($m['title'])) {
			$title=' <em>— '.$m['title'].'</em>';
		}
		$s.= "<li>$firstName $lastName, $state $title</li>";
	}
	$s.= "</ul>";
	$s.= "</div><!-- .bbg-grid -->";
	$s.= "<div class='bbg-grid--1-1-1-2'>";
	$s.=  "<strong>MINORITY</strong> (".$minority[0]['legislator']['party'].")";
	$s.= "<ul class='usa-unstyled-list'>";
	foreach ($minority as $m) {
		$firstName=$m['legislator']['first_name'];
		$lastName=$m['legislator']['last_name'];
		$state=$m['legislator']['state'];
		$title='';
		if (isset($m['title'])) {
			$title=' <em>— '.$m['title'].'</em>';
		}
		$s.= "<li>$firstName $lastName, $state $title</li>";
	}
	$s.= "</ul>";
	$s.= "</div><!-- .bbg-grid -->";
	$s.= "</section><!-- .usa-grid -->";


$s.= '</div>';
$s.= '</li>';
$s.= '</ul>';
$s.= '</div>';

	return $s;
}

// Add shortcode reference to Innovation Series on old posts and pages
function committee_shortcode($atts) {
	return getCommittee($atts['id'], $atts['title']);
}
add_shortcode('committee', 'committee_shortcode');


/* ODDI CUSTOM: Clear FB Cache when someone updates or publishes a post */
function clearFBCache( $post_ID, $post) {
	$urlToClear = get_permalink($post_ID);
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL,"https://graph.facebook.com");
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query(array('scrape' => 'true','id' => $urlToClear)));
	curl_exec ($ch);
	curl_close ($ch);

}
add_action( 'publish_post', 'clearFBCache', 10, 2 );

function sortByTitle($a, $b) {
    return strcmp($a["title"], $b["title"]);
}

function acf_load_contact_card_choices( $field ) {
    //http://stackoverflow.com/questions/4452599/how-can-i-reset-a-query-in-a-custom-wordpress-metabox#comment46272169_7845948
    //note that wp_reset_postdata doesn't work here, so we have to store a reference to post and put it back when we're done.  documented wordpress "bug"

	global $post;
	$post_original=$post;
    $field['choices'] = array();
    $qParamsContact=array(
		'post_type' => array('post')
		,'cat' => get_cat_id('contact')
		,'posts_per_page' => 100
	);
	$custom_query = new WP_Query( $qParamsContact );
	$choices = array();
	while ( $custom_query -> have_posts() )  {
		$custom_query->the_post();
		$choices[] = array(
			"post_id"=>get_the_ID(),
			"title"=>get_the_title()
		);
	}
	usort($choices, 'sortByTitle');
	foreach ($choices as $choice) {
		$field['choices'][ $choice["post_id"]] = $choice["title"];
	}

	$post=$post_original;
	// return the field
	return $field;
}

add_filter('acf/load_field/name=contact_post_id', 'acf_load_contact_card_choices');

function acf_load_committee_member_choices( $field ) {
    //http://stackoverflow.com/questions/4452599/how-can-i-reset-a-query-in-a-custom-wordpress-metabox#comment46272169_7845948
    //note that wp_reset_postdata doesn't work here, so we have to store a reference to post and put it back when we're done.  documented wordpress "bug"

	global $post;
	$post_original=$post;
    $field['choices'] = array();


    $boardPage=get_page_by_title('The Board');

	$qParams=array(
		'post_type' => array('page')
		,'post_status' => array('publish')
		,'post_parent' => $boardPage->ID
		,'orderby' => 'meta_value'
		,'meta_key' => 'last_name'
		,'order' => 'ASC'
		,'posts_per_page' => 100
	);
	$custom_query = new WP_Query($qParams);

	$choices = array();
	while ( $custom_query -> have_posts() )  {
		$custom_query->the_post();
		if (get_the_title() != 'Committees') {
			$choices[] = array(
				"post_id"=>get_the_ID(),
				"title"=>get_the_title()
			);
		}
	}
	usort($choices, 'sortByTitle');
	foreach ($choices as $choice) {
		$field['choices'][ $choice["post_id"]] = $choice["title"];
	}

	$post=$post_original;
	// return the field
	return $field;
}

add_filter('acf/load_field/name=committee_members', 'acf_load_committee_member_choices');
add_filter('acf/load_field/name=committee_chair', 'acf_load_committee_member_choices');

function fetchUrl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

function getJobs() {
	$jobsFilepath = get_template_directory() . "/jobcache.json";
	if (! file_exists($jobsFilepath)) {
		$jobsUrl="https://api.usa.gov/jobs/search.json?organization_ids=IB00";
		$result=fetchUrl($jobsUrl);
		file_put_contents($jobsFilepath, $result);
	} else {
		$result=file_get_contents($jobsFilepath);
	}
	$jobs = json_decode($result, true);

	return $jobs;

}

function getFeed($url,$id) {
	$feedFilepath = get_template_directory() . "/" . $id . ".xml";
	if (! file_exists($feedFilepath)) {
		$feedStr=fetchUrl($url);
		file_put_contents($feedFilepath, $feedStr);
	} else {
		$feedStr=file_get_contents($feedFilepath);
	}
	//$feed = implode(file($feedStr));
	$xml = simplexml_load_string($feedStr);
	$json = json_encode($xml,JSON_PRETTY_PRINT);
	$json=json_decode($json);
	return $json;
}

function getEntityLinks($entityID) {
	$url="http://api.bbg.gov/api/groups";
	$feedFilepath = get_template_directory() . "/groupscache.json";
	if (! file_exists($feedFilepath)) {
		$feedStr=fetchUrl($url);
		file_put_contents($feedFilepath, $feedStr);
	} else {
		$feedStr=file_get_contents($feedFilepath);
	}
	$json=json_decode($feedStr);

	$g=false;
	foreach ($json as $group) {
		if ($group->id ==$entityID) {
			$g=$group->subgroups;
		}
	}
	return $g;
}

/**** We use the excerpts on certain pages as structured data - for instance pages of individual Board Members have excerpts that drive their display in the Board Member list ***/
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

function renderContactCard($postIDs) {
	if (is_array($postIDs) && count($postIDs) > 0) {
		$qParamsContactCard=array(
			'post__in' => $postIDs,
			'ignore_sticky_posts' => true
		);
		$custom_query = new WP_Query( $qParamsContactCard );
		if ( $custom_query->have_posts() ) :
			echo '<div class="usa-grid-full bbg__contact-box">';
			echo '<h3 class="bbg__contact-box__title">Find out more</h3>';
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				//now let's get the custom fields associated with our related contact posts
				$id=get_the_ID();
				$email = get_post_meta( $id, 'email',true );
				$fullname = get_post_meta( $id, 'fullname',true );
				$phone = get_post_meta( $id, 'phone',true );
				$bio = get_the_content($id);
				$office = get_post_meta( $id, 'office',true );
				$jobTitle = get_post_meta( $id, 'job_title',true );

				if ($jobTitle!=""){
					$office = $jobTitle . ", " . $office;
				}

				echo '<div class="bbg__contact__card">';
				echo '<p>Contact '.$fullname.'<br/>';
				echo $office.'</p>';
				echo '<ul class="bbg__contact__card-list">';
				echo '<li class="bbg__contact__link email"><a href="mailto:'.$email.'" title="Email '.$fullname.'"><span class="bbg__contact__icon email"></span><span class="bbg__contact__text">'.$email.'</span></a></li>';
				echo '<li class="bbg__contact__link phone"><span class="bbg__contact__icon phone"></span><span class="bbg__contact__text">'.$phone.'</span></li>';
				echo '</ul></div>';

			endwhile;
			echo '</div>';
		endif;
		wp_reset_postdata();
	}
}

function bbgredesign_get_image_size_links($imgID) {
	//http://justintadlock.com/archives/2011/01/28/linking-to-all-image-sizes-in-wordpress
	$links = array();
	if ( wp_attachment_is_image( $imgID ) ) {
		$sizes = get_intermediate_image_sizes();
		$sizes[] = 'full';
		foreach ( $sizes as $size ) {
			$image = wp_get_attachment_image_src( $imgID, $size );
			/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
			if ( !empty( $image ) && ( true == $image[3] || 'full' == $size ) ) {
				$src=$image[0];
				$w=$image[1];
				$h=$image[2];
				if (false && $size=='full') {
					$key='full';
				} else {
					$key=$image[1];
				}
				$links[$key] = array('src'=>$src, 'width'=>$w,'height'=>$h, 'size'=>$size );
			}
		}
	}
	return $links;
}

/* Output Board Committees */
function outputCommittees() {
	$committeesPage=get_page_by_title('Special Committees');
	$thePostID=$committeesPage->ID;
	$qParams=array(
		'post_type' => array('page')
		,'post_status' => array('publish')
		,'post_parent' => $thePostID
	//	,'orderby' => 'meta_value'
	//	,'meta_key' => 'last_name'
		,'order' => 'ASC'
		,'posts_per_page' => 100
	);
	$custom_query = new WP_Query($qParams);
	$s="";
	$s.="<ul class='bbg__board__committee-list'>";
	while ( $custom_query->have_posts() )  {
		$custom_query->the_post();
		$s.="<li><a href='" . get_permalink(get_the_ID()) . "'>" . get_the_title() . ' &raquo;</a><br />' . get_the_excerpt() . '</li>';
	}
	$s.="</ul>";
	wp_reset_postdata();
	return $s;
}

function committee_list_shortcode($atts) {
	return outputCommittees();
}
add_shortcode('committee_list', 'committee_list_shortcode');

?>
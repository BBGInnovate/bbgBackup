<?php
/**
 * bbgRedesign functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bbgRedesign 
 */

//can't 'DEFINE' an array, so we just set a var.  Note that this should be kept here and not called on 'init' else it won't be available globally.
$STANDARD_POST_CATEGORY_EXCLUDES = array(
	get_cat_id('Special Days'),
	get_cat_id("From the CEO"),
	get_cat_id('Contact'),
	get_cat_id('Quotation'),
	get_cat_id('Employee'),
	get_cat_id('Intern Testimonial'),
	get_cat_id('Award'),
	get_cat_id('Impact'),
	get_cat_id('Media Development Map'),
	get_cat_id('Media Advisory')
);

require get_template_directory() . '/inc/bbg-functions-utilities.php';

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
		//add_image_size( 'small-thumb', 300, 180, true );
		add_image_size( 'small-thumb', 330, 198, true ); //Fixing so that these fill the width of the sidebar
		add_image_size( 'small-thumb-uncropped', 3330, 330, false );
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

	if (defined('USE_LIVE_RELOAD') && USE_LIVE_RELOAD) {
		wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', null, false, true);
		wp_enqueue_script('livereload');
	}

}
add_action( 'wp_enqueue_scripts', 'bbginnovate_scripts' );

function enqueueAdminStyles() {
	wp_enqueue_script( 'bbginnovate-bbgredesign', get_template_directory_uri() . '/js/bbgredesign.js', array('jquery'), '20160223', true );
	wp_enqueue_style( 'bbginnovate_admin_css', get_template_directory_uri() . '/bbgredesign_admin.css', array(), '20160403' );

	//wp_enqueue_script( 'qtipjs', 'https://cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.js', array('jquery'), '20160223', true );
	//wp_enqueue_style( 'qtipcss', 'https://cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.css', array(), '20160403' );
	wp_enqueue_script('qtipjs', get_template_directory_uri() . '/js/vendor/jquery.qtip.js', array('jquery'), '20160223', true );
	wp_enqueue_style('qtipcss', get_template_directory_uri() . '/css/jquery.qtip.css', array(), '20160403');



}

add_action( 'admin_enqueue_scripts', 'enqueueAdminStyles' );

function loggedInAlerts() {
	if ( is_user_logged_in() ) {
		echo '<script type="text/javascript">';
		echo 'jQuery(".bbg__site-alert").css("top","30px");';
		echo '</script>';
	}
}

function adminFooter() {

	echo '<script type="text/javascript">';
	echo 'jQuery(document).ready(function() {';
	echo 'jQuery("label").qtip();';
	echo '});';
	echo '</script>';
}
add_action( 'admin_footer', 'adminFooter' );


require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/jetpack.php';
require get_template_directory() . '/inc/bbg-custom-taxonomies.php';
require get_template_directory() . '/inc/bbg-functions-awards.php';
require get_template_directory() . '/inc/bbg-functions-boardAndSeniorManagement.php';
require get_template_directory() . '/inc/bbg-functions-bizOpps.php';
require get_template_directory() . '/inc/bbg-functions-congressional-committees.php';
require get_template_directory() . '/inc/bbg-functions-contactCards.php';
require get_template_directory() . '/inc/bbg-functions-impact.php';
require get_template_directory() . '/inc/bbg-functions-interns.php';
require get_template_directory() . '/inc/bbg-functions-jobs.php';
require get_template_directory() . '/inc/bbg-functions-nav.php';
require get_template_directory() . '/inc/bbg-functions-networks.php';
require get_template_directory() . '/inc/bbg-functions-quotations.php';
require get_template_directory() . '/inc/bbg-functions-shortcodes.php';
require get_template_directory() . '/inc/bbg-functions-sidebar-more.php';
require get_template_directory() . '/inc/bbg-functions-tinyMCE.php';
require get_template_directory() . '/inc/bbg-functions-category-tooltip.php';
//require get_template_directory() . '/inc/bbg-functions-tag-hierarchy.php';

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
	$class = str_replace("class='avatar", "class='avatar usa-avatar bbg__avatar", $class) ;

	//Adding a second version because we're using WP User Avatar plugin and it uses double quotes
	$class = str_replace('class="avatar', 'class="avatar usa-avatar bbg__avatar', $class) ;
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


/*===================================================================================
 * CUSTOM PAGINATION LOGIC - we show X posts on front page but more posts on 'older post' pages
 * the next several functions are for adding that functionality and also making it available in wordpress settings
 * =================================================================================*/

add_action('pre_get_posts', 'bbginnovate_modify_the_loop', 1 );
function bbginnovate_modify_the_loop(&$query) {
	
	//note that the homepage doesn't actually use the normal loop, and won't be affected by this.  
	//This query modification is for the 'blog index' page and all archive pages / feeds

	if ( $query->is_main_query() && !is_admin() && ($query -> is_home() || $query->is_archive() ||  $query->is_feed() )) {
		
		$termsToExclude =  array(
			get_cat_id('Contact'),
			get_cat_id('Quotation'),
			get_cat_id('Employee'),
			get_cat_id('Intern Testimonial'),
			get_cat_id('Media Development Map'),
			get_cat_id('Media Advisory')
		);

		//don't allow the awards on our news page - which is our 'is_home'
		if (!($query->is_archive()) || strtolower($query->query['category_name']) != 'award') {
			array_push($termsToExclude, get_cat_id('Award'));
		}
		if (!($query->is_archive()) || strtolower($query->query['category_name']) != 'special-days') {
			array_push($termsToExclude, get_cat_id('Special Days'));
		}

		//don't allow impact stories on the news page either
		if (!($query->is_archive()) || (strpos(strtolower($query->query['category_name']), 'impact') === false) ) {
			array_push($termsToExclude, get_cat_id('Impact'));
		}

		$tax_query = array(
			//'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => $termsToExclude,
				'operator' => 'NOT IN'
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

function featured_video ($url) {
	if(strpos($url, 'facebook.com')) {
		$return = apply_filters('the_content',$url);
	} else {
		if(strpos($url, 'youtu.be') !== false) {
			//URL's of the youtu.be form, which are what you get click share, don't naturally embed.  let's transform them.
			//Convert url's like https://youtu.be/SOME_KEY and https://youtu.be/SOME_KEY?t=55s to https://www.youtube.com?v=SOME_KEY&start=55
			$o = explode("/",$url);
			$key = array_pop($o);
			$timeSeconds=0;
			if (strpos($key,'?t=') !== false) {
				$o = explode("?",$key);
				$timeStr = array_pop($o);
				$key = $o[0];
				$o = explode("=",$timeStr);
				$timeSecondsStr=array_pop($o);
				$timeSeconds = str_replace("s","",$timeSecondsStr);
			}
			$url = "https://www.youtube.com/watch?v=" . $key;
			if ($timeSeconds > 0) {
				$url .= "&start=" . $timeSeconds;
			}
		}
		$url = str_replace("watch?v=", "embed/", $url);	//youtube
		$url = str_replace("&start=", "?start=", $url);	//this line fixes the case where they did youtube.com?v=xxx&start=123
		$url = str_replace("https://vimeo.com/", "https://player.vimeo.com/video/", $url); //vimeo

		$extraClass = "";
		if(strpos($url, 'facebook') !== false) {
			$extraClass = 'facebook';
		} else if(strpos($url, 'c-span') !== false) {
			$extraClass = 'c-span';
		}

		$return="<div class='bbg-embed-shell bbg__featured-video'><div class='embed-container $extraClass'>";
		$return.='<iframe src="' . $url . '" frameborder="0" allowfullscreen="" data-ratio="NaN" data-width="" data-height="" style="display: block; margin: 0px;"></iframe>';
		$return.="</div></div>";
	}
	return $return;
}

function featured_timeline ($url) {
	$return="<div class='bbg__featured-timeline'><div class='timeline-container'>";
	$return.="<iframe src='" . $url . "' frameborder='0' width='1040'></iframe>";
	$return.="</div></div>";

	return $return;
}


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
		$impact=false;
		$suppressOutput = false;
		if ( $categories ) {


			if ( !$selectedCategory ) {
				foreach ( $categories as $category ) {
					if ( $category->name == "Media Development Map" ) {
						$suppressOutput = true;
						break;
					}
				}
			}
			if (!$suppressOutput) {

				/******* TODO: Rewrite this section ... no need for so many loops ****/

				/* JBF 12/12/2016 - 'Press Release' takes precedence over everything else */
				if ( !$selectedCategory ) {
					foreach ( $categories as $category ) {
						if ( $category->name == "Press Release" ) {
							$selectedCategory = $category;
							break;
						}
					}
				}

				/* JBF 8/19 - we could have also directly called yoast_get_primary_term_id() but I'd like this to work even if the plug is disabled */
				if ( !$selectedCategory ) {
					$primaryCategoryID = get_post_meta( get_the_ID(), '_yoast_wpseo_primary_category', true );
					if ($primaryCategoryID && $primaryCategoryID != "") {
						foreach ( $categories as $category ) {
							if ( $category->term_id == $primaryCategoryID ) {
								$selectedCategory = $category;
								break;
							}
						}	
					}
				}

				if ( !$selectedCategory ) {
					foreach ( $categories as $category ) {
						if ( $category->name == "From the CEO" ) {
							$selectedCategory = $category;
							break;
						}
					}
				}

				if ( !$selectedCategory ) {
					foreach ( $categories as $category ) {
						if ( $category->name == "Impact" ) {
							$selectedCategory = $category;
							$impact = true;
							break;
						}
					}
				}

				if ( !$selectedCategory ) {
					foreach ( $categories as $category ) {
						if ( $category->name == "BBG" ) {
							$selectedCategory = $category;
							break;
						}
					}
				}

				if ( !$selectedCategory ) {
					foreach ( $categories as $category ) {
						$selectedCategory = $category;
					}
				}
				$link = false;
				if ($impact) {
					$link = get_permalink( get_page_by_path( "/our-work/impact-and-results/" ) );
				} else if ($selectedCategory) {
					$link = get_category_link( $selectedCategory->term_id );
				}
				if ($link) {
					$output .= '<h5 class="entry-category bbg__label"><a href="' . $link . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'bbginnovate' ), $selectedCategory->name ) ) . '">' . $selectedCategory->cat_name . '</a></h5>' . $separator;
				}
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
			$text = strip_tags($text);

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

		$addSeparator = FALSE;




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

				<div class="bbg__avatar__container">
					<?php echo $avatar; ?>
				</div>

				<div class="bbg__author__text">

					<h2 class="bbg__staff__author-name">
						<a href="<?php echo $authorPath ?>" class="bbg__author-link"><?php echo $authorName; ?></a>
					</h2><!-- .bbg__staff__author-name -->

					<div class="bbg__author-description">
						<?php echo '<div class="bbg__author-occupation">' . $occupation . '</div>'; ?>

						<div class="bbg__author-bio">
							<?php echo $description; ?>
						</div>

					</div><!-- .bbg__staff__author-description -->

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
					</div> <!-- .bbg__staff__author-contact -->

				</div><!-- .bbg__staff__author__text -->
		</div><!-- .bbg__article-author -->
		<?php
		do_action( 'bbg_post_author_bottom_card' );
	}
endif;




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

function getEntityLinks($entityID) {
	$url="https://api.bbg.gov/api/subgroups?group=".$entityID;
	$feedFilepath = get_template_directory() . "/external-feed-cache/subgroupscache_".$entityID.".json";
	if ( fileExpired($feedFilepath, 1440)) {  // 1440 min = 1 day
		$feedStr=fetchUrl($url);
		file_put_contents($feedFilepath, $feedStr);
	} else {
		$feedStr=file_get_contents($feedFilepath);
	}
	$json=json_decode($feedStr);

	$g=false;
	foreach ($json->subgroups as $subgroup) {
		if ($subgroup->group_id ==$entityID) {
			$g[]=$subgroup;
		}
	}
	return $g;
}

/**** We use the excerpts on certain pages as structured data - for instance pages of individual Board Members have excerpts that drive their display in the Board Member list ***/
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );

	require "config_bbgWPtheme.php"; //originally we had this at the top of the file, but calling get_fields in functions.php before everything runs caused an issue with ACF where it would never return a post object, instead always an ID

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
function outputSpecialCommittees($active) {
	$committeesPage=get_page_by_title('Special Committees');
	$thePostID=$committeesPage->ID;
	$qParams=array(
		'post_type' => array('page')
		,'post_status' => array('publish')
		,'post_parent' => $thePostID
		,'order' => 'ASC'
		,'posts_per_page' => 100
	);
	$custom_query = new WP_Query($qParams);
	$s="";
	$s.="<ul class='bbg__board__committee-list'>";
	while ( $custom_query->have_posts() )  {
		$custom_query->the_post();
		$committeeActive=get_post_meta( get_the_ID(), "committee_active", true );
		$committeeChairID = get_post_meta( get_the_ID(), "committee_chair", true );

		if ($committeeActive==$active) {

			$chair=get_post($committeeChairID);

			$s.="<li><a href='" . get_permalink(get_the_ID()) . "'>" . get_the_title() . ' &raquo;</a><br />' . get_the_excerpt() . '<br />Chair: <a href="' . get_permalink($chair->ID) . '">' . $chair->post_title . '</a></li>';
		}
	}
	$s.="</ul>";
	wp_reset_postdata();
	return $s;
}

function special_committee_list_shortcode($atts) {
	return outputSpecialCommittees($atts['active']);
}
add_shortcode('special_committee_list', 'special_committee_list_shortcode');

function getTinyEntityLogo($entityAbbr) {
	return $imgSrc=get_template_directory_uri().'/img/logo_'.$entityAbbr.'--circle-40.png';
}

function getEntityData() {
	/*** Possible todo: leverage wordpress transient cache ***/
	$entityParentPage = get_page_by_path('networks');
	$qParams=array(
		'post_type' => array('page'),
		'posts_per_page' => -1,
		'post_parent' => $entityParentPage->ID,
		'orderby' => 'meta_value_num',
		'meta_key' => 'entity_year_established',
		'order' => 'ASC'
	);
	$entities = array();
	$hp_query = new WP_Query($qParams);
	if ($hp_query -> have_posts()) {
		while ( $hp_query -> have_posts() )  {
			$hp_query->the_post();
			$id = get_the_ID();
			$fullName = get_post_meta( $id, 'entity_full_name', true );
			if ($fullName != "") {
				$abbreviation = strtolower(get_post_meta( $id, 'entity_abbreviation', true ));
				$abbreviation = str_replace("/", "", $abbreviation);
				$description = get_post_meta( $id, 'entity_description', true );
				$link = get_permalink( get_page_by_path( "/broadcasters/$abbreviation/" ) );
				$imgSrc = get_template_directory_uri().'/img/logo_'.$abbreviation.'--circle-200.png'; //need to fix this
				$entityLogoID = get_post_meta( $id, 'entity_logo',true );
				$entityLogo = "";
				if ($entityLogoID) {
					$entityLogoObj = wp_get_attachment_image_src( $entityLogoID , 'Full');
					$entityLogo = $entityLogoObj[0];
				}
				$featuredImageCutline="";
				$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment'));
				if ($thumbnail_image && isset($thumbnail_image[0])) {
					$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
				}
				$bannerPosition = get_field( 'adjust_the_banner_image', $id, true);
				$bannerPositionCSS = get_field( 'adjust_the_banner_image_css', $id, true);
				$bannerAdjustStr="";
				if ($bannerPositionCSS) {
					$bannerAdjustStr = $bannerPositionCSS;
				} else if ($bannerPosition) {
					$bannerAdjustStr = $bannerPosition;
				}
				/*
				$src = wp_get_attachment_image_src( get_post_thumbnail_id($id), array( 1900,700 ), false, '' );
				if (is_array($src)) {
					$src = $src[0];
				}
				*/
				$featuredImageID=get_post_thumbnail_id($id);
				if (!isset($_GET['img']) || $_GET['img'] == $abbreviation) {
					$entities[] = array(
						'abbreviation' => $abbreviation,
						'description' => $description,
						'link' => $link,
						'imgSrc' => $imgSrc,
						'entityLogo' => $entityLogo,
						'featuredImageID' => $featuredImageID,
						'featuredImageCutline' => $featuredImageCutline,
						'bannerAdjustStr' => $bannerAdjustStr
					);
				}
			}
		}
	}
	wp_reset_postdata();
	return $entities;
}

function getRandomEntityImage() {
	//	allEntities or rfa, rferl, voa, mbn, ocb
	$eData = getEntityData();
	$returnVal = false;
	if (count($eData)) {
		$randKey = array_rand($eData);
		$e = $eData[$randKey];
		if ($e) {
			//var_dump($e);
			return array(
				'imageID' => $e['featuredImageID'],
				'imageCutline' => $e['featuredImageCutline'],
				'bannerAdjustStr' => $e['bannerAdjustStr']
			);
			//die();
		}

	}

}

add_action( 'admin_bar_menu', 'toolbar_link_to_mypage', 999 );
function toolbar_link_to_mypage( $wp_admin_bar ) {
	$page = get_page_by_title('Author Guide');
	if ($page) {
		$args = array(
			'id'    => 'authorguide',
			'title' => 'Author Guide',
			'href'  => get_permalink($page->ID),
			'meta'  => array( 'class' => 'authorguide-toolbar-page', 'target' => '_blank' )
		);
		$wp_admin_bar->add_node( $args );
	}
}



if ( function_exists ('acf_add_options_page') ) {
	acf_add_options_page (array(
		'page_title' => 'Homepage Options',
		'menu_title' => 'Homepage Options',
		'menu_slug' => 'homepage-options',
		'capability' => 'edit_posts',
		'parent_slug' => '',
		'position' => false,
		'icon_url' => false
	));
	acf_add_options_page (array(
		'page_title' => 'Site Settings',
		'menu_title' => 'BBG Settings',
		'menu_slug' => 'site-settings',
		'capability' => 'edit_posts',
		'parent_slug' => '',
		'position' => false,
		'icon_url' => false
	));
}

function my_excerpt($post_id) {
	$post = get_post($post_id);
	if ($post->post_excerpt) {
		// excerpt set, return it
		return $post->post_excerpt;
	} else {
		setup_postdata( $post );
		$excerpt = get_the_excerpt();
		wp_reset_postdata();
		return $excerpt;
	}
}

function getSoapboxStr($soap) {
	//takes a soap post object and returns the markup
	$s = "";
	$id = $soap->ID;
	$soapCategory = wp_get_post_categories($id);

	$isCEOPost = FALSE;
	$isSpeech = FALSE;
	$soapHeaderPermalink = "";
	$soapHeaderText = "";
	$soapPostPermalink = get_the_permalink($id);
	$mugshot = "";
	$mugshotName = "";

	foreach ($soapCategory as $c) {
		$cat = get_category( $c );
		if ($cat->slug == "from-the-ceo") {
			$isCEOPost = TRUE;
			$soapHeaderText = "From the CEO";
			$soapHeaderPermalink = get_category_link($cat->term_id);
			$mugshot = "/wp-content/media/2016/07/john_lansing_ceo-sq-200x200.jpg";
			$mugshotName = "John Lansing";
		} else if ($cat->slug == "speech") {
			$isSpeech = true;
			$mugshotID = get_post_meta( $id, 'mugshot_photo', true );
			$mugshotName = get_post_meta( $id, 'mugshot_name', true );

			if ($mugshotID) {
				$mugshot = wp_get_attachment_image_src( $mugshotID , 'mugshot');
				$mugshot = $mugshot[0];
			}
		} else if ($cat->slug == "media-advisory") {
			$isMediaAdvisory = true;
			$soapHeaderText = "Media Advisory";
		}
	}

	$s .= '<div class="usa-width-one-half bbg__voice--featured">';
	if ($soapHeaderPermalink != "") {
		$s .= '<h6 class="bbg__label small"><a href="'.$soapHeaderPermalink.'">'.$soapHeaderText.'</a></h6>';
	} else if ($soapHeaderText != "") {
		$s .= '<h6 class="bbg__label small">' . $soapHeaderText.'</h6>';
	}

	$s .= '<h2 class="bbg-blog__excerpt-title"><a href="' . $soapPostPermalink. '">';
	$s .= $soap->post_title;
	$s .= '</a></h2>';

	$s .= '<p class="">';

	if ($mugshot != "") {
		$s .= '<span class="bbg__mugshot"><img src="' . $mugshot . '" class="bbg__ceo-post__mugshot" />';
		if ($mugshotName != "") {
			$s .= '<span class="bbg__mugshot__caption">' . $mugshotName . '</span>';
		}
		$s .= '</span>';
	}

	$s .= my_excerpt($id);
	$s .= ' <a href="' . $soapPostPermalink. '" class="bbg__read-more">READ MORE »</a></p>';
	$s .= '</div>';
	return $s;
}

add_filter('the_posts', 'show_future_posts');
function show_future_posts($posts) {
	global $wp_query, $wpdb;
	$returnVal=$posts;
	if(is_single() && $wp_query->post_count == 0) {
		$futurePosts = $wpdb->get_results($wp_query->request);
		if (count($futurePosts) > 0 && has_category('Event', $futurePosts[0])) {
			$returnVal = $futurePosts;
		}
	}
	return $returnVal;
}

/*** AKAMAI is already including HTTP_X_FORWARDED_FOR but it can include >1 IP address.  HTTP_TRUE_CLIENT_IP seems to be flawless.  This filter is applied so that iThemes security blocks actual IP addresses and not Akamai IP addresses ***/
function akamai_forwarding_callback( $headers ) {
    // (maybe) modify $string
    array_unshift($headers, 'HTTP_TRUE_CLIENT_IP');
    return $headers;
}
add_filter( 'itsec_filter_remote_addr_headers', 'akamai_forwarding_callback', 10, 1 );

/**
 * Add private/draft/future/pending pages to parent dropdown. Used for Author Guide.  Found at http://wpsmith.net/2013/add-privatedraftfuturepending-pages-to-parent-dropdown-in-page-attributes-and-quick-edit/
 */
add_filter( 'page_attributes_dropdown_pages_args', 'wps_dropdown_pages_args_add_parents' );
add_filter( 'quick_edit_dropdown_pages_args', 'wps_dropdown_pages_args_add_parents' );
function wps_dropdown_pages_args_add_parents( $dropdown_args, $post = NULL ) {
    $dropdown_args['post_status'] = array( 'publish', 'draft', 'pending', 'future', 'private', );
    return $dropdown_args;
}

function filter_query_vars( $qvars ) {
	$qvars[] = 'awardyear';
	$qvars[] = 'entity';
	return $qvars;
}
add_filter( 'query_vars', 'filter_query_vars' , 10, 1 );

/***** 
	On our "Homepage Options" you can select many featured posts.  We want the picker to sort the list of available
	posts in order of descending date rather than alphabetical order. see https://www.advancedcustomfields.com/resources/acf-fields-post_object-query/ for details
*****/
function order_post_objects_by_date( $args, $field, $post_id ) {
    $args['order'] = 'DESC';
    $args['orderby'] = 'post_date';
    $args['post_status'] = array(
    	'publish',
    	'future'
    );
    return $args;
}
add_filter('acf/fields/post_object/query', 'order_post_objects_by_date', 10, 3);



?>
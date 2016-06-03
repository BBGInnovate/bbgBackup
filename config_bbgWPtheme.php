<?php
$fields = get_fields('options');
if( $fields )
{
	foreach( $fields as $field_name => $value )
	{
		switch ($field_name) {
			case "site_setting_default_og_description":
				define("DEFAULT_DESCRIPTION", $value);
			break;
			case "site_setting_default_og_image":
				$defaultImage = wp_get_attachment_image_src( $value , 'Full');
				$defaultImageUrl = $defaultImage[0];
				define("DEFAULT_IMAGE", $defaultImageUrl);
			break;
			case "site_setting_site_title_markup":
				define("SITE_TITLE_MARKUP", $value);
			break;

		}
	}
}
//this file is called in header.php.  These are used for various og/twitter/social media tags

if (!defined('DEFAULT_DESCRIPTION')) {
	define('DEFAULT_DESCRIPTION','');
}
if (!defined('DEFAULT_IMAGE')) {
	define('DEFAULT_IMAGE','');
}
if (!defined('SITE_TITLE_MARKUP')) {
	define('SITE_TITLE_MARKUP','');
}

define("DEFAULT_TITLE", get_bloginfo('name'));
define("DEFAULT_AUTHOR", "");
define("DEFAULT_KEYWORDS", "");

//can't 'DEFINE' an array, so we just set a var
$STANDARD_POST_CATEGORY_EXCLUDES = array(
	get_cat_id("John's take"),
	get_cat_id('Contact'),
	get_cat_id('Quotation'),
	get_cat_id('Employee')
)

?>
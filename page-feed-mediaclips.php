<?php
/**
 * The template for displaying a media clip RSS feed for consumption by mailchimp campaigns.
 * parameters: you can filter by clip type with $_GET['clipType'] == "about" or "citation"
 * @package bbgRedesign
 * template name: RSS - Media Clips
 */

$parentTitle = "";
if( $post->post_parent ) {
	$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
	$parentTitle = $parent->post_title;
}

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageName = get_the_title();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

$today = date('Ymd');

$qParams = array(
	'post_type' => array( 'media_clips' ),
	'posts_per_page' => 999,
	'orderby' => 'meta_value',
	'meta_key' => 'media_clip_mail_date',
	'order', 'DESC'
);

if ( ! isset( $_GET['ignoreDate'] ) ) {
	$qParams['meta_query'] = array(
		array(
			'key'		=> 'media_clip_mail_date',
			'compare'	=> '=',
			'value'		=> $today
		)
	);
}

$clipType = false;
if (isset($_GET['clipType'])) {
	$clipType = $_GET['clipType'];
}

$custom_query = new WP_Query( $qParams );

?><?php
header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
do_action( 'rss_tag_pre', 'rss2' );
?><rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	do_action( 'rss2_ns' );
	?>
>
<channel>
	<title><?php wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php
		$date = get_lastpostmodified( 'GMT' );
		echo $date ? mysql2date( 'D, d M Y H:i:s +0000', $date, false ) : date( 'D, d M Y H:i:s +0000' );
	?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' );?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', 1 );?></sy:updateFrequency>
	<?php
	
	do_action( 'rss2_head'); 

	while( $custom_query -> have_posts() ) : $custom_query ->the_post();
		$id = get_the_ID();
		$currentClipType = get_post_meta($id, "media_clip_type",true);
		$outletTermObj = get_field('media_clip_outlet', get_the_ID());
		$outletName = $outletTermObj->name;
		$dateVal = get_field('media_clip_published_on', false, false);
		$date = new DateTime($dateVal);
		$rssDate = $date->format(DateTime::RSS);
		$displayDate = $date->format('F d, Y');
		$clipLink = get_field('media_clip_story_url', get_the_ID());

 		if (!$clipType || ($clipType == $currentClipType)):
	?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php echo $clipLink; ?></link>
		<pubDate><?php echo $rssDate;?></pubDate>
		<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
		<?php the_category_rss('rss2'); ?>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description><![CDATA[<?php echo "<strong>$outletName</strong>" . " - " . $displayDate . "<BR>"; the_excerpt_rss(); ?>]]></description>
		<?php 
			rss_enclosure(); 
			do_action( 'rss2_item' );
		?>
	</item>
	<?php 

		endif; //if (!$clipType || ($clipType == $currentClipType)):
		endwhile; //while( $custom_query -> have_posts() ) : $custom_query ->the_post();
	 ?>
</channel>
</rss>
<?php 

/*
<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
	<?php $content = get_the_content_feed('rss2'); ?>
	<?php if ( strlen( $content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
	<?php else : ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
	<?php endif; ?>
*/
?>	
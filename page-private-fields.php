<?php
/**
 * The template for displaying highlights from across the 5 BBG entities.
 * Features a banner map of recent headlines about the entities
 * and a subsection for each of the entities.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Media Custom Fields List
 */



$qParams = array(
	'post_type' => array('post'),
	'posts_per_page' => 200,
	'category__and' => array(
							get_cat_ID('Media Development Map')
					  ),
	'orderby', 'date',
	'order', 'DESC'
);
$custom_query = new WP_Query($qParams);
echo "<table width='1000'><tr><td width='300' ><strong>title</strong></td><td width='200'><strong>Images</strong></td><td width='200'><strong>Videos</strong></td><td width='300'><strong>Additional Files</strong></td></tr>";
if ($custom_query -> have_posts()) {
	$counter=0;
	while ( $custom_query -> have_posts() )  {
		$counter++;
		$custom_query->the_post();
		$id = get_the_ID();
		$url = get_permalink($id);
		$title = get_the_title($id);
		
		$images = get_field( 'hot_spot_rotating_featured_images', '', true );
		$files = get_field( 'media_dev_additional_files', '', true );
		$videos = get_field( 'media_dev_additional_videos', '', true );

		if ($images || $files || $videos) {
			echo "<tr>";
			echo "<td><a target='_blank' href='" . get_edit_post_link($id) . "'>$title</a></td>";
			echo "<td>" . count($images) . "</td>";
			echo "<td>" . count($files) . "</td>";
			echo "<td>" . count($videos) . "</td>";
			echo "</tr>";
		}
	}
}
wp_reset_postdata();
wp_reset_query();

?>
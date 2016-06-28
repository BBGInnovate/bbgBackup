<?php
/**
 * The template for displaying highlights from across the 5 BBG entities.
 * Features a banner map of recent headlines about the entities
 * and a subsection for each of the entities.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Headlines Generator
 */


$pressReleases = array();

$cats = get_categories();
//$slugs = ['voa-press-release','rferl-press-release','ocb-press-release','rfa-press-release','mbn-press-release'];
foreach ($cats as $cat) {
	$prCategoryID = $cat->term_id;
	$qParams = array(
		'post_type' => array('post'),
		'posts_per_page' => 20,
		'category__and' => array(
								$prCategoryID
						  ),
		'orderby', 'date',
		'order', 'DESC',
		'tax_query' => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-quote',
				'operator' => 'NOT IN'

			)
		)
	);
	$custom_query = new WP_Query($qParams);
	if ($custom_query -> have_posts()) {
		$counter=0;
		while ( $custom_query -> have_posts() )  {
			$counter++;
			$custom_query->the_post();
			$id = get_the_ID();
			$url = get_permalink($id);
			$title = get_the_title($id);
			echo $cat->slug . "\t" . $counter . "\t" . $title . "\t" . $url . "\n";

		}
	}
	wp_reset_postdata();
	wp_reset_query();
}
?>
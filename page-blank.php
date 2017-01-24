<?php
/**
 * The template for displaying content without any header or footer
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Blank Page (No Header, Footer, Styles)
 */
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	the_content();
	endwhile;
endif;
?>
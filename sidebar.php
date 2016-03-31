<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bbgRedesign
 */

if ( is_active_sidebar( 'sidebar-2' ) ) { // If there are widgets assigned to this sidebar...
    return; // stop processing this page, otherwise go on below
}
?>

<aside id="menu-content" class="widget-area sidenav" role="complementary">
    <!-- #primary-menu -->
    <?php dynamic_sidebar( 'sidebar-2' ); ?>
</aside><!-- #secondary -->
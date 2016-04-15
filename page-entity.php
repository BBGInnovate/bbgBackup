<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Entity

 */

$pageContent="";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent=get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
   		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

$id=$post->ID;
$fullName=get_post_meta( $id, 'entity_full_name', true );
$abbreviation=get_post_meta( $id, 'entity_abbreviation', true );
$description=get_post_meta( $id, 'entity_description', true );
$siteUrl=get_post_meta( $id, 'entity_site_url', true );
$rssFeed=get_post_meta( $id, 'entity_rss_feed', true );
$entityLogoID = get_post_meta( $id, 'entity_logo',true );
$entityLogo="";
if ($entityLogoID) {
	$entityLogoObj = wp_get_attachment_image_src( $entityLogoID , 'Full');
	$entityLogo = $entityLogoObj[0];
} 


//echo "<pre>FEED $rssFeed $entityItems</pre>";	

get_header(); 
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
					echo "<img src='$entityLogo' />";
					echo "<a href='$siteUrl'>$fullName ($abbreviation)</a> <BR>";
					echo "<strong>$description</strong><BR><BR>";
					echo "<h1>Latest Items</h1>";
					$entityJson=getFeed($rssFeed,$id);
					$counter=0;
					$maxItems=3;
					foreach ($entityJson->channel->item as $e) {
						$counter++;
						if ($counter <= $maxItems) {
							$title=$e->title;
							$url=$e->link;
							$description=$e->description;
							$enc = ($e->enclosure);
							//having a hard time accessing enclosure attributes <img src='$thumb' />
							echo "<a href='$url'>$title</a><BR>" . substr($description,0,100) . "<BR><BR>";
						}
					}
					echo $pageContent;

				?>

				<div class="bbg-post-footer">
				
				</div>

			<?php endwhile; // End of the loop. ?>
			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

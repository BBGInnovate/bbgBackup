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


//Default adds a space above header if there's no image set
$featuredImageClass = " bbg__article--no-featured-image";
$bannerPosition=get_post_meta( get_the_ID(), 'adjust_the_banner_image', true);



//echo "<pre>FEED $rssFeed $entityItems</pre>";	

get_header(); 
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
					/*
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
					*/
					?>


			<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>
				<?php
					$hideFeaturedImage = get_post_meta( get_the_ID(), "hide_featured_image", true );
					if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
						echo '<div class="usa-grid-full">';
						$featuredImageClass = "";
						$featuredImageCutline="";
						$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id(get_the_ID()), 'post_type' => 'attachment'));
						if ($thumbnail_image && isset($thumbnail_image[0])) {
							$featuredImageCutline=$thumbnail_image[0]->post_excerpt;
						}

						$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

						echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__profile-header__banner" style="background-image: url('.$src[0].'); background-position: '.$bannerPosition.'">';
						echo '</div>';
						echo '</div> <!-- usa-grid-full -->';
					}
				?><!-- .bbg__article-header__thumbnail -->



				<div class="usa-grid">


					<?php echo '<header class="entry-header bbg__article-header'.$featuredImageClass.'">'; ?>

						<div class="bbg__profile-photo">
							<img src="<?php echo $entityLogo; ?>" class="bbg__profile-photo__image"/>
						</div>
						<div class="bbg__profile-title">

							<?php echo '<h1 class="entry-title bbg__article-header__title">' . $abbreviation . '</h1>'; ?>

							<!-- .bbg__article-header__title -->
							<h5 class="entry-category bbg-label">
								<?php echo $fullName; ?>
							</h5><!-- .bbg-label -->

						</div>
					</header><!-- .bbg__article-header -->


					<div class="bbg__article-sidebar--left">
						<p><strong>We could include the contact info here</strong>, or the app download links, the summary of the entity, by the numbers, social links for the entity etc </p>

						<ul class="bbg__article-share ">
						&nbsp;
						</ul>
					</div><!-- .bbg__article-sidebar--left -->



					<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">
						<?php echo $pageContent; ?>

						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<a href="<?php echo $parent_link; ?>">Back to <?php echo $parent->post_title; ?></a>
						<?php } ?>
					</div><!-- .entry-content -->



					<div class="bbg__article-sidebar">
						<?php 
							echo '<h3 class="bbg__sidebar-label">Recent stories from ' . $abbreviation. '</h3>';
							echo '<ul class="bbg__profile__related-link__list">';

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
									echo '<li class="bbg__profile__related-link"><a href="' . $url . '">' . $title . '</a></li>';
								}
							}
							echo '</ul>';

						?>

					</div><!-- .bbg__article-sidebar -->

				</div><!-- .usa-grid -->

			</article>

			<div class="bbg-post-footer">
			
			</div>

			<?php endwhile; // End of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

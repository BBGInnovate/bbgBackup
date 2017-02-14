<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
 template name: Playlist Gallery

 */

$pageContent="";
$pageTitle = "";
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$pageContent = get_the_content();
		$pageContent = apply_filters('the_content', $pageContent);
		$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
		$pageTitle = get_the_title();
	endwhile;
endif;
wp_reset_postdata();
wp_reset_query();

$pageTagline = get_post_meta( get_the_ID(), 'page_tagline', true );
if ($pageTagline && $pageTagline!=""){
	$pageTagline = '<h6 class="bbg__page-header__tagline">' . $pageTagline . '</h6>';
}

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">
				<header class="page-header">
					<h5 class="bbg__label--mobile large"><?php echo $pageTitle; ?></h5>					
					<?php echo $pageTagline; ?>
				</header><!-- .page-header -->
			</div>
			
			<?php 
				if( have_rows('playlist_gallery_items') ):
					$i = 0;
				    while ( have_rows('playlist_gallery_items') ) : the_row();
				    	$i ++;
				        $title = get_sub_field('playlist_gallery_item_title');
				        $description = get_sub_field('playlist_gallery_item_description');
				        $link = get_sub_field('playlist_gallery_item_link');
				        $thumbnail = get_sub_field('playlist_gallery_item_thumbnail');
				        $thumbnailID = $thumbnail['ID'];
				        $thumbnailSrc = $thumbnail['sizes']['medium-thumb'];
				        if ($i ==1):
			?>
							<!-- BEGIN DISPLAY OF FEATURED PORTFOLIO ITEM -->
							<article class="bbg-blog__excerpt--featured has-post-thumbnail ">
								<header class="entry-header bbg-blog__excerpt-header--featured usa-grid-full">
									<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">
										<a target="_blank" href="<?php echo $link; ?>" rel="bookmark" tabindex="-1"><img width="1040" height="624" <?php echo ar_responsive_image($thumbnailID,'medium-thumb',1200); ?> class="attachment-large-thumb size-large-thumb"></a>
									</div>
									<div class="usa-grid">
										<h2 class="entry-title bbg-blog__excerpt-title--featured"><a target="_blank" href="<?php echo $link; ?>" rel="bookmark"><?php echo $title; ?></a></h2>
									</div>
								</header><!-- .bbg-blog__excerpt-header--featured -->
								<div class="usa-grid">
								<div class="entry-content bbg-blog__excerpt-content--featured ">
									<h3 class="usa-font-lead"><?php echo $description; ?></h3>
								</div><!-- .entry-content -->
								</div>
							</article>

			<?php 		else:
							if ($i == 2) {
								echo '<div class="usa-grid">';
							}
			?>
							<article class="bbg-portfolio__excerpt bbg-grid--1-2-3">
								<header class="entry-header bbg-portfolio__excerpt-header">
									<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
									<?php
										echo '<a target="_blank" href="' . $link . '"><img src="' . $thumbnailSrc . '"/></a>';
									?>
									</div>
									<h3 class="entry-title bbg-portfolio__excerpt-title"><a target="_blank"  href='<?php echo $link; ?>'><?php echo $title; ?></a></h3>
								</header><!-- .entry-header -->
								<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">
									<?php echo $description; ?>
								</div>
							</article>
				<?php 
							if ($i == 4) {
								echo '</div>';
							}
							endif;
					    endwhile;
					endif;
					if ($pageContent != "") {
						echo '<div class="usa-grid bbg__about__child__intro">' . $pageContent . '</div>';
					}
				?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

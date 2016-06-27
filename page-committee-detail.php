<?php
/**
 * The template for displaying committee detail pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Committee Detail
 */

$committeeReportID = get_post_meta( get_the_ID(), "committee_report", true );
$committeeResolutionID = get_post_meta( get_the_ID(), "committee_establishment_resolution", true ); 

$committeeReport=false;
if ($committeeReportID != "") {
	$committeeReportPost=get_post($committeeReportID);
	$committeeReport=array('title'=>$committeeReportPost->post_title, 'url'=>$committeeReportPost->guid);
}

$committeeResolution=false;
if ($committeeResolutionID != "") {
	$committeeResolutionPost=get_post($committeeResolutionID);
	$committeeResolution=array('title'=>$committeeResolutionPost->post_title, 'url'=>$committeeResolutionPost->guid);
}

$members=array();

/* chair comes first */
$committeeChairID = get_post_meta( get_the_ID(), "committee_chair", true );
$chair=get_post($committeeChairID);
if ($chair) {
	$profilePhotoID=get_post_meta($committeeChairID, 'profile_photo', true );
	$profilePhoto = "";
	if ($profilePhotoID) {
		$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
		$profilePhoto = $profilePhoto[0];
	}
	$members[] = array( 'name' => $chair->post_title, 'url' => get_permalink($chair->ID), 'chair'=>true, 'profilePhoto' => $profilePhoto);
}

/* add all non-chair members */
$committeeMemberIDs = get_post_meta( get_the_ID(), "committee_members", true );
foreach ($committeeMemberIDs as $memberID) {
	if ($memberID != $committeeChairID) {
		$member = get_post($memberID);
		$profilePhotoID=get_post_meta( $memberID, 'profile_photo', true );
		$profilePhoto = "";
		if ($profilePhotoID) {
			$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
			$profilePhoto = $profilePhoto[0];
		}
		$members[] = array( 'name' => $member->post_title, 'url' => get_permalink($member->ID), 'chair'=>false, 'profilePhoto' => $profilePhoto);
	}
}


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
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
								echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large">';
								//echo '<div style="position: absolute;"><h5 class="bbg__label">Label</h5></div>';
								echo the_post_thumbnail( 'large-thumb' );

								echo '</div>';
								echo '</div> <!-- usa-grid-full -->';

								if ($featuredImageCutline != "") {
									echo '<div class="usa-grid">';
										echo "<div class='bbg__article-header__caption'>$featuredImageCutline</div>";
									echo '</div> <!-- usa-grid -->';
								}
							}
						?><!-- .bbg__article-header__thumbnail -->

						<div class="usa-grid">

						<?php /* echo bbginnovate_post_categories(); */ ?>
						<!-- .bbg__label -->
						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<h5 class="entry-category bbg__label"><a href="<?php echo $parent_link; ?>" title="A list of the BBG broadcasters."><?php echo $parent->post_title; ?></a></h6>

						<?php } ?>


							<header class="entry-header">
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							</header><!-- .entry-header -->
						</div>



						<div class="usa-grid">
							<?php 

							the_content();

							echo "<h3>Committee Members</h3>";
							echo "<ul>";
							foreach ($members as $m) {
								echo "<li>";
								if ($m['profilePhoto']) {
									//echo "<img src='".$m['profilePhoto']."'/>";
								}
								echo "<a href='".$m['url']."'>".$m['name']."</a>";
								if ($m['chair']) {
									echo "<em> (Committee Chair)</em>";
								}

								echo "</li>";
								//<li>member1</li><li>member2</li></ul>";
							}
							echo "</ul>";


							if ($committeeResolution|| $committeeReport) {

								echo "<h3>Committee Docs</h3>";
								echo "<ul>";
								if ($committeeResolution) {
									$url=$committeeResolution['url'];
									$title=$committeeResolution['title'];
									echo "<li><a href='$url'>$title</a></li>";  
								}
								if ($committeeReport) {
									$url=$committeeReport['url'];
									$title=$committeeReport['title'];
									echo "<li><a href='$url'>$title</a></li>"; 
								}
								echo "</ul>";
							}
							 ?>
						</div><!-- .usa-grid -->



						<div class="usa-grid">
							<footer class="entry-footer bbg-post-footer 1234">
								<?php
									edit_post_link(
										sprintf(
											/* translators: %s: Name of current post */
											esc_html__( 'Edit %s', 'bbginnovate' ),
											the_title( '<span class="screen-reader-text">"', '"</span>', false )
										),
										'<span class="edit-link">',
										'</span>'
									);
								?>
							</footer><!-- .entry-footer -->
						</div><!-- .usa-grid -->
					</article><!-- #post-## -->


					<div class="bbg-post-footer">
					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>
					</div>

				<?php endwhile; // End of the loop. ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>

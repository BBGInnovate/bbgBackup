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
   template name: Impact model
 */

$bannerPosition = get_field( 'adjust_the_banner_image', '', true);
$videoUrl = get_field( 'featured_video_url', '', true );
$secondaryColumnContent = get_field( 'secondary_column_content', '', true );

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="usa-grid-full">

				<?php while ( have_posts() ) : the_post(); 
					//$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article bbg__impact-model"); ?>>
						<?php
							$hideFeaturedImage = get_post_meta( $id, "hide_featured_image", true );
							if ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
								echo '<div class="usa-grid-full">';
								$featuredImageClass = "";
								$featuredImageCutline = "";
								$thumbnail_image = get_posts(array('p' => get_post_thumbnail_id($id), 'post_type' => 'attachment'));
								if ($thumbnail_image && isset($thumbnail_image[0])) {
									$featuredImageCutline = $thumbnail_image[0]->post_excerpt;
								}

								$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 700,450 ), false, '' );

								echo '<div class="single-post-thumbnail clear bbg__article-header__thumbnail--large bbg__article-header__banner" style="background-image: url('.$src[0].'); background-position: '.$bannerPosition.'">';
								echo '</div>';
								echo '</div> <!-- usa-grid-full -->';
							}
						?><!-- .bbg__article-header__thumbnail -->

						<div class="usa-grid">
							<header class="entry-header">

								<?php if($post->post_parent) {
									//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
									$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
									$parent_link = get_permalink($post->post_parent);
								?>
								<h5 class="entry-category bbg-label"><a href="<?php echo $parent_link; ?>"><?php echo $parent->post_title; ?></a></h5>

								<?php } ?>


								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							</header><!-- .entry-header -->
						</div>



						<div class="usa-grid-full">
							<?php the_content(); ?>

							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>“Everyone has the right to freedom of opinion and expression; this right includes freedom to hold opinions without interference, and impart information and ideas through any media regardless of frontiers.”</h2>
									<h5>— The Universal Declaration of Human Rights</h5>
								</div>
							</section>


							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>Our challenges</h2>
									<p class="usa-font-lead">BBG networks operate in a competitive, diverse, fragmented global media environment undergoing revolutionary change. There is more information, more channels of distribution and Limited Freedom of the Press.</p>
								</div>
								<div class="usa-grid">
									<div class="usa-width-one-half">
										<img src="http://placehold.it/300x300" class="bbg__impact-model__graphic large" />
										<h3 class="bbg__big-type">6,233,903,487</h3>
										<p>people live in countries that have a press that is not free or partly free</p>
									</div>
									<div class="usa-width-one-half">
										<h3 class="">6 out of 7 people</h3>
										<p>live in countries without a free press</p>
										<br/>
										<h4>More: </h4>
										<ul>
											<li>CENSORSHIP</li>
											<li>PROPAGANDA</li>
											<li>DISINFORMATION</li>
											<li>THREATS TO JOURNALISTS</li>
											<li>RESTRICTIVE LAWS</li>
										</ul>

									</div>
								</div>
							</section>


							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h1 style="font-size: 6rem;">How do we measure impact?</h1>
									<p class="usa-font-lead">We measure impact across networks, across media, in more than 60 languages & in over 100 countries. Our shared mission provides the framework for a common standard to define & measure impact.</p>
								</div>
								<div class="usa-grid">
									<div class="usa-width-one-half">
										<h3>5 Networks. </h3>
										<h3>1 Mission. </h3>
									</div>
									<div class="usa-width-one-half">
										<h3>To inform, engage and connect people around the world in support of freedom and democracy.</h3>
									</div>
								</div>
							</section>

							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>IMPACT PILLARS & INDICATORS</h2>
									<p class="usa-font-lead">Below are a illustrative sample of core & optional indicators. The full impact model offers BBG networks 12 core and 28 optional indicators that they can use to fit with market conditions for each region. The indicators do not attempt to assess causality; they examine correlations.</p>
								</div>
							</section>

							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>INFORM</h2>
								</div>
								<div class="usa-grid">
									<div class="usa-width-one-half">
										<h3>Reach Audiences</h3>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5>Weekly reach</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5>Weekly digital visits</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5 class="bbg__impact-model__optional">Weekly reach of target segment*</h5>
										</div>
									</div>
									<div class="usa-width-one-half">
										<h3>Provide Value</h3>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/300x300" class="bbg__impact-model__graphic large" />
											<h5>Provide exceptional or unique information</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/300x300" class="bbg__impact-model__graphic large" />
											<h5 class="bbg__impact-model__optional">Weekly reach of target segment*</h5>
										</div>
									</div>
								</div>
							</section>


							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>ENGAGE / CONNECT</h2>
								</div>
								<div class="usa-grid">
									<div class="usa-width-one-third">
										<h3>Engage Audiences</h3>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5>Digital engagement</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5 class="bbg__impact-model__optional">Shared something or talked with someone as a result of reporting*</h5>
										</div>
									</div>
									<div class="usa-width-one-third">
										<h3>Engage Media</h3>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5 class="bbg__impact-model__optional">Content co-creation with affiliates*</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5 class="bbg__impact-model__optional">Content downloaded by affiliates*</h5>
										</div>
									</div>
									<div class="usa-width-one-third">
										<h3>Create Loyalty</h3>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5>Audience is likely to continue to use</h5>
										</div>
										<div class="bbg__impact-model__subsection">
											<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
											<h5 class="bbg__impact-model__optional">Appointment listening or viewing*</h5>
										</div>
									</div>
								</div>
							</section>


							<section class="usa-section bbg__impact-model__section">
								<div class="usa-grid">
									<h2>BE INFLUENTIAL</h2>
									<h3>in support of freedom and democracy</h3>
								</div>
								<div class="usa-grid">
									<div class="usa-width-one-third">
										<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
										<h3>People</h3>
										<p>Increased audience's understanding of current events</p>
									</div>
									<div class="usa-width-one-third">
										<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
										<h3>Media</h3>
										<p class="bbg__impact-model__optional">Drive the news agenda/high profile news pickups*</p>
									</div>
									<div class="usa-width-one-third">
										<img src="http://placehold.it/250x250" class="bbg__impact-model__graphic" />
										<h3>Government</h3>
										<p class="bbg__impact-model__optional">Attention from government officials*</p>
									</div>
								</div>
							</section>

						</div><!-- .usa-grid-full -->



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

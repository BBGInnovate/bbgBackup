<?php
/**
 * The custom home page for the Broadcasting Board of Governors.
 * It includes the mission, a portfolio of recent projects, recent blog posts and staff.
 *
 * @package bbgRedesign
  template name: Events
 */

$templateName = "events";

get_header();

?>

<div id="main" class="site-main">

	<div id="primary" class="content-area">
		<main id="bbg-home" class="site-content bbg-home-main" role="main">
			
			<!-- Site introduction -->
			<section id="mission" class="usa-section usa-grid">
			this page is about events
			</section><!-- Site introduction -->

			<!-- Recent posts -->
			<section id="recent-posts" class="usa-section">
				<div class="usa-grid">
					<h6 class="bbg-label">Upcoming Events</h6>
				</div>

				<div class="usa-grid-full">
					let's list upcoming events here.
				</div>
			</section><!-- Recent posts -->


			<!-- Recent posts -->
			<section id="recent-posts" class="usa-section">
				<div class="usa-grid">
					<h6 class="bbg-label">Previous Events</h6>
				</div>

				<div class="usa-grid-full">
					let's list previous events here.
				</div>
			</section><!-- Recent posts -->


		</main>
	</div><!-- #primary .content-area -->
	<div id="secondary" class="widget-area" role="complementary">
	</div><!-- #secondary .widget-area -->
</div><!-- #main .site-main -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
  template name: Jobs
 */

$pageContent ="";
while ( have_posts() ) : the_post();
	$pageContent=get_the_content();
	$pageContent = apply_filters('the_content', $pageContent);
	$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
endwhile;
$jobs=getJobs();
$s="";
$locationStr = "Location";
for ($i=0; $i < count($jobs); $i++) {
	$j=$jobs[$i];
	//var_dump($j); 
	$url = $j['url'];
	$title=$j['position_title'];
	$startDate=$j['start_date'];
	$endDate=$j['end_date'];
	$locations=$j['locations'];

	$s.= "<a href='$url'>$title</a><BR>";
	/*
	$s.= "Starts: $startDate<BR>";
	$s.= "Ends: $endDate<BR>";
	*/

	if (count($locations)>1){
		$locationStr = "Locations";
	}

	$s.= $locationStr.": ";
	for ($k=0; $k<count($locations); $k++) {
		$loc = $locations[$k];
		$s.= "$loc<BR>";
	}
	$s.= "<BR>";
}
$pageContent = str_replace("[jobs list]", $s, $pageContent);

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<header class="page-header bbg-page__header">
					<div class="bbg-avatar__container bbg-team__icon">
						<div class="bbg-avatar bbg-team__icon__image <?php // echo $iconName ?>" style="background-image: url(<?php// echo get_template_directory_uri() ?>/img/icon_team_<?php // echo $teamCategory->category_nicename; ?>.png);"></div>
					</div>
					<div class="bbg-team__text">
						<h1 class="page-title bbg-team__name">Jobs</h1>
						<h3 class="bbg-team__text-description bbg-page__header-description">Get Employed</h3>
						
					</div>
				</header><!-- .page-header -->
				<section class="usa-section usa-grid">
					<h6 class="bbg-label small">Jobs</h6>
					<div class="bbg-grid__container">
						<?php 
							echo $pageContent;
						?>
					</div><!-- .bbg-grid__container -->
				</section>
			</div><!-- .usa-grid -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>



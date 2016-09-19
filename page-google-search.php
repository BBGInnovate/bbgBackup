<?php
/**
 * A landing page for Google Search.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbgRedesign
   template name: Google Search
 */
get_header(); 

$searchQuery =  $_GET['search'];
if (isset($_GET['testsearch'])) {
	$searchQuery = 'flowers';
}

?>
<style>
.entry-summary img {
	height:100px !important;
	float:left;
	margin-right: 1rem;
}
article {
	clear:both !important;
}
.entry-date { 
	font-style: italic;
}
</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
					<div class="usa-grid">
						<header class="page-header">
							<h1 class="page-title bbg__label--mobile large">Search Results for: <span><?php echo $searchQuery; ?></span></h1>
						</header><!-- .entry-header -->
					</div>
					<div class="usa-grid">
					<?php
						if (isset($_GET['testsearch'])) {
							$filepath = get_template_directory() . "/external-feed-cache/googleSearchCache.json";
							$output=file_get_contents($filepath);
						} else {
							//from https://console.developers.google.com
							//https://developers.google.com/apis-explorer/?hl=en_US#p/customsearch/v1/
							$apiKey = GOOGLE_SITE_SEARCH_API_KEY;
							$cx = GOOGLE_SITE_SEARCH_CX;
							$siteUrl = "www.bbg.gov";
							$searchQuery = $_GET['search'];
							$url = "https://www.googleapis.com/customsearch/v1?q=" . $searchQuery;
							$url .= "&cx=".$cx."&siteSearch=".$siteUrl."&key=".$apiKey;

							$ch = curl_init(); 
							curl_setopt($ch, CURLOPT_URL, $url); 
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
							$output = curl_exec($ch); 
							curl_close($ch);  
						}
						$results = json_decode($output, true);
						foreach ($results['items'] as $r) {
							$title = $r['title'];
							$link = $r['link'];
							$snippet = $r['snippet'];
							$htmlSnippet = $r['htmlSnippet'];
							$thumb = "";

							$pos = strpos($snippet, "...");
							$date = substr($snippet, 0, $pos);
							$restStartPosition = $pos+3;
							$restOfSnippet = substr($snippet, $pos+3);
							$description = $restOfSnippet;
							if (isset($r['pagemap']) && isset($r['pagemap']['metatags']) && (count($r['pagemap']['metatags'][0]) > 0) && isset($r['pagemap']['metatags'][0]['og:description'])) {
								$description = $r['pagemap']['metatags'][0]['og:description'];
							}
							if (isset($r['pagemap']) && isset($r['pagemap']['cse_thumbnail']) && (count($r['pagemap']['cse_thumbnail'][0]) > 0) && isset($r['pagemap']['cse_thumbnail'][0]['src'])) {
								$thumb = $r['pagemap']['cse_thumbnail'][0]['src'];
							}
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
								<header class="entry-header bbg-blog__excerpt-header">
									<h3 class="entry-title bbg-search-results__title--list"><a href="<?php echo $link; ?>" rel="bookmark"><?php echo $title; ?></a></h3>
									<div class="entry-meta bbg__excerpt-meta">
										<span class="posted-on">
											<time class="entry-date published" ></time>
										</span>
									</div><!-- .entry-meta -->
								</header><!-- .bbg-blog__excerpt-header -->
								<div class="entry-summary bbg-search-results__excerpt-content">
									<?php 
										//echo "<img src='$thumb' /><p ><time class='entry-date published' >"; echo $date . "</time> - " .  $description; ?><!-- </p> -->
									<?php echo $description; ?>
								</div><!-- .entry-summary -->
							</article>
					<?php 								
						}
					?>
					</div><!-- .usa-grid -->
				</article><!-- #post-## -->
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
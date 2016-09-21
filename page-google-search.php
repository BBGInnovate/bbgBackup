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

$searchQuery="";

if (isset($_GET["search"])) {
	$searchQuery =  $_GET['search'];
} else if (isset($_GET['testsearch'])) {
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
	margin-bottom:3rem;
}
.entry-date { 
	font-style: italic;
}
article h3 {
	/* font-weight:normal; */
}
</style>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid-full">
				<div class="usa-grid">
					<?php 
					
						$url = site_url() . "/google-custom-search/?search=" . $searchQuery;
							echo "<header class='page-header'><h1 class='page-title bbg__label--mobile large'>Test Google Search</h1></header><form action='$url' method='GET'><input type='text' name='search'></form>";
					if ($searchQuery == "") {
						die();
					}
					
				?>
					<header class="page-header">
						<h1 class="page-title bbg__label--mobile large">Search Results for: <span><?php echo $searchQuery; ?></span></h1>
					</header><!-- .entry-header -->

				</div>
				<div class="usa-grid">
				<?php
					$prevLink = "";
					$nextLink = "";
					$baseUrl = "";
					$prevIndex = 0;
					$nextIndex = 0;
					$firstResultLabel=1;
					if (isset($_GET['testsearch'])) {
						$filepath = get_template_directory() . "/external-feed-cache/googleSearchCache.json";
						$output=file_get_contents($filepath);
					} else {
						//from https://console.developers.google.com
						//https://developers.google.com/apis-explorer/?hl=en_US#p/customsearch/v1/
						$apiKey = GOOGLE_SITE_SEARCH_API_KEY;
						$cx = GOOGLE_SITE_SEARCH_CX;
						$siteUrl = "www.bbg.gov";
						$searchQuery = urlencode(($_GET['search']));
						$apiUrl = "https://www.googleapis.com/customsearch/v1?q=" . $searchQuery;
						$apiUrl .= "&cx=".$cx."&siteSearch=".$siteUrl."&key=".$apiKey;
						$baseUrl = site_url() . "/google-custom-search/?search=" . $searchQuery;
						
						if (isset($_GET['start'])) {
							$start = $_GET['start'];
							$firstResultLabel = $start;
							$apiUrl .= "&start=" . $start;
							$prevIndex = $start - 10;
							if ($prevIndex <= 1) {
								$prevUrl = $baseUrl;
							} else {
								$prevUrl = $baseUrl . "&start=" . $prevIndex;	
							}
							$prevLink = "<a href=\"$prevUrl\" >Previous</a>";
						}
						$ch = curl_init(); 
						curl_setopt($ch, CURLOPT_URL, $apiUrl); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						$output = curl_exec($ch); 
						curl_close($ch);

					}

					$results = json_decode($output, true);
					$totalResults = 0;
					if (isset($results['queries']['request']) && isset($results['queries']['request'][0])) {
						$totalResults = $results['queries']['request'][0]['totalResults'];
					}
					$endResultLabel = min($totalResults, $firstResultLabel+9);

					$about= "";
					if ($totalResults > $endResultLabel) {
						$about = "about";
					}

					echo "<em>Showing results " . $firstResultLabel . "-" . $endResultLabel . " of $about $totalResults </em>";
					
					


					if (isset($results['queries']['nextPage']) && isset($results['queries']['nextPage'][0])) {
						$nextUrl = $baseUrl . "&start=" . $results['queries']['nextPage'][0]['startIndex'];	
						$nextLink = "<a href=\"$nextUrl\" >Next</a>";
					}

					foreach ($results['items'] as $r) {
						$title = $r['title'];
						$link = $r['link'];
						$snippet = $r['snippet'];
						$htmlSnippet = $r['htmlSnippet'];
						$thumb = "";

						/* if the title ends in BBG, don't include that */
						$pos = strpos($title, ' - BBG');
						if ($pos && $pos == (strlen($title)-6)) {
							$title = substr($title, 0, strlen($title)-6);
						}

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

						$title = str_replace("Mart?", "Martí", $title);
						$description = str_replace("Mart?", "Martí", $description);

						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class("bbg__article"); ?>>
							<header class="entry-header bbg-blog__excerpt-header">
								<h3 class="entry-title "><a href="<?php echo $link; ?>" rel="bookmark"><?php echo $title; ?></a></h3>
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
					}	//end foreach $reulsts as $r
				?>
				</div><!-- .usa-grid -->
				<?php 
					if ($prevLink != "" || $nextLink != "") {
						echo '<nav class="navigation posts-navigation" role="navigation">';
						echo '	<h2 class="screen-reader-text">Posts navigation</h2>';
						echo '	<div class="nav-links">';
						if ($prevLink != "") {
							echo '<div class="nav-previous">' . $prevLink . '</div>';
						}
						if ($nextLink != "") {
							echo '<div class="nav-next">' . $nextLink . '</div>';
						}
						echo '	</div>';
						echo '</nav>';
					}
				?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
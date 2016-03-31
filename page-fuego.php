<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Independent Publisher
 * @since   Independent Publisher 1.0 
  template name: Trending

 */

$pageBodyID = "trending";
require(dirname(__FILE__).'/../../fuego/init.php');
use OpenFuego\app\Getter as Getter;
$fuego = new Getter();
		
if (  isset( $_GET['hideLink'] ) && current_user_can('publish_posts') ) {
	$fuego -> hideLink( $_GET['hideLink'] );
}


get_header(); 

?>
<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<main id="content" class="site-content" role="main">

			<div class="usa-grid-full">
				<header class="page-header bbg-page__header" style="margin-bottom: 1.5rem;">
					<div class="bbg-avatar__container bbg-team__icon">
						<div class="bbg-avatar bbg-team__icon__image" style="background-image: url(<?php echo get_template_directory_uri() ?>/img/icon_trending.png);"></div>
					</div>
					<div class="usa-intro bbg-page__header-text bbg-team__text">
						<h1 class="bbg-page__header-title">Trending</h1>
						<h3 class="bbg-page__header-description">An auto-generated stream of conversations and links from the international media, innovation and policy communities.</h3>
						<p>Powered by <a href="https://github.com/niemanlab/openfuego">Fuego</a></p>
					</div>
				</header><!-- .page-header -->
			</div>
			<div class="usa-grid multicolumn">
			<?php 

			//require('../../fuego/init.php');
			$linkDetailID=0;

			if ( isset ( $_GET['linkID']) ) {
				$linkDetailID = $_GET['linkID'];
			} 
			$items = $fuego->getItems(20, 24, FALSE, TRUE, 2, $linkDetailID); // quantity, hours, scoring, metadata
			$counter=0;

			function twitterify($ret) {
				$ret = str_replace("https://medium.com/@","https://medium.com/", $ret);

				$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2", $ret);
				$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"https://\\2\" target=\"_blank\">\\2", $ret);
				$ret = preg_replace("/@(\w+)/", "<a href=\"https://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
				$ret = preg_replace("/#(\w+)/", "<a href=\"https://twitter.com/#\\1\" target=\"_blank\">#\\1</a>", $ret);
				return $ret;
			}

			function ago($time) {
				$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
				$lengths = array("60","60","24","7","4.35","12","10");

				$now = time();

					$difference     = $now - $time;
					$tense         = "ago";

				for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
				   $difference /= $lengths[$j];
				}

				$difference = round($difference);

				if($difference != 1) {
				   $periods[$j].= "s";
				}

				return "$difference $periods[$j] ago ";
			}


			foreach ($items as $key => $item) {
				$counter=$counter+1;
				$title = $item['tw_text'];
				$url = $item['url'];
				$desc = '<p>'.$item['tw_text'].'</p>';
				$author = $item['tw_screen_name'];

				$authorDisplayName=$author;
				if ( ($item['tw_screen_name'] == $item['first_user']) && $item['first_user_fullname'] != "") {
					$authorDisplayName = $item['first_user_fullname'];
				}
	 
				$image = "";
				$provider_name = "";
				$provider_url = "";
				$linkID=$item['link_id'];

				$weightedCount = $item['weighted_count'];

				$twitterImage = $item['tw_profile_image_url'];
				$twitterImage=str_replace("http:","https:",$twitterImage);

				$tweetUrl = "";

				$quoteMakerName = "";
				$quoteMakerHandle = "";
				$quotedMakerImage = "";
				$quotedTweet = "";
				$quotedTweetUrl = "";

				$convertedTime = $item['first_seen'];

				$agoTime = ago($item['first_seen']);

				$dt = new DateTime("@$convertedTime");
				/*$dateStamp = $dt->format('Y-m-d H:i:s');*/
				$dateStamp = $dt->format('F d, Y g:i');

				$imageSize = false; //Test if the image falls within a range of sizes (not too big, not too small).
				$imageSizeMax = OpenFuego\IMAGE_SIZE_MAX; //defined in init.php
				$imageSizeMin = OpenFuego\IMAGE_SIZE_MIN; //defined in init.php

				$isTwitter = false;

				/* often times some metadata values are set and others aren't, so we check each one.  The fuego backend process fills this section using an embed.ly api key */
				if ( isset ($item['metadata']) ) {
					$m = $item['metadata'];

					if ($m['provider_name'] == 'Twitter'){
						/*If it's a quoted tweet... */
						$isTwitter = true;

						//Remove twitter link to quoted material
						$desc = preg_replace('/(https:\/\/t\.co\/)[A-z0-9\.]*/', '', $desc);

						//Convert links, #hashtags and @name to clickable links.
						$desc = twitterify($desc);

						$twitterImage=$item['tw_profile_image_url_bigger'];
						$twitterImage=str_replace("http:","https:",$twitterImage);

						$tweetUrl = $item[tw_tweet_url];


						/*person quoted*/
						if ( isset ( $m['title'] ) ) {
							$quoteMakerName = $m['title'];
							$quoteMakerName = str_replace("on Twitter","", $quoteMakerName);
						}

						if ( isset ( $m['author_name'] ) ) {
							$quoteMakerHandle = $m['author_name'];	
						}

						if ( isset ($m['url'] ) ) {
							$quotedTweetUrl = $m['url'];
						}
						
						if ( isset ($m['description'] ) ) {
							$quotedTweet = $m['description'];
							$quotedTweet = twitterify($quotedTweet);
						}

						if ( isset ($m['thumbnail_url'] ) ) {
							$quoteMakerImage = $m['thumbnail_url'];
							$quoteMakerImage = str_replace("http:","https:", $quoteMakerImage);
						}

					}else{
						if ( isset ( $m['title'] ) ) {
							$title = $m['title'];	

							/*trying to remove offending news org credits */
							$search = array(' - BBC News', ' - BBC', ' - BBC World Service', ' - CNN.com', ' - CNNPolitics.com', ' - FT.com', ' - Premium Times Nigeria', ' | Reuters', ' | Reuters.com');
							$title = str_replace($search, '', $title);
						}

						if ( isset ($m['url'] ) ) {
							$url = $m['url'];
						}
						
						if ( isset ($m['description'] ) ) {
							$desc = '<p>'.$m['description'].'</p>';
						}

						$image ='';
						$image = $item['remoteImage'];
						/*
						if ( isset ($item['localImage'] ) ) {
							//$image=$m['thumbnail_url'];
							$image = $item['localImage'];
							$image = str_replace('/var/www/wordpress/','/',$image);
						}*/
						if ( $image != '' ) {
							if ($m['thumbnail_width'] <= $imageSizeMax && $m['thumbnail_height'] <= $imageSizeMax && $m['thumbnail_width'] >= $imageSizeMin){
								$imageSize = true;
							}
						}

						if ( isset ($m['provider_name'] ) ) {
							$provider_name=$m['provider_name'];

							/* fix bad capitalization on BBC */
							$provider_name = str_replace('Bbc', 'BBC', $provider_name);
						}

						if ( isset ($m['provider_url'] ) ) {
							$provider_url=$m['provider_url'];
						}
					}
				}

			?>
				<article data-weighted-count='<?php echo $weightedCount ?>' data-id='<?php echo $linkID ?>' class="bbg-fuego__article">

					<?php if (!$isTwitter){ ?>
						<header class='bbg-fuego__article__header'>
							<h5 class='bbg-fuego__article__header-source'><a href='<?php echo $provider_url; ?>' class='bbg-fuego__article__header-source-link'><?php echo $provider_name; ?></a></h5>

					<?php if ($counter<=2){ ?>
							<h3 class='bbg-fuego__article__header-title--large'><?php echo "<a href='$url' class='bbg-fuego__article__header-link'>$title</a>"; ?></h3>
					<?php } else { ?>
							<h3 class='bbg-fuego__article__header-title'><?php echo "<a href='$url' class='bbg-fuego__article__header-link'>$title</a>"; ?></h3>
					<?php } ?>


						</header>
						<div class='bbg-fuego__article-content'>
						<?php 
							echo "<a href='$url' class='bbg-fuego__article__description'>"; 


						if ($counter<=2){ 
							if ($image != "" && $imageSize) {
								echo "<div class='listThumbnail bbg-fuego__article__thumbnail--large' style='background-image: url($image);'></div>";
							}
						} else {
							if ($image != "" && $imageSize) {
								echo "<div class='listThumbnail bbg-fuego__article__thumbnail' style='background-image: url($image);'></div>";
							}
						}



							echo $desc; 

							echo "</a>"; 
						?>
						</div>
						<footer class="bbg-fuego__article__footer">
							<span class="bbg-fuego__article__footer-byline">
								<span class="author"><span class='firstShared'>first shared by </span><a class="twitter-link" href="http://twitter.com/<?php echo $author ?>" rel="author">
								<?php echo "<span class='bbg-fueg__article__footer-image' style='background-image: url(".$twitterImage.");'>" ?>
								</span>
								<?php echo "<a href='http://twitter.com/$author'>@$author</a>"; ?></span>
							</span>	
							<span class="sep sep-byline"> | </span>
							<time class="bbg-fuego__article__footer-date" itemprop="datePublished" pubdate="pubdate"><?php echo $agoTime ?></time>
						</footer>

					<?php } else { ?>

						<header class='bbg-fuego__article__header'>
							<h5 class='bbg-fuego__article__header-source'><a href='<?php echo $tweetUrl; ?>' style='float:none;' class='bbg-fuego__article__header-source-link'>Overheard on Twitter</a></h5>
						</header>
						<div class='bbg-fuego__article-content bbg-fuego__article__twitter-conversation'>
							<a href='https://twitter.com/<?php echo $author; ?>' target='_blank'>
								<div class='bbg-fuego__twitter-photo' style='background-image:url(<?php echo $twitterImage ?>);'>
								</div>
							</a>
							<div class='bbg-fuego__twitter__author'>
								<p class='bbg-fuego__twitter__author-name'>
									<?php echo $authorDisplayName; ?>
								</p>
								<p class='bbg-fuego__twitter__author-handle'>
									<a href='https://twitter.com/<?php echo $author; ?>' target='_blank'>@<?php echo $author; ?></a>
								</p>
							</div>
							<div class='clearAll'></div>
							<div class='bbg-fuego__twitter__tweet'>
								<?php echo $desc; ?>
							</div>

							<div class='clearAll'></div>

							<div class='bbg-fuego__twitter__quoted-tweet'>
								<a href='https://twitter.com/<?php echo $quoteMakerHandle; ?>' target='_blank'>
									<div class='bbg-fuego__twitter-photo' style='background-image:url(<?php echo $quoteMakerImage; ?>)' >
									</div>
								</a>
								<div class='bbg-fuego__twitter__quote'>
									<p class='bbg-fuego__twitter__author-name'><?php echo $quoteMakerName; ?> </p>
									<?php if ($quoteMakerHandle != ""): ?>
									<p class="bbg-fuego__twitter__author-handle ">
										<a href='https://twitter.com/<?php echo $quoteMakerHandle; ?>' target='_blank'>
											@<?php echo $quoteMakerHandle; ?>
										</a>
									</p>
									<?php endif; ?>
								</div>
								<div class='clearAll'></div>
								<div class='bbg-fuego__twitter__quote-text'>
									<p><?php echo $quotedTweet; ?></p>
								</div>
							</div>
						</div>
						<footer class="entry-meta bbg-fuego__article__footer">
							<span class="byline"><span class="author vcard"><span class='firstShared'>first shared by </span><a class="url fn n" href="http://twitter.com/<?php echo $author ?>" rel="author"><?php echo "<a href='http://twitter.com/$author'>@$author</a>"; ?></span></span>						
							<span class="sep sep-byline"> | </span>
							<time class="entry-date" itemprop="datePublished" pubdate="pubdate"><?php echo $agoTime ?></time>
						</footer>

					<?php } ?>

					<?php 

						if (current_user_can('publish_posts')) {
							echo "<a href='/trending?hideLink=$linkID' class='bbg-fuego__button__hide-link usa-button'>Hide this link</a><BR>";
						}
					?>
				</article>
			<?php 
			}
			?>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					setTimeout(function() {window.location.reload();}, 300000);
				});
			</script>
		</div><!-- .usa-grid -->
	</main>
	<!-- #content .site-content -->
</div><!-- #primary .content-area -->

		<div id="secondary" class="widget-area" role="complementary">
		</div><!-- #secondary .widget-area -->
</div>
</div><!-- #main .site-main -->

<?php 

get_footer();

?>
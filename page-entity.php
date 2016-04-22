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

$fullName = get_post_meta( $id, 'entity_full_name', true );
$abbreviation = get_post_meta( $id, 'entity_abbreviation', true );
$description = get_post_meta( $id, 'entity_description', true );
$siteUrl = get_post_meta( $id, 'entity_site_url', true );
$rssFeed = get_post_meta( $id, 'entity_rss_feed', true );
$entityLogoID = get_post_meta( $id, 'entity_logo',true );
$entityLogo = "";
if ($entityLogoID) {
	$entityLogoObj = wp_get_attachment_image_src( $entityLogoID , 'Full');
	$entityLogo = $entityLogoObj[0];
} 



//Entity fast facts / by-the-numbers
$budget = get_post_meta( $id, 'entity_budget', true );
$employees = get_post_meta( $id, 'entity_employees', true );
$languages = get_post_meta( $id, 'entity_languages', true );
$appLink = get_post_meta( $id, 'entity_mobile_apps_link', true );

if ($budget != "") {
	$budget = '<li><span class="bbg__article-sidebar__list-label">Annual budget: </span>'. $budget . '</li>';
}
if ($employees != "") {
	$employees = number_format( floatval( $employees ), 0, '.', ',' ); 
	$employees = '<li><span class="bbg__article-sidebar__list-label">Employees: </span>'. $employees . '</li>';
}
if ($languages != "") {
	$languages = '<li><span class="bbg__article-sidebar__list-label">Languages supported: </span>'. $languages . '</li>';
}
if ($appLink != "") {
	$appLink = '<h3><a href="https://innovation.bbg.gov/mobileapps/" class="bbg__article-sidebar__list-label">Download the apps </a></h3><p style="font-family: sans-serif; margin-top: 0;">'. $appLink . '</p>';
}




//Social + contact links
$twitterProfileHandle = get_post_meta( $id, 'entity_twitter_handle', true );
$facebook = get_post_meta( $id, 'entity_facebook', true );
$instagram = get_post_meta( $id, 'entity_instagram', true );




//Contact information
$email = get_post_meta( get_the_ID(), 'entity_email', true );
$phone = get_post_meta( get_the_ID(), 'entity_phone', true );
$street = get_post_meta( get_the_ID(), 'entity_street', true );
$city = get_post_meta( get_the_ID(), 'entity_city', true );
$state = get_post_meta( get_the_ID(), 'entity_state', true );
$zip = get_post_meta( get_the_ID(), 'entity_zip', true );
$learnMore = get_post_meta( get_the_ID(), 'entity_learn_more', true );
$address = "";
$map = "";
if ($email != "") {
	$email = '<li><span class="bbg__list-label">Email: </span><a href="mailto:' . $email . '" title="Email '. $abbreviation . '">'. $email . '</a></li>';
}

if ($phone != "") {
	$phone = '<li><span class="bbg__list-label">Tel: </span>'. $phone . '</li>';
}

if ($street != "" && $city!= "" && $state != "" && $zip != "") {
	$address = '<p>'. $street . '<br/>' . $city . ', ' . $state . ' ' . $zip;

	//Strip spaces for url-encoding.
	$street = str_replace(" ", "+", $street);
	$size = 400;
	$zoom = 13;
	$map = "http://maps.googleapis.com/maps/api/staticmap?center=".$street . ',+'.$city.',+'.$state.'+'.$zip."&zoom=".$zoom."&scale=false&size=".$size."x".$size."&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff0000%7Clabel:1%7C".$street.',+'.$city.',+'.$state.');';
}

if ($learnMore != "") {
	$learnMore = '<p><a href="'. $learnMore .'">Learn more</a> about '. $abbreviation . '</p>';
}





//Default adds a space above header if there's no image set
$featuredImageClass = " bbg__article--no-featured-image";
$bannerPosition=get_post_meta( $id, 'adjust_the_banner_image', true);


/**** BEGIN CREATING rssItems array *****/
$entityJson=getFeed($rssFeed,$id);
$rssItems=array();
$itemContainer=false;
$languageDirection="";

if (property_exists($entityJson, 'channel') && property_exists($entityJson->channel,'item')) {
	$itemContainer=$entityJson->channel;
} else {
	$itemContainer=$entityJson;
}
if ($itemContainer) {
	if (property_exists($itemContainer, 'language')) {
		if ($itemContainer->language=="ar"){
			$languageDirection=" rtl";
		}
	}
	foreach ($itemContainer->item as $e) {
		$title=$e->title;
		$url=$e->link;
		$description=$e->description;
		$enclosureUrl="";
		if (property_exists($e, 'enclosure') && property_exists($e->enclosure, '@attributes') && property_exists($e->enclosure->{'@attributes'}, 'url') ) {
			$enclosureUrl=($e->enclosure->{'@attributes'}->url);
		}
		$rssItems[]=array( 'title'=>$title, 'url'=>$url, 'description'=>$description, 'image'=>$enclosureUrl );
	}
}
/**** DONE CREATING rssItems array *****/

/**** FETCH related press releases ****/
$prCategoryName=get_post_meta( $id, 'entity_pr_category', true );
$pressReleases=array();
if ($prCategoryName != "") {
	$qParams=array(
		'post_type' => array('post'),
		'posts_per_page' => 5,
		'category__and' => array(get_cat_id('Press Release'),
								get_cat_id($prCategoryName)
						  )
	);
	$custom_query = new WP_Query($qParams);
	if ($custom_query -> have_posts()) {
		while ( $custom_query -> have_posts() )  {
			$custom_query->the_post();
			$id=get_the_ID();
			$pressReleases[]=array('url'=>get_permalink($id), 'title'=> get_the_title($id));
		}
	}
	
	wp_reset_postdata();
}

get_header(); 
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

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
						<?php 
						if ($budget != "" || $employees != "" || $languages != "" || $appLink != "") {
							echo '<h3 class="bbg__sidebar-label">Fast facts</h3>';
						} ?>

						<ul class="bbg__article-sidebar__list--labeled">
							<?php 
								echo $budget;
								echo $employees;
								echo $languages;
							?>
						</ul>

												<?php 
						if ($facebook!="" || $twitterProfileHandle!="" || $instagram!=""){
						?>
						<ul class="bbg__article-share " style="margin-bottom: 3rem;">
						<h3 class="bbg__sidebar-label bbg__contact-label">Social media </h3>

							<?php
								if ($facebook!=""){
									echo '<li class="bbg__article-share__link facebook"><a href="'.$facebook.'" title="Like '.get_the_title().' on Facebook"><span class="bbg__article-share__icon facebook"></span><span class="bbg__article-share__text">Facebook</span></a></li>'; 
								}
								if ($twitterProfileHandle!=""){
									echo '<li class="bbg__article-share__link twitter"><a href="https://twitter.com/'.$twitterProfileHandle.'" title="Follow '.get_the_title().' on Twitter"><span class="bbg__article-share__icon twitter"></span><span class="bbg__article-share__text">@'.$twitterProfileHandle.'</span></a></li>'; 
								}
								if ($instagram!=""){
									echo '<li class="bbg__article-share__link instagram"><a href="https://instagram.com/'.$instagram.'" title="Follow '.get_the_title().' on Instagram"><span class="bbg__article-share__icon instagram"></span><span class="bbg__article-share__text">Instagram</span></a></li>'; 
								}
							?>
							&nbsp;
						</ul>
						<?php } ?>

						<?php echo $appLink; ?>

					</div><!-- .bbg__article-sidebar--left -->





					<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">

						<?php echo $pageContent; ?>

						<?php
							if (count($pressReleases)) {
								echo '<h3>Recent '. $abbreviation .' press releases</h3><ul>';
								foreach ($pressReleases as $pr) {
									$url=$pr['url'];
									$title=$pr['title'];
									echo "<li><a href='$url'>$title</a></li>";
								}
								echo "</ul>";
							}
						?>

						<div class="bbg__contact-card">
							<div class="bbg__contact-card__map" style="background-image: url(<?php echo $map; ?>)"></div>
							<div class="bbg__contact-card__text">
							<h3>Contact information</h3>
							<?php 
							echo $address;
							echo '<ul class="usa-unstyled-list">';
							echo $phone;
							echo $email;
							echo '</ul>';
							echo $learnMore;
							?>
							</div>
						</div>


						<?php if($post->post_parent) {
							//borrowed from: https://wordpress.org/support/topic/link-to-parent-page
							$parent = $wpdb->get_row("SELECT post_title FROM $wpdb->posts WHERE ID = $post->post_parent");
							$parent_link = get_permalink($post->post_parent);
						?>
						<a href="<?php echo $parent_link; ?>">Back to <?php echo $parent->post_title; ?> »</a>
						<?php } ?>
					</div><!-- .entry-content -->


					<div class="bbg__article-sidebar">
						<?php 
							if (count($rssItems)) {
								echo '<h3 class="bbg__sidebar-label">Recent stories from ' . $abbreviation. '</h3>';
								echo '<ul class="bbg__profile__related-link__list'. $languageDirection .'">';
								$maxRelatedStories=3;
								for ( $i=0; $i<min($maxRelatedStories,count($rssItems)); $i++) {
									$o=$rssItems[$i];
									echo '<li class="bbg__profile__related-link">';
									echo '<a href="' . $o['url'] . '">';
									if ($o['image'] != "") {
										echo "<img src='". $o['image'] . "'/>";
									}
									echo $o['title'] . '</a>';
									echo '</li>';
								}
								echo '</ul>';
							}
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
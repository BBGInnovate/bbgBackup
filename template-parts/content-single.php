<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */




/**** BEGIN: get next post link for project links ****/
$projectCategoryID = get_cat_id('Project');
$isProject = has_category($projectCategoryID);
$prevLink = "";
$nextLink = "";

//Default adds a space above header if there's no image set
$featuredImageClass = " bbg__article--no-featured-image";

//Add featured video
$videoUrl = get_post_meta( get_the_ID(), 'featured_video_url', true );

//Dateline not automatically included - if the user checked the custom field, prepare it.
$dateline = "";
$includeDateline = get_post_meta( get_the_ID(), 'include_dateline', true );
if ( in_category('Press Release') && $includeDateline ){
	$dateline = '<span class="bbg__article-dateline">';
	$dateline .= get_post_meta( get_the_ID(), 'dateline_location', true );
	$dateline .= " — </span>";
}

//place dateline immediately inside first paragraph tag for formatting purposes
$pageContent = get_the_content();
$pageContent = apply_filters('the_content', $pageContent);
$pageContent = str_replace(']]>', ']]&gt;', $pageContent);
if ($dateline != "") {
	$needle="<p>";
	$replaceNeedle="<p>".$dateline;
	$pos = strpos($pageContent, $needle);
	if ($pos !== false) {
		$pageContent = substr_replace($pageContent, $replaceNeedle, $pos, strlen($needle));
	}
}


//if the user has selected a related profile, it will show up in the right sidebar
$relatedProfileID = get_post_meta( get_the_ID(), 'statement_related_profile', true );
$includeRelatedProfile = false;
if ($relatedProfileID) {
	
	$includeRelatedProfile = true;

	$alternatePhotoID = get_post_meta( get_the_ID(), 'statement_alternate_profile_image', true );
	if ($alternatePhotoID) {
		$profilePhotoID = $alternatePhotoID;
	} else {
		$profilePhotoID = get_post_meta( $relatedProfileID, 'profile_photo', true );	
	}
	
	$profilePhoto = "";

	if ($profilePhotoID) {
		$profilePhoto = wp_get_attachment_image_src( $profilePhotoID , 'mugshot');
		$profilePhoto = $profilePhoto[0];
	}
	$twitterProfileHandle = get_post_meta( $relatedProfileID, 'twitter_handle', true );
	$profileName = get_the_title($relatedProfileID);
	$occupation = get_post_meta( $relatedProfileID, 'occupation', true );
	$profileLink = get_page_link($relatedProfileID);
	$profileExcerpt = my_excerpt($relatedProfileID);

	$relatedProfile = '<div class="bbg__sidebar__primary">';
	$relatedProfile .= '<a href="' . $profileLink . '"><img class="bbg__sidebar__primary-image" src="'.$profilePhoto.'"/></a>';
	$relatedProfile .= '<h3 class="bbg__sidebar__primary-headline"><a href="' . $profileLink . '">' . $profileName . '</a></h3>';
	$relatedProfile .= '<span class="bbg__profile-excerpt__occupation">' . $occupation . '</span>';
	$relatedProfile .= '<p class="">' . $profileExcerpt . '</p></div>';
}

$listsInclude = get_field( 'sidebar_dropdown_include', '', true);

include get_template_directory() . "/inc/shared_sidebar.php";


//Include sidebar map

$includeMap = get_post_meta( get_the_ID(), 'map_include', true );
$mapLocation = get_post_meta( get_the_ID(), 'map_location', true );

if ( $includeMap && $mapLocation) {
	$mapHeadline = get_post_meta( get_the_ID(), 'map_headline', true );
	$mapDescription = get_post_meta( get_the_ID(), 'map_description', true );
	$mapPin = get_post_meta( get_the_ID(), 'map_pin', true );
	$mapZoom = get_post_meta( get_the_ID(), 'map_zoom', true );

	$key = 	'<?php echo MAPBOX_API_KEY; ?>';
	$zoom = 4;
	if ( $mapZoom > 0 && $mapZoom < 20 ) {
		$zoom = $mapZoom;
	}

	$lat = $mapLocation['lat'];
	$lng = $mapLocation['lng'];
	$pin = "";

	if ( $mapPin ){
		$pin = "pin-s+990000(" . $lng .",". $lat .")/";
	}

	//Static map version like this:
	//$map = "https://api.mapbox.com/v4/mapbox.emerald/" . $pin . $lng . ",". $lat . "," . $zoom . "/170x300.png?access_token=" . $key;
}

// Include sidebar list of people who worked on the project
$teamRoster = "";
if( have_rows('project_team_members') ):

	$s = "<div class='bbg__project-team'><h5 class='bbg__project-team__header'>Project team</h5>";
	while ( have_rows('project_team_members') ) : the_row();

		if ( get_row_layout() == 'team_member') {
			$teamMemberName = get_sub_field( 'team_member_name' );
			$teamMemberRole = get_sub_field( 'team_member_role' );
			$teamMemberTwitterHandle = get_sub_field( 'team_member_twitter_handle' );

			if ($teamMemberTwitterHandle && $teamMemberTwitterHandle != ""){
				$teamMemberName = "<a href='https://twitter.com/" . $teamMemberTwitterHandle ."'>" . $teamMemberName . "</a>";
			}

			$s .= "<p><span class='bbg__project-team__name'>$teamMemberName,</span> <span class='bbg__project-team__role'>$teamMemberRole</span></p>";
		}
	endwhile;
	$s .= "</div>";
	$teamRoster .= $s;
endif;

/**** If this press release is categorized as one of the entities, get the entity logo ****/
$categoriesThatShowEntityIcons = ['Press Release','Project','Media Advisory'];
$entityCategories = ['voa','rfa','mbn','ocb','rferl'];

$entityLogos = array();


//DEFAULT_IMAGE
if (in_category($categoriesThatShowEntityIcons))  {
	if ( in_category($entityCategories)) {
		foreach ( $entityCategories as $eCat ) { 
			if ( in_category($eCat) ) {
				$broadcastersPage=get_page_by_title('Our Networks');
				$args = array(
					'post_type' => 'page',
					'posts_per_page' => 1,
					'post_parent' => $broadcastersPage->ID,
					'name' => str_replace('-press-release', '', $eCat)
				);
				$custom_query = new WP_Query($args);
				if ( $custom_query->have_posts() ) {
					while ( $custom_query->have_posts() )  {
						$custom_query->the_post();
						$id = get_the_ID();
						$entityLogoID = get_post_meta( $id, 'entity_logo',true );
						$entityLogo = "";
						$entityLink = get_the_permalink($id);
						if ($entityLogoID) {
							$entityLogoObj = wp_get_attachment_image_src( $entityLogoID , 'Full');
							$entityLogo = $entityLogoObj[0];
						}
						$entityLogos[] = array(
							'logo' => $entityLogo,
							'link' => $entityLink
						);
					}
				}
				wp_reset_postdata();
				wp_reset_query();
			}
		}
	} elseif (in_category('bbg') || in_category('BBG in the News')) {
		/* for the time being, not showing a BBG logo because it's repetitious, particularly at mobile */
		// $entityLink = "https://www.bbg.gov";
		// $entityLogo = DEFAULT_IMAGE;
		
	}
}

/**** If this is a Threats to Press post show the profile ****/
$threatCategoryID = get_cat_id( 'threats-to-press' );
$isThreat = has_category( $threatCategoryID );

$journos = get_field( 'featured_journalists_section' );

$featuredJournalists = "";
$profilePhoto = "";

// check if the flexible content field has rows of data
if( $journos ) {
	// echo '<h2>we have rows</h2>';

 	// loop through the rows of data
    while ( have_rows('featured_journalists_section') ) : the_row();

	// display a sub field value
	$featuredJournalistsSectionLabel = get_sub_field('featured_journalists_section_label');
	$featuredJournalistsObj = get_sub_field('featured_journalist');

	if( $featuredJournalistsObj ) {
		// var_dump( $featuredJournalistsObj );
		// $featuredJournalists .= '<section class="usa-section">';
		$featuredJournalists .= '<div class="usa-grid-full">';
		// $featuredJournalists .= '<div class="usa-grid">';
		$featuredJournalists .= '<header class="page-header">';
		$featuredJournalists .= '<h5 class="bbg__label">' . $featuredJournalistsSectionLabel . '</h5>';
		$featuredJournalists .= '</header>';
		// $featuredJournalists .= '</div>';

		// $featuredJournalists .= '<div class="usa-grid">';

		foreach ( $featuredJournalistsObj as $journalists ) {
	    	foreach ($journalists as $journalist) {
				$profileTitle = $journalist->post_title;
				$profileName = $journalist->first_name . " " . $journalist->last_name;
				$profileOccupation = $journalist->occupation;
				$profilePhoto = $journalist->profile_photo;
				$profileUrl = get_permalink($journalist->ID);
				//$profileExcerpt = get_the_excerpt($relatedPages->ID);
				$profileExcerpt = my_excerpt($journalist->ID); //get_the_excerpt($relatedPages->ID);

				$profileOccupation = '<span class="bbg__profile-excerpt__occupation">' . $profileOccupation .'</span>';

				if ($profilePhoto) {
					$profilePhoto = wp_get_attachment_image_src( $profilePhoto , 'Full');
					$profilePhoto = $profilePhoto[0];
					$profilePhoto = '<a href="' . $profileUrl . '"><img src="' . $profilePhoto . '" class="bbg__profile-featured__profile__mugshot"/></a>';
				}

				$featuredJournalists .= '<div class="bbg__profile-excerpt--sidebar">';

				$featuredJournalists .= '<h3 class="bbg__profile__name"><a href="' . $profileUrl . '">'. $profileName .'</a></h3>';
				$featuredJournalists .= '<p class="bbg__profile-excerpt__text">' . $profilePhoto . $profileOccupation . $profileExcerpt . '</p>';

				$featuredJournalists .= '</div>';
			}
		}

		// $featuredJournalists .= '</div>';
		$featuredJournalists .= '</div>';
		// $featuredJournalists .= '</section>';
	}
    endwhile;
}

$addFeaturedGallery = get_post_meta( get_the_ID(), 'featured_gallery_add', true );
$addFeaturedMap = get_post_meta( get_the_ID(), 'featured_map_add', true );
$featuredMapCaption = get_post_meta( get_the_ID(), 'featured_map_caption', true );

if ($addFeaturedMap) {
	$featuredMapItems = get_field( 'featured_map_items', get_the_ID(), true);
	$features = [];

	for ($i=0; $i < count($featuredMapItems); $i++) {
		$item = $featuredMapItems[$i];
		$featuredMapItemLocation = $item['featured_map_item_coordinates'];
		$featuredMapItemTitle = $item['featured_map_item_title'];
		$featuredMapItemDescription = $item['featured_map_item_description'];
		$featuredMapItemLink = $item['featured_map_item_link'];
		$featuredMapItemVideoLink = $item['featured_map_item_video_link'];
		$im = $item['featured_map_item_image'];

		$featuredMapItemImageUrl = $im['sizes']['medium'];

		$popupBody = "";
		if ($featuredMapItemLink != "") {
			$popupBody .= "<h5><a style='font-weight: bold; ' href='$featuredMapItemLink'>$featuredMapItemTitle</a></h5><div class='u--show-medium-large'><img src='$featuredMapItemImageUrl'></div><BR>$featuredMapItemDescription";
		} else {
			$popupBody .= "<h5><span style='font-weight: bold;'>$featuredMapItemTitle</span></h5><div class='u--show-medium-large'><img src='$featuredMapItemImageUrl'></div><BR>$featuredMapItemDescription";
		}
		

		$features[] = array(
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => array($featuredMapItemLocation['lng'],$featuredMapItemLocation['lat'])
			),
			'properties' => array(
				'title' => "<a href='$featuredMapItemLink'>$featuredMapItemTitle</a>",
				'description' => $popupBody,
				'marker-size' => 'large',
				'marker-symbol' => ''
			)
		);
	}
	$geojsonObj= array(array(
		'type' => 'FeatureCollection',
		'features' => $features
	));
	$geojsonStr=json_encode(new ArrayValue($geojsonObj), JSON_PRETTY_PRINT, 10);

	echo "<script type='text/javascript'>\n";
	echo "geojson = $geojsonStr";
	echo "</script>";
	//echo $geojsonStr;
}

/* TODO: add the following fields */
// media_dev_sponsors
// media_dev_additional_files
// media_dev_presenters

$mediaDevSponsors = "";
if ( have_rows('media_dev_sponsors') ):
	$mediaDevSponsors .= '<h3 class="bbg__sidebar-label">Funders</h3>';
	if( have_rows('media_dev_sponsors') ):
		$mediaDevSponsors .= "<ul class='usa-unstyled-list'>";
		while ( have_rows('media_dev_sponsors') ) : the_row();
			$sponsorName = get_sub_field('media_dev_participant_name');
			$mediaDevSponsors .= "<li>";
			$mediaDevSponsors .=  '<h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name">';
			$mediaDevSponsors .= $sponsorName;
			$mediaDevSponsors .= '</h5>';
			$mediaDevSponsors .= '</li>';
		endwhile;
		$mediaDevSponsors .= "</ul><BR>";
	endif;
endif;

$mediaDevPresenters = "";
if( have_rows('media_dev_presenters') ):
	$mediaDevSponsors .= '<h3 class="bbg__sidebar-label">Presenters</h3>';
	$mediaDevPresenters .= "<ul class='usa-unstyled-list'>";
	while ( have_rows('media_dev_presenters') ) : the_row();
		$presenterName = get_sub_field('media_dev_participant_name');
		$presenterTitle = get_sub_field('media_dev_participant_job_title');
		$mediaDevPresenters .= '<li>';
		$mediaDevPresenters .= '<h5 class="bbg__sidebar__primary-headline bbg__profile-excerpt__name">' . $presenterName . '</h5>';
		$mediaDevPresenters .= '<span class="bbg__profile-excerpt__occupation">' . $presenterTitle . '</span>';
		$mediaDevPresenters .= '</li>';
	endwhile;
	$mediaDevPresenters .= "</ul><BR>";
endif;



/* Displaying award info -- not implemented yet*/
$awardCategoryID = get_cat_id('Award');
$isAward = has_category($awardCategoryID);

if ($isProject) {
	//$categories=get_the_category();
	$post_id = $post->ID; // current post ID
	if (isset($_GET['category_id'])) {
		$args = array(
			'category__and' => array($projectCategoryID,$_GET['category_id']),
			'orderby'  => 'post_date',
			'order'    => 'DESC',
			'posts_per_page' => -1
		);
	} else {
		$args = array(
			'category' => $projectCategoryID,
			'orderby'  => 'post_date',
			'order'    => 'DESC',
			'posts_per_page' => -1
		);
	}

	$posts = get_posts( $args );
	// get IDs of posts retrieved from get_posts
	$ids = array();
	foreach ( $posts as $thepost ) {
	    $ids[] = $thepost->ID;
	}
	// get and echo previous and next post in the same category
	$thisindex = array_search( $post_id, $ids );

	if ($thisindex > 0) {
		$previd = $ids[ $thisindex - 1 ];
		$prevPost = get_post($previd);
		$prevPostTitle = $prevPost->post_title;

		$prevPostPermalink=esc_url( get_permalink($previd) );
		if (isset($_GET['category_id'])) {
			$prevPostPermalink=add_query_arg('category_id', $_GET['category_id'], $prevPostPermalink);
		}

		$prevLink = '<a rel="prev" href="' . $prevPostPermalink . '" title="' . $prevPostTitle . '"><span class="bbg__article__nav-icon left-arrow"></span><span class="bbg__article__nav-text">Previous: ' . $prevPostTitle . '</span></a>';
		$prevLink = '<div class="bbg__article__nav-link bbg__article__nav-previous">' . $prevLink . '</div>';
	}
	if ($thisindex < (count($ids)-1)) {
		$nextid = $ids[ $thisindex + 1 ];
		$nextPost = get_post($nextid);
		$nextPostTitle = $nextPost->post_title;

		$nextPostPermalink=esc_url( get_permalink($nextid) );
		if (isset($_GET['category_id'])) {
			$nextPostPermalink=add_query_arg('category_id', $_GET['category_id'], $nextPostPermalink);
		}

		$nextLink = '<a rel="next" href="' . $nextPostPermalink . '" title="' . $nextPostTitle . '"><span class="bbg__article__nav-icon right-arrow"></span><span class="bbg__article__nav-text">Next: ' . $nextPostTitle . '</span></a>';
		$nextLink = '<div class="bbg__article__nav-link bbg__article__nav-next">' . $nextLink . '</div>';
	}

	$prevLink = "";
	$nextLink = "";
}
/**** END CREATING NEXT/PREV LINKS ****/

/* Displaying award info -- not implemented yet*/
$podcastCategoryID = get_cat_id('Podcasts');
$isPodcast = has_category($podcastCategoryID);
$soundcloudPlayer = "";
if ($isPodcast) {
	$podcastSoundcloudURL = get_post_meta( get_the_ID(), 'podcast_soundcloud_url', true );
	$podcastTranscript = get_post_meta( get_the_ID(), 'podcast_transcript', true );
	$podcastTranscript = apply_filters('the_content', $podcastTranscript);
	if ($podcastTranscript) {
		$podcastTranscript = '<div id="podcastTranscript" class="usa-accordion-bordered bbg__committee-list"><ul class="usa-unstyled-list"><li><button id="transcriptButton" class="usa-button-unstyled" aria-expanded="false" aria-controls="collapsible-podcast-1">Transcript</button><div id="collapsible-podcast-1" aria-hidden="true" class="usa-accordion-content">' . $podcastTranscript . '</div><li></ul></div>';
	}
	$soundcloudPlayer = "<div><iframe width='100%' height='166' scrolling='no' frameborder='no' src='$podcastSoundcloudURL'></iframe><a onClick=\"tButton = jQuery('#transcriptButton'); if (tButton.attr('aria-expanded')=='false') {tButton.click(); }\" href='#podcastTranscript' style='cursor:pointer;'>View Transcript</a><BR><BR></div>";
}


//the title/headline field, followed by the URL and the author's twitter handle
$twitterText = "";
$twitterText .= html_entity_decode( get_the_title() );
$twitterText .= " by @bbggov";
$twitterText .= " " . get_permalink();

$twitterURL="//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
$fbUrl="//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );
$hideFeaturedImage = FALSE;
?>
<style>
.leaflet-popup-pane {
	min-width: 300px !important;
}
</style>

<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>

	<?php
		//in order of priority, use one of the following: featured map, video, image
		
		if ($addFeaturedGallery) {
			$hideFeaturedImage = true;
		}
		if ($addFeaturedMap) {
			echo "<div class='usa-grid-full'><div id='map-featured' class='bbg__map--banner'></div>";
			if ($featuredMapCaption != "") {
				echo "<p class='bbg__article-header__caption'>$featuredMapCaption</p>";
			}
			echo "</div>";
			$hideFeaturedImage = TRUE;
		} else if ( $videoUrl != "" ) {
			echo featured_video($videoUrl);
			$hideFeaturedImage = TRUE;
		} elseif ( has_post_thumbnail() && ( $hideFeaturedImage != 1 ) ) {
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

			if ( $featuredImageCutline != "" ) {
				echo '<div class="usa-grid">';
					echo "<div class='wp-caption-text'>$featuredImageCutline</div>";
				echo '</div> <!-- usa-grid -->';
			}

			echo '</div>';
			echo '</div> <!-- usa-grid-full -->';
		}
	?><!-- .bbg__article-header__thumbnail -->

	<div class="bbg__article__nav">
		<?php echo $prevLink; ?>
		<?php echo $nextLink; ?>
	</div><!-- .bbg__article__nav -->


	<div class="usa-grid">

		<?php echo '<header class="entry-header bbg__article-header' . $featuredImageClass . '">'; ?>

		<?php 
			echo bbginnovate_post_categories(); 
		?>
		<!-- .bbg__label -->

			<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
			<!-- .bbg__article-header__title -->

			<?php 
				if ($addFeaturedGallery) {
					echo "<div class='usa-grid-full bbg__article-featured__gallery' >";
					$featuredGalleryID = get_post_meta( get_the_ID(), 'featured_gallery_id', true );
					putUniteGallery($featuredGalleryID);
					echo "</div>";
				}
			?>

			<div class="entry-meta bbg__article-meta">
				<?php bbginnovate_posted_on(); ?>
			</div><!-- .bbg__article-meta -->
		</header><!-- .bbg__article-header -->

		

		<div class="bbg__article-sidebar--left">
			<?php
				$numLogos = count($entityLogos);
				if ( $numLogos > 0 && $numLogos < 3) {
					for ($i=0; $i < $numLogos; $i++) {
						$e = $entityLogos[$i];
						$entityLink = $e['link'];
						$entityLogo = $e['logo'];
						$firstClass = "";
						//attach a utility class to allow us to apply spacing at mobile when you have multiple entity icons
						if ($i ==0 && $numLogos > 0) {
							$firstClass = "bbg__entity-logo__press-release-first-of-many";
						}
						echo '<a href="'.$entityLink.'" title="Learn more"><img src="'. $entityLogo . '" class="bbg__entity-logo__press-release ' . $firstClass . '"/></a>';	
					}
				}
			?>

			<h3 class="bbg__sidebar-label bbg__contact-label">Share </h3>
			<ul class="bbg__article-share">
				<li class="bbg__article-share__link facebook">
					<a href="<?php echo $fbUrl; ?>">
						<span class="bbg__article-share__icon facebook"></span>
					</a>
				</li>
				<li class="bbg__article-share__link twitter">
					<a href="<?php echo $twitterURL; ?>">
						<span class="bbg__article-share__icon twitter"></span>
					</a>
				</li>
			</ul>

		</div><!-- .bbg__article-sidebar--left -->

		<div class="entry-content bbg__article-content <?php echo $featuredImageClass; ?>">
			<?php

				if ($isPodcast) {
					echo $soundcloudPlayer;
				}

				echo $pageContent;

				if ($isPodcast) {
					echo $podcastTranscript;
				}

				/* START AWARD INFO */
				if ($isAward) {
					$awardDescription = get_post_meta( get_the_ID(), 'standardpost_award_description', true );	
					if ( isset($awardDescription) && $awardDescription!= "" ) {
						$awardOrganization = get_field( 'standardpost_award_organization', get_the_ID(), true);
						$awardOrganization = $awardOrganization -> name;
					
						$awardLogo = get_post_meta( get_the_ID(), 'standardpost_award_logo', true );
						$awardLogoImage = "";
						if ( $awardLogo ){
							$awardLogoImage = wp_get_attachment_image_src( $awardLogo , 'small-thumb-uncropped');
							$awardLogoImage = $awardLogoImage[0];
							// $awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__sidebar__primary-image"/>';
							$awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__profile-excerpt__photo"/>';
						}
		
						echo '<div class="usa-grid-full bbg__contact-box">';
							echo '<h3>About ' . $awardOrganization . '</h3>';
							echo $awardLogoImage;
							echo '<p><span class="bbg__tagline">' . $awardDescription . '</span></p>';
						echo '</div>';
					}
					/* END AWARD INFO */
				}


				/* START CONTACT CARDS */
				$contactPostIDs = get_post_meta( $post->ID, 'contact_post_id',true );
				renderContactCard($contactPostIDs);
				/* END CONTACT CARDS */
			?>
		</div><!-- .entry-content -->

		<div class="bbg__article-sidebar">
			<?php
				if ($includeRelatedProfile) {
					echo $relatedProfile;
				}

				echo $featuredJournalists;

				if ( $includeMap  && $mapLocation){
					//echo "<img src='" . $map . "' class='bbg__locator-map'/>";
					echo "<h4>" . $mapHeadline . "</h4>";
					echo "<div id='map' class='bbg__locator-map'>";
						echo "<p>" . $mapDescription . "</p>";
					echo "</div>";
				}

				if ($isAward) {
					echo "<h5 class='bbg__label small bbg__sidebar__download__label'>About the Award</h5>";
					echo getAwardInfo(get_the_ID());
				}

				if ( $includeSidebar ) {
					echo $sidebar;
				}

				if ( $listsInclude ) {
					echo $sidebarDownloads;
				}

				echo $mediaDevSponsors;
				echo $mediaDevPresenters;

				echo $teamRoster;

				

				echo getAccordion();
			?>
			<p></p>
		</div><!-- .bbg__article-sidebar -->

	</div><!-- .usa-grid -->
</article><!-- #post-## -->




<?php
if ( $addFeaturedMap){
?>
	<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />
	<script type="text/javascript">
		L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';
		var map = L.mapbox.map('map-featured', 'mapbox.emerald')
	    var markers = L.mapbox.featureLayer();
	    for (var i = 0; i < geojson[0].features.length; i++) {
	        var coords = geojson[0].features[i].geometry.coordinates;
	        var title = geojson[0].features[i].properties.title; //a[2];
	        var description = geojson[0].features[i].properties['description'];
	        var marker = L.marker(new L.LatLng(coords[1], coords[0]));
	        var popupText = description;

	        //rather than just use html, do this - http://stackoverflow.com/questions/10889954/images-size-in-leaflet-cloudmade-popups-dont-seem-to-count-to-determine-popu
	       	var divNode = document.createElement('DIV');
			divNode.innerHTML =popupText;
	        marker.bindPopup(divNode);
	        marker.addTo(markers);
	    }
	    markers.addTo(map);
		map.scrollWheelZoom.disable();
		function centerMap(){
			map.fitBounds(markers.getBounds());
		}
		centerMap();

		//Recenter the map on resize
		function resizeStuffOnResize(){
		  waitForFinalEvent(function(){
				centerMap();
		  }, 500, "some unique string");
		}

		//Wait for the window resize to 'end' before executing a function---------------
		var waitForFinalEvent = (function () {
			var timers = {};
			return function (callback, ms, uniqueId) {
				if (!uniqueId) {
					uniqueId = "Don't call this twice without a uniqueId";
				}
				if (timers[uniqueId]) {
					clearTimeout (timers[uniqueId]);
				}
				timers[uniqueId] = setTimeout(callback, ms);
			};
		})();

		window.addEventListener('resize', function(event){
			resizeStuffOnResize();
		});

		resizeStuffOnResize();

	</script>
<?php } ?>



<?php
/* if the map is set, then load the necessary JS and CSS files */
if ( $includeMap  && $mapLocation){
?>
	<script src='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox.js/v2.2.0/mapbox.css' rel='stylesheet' />

	<script type="text/javascript">
	L.mapbox.accessToken = '<?php echo MAPBOX_API_KEY; ?>';
	var map = L.mapbox.map('map', 'mapbox.streets')
		//.setView([38.91338, -77.03236], 16);
		<?php echo '.setView(['. $lat . ', ' . $lng . '], ' . $zoom . ');'; ?>

	map.scrollWheelZoom.disable();

	L.mapbox.featureLayer({
		// this feature is in the GeoJSON format: see geojson.org
		// for the full specification
		type: 'Feature',
		geometry: {
			type: 'Point',
			// coordinates here are in longitude, latitude order because
			// x, y is the standard for GeoJSON and many formats
			coordinates: [
				//-77.03221142292,
				//38.913371603574
				<?php echo $lng . ', ' . $lat; ?>
			]
		},
		properties: {
			title: '<?php echo $mapHeadline; ?>',
			description: '<?php echo $mapDescription; ?>',
			// one can customize markers by adding simplestyle properties
			// https://www.mapbox.com/guides/an-open-platform/#simplestyle
			'marker-size': 'large',
			'marker-color': '#981b1e',
			'marker-symbol': ''
		}
	}).addTo(map);

	</script>
<?php } ?>

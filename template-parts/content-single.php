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
	$profilePhotoID = get_post_meta( $relatedProfileID, 'profile_photo', true );
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

/*** For Events, there is often an eventBriteID that has been entered - render the iFrame ***/
$eventBriteID = get_post_meta( get_the_ID(), 'board_meeting_eventbrite_id', true );
$eventStr="";
if ($eventBriteID) {
	$eventStr='<div style="width:100%; text-align:left;" ><iframe  src="//eventbrite.com/tickets-external?eid='.$eventBriteID.'&ref=etckt" frameborder="0" height="260" width="100%" vspace="0" hspace="0" marginheight="5" marginwidth="5" scrolling="auto" allowtransparency="true"></iframe><div style="font-family:Helvetica, Arial; font-size:10px; padding:5px 0 5px; margin:2px; width:100%; text-align:left;" ><a class="powered-by-eb" style="color: #dddddd; text-decoration: none;" target="_blank" href="http://www.eventbrite.com/r/etckt">Powered by Eventbrite</a></div></div>';
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
$entityCategories=['voa','rfa','mbn','ocb','rferl'];
$entityLogo="";
$entityLink="";
if ( (in_category('Press Release') && in_category($entityCategories) ) || in_category('Project') ) {
	foreach ($entityCategories as $eCat) {
		if ($entityLogo=="" && in_category($eCat)) {
			$broadcastersPage=get_page_by_title('Our Broadcasters');
			$args = array(
				'post_type'=> 'page',
				'posts_per_page' => 1,
				'post_parent' => $broadcastersPage->ID,
				'name' => str_replace('-press-release', '', $eCat)
			);
			$custom_query = new WP_Query($args);
			if ($custom_query -> have_posts()) {
				while ( $custom_query -> have_posts() )  {
					$custom_query->the_post();
					$id=get_the_ID();
					$entityLogoID = get_post_meta( $id, 'entity_logo',true );
					$entityLogo="";
					$entityLink=get_the_permalink($id);
					if ($entityLogoID) {
						$entityLogoObj = wp_get_attachment_image_src( $entityLogoID , 'Full');
						$entityLogo = $entityLogoObj[0];
					}
				}
			}
			wp_reset_postdata();
			wp_reset_query();
		}
	}
}

/* Displaying award info -- not implemented yet*/
$awardCategoryID = get_cat_id('Award');
$isAward = has_category($awardCategoryID);

$award = "";

if ($isAward) {

	$awardTitle = get_post_meta( get_the_ID(), 'standardpost_award_title', true );
	$awardYear = get_post_meta( get_the_ID(), 'standardpost_award_year', true );
	$awardRecipient = get_post_meta( get_the_ID(), 'standardpost_award_recipient', true );
	$awardWinningWork = get_post_meta( get_the_ID(), 'standardpost_award_winning_work', true );
	$awardWinner = get_post_meta( get_the_ID(), 'standardpost_award_winner', true );
	$awardLink = get_post_meta( get_the_ID(), 'standardpost_award_link', true );

	$awardOrganization = get_field( 'standardpost_award_organization', get_the_ID(), true);
	$awardOrganization = $awardOrganization -> name;
	$awardLogo = get_post_meta( get_the_ID(), 'standardpost_award_logo', true );
	$awardOrgUrl = get_post_meta( get_the_ID(), 'standardpost_award_org_url', true );
	$awardDescription = get_post_meta( get_the_ID(), 'standardpost_award_description', true );


	$award .='<div class="bbg__sidebar__primary">';
		$awardLogoImage = "";

		if ( $awardLogo ){
			$awardLogoImage = wp_get_attachment_image_src( $awardLogo , 'small-thumb-uncropped');
			$awardLogoImage = $awardLogoImage[0];
			// $awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__sidebar__primary-image"/>';
			$awardLogoImage = '<img src="' . $awardLogoImage . '" class="bbg__profile-excerpt__photo"/>';
		}

		// $award .= $awardLogoImage;

		// Award-winning work with link to work if it exists
		if ( $awardLink && $awardLink != "" ){
			$award .= '<h4 class="bbg__sidebar__primary-headline"><a href="' . $awardLink .'">' . $awardWinningWork . '</a></h4>';
		} else {
			$award .= '<h4 class="bbg__sidebar__primary-headline">' . $awardWinningWork . '</h4>';
		}

		$award .= '<p><strong>Producer: </strong>' . $awardWinner . '<br/>';
		$award .= '<strong>Network: </strong>' . $awardRecipient . '</p>';

		$award .= '<p><strong>Award: </strong>' . $awardYear . ' ' . $awardTitle . '<br/>';
		$award .= '<strong>Presented by: </strong><a href="' . $awardOrgUrl .'">' . $awardOrganization . '</a><br/>';
	$award .= '</div>';
}

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
}
/**** END CREATING NEXT/PREV LINKS ****/



//the title/headline field, followed by the URL and the author's twitter handle
$twitterText = "";
$twitterText .= html_entity_decode( get_the_title() );
$twitterText .= " by @bbggov";
$twitterText .= " " . get_permalink();

$twitterURL="//twitter.com/intent/tweet?text=" . rawurlencode( $twitterText );
$fbUrl="//www.facebook.com/sharer/sharer.php?u=" . urlencode( get_permalink() );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( "bbg__article" ); ?>>

	<?php
		//If a featured video is set, include it.
		//ELSE if a featured image is set, include it.
		$hideFeaturedImage = FALSE;
		if ( $videoUrl != "" ) {
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

		<?php echo '<header class="entry-header bbg__article-header'.$featuredImageClass.'">'; ?>

		<?php echo bbginnovate_post_categories(); ?>
		<!-- .bbg__label -->

			<?php the_title( '<h1 class="entry-title bbg__article-header__title">', '</h1>' ); ?>
			<!-- .bbg__article-header__title -->

			<div class="entry-meta bbg__article-meta">
				<?php bbginnovate_posted_on(); ?>
			</div><!-- .bbg__article-meta -->
		</header><!-- .bbg__article-header -->

		<div class="bbg__article-sidebar--left">
			<?php
				if ($entityLogo!=""){
					echo '<a href="'.$entityLink.'" title="Learn more"><img src="'. $entityLogo . '" class="bbg__entity-logo__press-release"/></a>';
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
				echo $eventStr;
				echo $pageContent;

				/* START AWARD ORGANIZATION */
				echo '<div>';
					if ($awardDescription && $awardDescription!= ""){
						echo '<h3>About ' . $awardOrganization . '</h3>';
						echo $awardLogoImage;
						// echo '<div class="bbg__tagline">';
						echo '<p><span class="bbg__tagline">' . $awardDescription . '</span></p>';
						// echo '</div>';
					}
				echo '</div>';
				/* END AWARD ORGANIZATION */

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

				if ( $includeMap  && $mapLocation){
					//echo "<img src='" . $map . "' class='bbg__locator-map'/>";
					echo "<h4>" . $mapHeadline . "</h4>";
					echo "<div id='map' class='bbg__locator-map'>";
						echo "<p>" . $mapDescription . "</p>";
					echo "</div>";
				}

				if ( $includeSidebar ) {
					echo $sidebar;
				}

				if ( $listsInclude ) {
					echo $sidebarDownloads;
				}

				echo $teamRoster;

				echo $award;
			?>
			<p></p>
		</div><!-- .bbg__article-sidebar -->

	</div><!-- .usa-grid -->
</article><!-- #post-## -->

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

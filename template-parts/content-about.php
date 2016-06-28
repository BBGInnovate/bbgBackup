<?php
/**
 * Template part for displaying a multicolumn child pages in About pages
 * 3 columns without byline or date
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

global $grids;
global $specialMissionClass;
global $headline;
global $tagline;
global $excerpt;
global $includePageDescription;

$includeDescription = TRUE;
if ( isset ( $includePageDescription ) && $includePageDescription == FALSE ) {
	$includeDescription = FALSE;
}

if ( ! isset ($grids) ) {
	$grids = "bbg-grid--1-2-2";
}
$pageTitle = strtolower(get_the_title());

if ( $specialMissionClass == TRUE && $pageTitle == "mission" ) {
	$grids .= " bbg__about__excerpt-content--large";
}

$classNames = "bbg__about__excerpt bbg__about__child " . "bbg__about__child--" . $pageTitle . " " . $grids;

$postPermalink = esc_url( get_permalink() );
if ( isset( $_GET['category_id'] ) ) {
	$postPermalink = add_query_arg('category_id', $_GET['category_id'], $postPermalink);
}

if ( $headline ) {
	$pageName = $headline;
} else {
	$pageName = get_the_title();
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classNames); ?>>
	<header class="entry-header bbg__about__excerpt-header">
	<?php
		if ( $hideLink == FALSE ) {
			$link = sprintf( '<a href="%s" rel="bookmark">', $postPermalink );
			$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink );
			$linkLabel = '<h6 class="bbg__label">' . $link;

			echo "<!-- Child page title -->";
			echo $linkLabel;
			echo $pageName;
			echo "</a></h6>";

		} else {
			echo "<h6 class='bbg__label'>$pageName</h6>";
		}
		?>

		<!-- Child page thumbnail -->
		<?php
			if ( has_post_thumbnail() ) {
				echo '<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">';
					echo $linkImage;
						$thumbnail = the_post_thumbnail('medium-thumb');
						echo $thumbnail;
					echo '</a>';
				echo '</div>';
			}
		?>

		<!-- Child page headline text -->
		<?php if ($tagline) { ?>
			<h3 class="bbg__about__child__second-headline">
				<?php
					echo $link;
					echo $tagline;
				?>
			</a>
			</h3>
		<?php } ?>

	</header><!-- .entry-header -->

	<!-- Child page excerpt -->
	<?php if ($includeDescription) { ?>
		<div class="entry-content">
			<?php
				if ($pageTitle == "mission") {
					the_excerpt();
				} else {
					echo $excerpt;
				}
			?>
		</div><!-- .bbg__about__excerpt-title -->
	<?php } ?>

</article><!-- .bbg__about__excerpt -->

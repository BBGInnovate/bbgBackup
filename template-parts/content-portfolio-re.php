<?php
/**
 * Template part for displaying a portfolio excerpt
 * 3 columns without byline or date
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

global $includePortfolioDescription;
global $gridClass;

$includeDescription = TRUE;
if ( isset ($includePortfolioDescription) && $includePortfolioDescription == FALSE ) {
	$includeDescription = FALSE;
}

$postPermalink = esc_url( get_permalink() );
if ( isset($_GET['category_id']) ) {
	$postPermalink = add_query_arg('category_id', $_GET['category_id'], $postPermalink);
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		$link = sprintf('<a href="%s" rel="bookmark">', $postPermalink);
		$linkImage = sprintf('<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink);
		$linkH3 = '<h3 class="entry-title bbg-portfolio__excerpt-title">' . $link;
	?>
		<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
			<?php
				echo $linkImage;

				// SET DEFAULT THUMB IF NONE EXISTS
				$thumbnail = '<img src="' . get_template_directory_uri() . '/img/BBG-portfolio-project-default.png" alt="White BBG logo on medium gray background" />';

				if ( has_post_thumbnail() ) {
					$thumbnail = the_post_thumbnail( 'medium-thumb' );
				}
				echo $thumbnail;
			?>
			</a>
		</div>

		<?php echo buildLabel( implode( get_post_class( $classNames ) ) );	//check bbg-functions-utilities ?>

		<header class="">
			<?php the_title( sprintf( $linkH3, $postPermalink ), '</a></h3>' ); ?>
		</header>

		<?php 
			$content = get_the_content();
			$trimmed_content = wp_trim_words($content, 25, '... '. '<a href="' . get_permalink() . '">READ MORE</a>');
			$content_block  = '<p>';
			$content_block .= $trimmed_content;
			$content_block .= '</p>';
			echo $content_block;
		?>
</article>
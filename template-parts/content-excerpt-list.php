<?php
/**
 * Template part for displaying excerpts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */


//You can set the grid and breakpoints
global $gridClass;
if (! isset ($gridClass)) {
	$gridClass="";
}

//The image is included by default 
global $includeImage;
if (! isset ($includeImage)) {
	$includeImage=TRUE;
}

//The byline meta info is displayed by default 
global $includeMeta;
if (! isset ($includeMeta)) {
	$includeMeta=TRUE;
}

//The article excerpt is displayed by default 
global $includeExcerpt;
if (! isset ($includeExcerpt)) {
	$includeExcerpt=TRUE;
}

//The article date (event date) is hidden by default
global $includeDate;
if (! isset ($includeDate)) {
	$includeDate=FALSE;
}


/*
//Remove the space below headlines if there's no image, meta or excerpt
//to create a list of headlines
if (!$includeImage && !$includeMeta && !$includeExcerpt){
	$removeSpace = "u--remove-margin-bottom";
}
$classNames="bbg-blog__excerpt--list " . $gridClass . " ". $removeSpace;
*/

//Concatenate misc. classes
// $classNames="bbg-blog__excerpt--list " . $gridClass . " ";
$classNames="bbg-blog__excerpt--list ";

//Define the link to the post
$link = sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) );
$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', esc_url( get_permalink() ) );
?>

<div class="usa-width-one-third">
	<article id="post-<?php the_ID(); ?>" <?php post_class($classNames); ?> >

		<header class="entry-header bbg-blog__excerpt-header">
			<h3 class="entry-title bbg-blog__excerpt-title--list <?php if ($includeDate) { echo 'bbg__excerpt-title--showDate'; }; ?>">
				<a href="<?php echo esc_url( get_permalink() );?>" rel="bookmark"><?php echo get_the_title(); ?></a>
			</h3>
		</header>

		<?php if ( 'post' === get_post_type() ) : ?>
			<?php if ($includeMeta) { ?>
				<div class="entry-meta bbg__excerpt-meta">
					<?php bbginnovate_posted_on(); ?>
				</div>
			<?php } ?>
		<?php endif; ?>

		<?php if ($includeExcerpt) { ?>
			<div class="entry-content bbg-blog__excerpt-content">
				<?php
					the_excerpt();
					// $post_content = wp_trim_words(get_the_content(), 18);
					// echo $post_content;
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
						'after'  => '</div>',
					) );
				?>
			</div>
		<?php } ?>
	</article>
</div>
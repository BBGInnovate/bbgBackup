<?php
/**
 * Template part for displaying a portfolio excerpt
 * 3 columns without byline or date
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */

if ( !isset ($gridClass) ) {
	$gridClass = "bbg-grid--1-2-3";
}
$classNames = "bbg-portfolio__excerpt " . $gridClass;

$postPermalink = esc_url( get_permalink() );
$link = sprintf( '<a href="%s" rel="bookmark">', $postPermalink );
$linkImage = sprintf( '<a href="%s" rel="bookmark" tabindex="-1">', $postPermalink );
$linkH3 = '<h3 class="entry-title bbg-portfolio__excerpt-title">'.$link;

$networkLong = array(
	'voa' => 'Voice of America',
	'ocb' => 'Office of Cuba Broadcasting',
	'mbn' => 'Middle East Broadcasting Network',
	'rferl' => 'Radio Free Europe / Radio Liberty',
	'rfa' => 'Radio Free Asia'
);
$network = get_post_meta( get_the_ID(), 'burke_network', true );
$isWinner = get_post_meta( get_the_ID(), 'burke_is_winner', true );
$occupation = get_post_meta( get_the_ID(), 'burke_occupation', true );
$reasonTagline = get_post_meta( get_the_ID(), 'burke_reason_tagline', true );
$network = $networkLong[$network];



?>



<article id="post-<?php the_ID(); ?>" <?php post_class($classNames); ?>>
	<header class="entry-header bbg-portfolio__excerpt-header">
		<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
			<?php
				echo $linkImage;
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'medium-thumb' );
				} else {
					echo '<img src="' . get_template_directory_uri() . '/img/BBG-portfolio-project-default.png" alt="White BBG logo on medium gray background" />';
				}
			?>
			</a>
		</div>
		<?php echo buildLabel(implode(get_post_class($classNames)));	//check bbg-functions-utilities ?>
		<?php the_title( sprintf( $linkH3, $postPermalink ), '</a></h3>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content bbg-portfolio__excerpt-content bbg-blog__excerpt-content">
		<?php 
			echo "<div class='bbg-burke__occupation'>$occupation</div>";
			echo "<div class='bbg-burke__network'>$network</div><BR>";
			echo "<div class='bbg-burke__tagline'>$reasonTagline</div>";
			//the_excerpt(); 
			//echo "<em>"
		?>
	</div><!-- .bbg-portfolio__excerpt-title -->

</article><!-- .bbg-portfolio__excerpt -->

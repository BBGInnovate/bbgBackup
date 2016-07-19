<?php
/**
 * The template for displaying standard single project posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bbgRedesign
 */
/* we go through the loop once and reset it in order to get some vars for our og tags */
if ( have_posts() ) {
	the_post();


	$metaAuthor = get_the_author();
	$metaAuthorTwitter = get_the_author_meta( 'twitterHandle' );
	$ogTitle = get_the_title();

	$metaKeywords = strip_tags(get_the_tag_list('',', ',''));

	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'Full' );
	$ogImage = $thumb['0'];

	$socialImageID = get_post_meta( $post->ID, 'social_image',true );
	if ($socialImageID) {
		$socialImage = wp_get_attachment_image_src( $socialImageID , 'Full');
		$ogImage = $socialImage[0];
	}

	$ogDescription = get_the_excerpt(); //get_the_excerpt()
	rewind_posts();
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			<div class="bbg__article-footer usa-grid">
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( !in_category('Project') &&(comments_open() || get_comments_number())):
						comments_template();
					endif;
				?>
			</div>
		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<!--
<div id='promo'>
	<div class='promoOffer'>
		<div id='closeX'>X</div>
		<h3>Have smarter conversations</h3>
		<div class='clearAll'></div>
		<p>Want to always know what’s trending? Sign up for the daily Africa Rizing newsletter.</p>
		<form action="https://tinyletter.com/africarizing" method="post" target="tinyletterhider" class="tinyletter-form">
			<label></label>
			<input type="email" placeholder="Your email address" name="email" id="name" />
			<span class="tinyletter-confirmation">You’re almost done! Check your email to confirm subscription.</span>
			<input type="hidden" value="1" name="embed"/>
			<button type="submit">Subscribe</button>
		</form>
		<iframe class="tinyletterhider" name="tinyletterhider"></iframe>
	</div>
</div>
-->
<script type="text/javascript">
	/*
	var showOffer = true;
	var documentHeight = jQuery(document).height();
	var windowHeight = jQuery(window).height();
	var promoScrollConstant = 800; //This is a magic variable... we should figure out what this should actually be.
	var deltaBottom = windowHeight + promoScrollConstant //windowHeight

	jQuery(document).scroll(function() {
		var y = jQuery(this).scrollTop();
		if (Cookies.get('promoOfferClosed') == 'true') {
			showOffer=false;
		}
		console.log("Doc height: " + documentHeight);
		console.log("y: " + y);
		console.log("deltaBottom: " + deltaBottom);
		console.log(documentHeight + " - " + y + " ?<? " + deltaBottom);
		console.log((documentHeight -  y) + " ?<? " + deltaBottom);
		if (documentHeight - y < deltaBottom && showOffer) {
			jQuery('.promoOffer').fadeIn();
		} else {
			jQuery('.promoOffer').fadeOut();
		}
		jQuery('#closeX').click(function(){
			showOffer = false;
			jQuery('.promoOffer').fadeOut();

			//3 day expiration if they click the 'X' without subscribing
			//but if the cookie is already set, that means they hit subscribe and then X, so stick with the original 365 and don't overwrite
			if (Cookies.get('promoOfferClosed') != 'true') {
				Cookies.set('promoOfferClosed', 'true', { expires: 3 });
			}
		})
	});

	jQuery(document).ready(function(){
		jQuery( '.tinyletter-form' ).submit(function() {
			//2 year expiration if they click the 'X'
			Cookies.set('promoOfferClosed', 'true', { expires: 730 });

			jQuery('.fieldtogglization').hide();
			jQuery('form input#name').hide();
			jQuery('form button').hide();
			jQuery('.tinyletter-confirmation').slideDown();
		});
	})
	*/
</script>

<style type="text/css">
/*
#promo {
	position: fixed; 
	bottom: 0; 
	width: 100%;
}
.promoOffer {
	width: 80%;
	max-width: 1040px; 
	margin: 0 auto; 
	padding: 5px 20px 70px 20px;
	background-color: #CCC; 
	color: #333;
	display: none;
	border-radius: 5px 5px 0 0;
}
.promoOffer p,
.promoOffer h3,
.promoOffer form,
.promoOffer label,
.promoOffer input {
	font-family: 'Myriad Pro', Helvetica, Arial, sans-serif;
	color: #333;
	text-align: center;
}
.promoOffer input {
	width: 90%;
	margin-bottom: 10px
}

.promoOffer h3 {
	float: left;
	clear: none;
	width: 90%;
}
.promoOffer #closeX {
	float: right;
	border: 1px solid #FFF;
	font-family: 'Myriad Pro', Helvetica, Arial, sans-serif;
	cursor: pointer; cursor: hand;
	padding: 4px 8px 0 8px;
	margin-top: 15px;
	border-radius: 3px;
}

.tinyletterhider {
	width:1px;
	height:1px;
	overflow:hidden;
	position:absolute;
	left:-99999em;
	visibility:hidden; top:0;
}
.tinyletter-confirmation {
	display: none; 
	margin: 0; 
	background: #57ad68; 
	padding: 5px 10px; 
	color: white; 
	border-radius: 2px;
}
@media only screen and (min-width: 400px) {
	.promoOffer {
		width: 85%;
	}
	.promoOffer p,
	.promoOffer h3,
	.promoOffer form,
	.promoOffer label,
	.promoOffer input {
		text-align: left;
	}
	.promoOffer input {
		width: 60%;
		margin: 0 5px 0 0;
	}
}
*/
</style>

<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bbginnovate
 */

if ( ! function_exists( 'bbginnovate_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function bbginnovate_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'bbginnovate' ),
		$time_string
		//'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);



	$byline = "";
	$includeByline = get_post_meta( get_the_ID(), 'include_byline', true );
	if ( $includeByline ){
		$bylineOverride = get_post_meta( get_the_ID(), 'byline_override', true );
		if ($bylineOverride == "") {
			$byline = sprintf(
				esc_html_x( 'by %s', 'post author', 'bbginnovate' ),
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
		} else {
			$byline = '<span class="bbg__article-byline">by ' . $bylineOverride . "</span>";
		}
		$byline = '<span class="byline"> ' . $byline . '</span> <span class="u--seperator"> </span><span class="posted-on">' . $posted_on . '</span>'; 
	} else {
		$byline = '<span class="posted-on">' . $posted_on . '</span>'; 
	}
	echo $byline;


}
endif;

if ( ! function_exists( 'bbginnovate_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function bbginnovate_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'bbginnovate' ) );
		if ( $categories_list && bbginnovate_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'bbginnovate' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'bbginnovate' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'bbginnovate' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'bbginnovate' ), esc_html__( '1 Comment', 'bbginnovate' ), esc_html__( '% Comments', 'bbginnovate' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'bbginnovate' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function bbginnovate_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'bbginnovate_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'bbginnovate_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so bbginnovate_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so bbginnovate_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in bbginnovate_categorized_blog.
 */
function bbginnovate_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'bbginnovate_categories' );
}
add_action( 'edit_category', 'bbginnovate_category_transient_flusher' );
add_action( 'save_post',     'bbginnovate_category_transient_flusher' );

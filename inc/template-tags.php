<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 */

if ( ! function_exists( 'conanMD_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function conanMD_posted_on() {

	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		__( 'by %s', 'ConanMD' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="posted-on">' . conanMD_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;


if ( ! function_exists( 'conanMD_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function conanMD_time_link() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
		/* translators: %s: post date */
		__( '<span class="screen-reader-text">Posted on</span> %s', 'ConanMD' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}
endif;


if ( ! function_exists( 'conanMD_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function conanMD_entry_footer() {

	// Get Tags for posts.
	$tags_list = get_the_tag_list();

	// We don't want to output .entry-footer if it will be empty, so make sure its not.
	if ( ( ( conanMD_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

		echo '<footer class="entry-footer">';

			if ( 'post' === get_post_type() ) {
				if ( $tags_list ) {
					echo '<span class="cat-tags-links">';

						if ( $tags_list ) {
							conanMD_entry_tags();
						}

					echo '</span>';
				}
			}

			conanMD_edit_link();

		echo '</footer> <!-- .entry-footer -->';
	}
}
endif;


if ( ! function_exists( 'conanMD_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function conanMD_edit_link() {

	$link = edit_post_link(
		'<i class="material-icons">edit</i>',
		'<span class="edit-link">',
		'</span>',
		'',
		'mdl-button mdl-js-button mdl-button--icon'
	);

	return $link;
}
endif;


if ( ! function_exists( 'conanMD_comment_link' ) ) :
/**
 * Returns an accessibility-friendly link to show comments.
 *
 */
function conanMD_comment_link() {

	echo '<span class="comment-link">';
	echo '<a class="mdl-button mdl-js-button mdl-button--icon" href="' . get_the_permalink() . '#comments">';
	echo '<i class="material-icons">comment</i>';
	echo '</a>';
	echo '<b>' . get_comments_number() . '</b>';
	echo '</span>';

}
endif;


if ( ! function_exists( 'conanMD_view_link' ) ) :
/**
 * Returns an accessibility-friendly link to show view counts.
 *
 */
function conanMD_view_link() {

	if ( function_exists('the_views') ) : 

		$post_views = intval( post_custom( 'views' ) );

		echo '<span class="view-link">';
		echo '<a class="mdl-button mdl-js-button mdl-button--icon" href="' . get_the_permalink() . '">';
		echo '<i class="material-icons">visibility</i>';
		echo '</a>';
		echo '<b>' . $post_views . '</b>';
		echo '</span>';

	else : 
		echo '';
	endif;
}
endif;

/**
 * Display a front page section.
 *
 * @param $partial WP_Customize_Partial Partial associated with a selective refresh request.
 * @param $id integer Front page section to display.
 */
function conanMD_front_page_section( $partial = null, $id = 0 ) {
	if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
		// Find out the id and set it up during a selective refresh.
		global $ConanMDcounter;
		$id = str_replace( 'panel_', '', $partial->id );
		$ConanMDcounter = $id;
	}

	global $post; // Modify the global post object before setting up post data.
	if ( get_theme_mod( 'panel_' . $id ) ) {
		global $post;
		$post = get_post( get_theme_mod( 'panel_' . $id ) );
		setup_postdata( $post );
		set_query_var( 'panel', $id );

		get_template_part( 'template-parts/page/content', 'front-page-panels' );

		wp_reset_postdata();
	} elseif ( is_customize_preview() ) {
		// The output placeholder anchor.
		echo '<article class="panel-placeholder panel ConanMD-panel ConanMD-panel' . $id . '" id="panel' . $id . '"><span class="ConanMD-panel-title">' . sprintf( __( 'Front Page Section %1$s Placeholder', 'ConanMD' ), $id ) . '</span></article>';
	}
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function conanMD_categorized_blog() {
	$category_count = get_transient( 'conanMD_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'conanMD_categories', $category_count );
	}

	return $category_count > 1;
}

/**
 * Prints HTML with category and tags for current post.
 * 
 */
function conanMD_entry_tags() {
	$posttags = get_the_tags();
	if ($posttags) {
		printf( '<div class="tags-links"><span class="screen-reader-text">%s</span>', __( 'Tags', 'ConanMD' ) );
		foreach ( $posttags as $tag ) {
			echo '<a class="entry-tag-item" href="' . esc_url( get_category_link( $tag->term_id ) ) . '">
				  	<span class="mdl-chip">
					  <span class="mdl-chip__text">' . esc_html( $tag->name ) . '</span>
					</span>
				</a>';
		}
		printf( '</div>' );
	}
}

/**
 * Comment Edit Button
 *
 */
function conanMD_edit_comment_link( $text = null ) {
    $comment = get_comment();
 
    if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) ) {
        return;
    }
 
    if ( null === $text ) {
        $text = __( 'Edit This' );
    }
 
    $link = '<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
				id="comment-edit-link-' . $comment->comment_ID . '"
				href="' . esc_url( get_edit_comment_link( $comment ) ) . '">' . $text . '</a>'
			. '<div class="mdl-tooltip" data-mdl-for="comment-edit-link-' . $comment->comment_ID . '">' . __( 'Edit' ) . '</div>';

    echo apply_filters( 'edit_comment_link', $link, $comment->comment_ID, $text );
}

/**
 * Comment Reply Button
 *
 */
function conanMD_comment_reply_link( $args = array(), $comment = null, $post = null ) {
    $defaults = array(
        'add_below'     => 'comment',
        'respond_id'    => 'respond',
        'reply_text'    => __( 'Reply' ),
        'reply_to_text' => __( 'Reply to %s' ),
        'login_text'    => __( 'Log in to Reply' ),
        'max_depth'     => 0,
        'depth'         => 0,
    );
 
    $args = wp_parse_args( $args, $defaults );
 
    if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] ) {
        return;
    }
 
    $comment = get_comment( $comment );
 
    if ( empty( $post ) ) {
        $post = $comment->comment_post_ID;
    }
 
    $post = get_post( $post );
 
    if ( ! comments_open( $post->ID ) ) {
        return false;
    }
 
    $args = apply_filters( 'comment_reply_link_args', $args, $comment, $post );
 
    if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
        $link = sprintf( '<a rel="nofollow" class="comment-reply-login" href="%s">%s</a>',
            esc_url( wp_login_url( get_permalink() ) ),
            $args['login_text']
        );
    } else {
        $onclick = sprintf( 'return addComment.moveForm( "%1$s-%2$s", "%2$s", "%3$s", "%4$s" )',
            $args['add_below'], $comment->comment_ID, $args['respond_id'], $post->ID
        );
 
        $link = sprintf( 
			"<a rel='nofollow' 
				 class='mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon' 
				 id='comment-reply-link-%s' href='%s' onclick='%s' aria-label='%s'>%s</a>
			<div class='mdl-tooltip' data-mdl-for='comment-reply-link-%s'>%s</div>",
			$comment->comment_ID,
            esc_url( add_query_arg( 'replytocom', $comment->comment_ID, get_permalink( $post->ID ) ) ) . "#" . $args['respond_id'],
            $onclick,
            esc_attr( sprintf( $args['reply_to_text'], $comment->comment_author ) ),
            $args['reply_text'],
			$comment->comment_ID,
			__( 'Reply' )
        );
    }

    $output = apply_filters( 'comment_reply_link', $link, $args, $comment, $post );
	echo $output;
}


/**
 * Flush out the transients used in conanMD_categorized_blog.
 */
function conanMD_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'conanMD_categories' );
}
add_action( 'edit_category', 'conanMD_category_transient_flusher' );
add_action( 'save_post',     'conanMD_category_transient_flusher' );


/**
 * Redesign the Password Form.
 * 
 */
function conanMD_password_form( $post = 0 ) {
	$post = get_post( $post );
    $label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );

	$output = '<p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>';

    $output .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">';
    $output .= '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">';
	$output .= '<label class="mdl-textfield__label" for="' . $label . '">' . __( 'Password:' ) . '</label>';
	$output .= '<input class="mdl-textfield__input" name="post_password" id="' . $label . '" type="password" size="20" />';
	$output .= '</div>';
	$output .= '<div class="post-password-form-submit"><button class="mdl-button mdl-js-button mdl-button--icon" type="submit" name="Submit"><i class="material-icons">check</i></button></div>';
	$output .= '</form>';

    return $output;
}
add_filter( 'the_password_form', 'conanMD_password_form' );

/**
 * Removing protected prefix from post titles
 * 
 */
function conanMD_protected_title( $format ) {
	return '<i class="material-icons">lock</i>%s';
}
add_filter( 'protected_title_format',   'conanMD_protected_title' );

/**
 * Removing private prefix from post titles
 * 
 */
function conanMD_private_title( $format ) {
	return '<i class="material-icons">visibility_off</i>%s';
}
add_filter( 'private_title_format',   'conanMD_private_title' );


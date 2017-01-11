<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				$comments_number = get_comments_number();
				if ( '1' === $comments_number ) {
					/* translators: %s: post title */
					printf( _x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'ConanMD' ), get_the_title() );
				} else {
					printf(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'%1$s Reply to &ldquo;%2$s&rdquo;',
							'%1$s Replies to &ldquo;%2$s&rdquo;',
							$comments_number,
							'comments title',
							'ConanMD'
						),
						number_format_i18n( $comments_number ),
						get_the_title()
					);
				}
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'avatar_size' => 100,
					'style'       => 'ol',
					'short_ping'  => true,
					'reply_text'  => conanMD_get_svg( array( 'icon' => 'mail-reply' ) ) . __( 'Reply', 'ConanMD' ),
				) );
			?>
		</ol>

		<?php the_comments_pagination( array(
			'prev_text' => conanMD_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous', 'ConanMD' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'ConanMD' ) . '</span>' . conanMD_get_svg( array( 'icon' => 'arrow-right' ) ),
		) );

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'ConanMD' ); ?></p>
	<?php
	endif;

	comment_form();
	/*
	comment_form(array(
		'fields'				=> apply_filters( 'comment_form_default_fields', array(
									'author' => '<div class="comment-info">' . 
												'<div class="comment-form-author mdl-textfield mdl-js-textfield mdl-textfield--floating-label">' . 
												'<input class="mdl-textfield__input" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' />' . 
												'<label class="mdl-textfield__label" for="author">' . __( 'Name' ) . '</label> ' .
												'</div>',
									'email'  => '<div class="comment-form-email mdl-textfield mdl-js-textfield mdl-textfield--floating-label">' . 
												'<input class="mdl-textfield__input" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' />' . 
												'<label class="mdl-textfield__label" for="email">' . __( 'Email' ) . '</label> ' .
												'</div>',
									'url'    => '<div class="comment-form-url mdl-textfield mdl-js-textfield mdl-textfield--floating-label">' . 
												'<input class="mdl-textfield__input" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />' . 
												'<label class="mdl-textfield__label" for="url">' . __( 'Website' ) . '</label> ' .
												'</div>' . 
												'</div><!-- .comment-info -->' ) ),
        'comment_field'        => '<div class="comment-form mdl-textfield mdl-js-textfield mdl-textfield--floating-label">' . 
								  '<textarea id="comment" name="comment" class="mdl-textfield__input" rows="1" type="text" maxlength="65525" aria-required="true" oninput="resize(this)"></textarea>' . 
								  '<label class="mdl-textfield__label" for="comment">' . sprintf( __( 'Leave a Reply' ) ) . '</label>' . 
								  '</div>',
        'must_log_in'          => '<p class="must-log-in">' . sprintf(
                                      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
                                      wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )
                                  ) . '</p>',
        'logged_in_as'         => '<p class="logged-in-as">' . sprintf(
                                      __( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a>. <a href="%4$s">Log out?</a>' ),
                                      get_edit_user_link(),
                                      esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.' ), $user_identity ) ),
                                      $user_identity,
                                      wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )
                                  ) . '</p>',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'action'               => site_url( '/wp-comments-post.php' ),
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'class_form'           => 'commentform',
        'class_submit'         => 'submit',
        'name_submit'          => 'submit',
        'title_reply'          => '',
        'title_reply_to'       => __( 'Leave a Reply to %s' ),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h2>',
        'cancel_reply_before'  => '<small>',
        'cancel_reply_after'   => '</small>',
        'cancel_reply_link'    => __( 'Cancel reply' ),
        'label_submit'         => __( 'Post Comment' ),
        'format'               => 'xhtml',
		) );
	
		*/
	?>

</div><!-- #comments -->

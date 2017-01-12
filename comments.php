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
					'walker'      => new ConanMD_Walker_Comment,
					'style'       => 'ol',
					'avatar_size' => 64,
					'short_ping'  => true,
					'reply_text'  => '<span class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon icon">
									  	<i class="material-icons">reply</i>
									  </span>' . __( 'Reply', 'ConanMD' ),
				) );
			?>
		</ol>

		<?php the_comments_pagination( array(
			'prev_text' => 	'<span class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon nav-title-icon-wrapper">
								<i class="material-icons">arrow_back</i>
							</span>' . 
							'<span class="screen-reader-text">' . __( 'Previous', 'ConanMD' ) . '</span>',
			'next_text' => 	'<span class="screen-reader-text">' . __( 'Next', 'ConanMD' ) . '</span>' . 
							'<span class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon nav-title-icon-wrapper">
								<i class="material-icons">arrow_forward</i>
							</span>',
		) );

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'ConanMD' ); ?></p>
	<?php
	endif;

	comment_form(array(
		'comment_field'       	=> 	'<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label comment-form-textarea">' .
										'<textarea	class="mdl-textfield__input"
													id="comment"
													name="comment"
													cols="45"
													rows="8"
													maxlength="65525"
													aria-required="true"></textarea>' .
										'<label class="mdl-textfield__label" for="comment">' . _x( 'Comment', 'noun' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
										'<span class="mdl-textfield__error">' . __( 'Three or more characters!', 'ConanMD' ) . '</span>' .
									'</div>',
		'fields'				=>	apply_filters( 'comment_form_default_fields', array(
									'author' => '<div class="comment-info">' . 
													'<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label comment-form-author">' . 
														'<input class="mdl-textfield__input" 
																id="author" 
																name="author" 
																type="text" 
																value="' . esc_attr( $commenter['comment_author'] ) . '" 
																size="30" 
																maxlength="245" 
																pattern=".{3,}"
																' . $aria_req . $html_req . ' />' . 
														'<label class="mdl-textfield__label" for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
														'<span class="mdl-textfield__error">' . __( 'Use 3 characters at least', 'ConanMD' ) . '</span>' .
													'</div>',
									'email'  => 	'<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label comment-form-email">' . 
														'<input class="mdl-textfield__input" 
																id="email" 
																name="email" 
																' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' 
																value="' . esc_attr(  $commenter['comment_author_email'] ) . '" 
																size="30" 
																maxlength="100" 
																pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[A-Za-z]{2,6}$" 
																aria-describedby="email-notes" 
																' . $aria_req . $html_req  . ' />' . 
														'<label class="mdl-textfield__label" for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
														'<span class="mdl-textfield__error">' . __( 'Your email address is invalid', 'ConanMD' ) . '</span>' .
													'</div>',
									'url'    => 	'<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label comment-form-url">' . 
														'<input class="mdl-textfield__input" 
																id="url" 
																name="url" 
																' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' 
																value="' . esc_attr( $commenter['comment_author_url'] ) . '" 
																size="30" 
																maxlength="200" 
																pattern="https?://.+" />' . 
														'<label class="mdl-textfield__label" for="url">' . __( 'Website' ) . '</label> ' .
														'<span class="mdl-textfield__error">' . __( 'Include http:// or https://', 'ConanMD' ) . '</span>' .
													'</div>' . 
												'</div><!-- .comment-info -->',
									) ),
		'must_log_in'          	=> 	'<div class="mdl-card mdl-shadow--2dp must-log-in">
										<div>'
											. sprintf( 
												__( 'You must be <a href="%s" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--blue mdl-color-text--white">logged in</a> to post a comment.', 'ConanMD' ),
												wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )
											) . 
										'</div>
									</div>',
		'logged_in_as'         	=> 	'<div class="logged-in-as">
										<a id="logged-in-avatar" href="' . get_edit_user_link() . '">' . get_avatar( $user_ID, 48 ) . '</a>
										<div class="mdl-tooltip" data-mdl-for="logged-in-avatar">' . sprintf( __( 'Edit your profile', 'ConanMD' ), $user_identity ) . '</div>
										' . sprintf( __( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a>. <a href="%4$s">Log out?</a>' ),
                                      	get_edit_user_link(),
                                      	esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.' ), $user_identity ) ),
                                      	$user_identity,
                                      	wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . 
									'</div>',
        'comment_notes_before'	=>	'',
        'comment_notes_after'	=>	'',
		'class_form'           	=> 	'mdl-card mdl-shadow--2dp comment-form',
		'class_submit'      	=>	'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--blue mdl-color-text--white',
		'submit_button'       	=>	'<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
		'submit_field'        	=>	'<div class="form-submit">%1$s %2$s</div>',
		) );
	/*
	comment_form(array(
        
        'name_submit'          => 'submit',
        'title_reply'          => '',
        'title_reply_to'       => __( 'Leave a Reply to %s' ),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h2>',
        'cancel_reply_before'  => '<small>',
        'cancel_reply_after'   => '</small>',
        'cancel_reply_link'    => __( 'Cancel reply' ),
		) );
	
		*/
	?>

</div><!-- #comments -->

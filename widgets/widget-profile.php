<?php
/**
 * Widget Profile
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

class conanMD_Profile_Widget extends WP_Widget {

	
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){

		$widget_ops = array(
			'classname' => 'cmd-widget-profile',
			'description' => __( 'Your Profile' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_profile_widget', 'MD ' . __('Profile' ), $widget_ops );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $user_id = empty( $instance['userid'] ) ? 1 : $instance['userid'];
        $user_facebook = !empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $user_twitter = !empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $user_weibo = !empty( $instance['weibo'] ) ? $instance['weibo'] : '';
        $user_github = !empty( $instance['github'] ) ? $instance['github'] : '';
        $user_instagram = !empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $user_googleplus = !empty( $instance['googleplus'] ) ? $instance['googleplus'] : '';
        $user_occupation = empty( $instance['occupation'] ) ? '' : $instance['occupation'];
        $user_description = empty( $instance['description'] ) ? get_the_author_meta( 'description', $user_id ) : $instance['description'];
        $rss = !empty( $instance['rss'] ) ? '1' : '0';
        $count = 0;

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

        echo '<div class="mdl-card mdl-shadow--2dp cmd-widget-profile-wrap">';

		echo '<div class="cmd-widget-profile-header" style="background-image: url('. get_header_image() .');"></div>
            <div class="cmd-widget-profile-social-wrap">
                ' . get_avatar( get_the_author_meta( 'user_email', $user_id ), 72 ) . '
                <div class="cmd-widget-profile-social">';

        if ( $user_facebook ) {
			echo '<a id="social-facebook" target="_blank" href="' . $user_facebook . '">';
            echo '<svg class="icon icon-facebook" aria-hidden="true" role="img"><use href="#icon-facebook" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-facebook"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-facebook">Facebook</div></a>';
            $count++;
        }
        if ( $user_twitter ) {
			echo '<a id="social-twitter" target="_blank" href="' . $user_twitter . '">';
            echo '<svg class="icon icon-twitter" aria-hidden="true" role="img"><use href="#icon-twitter" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-twitter"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-twitter">Twitter</div></a>';
            $count++;
        }
        if ( $user_weibo ) {
			echo '<a id="social-weibo" target="_blank" href="' . $user_weibo . '">';
            echo '<svg class="icon icon-weibo" aria-hidden="true" role="img"><use href="#icon-weibo" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-weibo">Weibo</div></a>';
            $count++;
        }
        if ( $user_github ) {
			echo '<a id="social-github" target="_blank" href="' . $user_github . '">';
            echo '<svg class="icon icon-github" aria-hidden="true" role="img"><use href="#icon-github" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-github"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-github">Github</div></a>';
            $count++;
        }
        if ( $user_instagram ) {
			echo '<a id="social-instagram" target="_blank" href="' . $user_instagram . '">';
            echo '<svg class="icon icon-instagram" aria-hidden="true" role="img"><use href="#icon-instagram" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-instagram"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-instagram">Instagram</div></a>';
            $count++;
        }
        if ( $user_googleplus && $count<5 ) {
			echo '<a id="social-gplus" target="_blank" href="' . $user_googleplus . '">';
            echo '<svg class="icon icon-google-plus" aria-hidden="true" role="img"><use href="#icon-google-plus" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-google-plus"></use></svg>';
            echo '<div class="mdl-tooltip" for="social-gplus">Google+</div></a>';
        }

        echo '</div></div>';
        
        echo '<div class="mdl-card__title cmd-widget-profile-title">';
        if ( get_the_author_meta( 'user_nicename', $user_id ) ) {
            echo '<h3 class="mdl-card__title-text">'. get_the_author_meta( 'user_nicename', $user_id ) . '</h3>';
            if ( $user_occupation ) {
                echo '<h6 class="mdl-card__subtitle-text">'. $user_occupation .'</h6>';
            } 
        }
        echo "</div>\n";
        
        if ( $user_description ) {
            echo '<div class="mdl-card__supporting-text">'. $user_description .'</div>';
        }

        if ( $rss ) {
            echo '<div class="mdl-card__actions mdl-card--border">
                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '"><i class="material-icons">rss_feed</i>' 
                    . __('Subscribe entries', 'ConanMD')
                    . '</a>';
            echo "</div>\n";
        }

		echo "</div>\n";
		echo $args['after_widget'];

	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
        $instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
        $instance['userid'] = strip_tags( $new_instance['userid'] );
        $instance['facebook'] = strip_tags( $new_instance['facebook'] );
        $instance['twitter'] = strip_tags( $new_instance['twitter'] );
        $instance['weibo'] = strip_tags( $new_instance['weibo'] );
        $instance['github'] = strip_tags( $new_instance['github'] );
        $instance['instagram'] = strip_tags( $new_instance['instagram'] );
        $instance['googleplus'] = strip_tags( $new_instance['googleplus'] );
        $instance['occupation'] = strip_tags( $new_instance['occupation'] );
        if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['description'] = $new_instance['description'];
		} else {
			$instance['description'] = wp_kses_post( $new_instance['description'] );
		}
        $instance['rss'] = $new_instance['rss'] ? 1 : 0;

		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'userid' => 1, 'rss' => 1 ) );
        $title = sanitize_text_field( $instance['title'] );
        $userid = sanitize_text_field( $instance['userid'] );
        $facebook = sanitize_text_field( $instance['facebook'] );
        $twitter = sanitize_text_field( $instance['twitter'] );
        $weibo = sanitize_text_field( $instance['weibo'] );
        $github = sanitize_text_field( $instance['github'] );
        $instagram = sanitize_text_field( $instance['instagram'] );
        $googleplus = sanitize_text_field( $instance['googleplus'] );
        $occupation = sanitize_text_field( $instance['occupation'] );
        $description = sanitize_text_field( $instance['description'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('userid'); ?>"><?php _e( 'User ID:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('userid'); ?>" name="<?php echo $this->get_field_name('userid'); ?>" type="text" value="<?php echo esc_attr($userid); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e( 'Facebook URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e( 'Twitter URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('weibo'); ?>"><?php _e( 'Weibo URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('weibo'); ?>" name="<?php echo $this->get_field_name('weibo'); ?>" type="text" value="<?php echo esc_attr($weibo); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('github'); ?>"><?php _e( 'Github URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('github'); ?>" name="<?php echo $this->get_field_name('github'); ?>" type="text" value="<?php echo esc_attr($github); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e( 'Instagram URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo esc_attr($instagram); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e( 'Google+ URL:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" type="text" value="<?php echo esc_attr($googleplus); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('occupation'); ?>"><?php _e( 'Occupation:', 'ConanMD' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('occupation'); ?>" name="<?php echo $this->get_field_name('occupation'); ?>" type="text" value="<?php echo esc_attr($occupation); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Bio:', 'ConanMD' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
		</p>
        <p>
			<input class="checkbox" type="checkbox"<?php checked( $instance['rss'] ); ?> id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" />
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e( 'Show RSS Feed button', 'ConanMD' ); ?></label>
		</p>
		<?php
	}
}

?>
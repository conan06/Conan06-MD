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
        $user_id = 1;
        $user_job = 'Student';

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

        echo '<div class="mdl-card mdl-shadow--2dp cmd-widget-profile-wrap">';

		echo '<div class="cmd-widget-profile-header" style="background-image: url('. get_header_image() .');"></div>
            <div class="cmd-widget-profile-social-wrap">
                ' . get_avatar( get_the_author_meta( 'user_email', $user_id ), 72 ) . '
                <div class="cmd-widget-profile-social">
                    <svg class="icon icon-facebook" aria-hidden="true" role="img">
                        <use href="#icon-facebook" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-facebook"></use>
                    </svg>
                    <svg class="icon icon-twitter" aria-hidden="true" role="img">
                        <use href="#icon-twitter" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-twitter"></use>
                    </svg>
                    <svg class="icon icon-github" aria-hidden="true" role="img">
                        <use href="#icon-github" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-github"></use>
                    </svg>
                    <svg class="icon icon-instagram" aria-hidden="true" role="img">
                        <use href="#icon-instagram" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-instagram"></use>
                    </svg>
                    <svg class="icon icon-youtube" aria-hidden="true" role="img">
                        <use href="#icon-youtube" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-youtube"></use>
                    </svg>
                </div>
            </div>';

        echo '<div class="mdl-card__title cmd-widget-profile-title">';
        echo '<h3 class="mdl-card__title-text">'. get_the_author_meta( 'user_nicename', $user_id ) . '</h3>';
        echo '<h6 class="mdl-card__subtitle-text">'. $user_job .'</h6>';
        echo "</div>\n";
        
        echo '<div class="mdl-card__supporting-text">'. get_the_author_meta( 'description', $user_id ) .'</div>';

        echo '<div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '">' 
                . __('Subscribe entries', 'ConanMD')
                . '</a>';
        echo "</div>\n";

		echo "</div>\n";
		echo $args['after_widget'];

	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
        $instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php
	}
}

?>
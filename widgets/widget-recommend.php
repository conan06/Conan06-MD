<?php
/**
 * Widget Recommend Article
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

function get_mdl_recommend_article( $initial = true, $echo = true ) {
    

    return apply_filters( 'get_mdl_recommend_article', $recommend_output );
}

/*******************************************************************************/

class conanMD_Recommend_Widget extends WP_Widget {

	
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){

		$widget_ops = array(
			'classname' => 'cmd-widget-recommend',
			'description' => __( 'Displays a recommended article.', 'ConanMD' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_recommend_article_widget', 'MD' . __('Recommend Article', 'ConanMD' ), $widget_ops );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo '<div id="cmd-recommend-wrap" class="cmd-recommend-wrap">';
		get_mdl_recommend_article();
		echo '</div>';
		echo $args['after_widget'];

	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['post'] = strip_tags( $new_instance['post'] );

		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$post = sanitize_text_field( $instance['post'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('Article ID:', 'ConanMD' ) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>" value="<?php echo esc_attr($post); ?>">
		</p>

		<?php
	}
}

?>
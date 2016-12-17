<?php
/**
 * Widget Tag Cloud
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

function get_mdl_tag_cloud() {
	$args = array(
		'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 16,
		'format' => 'flat', 'separator' => "\n", 'orderby' => 'count', 'order' => 'DESC',
		'exclude' => '', 'include' => '', 'link' => 'view', 'post_type' => ''
	);

	$tags = get_terms( 'post_tag', $args ); // Get tags

	if ( is_wp_error( $tags ) )
		return;

	foreach ( $tags as $key => $tag ) {
		$link = get_term_link( intval($tag->term_id), $tag->taxonomy );
		if ( is_wp_error( $link ) )
			return;

		$tags[ $key ]->link = $link;
		$tags[ $key ]->id = $tag->term_id;
	}

    $tags_data = array();
    foreach ( $tags as $key => $tag ) { 
        $tags_data[] = array(
            'url'        => '#' != $tag->link ? $tag->link : '#',
            'name'       => $tag->name,
            'class'      => 'mdl-button mdl-chip tag-link-' . $tag->id,
        );
    }

    $output = '';
    foreach ( $tags_data as $key => $tag_data ) {
        $class = $tag_data['class'] . ' tag-link-position-' . ( $key + 1 );
        $a = "<a href='" . esc_url( $tag_data['url'] ) . "' class='" . esc_attr( $class ) . "''><span class='mdl-chip__text'>" . esc_html( $tag_data['name'] ) . "</span></a>";
        $output .= $a;
    }

	return $output;
    
}


class conanMD_Tag_Cloud_Widget extends WP_Widget {

	
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){

		$widget_ops = array(
			'classname' => 'cmd-widget-tag-cloud',
			'description' => __( 'A cloud of your most used tags.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_tag_cloud_widget', 'MD ' . __('Tag Cloud' ), $widget_ops );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		$tag_cloud = get_mdl_tag_cloud();

		if ( empty( $tag_cloud ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<div class="mdl-card mdl-shadow--2dp cmd-widget-tag-cloud-wrap">';

		echo $tag_cloud;

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
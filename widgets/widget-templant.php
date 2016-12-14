<?php
/**
 * Widget Recommend Article
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

class conanMD_Calendar_Widget extends WP_Widget {

	
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){
		
		
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		
	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
        
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		
	}
}

function conanMD_register_widget() {
	register_widget('conanMD_Calendar_Widget');			
}
add_action('widgets_init','conanMD_register_widget');

?>
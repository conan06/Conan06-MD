<?php
/**
 * Widgets
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

require_once get_parent_theme_file_path( '/widgets/widget-calendar.php' );
require_once get_parent_theme_file_path( '/widgets/widget-tag-cloud.php' );
require_once get_parent_theme_file_path( '/widgets/widget-recommend.php' );

function mfn_register_widget()
{
	register_widget('conanMD_Recommend_Widget');		// Recommend Article
	register_widget('conanMD_Calendar_Widget');			// Calendar
	register_widget('conanMD_Tag_Cloud_Widget');		// Tag Cloud
}
add_action('widgets_init','mfn_register_widget');

?>
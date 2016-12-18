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
require_once get_parent_theme_file_path( '/widgets/widget-nav-menu.php' );
require_once get_parent_theme_file_path( '/widgets/widget-links.php' );
require_once get_parent_theme_file_path( '/widgets/widget-recommend.php' );

function mfn_register_widget()
{
	register_widget('conanMD_Recommend_Widget');		// Recommend Article
	register_widget('conanMD_Calendar_Widget');			// Calendar
	register_widget('conanMD_Tag_Cloud_Widget');		// Tag Cloud
	register_widget('conanMD_Nav_Menu_Widget');			// Navigation Menu
	register_widget('conanMD_Links_Widget');		// Links
}
add_action('widgets_init','mfn_register_widget');

?>
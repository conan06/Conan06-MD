<?php
/**
 * Displays footer widgets if assigned
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

?>

<?php
if ( is_active_sidebar( 'sidebar-3' ) ) :
?>

	<aside class="widget-area" role="complementary">
		<?php
		if ( is_active_sidebar( 'sidebar-3' ) ) { ?>
			<div class="widget-column footer-widget">
				<?php dynamic_sidebar( 'sidebar-3' ); ?>
			</div>
		<?php } ?>
	</aside><!-- .widget-area -->

<?php endif; ?>

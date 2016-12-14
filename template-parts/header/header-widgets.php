<?php
/**
 * Displays header widgets if assigned
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

?>

<?php
if ( is_active_sidebar( 'sidebar-2' ) ) :
?>

	<aside class="widget-area" role="complementary">
		<?php
		if ( is_active_sidebar( 'sidebar-2' ) ) { ?>
			<div class="widget-column header-widget">
				<?php dynamic_sidebar( 'sidebar-2' ); ?>
			</div>
		<?php } ?>
	</aside><!-- .widget-area -->

<?php endif; ?>

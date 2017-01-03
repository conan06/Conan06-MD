<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

?>
<a href="<?php printf( site_url() ) ?>" class="site-logo">
     <figure>
        <img src="<?php printf( esc_url( get_site_icon_url( 64 ) ) ) ?>" alt=" Google">
    </figure>
</a><!-- .site-logo -->

<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php _e( 'Top Menu', 'ConanMD' ); ?>">
	<button class="menu-toggle" aria-controls="top-menu" aria-expanded="false"><?php echo conanMD_get_svg( array( 'icon' => 'bars' ) ); echo conanMD_get_svg( array( 'icon' => 'close' ) ); _e( 'Menu', 'ConanMD' ); ?></button>
	<?php wp_nav_menu( array(
		'theme_location' => 'top',
		'menu_id'        => 'top-menu',
	) ); ?>

	<?php if ( ( conanMD_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="menu-scroll-down"><?php echo conanMD_get_svg( array( 'icon' => 'arrow-right' ) ); ?><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'ConanMD' ); ?></span></a>
	<?php endif; ?>
</nav><!-- #site-navigation -->

<div class="navigation-more-menu-wrapper">
	<button id="navigation-more-menu" class="mdl-button mdl-js-button mdl-button--icon">
		<i class="material-icons">more_vert</i>
	</button>

	<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="navigation-more-menu">
		<li class="mdl-menu__item">RSS</li>
	</ul>
</div>

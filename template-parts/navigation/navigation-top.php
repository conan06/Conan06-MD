<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

function mdl_register( $echo = true ) {
    if ( ! is_user_logged_in() ) {
        if ( get_option('users_can_register') )
            $link = '<a class="mdl-menu__item" href="' . esc_url( wp_registration_url() ) . '"><i class="material-icons">person_add</i>' . __('Register') . '</a>';
        else
            $link = '';
    } elseif ( current_user_can( 'read' ) ) {
        $link = '<a class="mdl-menu__item" href="' . admin_url() . '"><i class="material-icons">settings</i>' . __('Site Admin') . '</a>';
    } else {
        $link = '';
    }

    $link = apply_filters( 'register', $link );
 
    if ( $echo ) {
        echo $link;
    } else {
        return $link;
    }
}

function mdl_loginout($redirect = '', $echo = true) {
    if ( ! is_user_logged_in() )
        $link = '<a class="mdl-menu__item mdl-menu__item--full-bleed-divider" href="' . esc_url( wp_login_url($redirect) ) . '"><i class="material-icons">person</i>' . __('Log in') . '</a>';
    else
        $link = '<a class="mdl-menu__item mdl-menu__item--full-bleed-divider" href="' . esc_url( wp_logout_url($redirect) ) . '"><i class="material-icons">exit_to_app</i>' . __('Log out') . '</a>';
 
    if ( $echo ) {
        echo apply_filters( 'loginout', $link );
    } else {
        return apply_filters( 'loginout', $link );
    }
}

?>
<a href="<?php printf( site_url() ) ?>" class="site-logo">
     <figure>
        <img src="<?php printf( esc_url( get_site_icon_url( 64 ) ) ) ?>" alt=" Google">
    </figure>
</a><!-- .site-logo -->

<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php _e( 'Top Menu', 'ConanMD' ); ?>">
	<button class="mdl-button mdl-js-button mdl-button--icon menu-toggle" aria-controls="top-menu" aria-expanded="false">
		<i class="material-icons icon-bars">menu</i>
		<i class="material-icons icon-close">arrow_back</i>
	</button>
	<?php wp_nav_menu( array(
		'theme_location' => 'top',
		'menu_id'        => 'top-menu',
	) ); ?>

	<?php if ( ( conanMD_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="mdl-button mdl-js-button mdl-button--icon menu-scroll-down"><i class="material-icons">arrow_downward</i><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'ConanMD' ); ?></span></a>
	<?php endif; ?>
</nav><!-- #site-navigation -->

<div class="navigation-more-menu-wrapper">

	<button id="navigation-search" class="mdl-button mdl-js-button mdl-button--icon">
		<i class="material-icons">search</i>
	</button>

	<button id="navigation-more-menu" class="mdl-button mdl-js-button mdl-button--icon">
		<i class="material-icons">more_vert</i>
	</button>

	<div class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="navigation-more-menu">
		<?php mdl_register(); ?>
		<?php mdl_loginout(); ?>
		<a class="mdl-menu__item" href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><i class="material-icons">rss_feed</i><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>
		<a class="mdl-menu__item" href="<?php echo esc_url( get_bloginfo( 'comments_rss2_url' ) ); ?>"><i class="material-icons">rss_feed</i><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>
	</div>
</div>

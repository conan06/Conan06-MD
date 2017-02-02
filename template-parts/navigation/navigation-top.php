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
	 	<?php if ( has_custom_logo() ) : ?>
			<img src="<?php printf( esc_url( get_site_icon_url( 64 ) ) ) ?>" alt="<?php bloginfo('name'); ?>">
		<?php else : ?>
			<img src="<?php printf( esc_url( get_theme_file_uri( '/assets/images/site_icon.png' ) ) )  ?>" alt="<?php bloginfo('name'); ?>">
		<?php endif; ?>
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
	<div class="navigation-more-menu-container">

		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--align-right">
				<label class="mdl-button mdl-js-button mdl-button--icon" for="navigation-search">
					<i class="material-icons">search</i>
				</label>
				<div class="mdl-textfield__expandable-holder">
					<input id="navigation-search"
							class="mdl-textfield__input"
							type="search"
							placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'ConanMD' ); ?>"
							value="<?php echo get_search_query(); ?>"
							name="s">
				</div>
			</div>
		</form>

		<button id="navigation-more-menu" class="mdl-button mdl-js-button mdl-button--icon">
			<i class="material-icons">more_vert</i>
		</button>

		<div class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="navigation-more-menu">
			<?php conanMD_register(); ?>
			<?php conanMD_loginout(); ?>
			<a class="mdl-menu__item" href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><i class="material-icons">rss_feed</i><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>
			<a class="mdl-menu__item" href="<?php echo esc_url( get_bloginfo( 'comments_rss2_url' ) ); ?>"><i class="material-icons">rss_feed</i><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>
		</div>

	</div>
</div>

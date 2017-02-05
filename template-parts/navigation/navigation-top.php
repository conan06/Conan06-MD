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

<header class="mdl-layout__header mdl-layout__header--transparent navigation-top">
	<div class="mdl-layout__header-row">

		<span class="mdl-layout-title">
			<a href="<?php printf( site_url() ) ?>"><?php bloginfo('name'); ?></a>
		</span>
		<div class="mdl-layout-spacer"></div>
		
		<div class="navigation-icon-menu-wrapper">
			<div class="navigation-icon-menu-container">
				<button id="navigation-search" class="mdl-button mdl-js-button mdl-button--icon">
					<i class="material-icons">search</i>
				</button>
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
	</div>
</header>

<?php if ( has_nav_menu( 'top' ) ) : ?>
	<div class="mdl-layout__drawer">
		<a href="<?php printf( site_url() ) ?>" class="site-logo">
			<figure>
				<?php if ( has_custom_logo() ) : ?>
					<img src="<?php printf( esc_url( get_site_icon_url( 64 ) ) ) ?>" alt="<?php bloginfo('name'); ?>">
				<?php else : ?>
					<img src="<?php printf( esc_url( get_theme_file_uri( '/assets/images/site_icon.png' ) ) )  ?>" alt="<?php bloginfo('name'); ?>">
				<?php endif; ?>
			</figure>
			<span class="mdl-layout-title"><?php bloginfo('name'); ?></span>
		</a>

		<nav id="site-navigation" class="mdl-navigation" role="navigation" aria-label="<?php _e( 'Top Menu', 'ConanMD' ); ?>">

			<?php wp_nav_menu( array(
				'theme_location' => 'top',
				'menu_id'        => 'top-menu',
				'walker' 		 => new ConanMD_Walker_Nav_Menu(),
			) ); ?>

		</nav><!-- #site-navigation -->
	</div>
<?php endif; ?>

<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div class="wrap-content">

		<header class="page-header">
			<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'ConanMD' ); ?></h1>
		</header><!-- .page-header -->
		
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<div class="null-image"></div>

				<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'ConanMD' ); ?></p>

				<?php get_search_form(); ?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
</div><!-- .wrap -->

<?php get_footer();

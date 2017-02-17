<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<!--
                                         
	  .oooooo.                                                 .oooo.       .ooo   
	 d8P'  `Y8b                                               d8P'`Y8b    .88'     
	888           .ooooo.  ooo. .oo.    .oooo.   ooo. .oo.   888    888  d88'      
	888          d88' `88b `888P"Y88b  `P  )88b  `888P"Y88b  888    888 d888P"Ybo. 
	888          888   888  888   888   .oP"888   888   888  888    888 Y88[   ]88 
	`88b    ooo  888   888  888   888  d8(  888   888   888  `88b  d88' `Y88   88P 
	 `Y8bood8P'  `Y8bod8P' o888o o888o `Y888""8o o888o o888o  `Y8bd8P'   `88bod8'  
 
-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'ConanMD' ); ?></a>

	<?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
	
	<?php if ( ! ( is_single() || is_page() ) || (( is_single() || is_page() ) && ! has_post_thumbnail() ) )  : ?>
		<?php get_template_part( 'template-parts/header/header', 'image' ); ?>
	<?php endif; ?>

	<?php
	// If a regular post or page, and not the front page, show the featured image.
	if ( is_single() || ( is_page() && ! conanMD_is_frontpage() ) ) :
		if ( is_single() && has_post_thumbnail() ) :
			// post with featured image

			echo '<section class="single-info single-info-with-image">
				<div class="single-header">
					<div class="single-featured-image-wrapper">';
			
			echo '<div class="single-featured-image"  data-parallax="scroll" data-image-src="' . wp_get_attachment_url( get_post_thumbnail_id($post->ID)) . '" data-z-index="1" data-speed="0.85"></div>';
						
			echo '</div></div>';
				
			echo '<div class="single-info-wrapper">
					<div class="single-info-content">
						<div class="single-info-header">
							<div class="single-info-meta">';

			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				foreach( $categories as $category ) {
					echo '<a class="single-info-category" href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category tag">' . esc_html( $category->name ) . '</a>';
				}
			}

			echo '<time  class="single-info-date" datetime="' . get_the_date(c) . '">' . get_the_date() . '</time>';
			echo '</div>
				  <div class="single-info-title">';

			echo '<h1>' . get_the_title() . '</h1>';

			echo '</div></div><div class="single-info-more"><div><div class="single-info-byline">';

			echo '<figure><a class="single-info-author-link" id="author-link" href="'. esc_url( get_author_posts_url( $post->post_author ) ) . '" rel="author">' . get_avatar( get_the_author_meta( 'user_email', $post->post_author ), 60 ) . '</a></figure>';

			echo '<div class="mdl-tooltip" data-mdl-for="author-link">' . __( 'View all posts', 'ConanMD' ) . '</div>';

			echo '<aside><span class="single-info-author">';

			echo the_author_meta( 'display_name' , $post->post_author );

			echo '</span><span class="single-info-description">';
			
			echo the_author_meta( 'description' , $post->post_author );

			echo '</span></aside></div></div></div></div>
				</div>
			</section>';

		elseif ( is_single() ):
			// post without featured image
			echo '<section class="single-info">
				<div class="single-info-wrapper">
					<div class="single-info-content">
						<div class="single-info-header">
							<div class="single-info-meta">';

			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				foreach( $categories as $category ) {
					echo '<a class="single-info-category" href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category tag">' . esc_html( $category->name ) . '</a>';
				}
			}

			echo '<time  class="single-info-date" datetime="' . get_the_date(c) . '">' . get_the_date() . '</time>';
			echo '</div>
				  <div class="single-info-title">';

			echo '<h1>' . get_the_title() . '</h1>';

			echo '</div></div><div class="single-info-more"><div><div class="single-info-byline">';
			
			echo '<figure><a class="single-info-author-link" id="author-link" href="'. esc_url( get_author_posts_url( $post->post_author ) ) . '" rel="author">' . get_avatar( get_the_author_meta( 'user_email', $post->post_author ), 60 ) . '</a></figure>';

			echo '<div class="mdl-tooltip" data-mdl-for="author-link">' . __( 'View all posts', 'ConanMD' ) . '</div>';

			echo '<aside><span class="single-info-author">';

			echo get_the_author_meta( 'display_name' , $post->post_author );

			echo '</span><span class="single-info-description">';
			
			if ( !empty( get_the_author_meta( 'description' , $post->post_author ) ) ) {
				echo get_the_author_meta( 'description' , $post->post_author );
			} else {
				printf( __( '<a href="%s">Setting your description.</a>', 'ConanMD' ), get_dashboard_url() . 'profile.php#description' );
			}

			echo '</span></aside></div></div></div></div>
				</div>
			</section>';

		elseif ( is_page() && has_post_thumbnail() ):
			// page with featured image
			echo '<div class="single-featured-image-header">';
			the_post_thumbnail( 'ConanMD-featured-image' );
			echo '</div><!-- .single-featured-image-header -->';

		else :
			// page without featured image
			
		endif;
	endif;
	?>

	<div class="site-content-contain">
		<div id="content" class="site-content">

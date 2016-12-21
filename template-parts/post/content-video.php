<?php
/**
 * Template part for displaying video posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( is_sticky() && is_home() ) :
			echo conanMD_get_svg( array( 'icon' => 'thumb-tack' ) );
		endif;
	?>

	<?php if ( is_home() ) : ?>
		<header class="entry-header">
			<?php
				if ( 'post' === get_post_type() ) :
					echo '<div class="entry-meta">';
						if ( is_single() ) :
							conanMD_posted_on();
						else :
							echo conanMD_time_link();
							conanMD_edit_link();
						endif;
					echo '</div><!-- .entry-meta -->';
				endif;
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php
		$content = apply_filters( 'the_content', get_the_content() );
		$video = false;

		// Only get video from the content if a playlist isn't present.
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {
			$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
		}
	?>

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() && empty( $video ) ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'ConanMD-featured-image' ); ?>
			</a>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">

		<?php if ( ! is_single() ) :

			// If not a single post, highlight the video file.
			if ( ! empty( $video ) ) :
				foreach ( $video as $video_html ) {
					echo '<div class="entry-video">';
						echo $video_html;
					echo '</div>';
				}
			endif;

		endif;

		if ( is_single() || empty( $video ) ) :

			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ConanMD' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'ConanMD' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );

		endif; ?>

	</div><!-- .entry-content -->

	<?php if ( is_single() ) : ?>
		<?php conanMD_entry_footer(); ?>
	<?php endif; ?>

</article><!-- #post-## -->

<?php
/**
 * Template part for displaying posts
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

	<?php if ( ! is_single() ) : ?>
		<div class="mdl-card mdl-shadow--2dp">

			<?php if ( '' !== get_the_post_thumbnail() ) : ?>
				<figure class="mdl-card__media">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'ConanMD-featured-image' ); ?>
					</a>
				</figure>
			<?php endif; ?>

			<header class="mdl-card__title">
				<?php the_title( '<h2 class="mdl-card__title-text"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
				<?php 
					if ( 'post' === get_post_type() ) :
						echo '<h6 class="mdl-card__subtitle-text">';
						echo conanMD_time_link();
						echo '</h6>';
					endif;
				?>
			</header>

	<?php endif; ?>

	<?php if ( ! is_single() ) : ?>

		<div class="mdl-card__supporting-text">
			<?php 
				$post_excerpt = get_the_excerpt();
				if ( '' != $post_excerpt ) :
					echo $post_excerpt;
				else :
					the_content();
				endif;
			?>
		</div>

		<div class="mdl-card__actions mdl-card--border">
			<a href="<?php the_permalink(); ?>" class="mdl-button mdl-js-button mdl-js-ripple-effect">
				<?php printf(
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ConanMD' ),
					get_the_title()
				) ?>
			</a>
			<div class="mdl-layout-spacer"></div>
			<?php conanMD_comment_link(); ?>
			<?php conanMD_view_link(); ?>
			<?php conanMD_edit_link(); ?>
		</div>

	<?php else : ?>

		<div class="entry-content">
			<?php

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
		?>
		</div><!-- .entry-content -->

	<?php endif; ?>

	<?php
		if ( is_sticky() && is_home() ) :
			echo '<div class="mdl-card__menu"><i class="material-icons">whatshot</i></div>';
		endif;
	?>

	<?php if ( ! is_single() ) : ?>
		</div>
	<?php else : ?>
		<?php conanMD_entry_footer(); ?>
	<?php endif; ?>

</article><!-- #post-## -->

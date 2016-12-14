<?php
/**
 * Widget Recommend Article
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

class conanMD_Recommend_Widget extends WP_Widget {

	
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){

		$widget_ops = array(
			'classname' => 'cmd-widget-recommend',
			'description' => __( 'Displays a recommended article.', 'ConanMD' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_recommend_article_widget', 'MD' . __('Recommend Article', 'ConanMD' ), $widget_ops );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		// Get the latest post or the user-defined post
		$post_id = apply_filters( 'widget_post_id', empty( $instance['post'] ) ? 
				wp_get_recent_posts( array( 'numberposts' => '1') )[0]['ID'] : $instance['post'] );

		echo $args['before_widget'];
		
		if (!empty($post_id)) {
			echo '<div id="cmd-recommend-wrap" class="cmd-recommend-wrap">';
			//TODO
			$article = get_post($post_id);
			$output = "\n\t\t".'
			<div class="cmd-recommend-content">
				<figure>';

			if ( has_post_thumbnail($article) ) {
				$output .= '<div class="cmd-recommend-background">'. get_the_post_thumbnail( $article, 'full' ) . '</div>';
			}

			$output .= "\n\t\t".'
				</figure>
				<section>
					<div class="cmd-recommend-meta">';
			$output .= '<time datetime="' . $article->post_date . '">' . get_the_date( '', $article ) . '</time>';

			$categories = get_the_category( $post_id );
			if ( ! empty( $categories ) ) {
				foreach( $categories as $category ) {
					$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category tag">' . esc_html( $category->name ) . '</a>';
				}
			}
			
			$output .= "\n\t\t".'
					</div>
					<a href="' . get_permalink( $article ) . '" class="cmd-recommend-link">
						<h1 class="cmd-recommend-title">' . $article->post_title . '</h1>
						<span class="cmd-recommend-summary">' . $article->post_excerpt . '</span>
						<span class="cmd-recommend-read-more">' . sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'ConanMD' ), $article->post_title ) . '</span>
					</a>';
			$output .= "\n\t\t".'
				</section>
			</div>';

			echo $output;
			echo '</div>';
		}
		
		echo $args['after_widget'];

	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['post'] = strip_tags( $new_instance['post'] );

		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$post = sanitize_text_field( $instance['post'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('post'); ?>"><?php _e('Article ID:', 'ConanMD' ) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('post'); ?>" name="<?php echo $this->get_field_name('post'); ?>" value="<?php echo esc_attr($post); ?>">
		</p>

		<?php
	}
}

?>
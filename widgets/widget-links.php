<?php
/**
 * Widget Links
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

function mdl_walk_bookmarks( $bookmarks, $args = '' ) {
    $defaults = array(
        'show_updated' 		=> 0,
		'show_description' 	=> 0,
        'show_images' 		=> 1,
		'show_name' 		=> 0,
        'before' 			=> '<li>',
		'after' 			=> '</li>',
		'between' 			=> "\n",
        'show_rating' 		=> 0,
		'link_before' 		=> '',
		'link_after' 		=> ''
    );
 
    $r = wp_parse_args( $args, $defaults );
 
    $output = ''; // Blank string to start with.
 
    foreach ( (array) $bookmarks as $bookmark ) {

        $desc = esc_attr( sanitize_bookmark_field( 'link_description', $bookmark->link_description, $bookmark->link_id, 'display' ) );
        $name = esc_attr( sanitize_bookmark_field( 'link_name', $bookmark->link_name, $bookmark->link_id, 'display' ) );
        $title = $desc;

        if ( ! isset( $bookmark->recently_updated ) ) {
            $bookmark->recently_updated = false;
        }

		if ( $r['show_description'] && '' != $desc ) {
			$r['before'] = '<li class="mdl-list__item cmd-widget-links-item mdl-list__item mdl-list__item--two-line">';
		} else {
			$r['before'] = '<li class="mdl-list__item cmd-widget-links-item mdl-list__item">';
		}

        $output .= $r['before'];

        if ( $r['show_updated'] && $bookmark->recently_updated ) {
            $output .= '<em>';
        }

        $the_link = '#';

        if ( ! empty( $bookmark->link_url ) ) {
            $the_link = esc_url( $bookmark->link_url );
        }
 
        if ( $r['show_updated'] ) {
            if ( '00' != substr( $bookmark->link_updated_f, 0, 2 ) ) {
                $title .= ' (';
                $title .= sprintf(
                    __('Last updated: %s'),
                    date(
                        get_option( 'links_updated_date_format' ),
                        $bookmark->link_updated_f + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
                    )
                );
                $title .= ')';
            }
        }
        $alt = ' alt="' . $name . ( $r['show_description'] ? ' ' . $title : '' ) . '"';
 
        if ( '' != $title ) {
            $title = ' title="' . $title . '"';
        }

        $rel = $bookmark->link_rel;
        if ( '' != $rel ) {
            $rel = ' rel="' . esc_attr($rel) . '"';
        }
        $target = $bookmark->link_target;
        if ( '' != $target ) {
            $target = ' target="' . $target . '"';
        }
		
        $output .= '<a href="' . $the_link . '"' . $rel . $title . $target . ' class="mdl-list__item-primary-content">';
 
        $output .= $r['link_before'];
 
        if ( $bookmark->link_image != null && $r['show_images'] ) {
            if ( strpos( $bookmark->link_image, 'http' ) === 0 ) {
                $output .= "<img src=\"$bookmark->link_image\" $alt $title class=\"mdl-list__item-avatar\" />";
            } else {
				// If it's a relative path
                $output .= "<img src=\"" . get_option('siteurl') . "$bookmark->link_image\" $alt $title class=\"mdl-list__item-avatar\" />";
            }
            if ( $r['show_name'] ) {
                $output .= "<span>$name</span>";
            }
        } else {
            $output .= '<span>' . $name . '</span>';
        }

		if ( $r['show_description'] && '' != $desc ) {
            $output .= '<span class="mdl-list__item-sub-title">' . $r['between'] . $desc . '</span>';
        }
 
        $output .= $r['link_after'];
 
        $output .= '</a>';

		if ( strchr($rel, 'rel="me"') ) {
			$output .= '<i class="material-icons">star</i>';
		}
 
        if ( $r['show_updated'] && $bookmark->recently_updated ) {
            $output .= '</em>';
        }
 
        if ( $r['show_rating'] ) {
            $output .= $r['between'] . sanitize_bookmark_field(
                'link_rating',
                $bookmark->link_rating,
                $bookmark->link_id,
                'display'
            );
        }
        $output .= $r['after'] . "\n";
    } // end while
 
    return $output;
}

function mdl_list_bookmarks( $args = '' ) {
    $defaults = array(
        'orderby' => 'name',
		'order' => 'ASC',
        'limit' => -1,
		'category' => '',
		'exclude_category' => '',
        'category_name' => '',
		'hide_invisible' => 1,
        'show_updated' => 0,
		'echo' => 1,
        'categorize' => 1,
		'title_li' => __('Bookmarks'),
        'title_before' => '<h2>',
		'title_after' => '</h2>',
        'category_orderby' => 'name',
		'category_order' => 'ASC',
        'class' => 'linkcat', 
		'category_before' => '<li id="%id" class="%class">',
        'category_after' => '</li>'
    );
 
    $r = wp_parse_args( $args, $defaults );
 
    $output = '';
 
    if ( ! is_array( $r['class'] ) ) {
        $r['class'] = explode( ' ', $r['class'] );
    }
    $r['class'] = array_map( 'sanitize_html_class', $r['class'] );
    $r['class'] = trim( join( ' ', $r['class'] ) );
 
    if ( $r['categorize'] ) {
        $cats = get_terms( 'link_category', array(
            'name__like' => $r['category_name'],
            'include' => $r['category'],
            'exclude' => $r['exclude_category'],
            'orderby' => $r['category_orderby'],
            'order' => $r['category_order'],
            'hierarchical' => 0
        ) );
        if ( empty( $cats ) ) {
            $r['categorize'] = false;
        }
    }
 
    if ( $r['categorize'] ) {
        // Split the bookmarks into ul's for each category
        foreach ( (array) $cats as $cat ) {
            $params = array_merge( $r, array( 'category' => $cat->term_id ) );
            $bookmarks = get_bookmarks( $params );
            if ( empty( $bookmarks ) ) {
                continue;
            }
            $output .= str_replace(
                array( '%id', '%class' ),
                array( "linkcat-$cat->term_id", $r['class'] ),
                $r['category_before']
            );

            $catname = apply_filters( 'link_category', $cat->name );
 
            $output .= $r['title_before'];
            $output .= $catname;
            $output .= $r['title_after'];
            $output .= "\n\t<ul class='mdl-card mdl-shadow--4dp mdl-list cmd-widget-links-wrap'>\n";
            $output .= mdl_walk_bookmarks( $bookmarks, $r );
            $output .= "\n\t</ul>\n";
            $output .= $r['category_after'] . "\n";
        }
    } else {
        //output one single list using title_li for the title
        $bookmarks = get_bookmarks( $r );
 
        if ( ! empty( $bookmarks ) ) {
            if ( ! empty( $r['title_li'] ) ) {
                $output .= str_replace(
                    array( '%id', '%class' ),
                    array( "linkcat-" . $r['category'], $r['class'] ),
                    $r['category_before']
                );
                $output .= $r['title_before'];
                $output .= $r['title_li'];
                $output .= $r['title_after'];
                $output .= "\n\t<ul class='mdl-card mdl-shadow--4dp mdl-list cmd-widget-links-wrap'>\n";
                $output .= _walk_bookmarks( $bookmarks, $r );
                $output .= "\n\t</ul>\n";
                $output .= $r['category_after'] . "\n";
            } else {
                $output .= _walk_bookmarks( $bookmarks, $r );
            }
        }
    }

    $html = apply_filters( 'wp_list_bookmarks', $output );
 
    if ( ! $r['echo'] ) {
        return $html;
    }
    echo $html;
}

class conanMD_Links_Widget extends WP_Widget {

	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cmd-widget-links',
			'description' => __( 'Your blogroll' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_links_widget', 'MD ' . __( 'Links' ), $widget_ops );
	}

	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	public function widget( $args, $instance ) {
		$category = isset($instance['category']) ? $instance['category'] : false;
		$orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'name';
		$order = $orderby == 'rating' ? 'DESC' : 'ASC';

		$before_widget = preg_replace( '/id="[^"]*"/', 'id="%id"', $args['before_widget'] );

		$widget_links_args = array(
			'title_before'     => $args['before_title'],
			'title_after'      => $args['after_title'],
			'category_before'  => $before_widget,
			'category_after'   => $args['after_widget'],
			'show_images'      => true,
			'show_description' => true,
			'show_name'        => true,
			'show_rating'      => false,
			'category'         => $category,
			'orderby'          => $orderby,
			'order'            => $order,
			'limit'            => -1
		);

		mdl_list_bookmarks( apply_filters( 'widget_links_args', $widget_links_args, $instance ) );
	}

	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	public function update( $new_instance, $old_instance ) {
		$new_instance = (array) $new_instance;
		$instance = array( 'images' => 0, 'name' => 0, 'description' => 0, 'rating' => 0 );
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}

		$instance['orderby'] = 'name';
		if ( in_array( $new_instance['orderby'], array( 'name', 'rating', 'id', 'rand' ) ) )
			$instance['orderby'] = $new_instance['orderby'];

		$instance['category'] = intval( $new_instance['category'] );

		return $instance;
	}

	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	public function form( $instance ) {

		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'images' => true, 'name' => true, 'description' => false, 'rating' => false, 'category' => false, 'orderby' => 'name', 'limit' => -1 ) );
		$link_cats = get_terms( 'link_category' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Select Link Category:' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
				<option value=""><?php _ex('All Links', 'links widget'); ?></option>
				<?php
				foreach ( $link_cats as $link_cat ) {
					echo '<option value="' . intval( $link_cat->term_id ) . '"'
						. selected( $instance['category'], $link_cat->term_id, false )
						. '>' . $link_cat->name . "</option>\n";
				}
				?>
			</select>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:' ); ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
				<option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php _e( 'Link title' ); ?></option>
				<option value="rating"<?php selected( $instance['orderby'], 'rating' ); ?>><?php _e( 'Link rating' ); ?></option>
				<option value="id"<?php selected( $instance['orderby'], 'id' ); ?>><?php _e( 'Link ID' ); ?></option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php _ex( 'Random', 'Links widget' ); ?></option>
			</select>
		</p>
		<?php
	}
}

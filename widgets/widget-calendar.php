<?php
/**
 * Widget Calendar
 * Version: 0.1
 * @package ConanMD
 * @author Conan06
 * @link http://conan06.com
 */

function get_mdl_calendar( $initial = true, $echo = true ) {
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
 
    $key = md5( $m . $monthnum . $year );
    $cache = wp_cache_get( 'get_mdl_calendar', 'calendar' );
 
    if ( $cache && is_array( $cache ) && isset( $cache[ $key ] ) ) {
        /** This filter is documented in wp-includes/general-template.php */
        $output = apply_filters( 'get_mdl_calendar', $cache[ $key ] );
 
        if ( $echo ) {
            echo $output;
            return;
        }
 
        return $output;
    }
 
    if ( ! is_array( $cache ) ) {
        $cache = array();
    }
 
    // Quick check. If we have no posts at all, abort!
    if ( ! $posts ) {
        $gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1");
        if ( ! $gotsome ) {
            $cache[ $key ] = '';
            wp_cache_set( 'get_mdl_calendar', $cache, 'calendar' );
            return;
        }
    }
 
    if ( isset( $_GET['w'] ) ) {
        $w = (int) $_GET['w'];
    }

    // week_begins = 0 stands for Sunday
    $week_begins = (int) get_option( 'start_of_week' );
    $ts = current_time( 'timestamp' );
 
    // Let's figure out when we are
    if ( ! empty( $monthnum ) && ! empty( $year ) ) {
        $thismonth = zeroise( intval( $monthnum ), 2 );
        $thisyear = (int) $year;
    } elseif ( ! empty( $w ) ) {
        // We need to get the month from MySQL
        $thisyear = (int) substr( $m, 0, 4 );
        //it seems MySQL's weeks disagree with PHP's
        $d = ( ( $w - 1 ) * 7 ) + 6;
        $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
    } elseif ( ! empty( $m ) ) {
        $thisyear = (int) substr( $m, 0, 4 );
        if ( strlen( $m ) < 6 ) {
            $thismonth = '01';
        } else {
            $thismonth = zeroise( (int) substr( $m, 4, 2 ), 2 );
        }
    } else {
        $thisyear = gmdate( 'Y', $ts );
        $thismonth = gmdate( 'm', $ts );
    }
 
    $unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
    $last_day = date( 't', $unixmonth );
 
    // Get the next and previous month and year with at least one post
    $previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
        FROM $wpdb->posts
        WHERE post_date < '$thisyear-$thismonth-01'
        AND post_type = 'post' AND post_status = 'publish'
            ORDER BY post_date DESC
            LIMIT 1");
    $next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
        FROM $wpdb->posts
        WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
        AND post_type = 'post' AND post_status = 'publish'
            ORDER BY post_date ASC
            LIMIT 1");
 
    /* translators: Calendar caption: 1: month name, 2: 4-digit year */
    $calendar_caption = _x('%1$s %2$s', 'calendar caption');
	$calendar_figure = get_theme_file_uri( '/assets/images/calendar/calendar-' . $thismonth );
    
    $calendar_output = '<figure data-parallax="scroll" data-image-src="' . $calendar_figure . '-768x768.jpg" data-z-index="2" data-speed="0.85">
    </figure>
	<div class="cmd-calendar-contain">
	<nav class="mdl-cell mdl-cell--12-col">';
	if ( $previous ) {
		$calendar_output .= "\n\t\t".'
		<a href="' . get_month_link( $previous->year, $previous->month ) . '" 
		   class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect">
			<i class="material-icons">keyboard_arrow_left</i>
		</a>';
	} else {
		$calendar_output .= "\n\t\t".'<a class="cmd-widget-calendar-blank"></a>';
	}
	
	$calendar_output .= "\n\t\t".'<div class="section-spacer"></div>';

	$calendar_output .= "\n\t\t".'<span class="cmd-calendar-title">' . sprintf( 
		$calendar_caption, 
		$wp_locale->get_month( $thismonth ), 
		date( 'Y', $unixmonth ) 
	) . '</span>';

	$calendar_output .= "\n\t\t".'<div class="section-spacer"></div>';
	
	if ( $next ) {
		$calendar_output .= "\n\t\t".'
		<a href="' . get_month_link( $next->year, $next->month ) . '" 
		   class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect">
			<i class="material-icons">keyboard_arrow_right</i>
		</a>';
	} else {
		$calendar_output .= "\n\t\t".'<a class="cmd-widget-calendar-blank"></a>';
	}
	
	$calendar_output .= '
	</nav>

    <section class="cmd-widget-calendar-week">';
 
    $myweek = array();
 
    for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
        $myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
    }
 
    foreach ( $myweek as $wd ) {
        $day_name = $initial ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
        $wd = esc_attr( $wd );
        $calendar_output .= "\n\t\t<div title=\"$wd\">$day_name</div>";
    }
 
    $calendar_output .= '
    </section>
 
    <section class="cmd-widget-calendar-day">
    <div class="cmd-widget-calendar-row">';
 
    $daywithpost = array();
 
    // Get days with posts
    $dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH(post_date)
        FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
        AND post_type = 'post' AND post_status = 'publish'
        AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
    if ( $dayswithposts ) {
        foreach ( (array) $dayswithposts as $daywith ) {
            $daywithpost[] = $daywith[0];
        }
    }
 
    // See how much we should pad in the beginning
    $pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
    while ( 0 != $pad ) {
        $calendar_output .= "\n\t\t".'<div class="section-spacer"></div>';
		$pad--;
    }
 
    $newrow = false;
    $daysinmonth = (int) date( 't', $unixmonth );
 
    for ( $day = 1; $day <= $daysinmonth; ++$day ) {
        if ( isset($newrow) && $newrow ) {
            $calendar_output .= "\n\t</div>\n\t<div class='cmd-widget-calendar-row'>\n\t\t";
        }
        $newrow = false;
 
        if ( $day == gmdate( 'j', $ts ) &&
            $thismonth == gmdate( 'm', $ts ) &&
            $thisyear == gmdate( 'Y', $ts ) ) {
            $calendar_output .= '<div id="today"><span class="mdl-color--blue">';
        } else {
            $calendar_output .= '<div><span>';
        }
 
        if ( in_array( $day, $daywithpost ) ) {
            // any posts today?
            $date_format = date( _x( 'F j, Y', 'daily archives date format' ), strtotime( "{$thisyear}-{$thismonth}-{$day}" ) );
            /* translators: Post calendar label. 1: Date */
            $label = sprintf( __( 'Posts published on %s' ), $date_format );
            $calendar_output .= sprintf(
                '<a class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect mdl-color-text--blue cmd-widget-calendar-link" href="%s" aria-label="%s">%s</a>',
                get_day_link( $thisyear, $thismonth, $day ),
                esc_attr( $label ),
                $day
            );
        } else {
            $calendar_output .= $day;
        }
        $calendar_output .= '</span></div>';
 
        if ( 6 == calendar_week_mod( date( 'w', mktime(0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
            $newrow = true;
        }
    }
 
    $pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins );
    while ( $pad != 0 && $pad != 7 ) {
        $calendar_output .= "\n\t\t".'<div class="section-spacer"></div>';
		$pad--;
    }
    $calendar_output .= "\n\t</div>\n\t</section>\n\t</div>";
 
    $cache[ $key ] = $calendar_output;
    wp_cache_set( 'get_mdl_calendar', $cache, 'calendar' );
 
    if ( $echo ) {
        echo apply_filters( 'get_mdl_calendar', $calendar_output );
        return;
    }

    return apply_filters( 'get_mdl_calendar', $calendar_output );
}

/*******************************************************************************/

class conanMD_Calendar_Widget extends WP_Widget {

	private static $instance = 0;

	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){
		
		$widget_ops = array(
			'classname' => 'cmd-widget-calendar',
			'description' => __( 'A calendar of your site&#8217;s Posts.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'conanMD_calendar_widget', 'MD ' . __( 'Calendar' ), $widget_ops );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];
        if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( 0 === self::$instance ) {
			echo '<div id="cmd-calendar-wrap" class="mdl-card mdl-shadow--2dp cmd-calendar-wrap">';
		} else {
			echo '<div class="mdl-card mdl-shadow--2dp cmd-calendar-wrap">';
		}
		get_mdl_calendar();
		echo '</div>';
		echo $args['after_widget'];

		self::$instance++;
	}


	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		
        $instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php
	}
}

?>
<?php
/**
 * Plugin Name: Micro Archive Widget
 * Plugin URI: https://github.com/ziggi/micro-archive-widget
 * Description: An archive widget that collapses the standard archive widget into an micro by year
 * Version: 1.0
 * Author: Sergei Marochkin
 * Author URI: http://ziggi.org
 * Based on: Accordion Archive Widget by Pat Hartl (http://pathartl.me)
 * License: CC0 1.0
 */

function micro_archives_js() {
	wp_enqueue_script('micro_archives_script', plugins_url( '/script.js', __FILE__ ), null, null, true);
}
add_action('wp_enqueue_scripts', 'micro_archives_js');

function micro_archives_styles() {
	wp_register_style('micro_archives', plugins_url( '/style.css', __FILE__ ), array(), '1.0', 'all');
	wp_enqueue_style('micro_archives');
}
add_action('wp_enqueue_scripts', 'micro_archives_styles');

/**
 * Micro Archives widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Micro_Archives extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_micro_archive', 'description' => __( 'A yearly archive of your site&#8217;s Posts in an micro.') );
		parent::__construct('micro_archives', __('Micro Archives'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty($instance['title'] ) ? __( 'Micro Archives' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ($title) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$archives = wp_get_archives(array('echo' => 0));

		preg_match_all('#<a href=\'(.+)\'>(.+) (\d+)<\/a>#', $archives, $matches);
		
		$elements = array();
		
		foreach ($matches[0] as $i => $match) {
			list($url, $month, $year) = array($matches[1][$i], $matches[2][$i], $matches[3][$i]);
			$elements[$year][$month][] = $url;
		}

		echo '<ul>';

		foreach ($elements as $year => $months) {
			echo '<li class="archive-micro-year"><a>' . $year . '</a>';
			echo '<ul>';

			foreach ($months as $month => $urls) {
				foreach ($urls as $url) {
					echo '<li class="archive-micro-month"><a href="' . $url . '">' . $month . '</a></li>';
				}
			}

			echo '</ul></li>';
		}

		echo '</ul>';

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);
		$field_id = $this->get_field_id('title');
		$field_name = $this->get_field_name('title');
		$value = esc_attr($title);
		
		echo '
			<p>
				<label for="' . $field_id . '">' . __('Title:') . '</label>
				<input class="widefat" id="' . $field_id . '" name="' . $field_name . '" type="text" value="' . $value . '" />
			</p>';
	}
}

function register_micro_archive_widget() {
	register_widget( 'WP_Widget_Micro_Archives' );
}
add_action( 'widgets_init', 'register_micro_archive_widget' );

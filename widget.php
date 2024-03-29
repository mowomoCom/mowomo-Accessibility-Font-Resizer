<?php
/*
 * widget.php
 * mowomo Font Resizer Widget
 */

if (function_exists('register_sidebar') && class_exists('WP_Widget')) {
	class mowomo_FR_Widget extends WP_Widget {

		/* Constructor */
		function __construct() {
			$widget_ops = array( 'classname' => 'mowomo_FR_Widget', 'description' => __( 'Displays options to change the font size.', 'mowomo-font-resizer' ) );
			parent::__construct('mowomo_FR_Widget', 'mowomo Font Resizer', $widget_ops);
			$this->alt_option_name = 'mowomo_FR_Widget';

		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {
			extract($args);

			$default_value = array(
						'title' => __('Font Resizer', 'mowomo-font-resizer'),
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );
			$widget_title  = esc_attr($instance['title']);

			echo $before_widget;

			if ($widget_title !== FALSE) {
				echo $before_title . apply_filters('widget_title', $widget_title) . $after_title;
			}

			// The real content:
			mwm_font_resizer_place();

			echo $after_widget;
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}

		/** @see WP_Widget::form */
		function form($instance) {

			$default_value = array(
						'title' => __('Font Resizer', 'mowomo-font-resizer'),
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );
			$title         = esc_attr($instance['title']);
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>" /><?php _e('Title:', 'mowomo-font-resizer'); ?></label><br />
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>

			<?php
		}

	}

	function mwm_font_resizer_widget() {
		register_widget('mowomo_FR_Widget');
	}
	add_action('widgets_init', 'mwm_font_resizer_widget' );
}

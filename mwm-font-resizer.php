<?php
/*
Plugin Name: mowomo Font Resizer
Plugin URI: http://zenoweb.nl
Description: mowomo Font Resizer allows the visitors of your website to change the font size of your text.
Author: Marcel Pol
Version: 1.7.4
Author URI: http://zenoweb.nl/
Text Domain: mowomo-font-resizer
Domain Path: /lang/
*/

/*  Copyright 2010 - 2013  Cubetech GmbH
	Copyright 2015 - 2019  Marcel Pol     (email: marcel@timelord.nl)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version.
define('ZENO_FR_VER', '1.7.4');


/*
 * Add the options to WordPress if they don't exist.
 */
add_option('mwm_font_resizer',             'html', '', 'yes');
add_option('mwm_font_resizer_ownid',       '',     '', 'yes');
add_option('mwm_font_resizer_ownelement',  '',     '', 'yes');
add_option('mwm_font_resizer_resizeMax',   '24',   '', 'no' );
add_option('mwm_font_resizer_resizeMin',   '10',   '', 'no' );
add_option('mwm_font_resizer_resizeSteps', '1.6',  '', 'no' );
add_option('mwm_font_resizer_letter',      'A',    '', 'yes');
add_option('mwm_font_resizer_cookieTime',  '31',   '', 'no' );


/*
 * Register an administration page.
 */
function mwm_font_resizer_add_admin_page() {
	add_options_page( __( 'mowomo Font Resizer', 'mowomo-font-resizer' ), __( 'mowomo Font Resizer', 'mowomo-font-resizer' ), 'manage_options', 'mowomo-font-resizer', 'mwm_font_resizer_admin_page');
}
add_action('admin_menu', 'mwm_font_resizer_add_admin_page');


/*
 * Generates the Settings Page.
 */
function mwm_font_resizer_admin_page() {
	?>
	<div class="wrap" style="padding: 20px 32px 12px; background-color: white; border-radius: 5px;">
		<h1><?php _e( 'mowomo Accessibility Font Resizer', 'mowomo-font-resizer' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'mwm_font_resizer' );
			do_settings_sections( 'mwm_font_resizer' );
			submit_button();
			?>
		</form>
		<div class="wrap">
		<h2>SHORTCODE</h2>
			<p><?php _e('If you need a shortcode use this <strong>[accessibility_font]</strong>','mowomo-font-resizer');?></p>

		</div>

		<h2><?php _e('Credits', 'mowomo-font-resizer'); ?></h2>
		<div class="postbox">
			<div class="widget" style="padding: 10px 20px;">
				<p><?php _e('Based on plugin ', 'mowomo-font-resizer'); ?>
					<a href="https://wordpress.org/plugins/zeno-font-resizer/" target="_blank" title="<?php _e('ZENO Font Resizer', 'mowomo-font-resizer'); ?>"><?php _e('ZENO Font Resizer', 'mowomo-font-resizer'); ?></a>.
				</p>
			</div>
		</div>

	</div>
	<?php
}


/*
 * Enqueue the dependencies.
 */
function mwm_font_resizer_enqueue(){
	$mwm_font_resizer_path = plugins_url( 'js/', __FILE__ );
	wp_register_script('mwm_font_resizer_cookie',   $mwm_font_resizer_path . 'js.cookie.js', 'jquery', ZENO_FR_VER, true);
	wp_register_script('mwm_font_resizer_fontsize', $mwm_font_resizer_path . 'jquery.fontsize.js', 'jquery', ZENO_FR_VER, true);
	wp_enqueue_script('jquery');
	wp_enqueue_script('mwm_font_resizer_cookie');
	wp_enqueue_script('mwm_font_resizer_fontsize');
}
add_action('wp_enqueue_scripts', 'mwm_font_resizer_enqueue');


/*
 * Generate the font-resizer text on the frontend.
 * Used as template function for developers.
 * Parameter: $echo, boolean:
 *            - true: echo the template code (default).
 *            - false: return the template code.
 */
function mwm_font_resizer_place( $echo = true ) {
	$html = '
	<div class="mwm_font_resizer_container">
		<p class="mwm_font_resizer" style="text-align: center; font-weight: bold;">
			<span>
				<a href="#" class="mwm_font_resizer_minus" title="' . esc_attr__( 'Decrease font size', 'mowomo-font-resizer' ) . '" style="font-size: 0.7em;">' .
					get_option('mwm_font_resizer_letter') . '<span class="screen-reader-text"> ' . __('Decrease font size.', 'mowomo-font-resizer') . '</span>' .
				'</a>
				<a href="#" class="mwm_font_resizer_reset" title="' . esc_attr__( 'Reset font size', 'mowomo-font-resizer' ) . '">' .
					get_option('mwm_font_resizer_letter') . '<span class="screen-reader-text"> ' . __('Reset font size.', 'mowomo-font-resizer') . '</span>' .
				'</a>
				<a href="#" class="mwm_font_resizer_add" title="' . esc_attr__( 'Increase font size', 'mowomo-font-resizer' ) . '" style="font-size: 1.3em;">' .
					get_option('mwm_font_resizer_letter') . '<span class="screen-reader-text"> ' . __('Increase font size.', 'mowomo-font-resizer') . '</span>' .
				'</a>
			</span>
			<input type="hidden" id="mwm_font_resizer_value" value="' . get_option('mwm_font_resizer') . '" />
			<input type="hidden" id="mwm_font_resizer_ownid" value="' . get_option('mwm_font_resizer_ownid') . '" />
			<input type="hidden" id="mwm_font_resizer_ownelement" value="' . get_option('mwm_font_resizer_ownelement') . '" />
			<input type="hidden" id="mwm_font_resizer_resizeMax" value="' . get_option('mwm_font_resizer_resizeMax') . '" />
			<input type="hidden" id="mwm_font_resizer_resizeMin" value="' . get_option('mwm_font_resizer_resizeMin') . '" />
			<input type="hidden" id="mwm_font_resizer_resizeSteps" value="' . get_option('mwm_font_resizer_resizeSteps') . '" />
			<input type="hidden" id="mwm_font_resizer_cookieTime" value="' . get_option('mwm_font_resizer_cookieTime') . '" />
		</p>
	</div>
	';
	if ( $echo == true ) {
		echo $html;
	} else {
		return $html;
	}
}


/*
 * Add CSS for broken themes.
 * Handbook: https://make.wordpress.org/accessibility/handbook/markup/the-css-class-screen-reader-text/
 *
 * @since 1.7.4
 */
function mwm_font_resizer_head_style() {
	echo '
	<style id="mowomo-font-resizer" type="text/css">
		p.mwm_font_resizer .screen-reader-text {
			border: 0;
			clip: rect(1px, 1px, 1px, 1px);
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
			word-wrap: normal !important;
		}
	</style>
	';
}
add_action('wp_head', 'mwm_font_resizer_head_style');


/*
 * Add Settings link to the main Plugin page.
 */
function mwm_font_resizer_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/mowomo-font-resizer.php' ) ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=mowomo-font-resizer' ) . '">' . __( 'Settings', 'mowomo-font-resizer' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'mwm_font_resizer_links', 10, 2 );


/*
 * Load language files for frontend and backend.
 */
function mwm_font_resizer_load_lang() {
	load_plugin_textdomain( 'mowomo-font-resizer', false, plugin_basename(dirname(__FILE__)) . '/lang' );
}
add_action('plugins_loaded', 'mwm_font_resizer_load_lang');


/*
 * Register Settings
 */
function mwm_font_resizer_register_settings() {
	add_settings_section(
		'mwm_font_resizer',
		'',
		'',
		'mwm_font_resizer'
	);

	add_settings_field(
		'mwm_font_resizer',
		__( 'HTML Element', 'mowomo-font-resizer' ),
		'mwm_font_resizer_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer', 'strval' ); // 'html'
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_ownid', 'strval' ); // empty by default
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_ownelement', 'strval' );   // empty by default

	add_settings_field(
		'mwm_font_resizer_resizeSteps',
		__( 'Resize Steps', 'mowomo-font-resizer' ),
		'mwm_font_resizer_resizeSteps_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_resizeSteps', 'floatval' ); // 1.6

	add_settings_field(
		'mwm_font_resizer_resizeMin',
		__( 'Minimum Size', 'mowomo-font-resizer' ),
		'mwm_font_resizer_resizeMin_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_resizeMin', 'intval' ); // 10

	add_settings_field(
		'mwm_font_resizer_resizeMax',
		__( 'Maximum Size', 'mowomo-font-resizer' ),
		'mwm_font_resizer_resizeMax_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_resizeMax', 'intval' ); // 24

	add_settings_field(
		'mwm_font_resizer_letter',
		__( 'Resize Character', 'mowomo-font-resizer' ),
		'mwm_font_resizer_letter_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_letter', 'strval' ); // A

	add_settings_field(
		'mwm_font_resizer_cookieTime',
		__( 'Cookie Settings', 'mowomo-font-resizer' ),
		'mwm_font_resizer_cookieTime_callback_function',
		'mwm_font_resizer',
		'mwm_font_resizer'
	);
	register_setting( 'mwm_font_resizer', 'mwm_font_resizer_cookieTime', 'intval' ); // 31
}
add_action( 'admin_init', 'mwm_font_resizer_register_settings' );


/*
 * Callback functions for option page.
 */
function mwm_font_resizer_callback_function() {
	?>
	<label>
		<input type="radio" name="mwm_font_resizer" value="html" <?php if (get_option('mwm_font_resizer')=="html") echo "checked"; ?> />
		<?php _e( 'Default setting, resize whole content in html element (&lt;html&gt;All content of your site&lt;/html&gt;).', 'mowomo-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="mwm_font_resizer" value="body" <?php if (get_option('mwm_font_resizer')=="body") echo "checked"; ?> />
		<?php _e( 'Resize whole content in body element (&lt;body&gt;All content of your site&lt;/body&gt;).', 'mowomo-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="mwm_font_resizer" value="innerbody" <?php if (get_option('mwm_font_resizer')=="innerbody") echo "checked"; ?> />
		<?php _e( 'Use div with id innerbody (&lt;div id="innerbody"&gt;Resizable text&lt;/div&gt;).', 'mowomo-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="mwm_font_resizer" value="ownid" <?php if (get_option('mwm_font_resizer')=="ownid") echo "checked"; ?> />
		<input type="text" name="mwm_font_resizer_ownid" value="<?php echo get_option('mwm_font_resizer_ownid'); ?>" /><br />
		<?php _e( 'Use your own div id (&lt;div id="yourid"&gt;Resizable text&lt;/div&gt;).', 'mowomo-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="mwm_font_resizer" value="ownelement" <?php if (get_option('mwm_font_resizer')=="ownelement") echo "checked"; ?> />
		<input type="text" name="mwm_font_resizer_ownelement" value="<?php echo get_option('mwm_font_resizer_ownelement'); ?>" /><br />
		<?php _e( 'Use your own element (For example: for a span with class "bla" (&lt;span class="bla"&gt;Resizable text&lt;/span&gt;), enter the css definition, "span.bla" (without quotes)).', 'mowomo-font-resizer' ); ?>
	</label><?php
}
function mwm_font_resizer_resizeSteps_callback_function() {
	?>
	<label for="mwm_font_resizer_resizeSteps">
		<input type="text" name="mwm_font_resizer_resizeSteps" value="<?php echo get_option('mwm_font_resizer_resizeSteps'); ?>" style="width: 3em"> <b><?php _e( 'px.', 'mowomo-font-resizer' ); ?></b><br />
		<?php _e( 'Set the resize steps in pixel (default: 1.6px).', 'mowomo-font-resizer' ); ?>
	</label><?php
}
function mwm_font_resizer_resizeMin_callback_function() {
	?>
	<label for="mwm_font_resizer_resizeMin">
		<input type="text" name="mwm_font_resizer_resizeMin" value="<?php echo get_option('mwm_font_resizer_resizeMin'); ?>" style="width: 3em"> <b><?php _e( 'px.', 'mowomo-font-resizer' ); ?></b><br />
		<?php _e( 'Set the minimum font size in pixels (default: 10px).', 'mowomo-font-resizer' ); ?>
	</label><?php
}
function mwm_font_resizer_resizeMax_callback_function() {
	?>
	<label for="mwm_font_resizer_resizeMax">
		<input type="text" name="mwm_font_resizer_resizeMax" value="<?php echo get_option('mwm_font_resizer_resizeMax'); ?>" style="width: 3em"> <b><?php _e( 'px.', 'mowomo-font-resizer' ); ?></b><br />
		<?php _e( 'Set the maximum font size in pixels (default: 24px).', 'mowomo-font-resizer' ); ?>
	</label><?php
}
function mwm_font_resizer_letter_callback_function() {
	?>
	<label for="mwm_font_resizer_letter">
		<input type="text" name="mwm_font_resizer_letter" value="<?php echo get_option('mwm_font_resizer_letter'); ?>" maxlength="1" style="width: 3em"><br />
		<?php _e( 'Sets the letter to be displayed in the resizer in the website.', 'mowomo-font-resizer' ); ?>
	</label><?php
}
function mwm_font_resizer_cookieTime_callback_function() {
	?>
	<label for="mwm_font_resizer_cookieTime">
		<input type="text" name="mwm_font_resizer_cookieTime" value="<?php echo get_option('mwm_font_resizer_cookieTime'); ?>" style="width: 3em"> <b><?php _e( 'days.', 'mowomo-font-resizer' ); ?></b><br />
		<?php _e( 'Set the cookie store time (default: 31 days).', 'mowomo-font-resizer' ); ?>
	</label><?php
}


/*
 * Delete the options when you uninstall the plugin.
 */
function mwm_font_resizer_uninstaller() {
	delete_option('mwm_font_resizer');
	delete_option('mwm_font_resizer_ownid');
	delete_option('mwm_font_resizer_ownelement');
	delete_option('mwm_font_resizer_resizeMax');
	delete_option('mwm_font_resizer_resizeMin');
	delete_option('mwm_font_resizer_resizeSteps');
	delete_option('mwm_font_resizer_letter');
	delete_option('mwm_font_resizer_cookieTime');
}
register_uninstall_hook( __FILE__, 'mwm_font_resizer_uninstaller' );


/* Load the widget */
include('widget.php');
include('shortcode.php');

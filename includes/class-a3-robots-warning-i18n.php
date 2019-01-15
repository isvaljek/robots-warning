<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'a3-robots-warning',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

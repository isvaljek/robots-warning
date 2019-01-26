<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://appandapp.net/isvaljek
 * @since             1.0.0
 * @package           A3_Robots_Warning
 *
 * @wordpress-plugin
 * Plugin Name:       A3 Robots Warning
 * Plugin URI:        https://appandapp.net/wp/plugins/a3-robots-warning/
 * Description:       Warns you of server IP address changes, so you can take care of Search Engine Visibility settings.
 * Version:           1.0.5
 * Author:            Ivan Švaljek
 * Author URI:        https://appandapp.net/isvaljek
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       a3-robots-warning
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'A3_ROBOTS_WARNING_VERSION', '1.0.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-a3-robots-warning-activator.php
 */
function activate_a3_robots_warning() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-a3-robots-warning-activator.php';
	A3_Robots_Warning_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-a3-robots-warning-deactivator.php
 */
function deactivate_a3_robots_warning() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-a3-robots-warning-deactivator.php';
	A3_Robots_Warning_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_a3_robots_warning' );
register_deactivation_hook( __FILE__, 'deactivate_a3_robots_warning' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-a3-robots-warning.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_a3_robots_warning() {

	$plugin = new A3_Robots_Warning();
	$plugin->run();

}
run_a3_robots_warning();

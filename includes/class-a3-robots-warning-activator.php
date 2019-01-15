<?php

/**
 * Fired during plugin activation
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		A3_Robots_Warning_Logging::write_log(["!!! activate !!! wp_next_scheduled( 'a3rw_check_ip' )" => wp_next_scheduled( 'a3rw_check_ip' )]);
		
		if ( !wp_next_scheduled( 'a3rw_check_ip' ) ) {				
			update_option('a3rw_check_ip_cron', 0);
		}
		
		wp_clear_scheduled_hook('a3rw_check_ip');
		wp_schedule_event( time(), 'every_minute', 'a3rw_check_ip' );
	}

}

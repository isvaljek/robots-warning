<?php

/**
 * The file that defines the IP change detection
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * The IP change detection class.
 *
 * Detect the IP changes to allow notifications and warnings
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_Detection {

	/**
	 * IP change detection.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	public static function has_ip_changed(){
		$server_ip = A3_Robots_Warning_Detection::server_ip();
		$old_ip = get_option('a3rw_server_ip');  
		$valid_response = !empty($server_ip) && !is_wp_error($server_ip);		
		$ip_changed = $valid_response && $server_ip != $old_ip;

		if($ip_changed) A3_Robots_Warning_Logging::write_log(json_encode(['ip_changed' => [$server_ip, $old_ip] ]));
		
		if($old_ip === false) update_option('a3rw_server_ip', $server_ip);

		// IP change detected if we get a valid response from ipecho.net, and the IP is not the one stored in our DB
		return $ip_changed;		
	}
	
	public static function server_ip(){
		$response = wp_remote_get("http://ipecho.net/plain");		

		if ( is_array( $response ) ) {			
			$server_ip = $response['body']; // use the content
			A3_Robots_Warning_Logging::write_log(json_encode(['http://ipecho.net/plain' => $server_ip]));

			if( filter_var( $server_ip, FILTER_VALIDATE_IP ) ){
				return $server_ip;
			}									
		}
		else {
			A3_Robots_Warning_Logging::write_log(json_encode(['http://ipecho.net/plain' => 'WP_Error']));
		}

		return false;
	}
}

<?php

/**
 * The file that defines the IP change detection
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    Robots_Warning
 * @subpackage Robots_Warning/includes
 */

/**
 * The IP change detection class.
 *
 * Detect the IP changes to allow notifications and warnings
 *
 * @since      1.0.0
 * @package    Robots_Warning
 * @subpackage Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Detection {

	/**
	 * IP change detection.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	public static function has_ip_changed(){
        //$server_ip = $_SERVER['SERVER_ADDR'];
        $server_ip = file_get_contents("http://ipecho.net/plain");

		$old_ip = get_option('a3_server_ip');  
		
		if($old_ip === false) update_option('a3_server_ip', $server_ip);

        return $server_ip != $old_ip;
	}
	
	public static function server_ip(){
		return file_get_contents("http://ipecho.net/plain");
	}
}

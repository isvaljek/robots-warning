<?php

/**
 * The file that handles logging
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * The logging class.
 *
 * Logging methods
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_Logging {

	/**
	 * Logging functionality.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	public static function write_log ( $log )  {
		if ( is_array( $log ) || is_object( $log ) ) {
		   error_log( print_r( $log, true ) );
		} else {
		   error_log( $log );
		}
	}  
}

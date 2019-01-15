<?php

/**
 * The file that handles notifications
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * The notifications class.
 *
 * Create and display notifications
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_Notifications {

	/**
	 * Notifications creation and display.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	public function create_notification($message, $type){

        // if(!is_admin()) {
		// 	return false;
		// }
	
		// todo: check these are valid
		if(!in_array($type, array('error', 'info', 'success', 'warning'))) {
			return false;
		}
	
		// Store/retrieve a option associated with the current logged in user
		$optionName = 'a3rw_robots_notification'; // . get_current_user_id();
	
		// Check if this option already exists. We can use this to add
		// multiple notifications during a single pass through our code
		$notifications = get_option($optionName);
	
		if(!$notifications) {
			$notifications = array(); // initialise as a blank array
		}
	
		$notifications[md5($message)] = array(
			'message' => $message,
			'type' => $type
		);
	
		update_option($optionName, $notifications);  // no need to provide an expiration, will
														// be removed immediately
	}
	
	/**
	 * The handler to output our admin notification messages
	 */
	public function ip_change_admin_notice_handler() {

		if( !is_admin() || !(current_user_can('administrator') || current_user_can('editor') || current_user_can('manage_woocommerce') ) ) {
			// Only process this when in admin context
			return;
		}

		$optionName = 'a3rw_robots_notification'; // . get_current_user_id();

		// Check if there are any notices stored
		$notifications = get_option($optionName);				

		if( is_array($notifications) && count($notifications) > 0 && A3_Robots_Warning_Detection::has_ip_changed() ):
			foreach($notifications as $notification):
				?>
					<div class="notice notice-custom notice-<?= $notification['type']?> is-dismissible">
						<p><?= $notification['message']?></p>
					</div>
				<?php
			endforeach;			
		endif;

		// Clear away our option data, it's not needed any more
		// delete_option($optionName);

	}

	public function option_updated ( $option_name, $old_value, $value ) {		
		if($option_name === 'blog_public') {
			$optionName = 'a3rw_robots_notification'; // . get_current_user_id();

			delete_option($optionName);
		}
	}
}

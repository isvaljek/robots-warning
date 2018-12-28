<?php

/**
 * The file that handles notifications
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    Robots_Warning
 * @subpackage Robots_Warning/includes
 */

/**
 * The notifications class.
 *
 * Create and display notifications
 *
 * @since      1.0.0
 * @package    Robots_Warning
 * @subpackage Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Notifications {

	/**
	 * Notifications creation and display.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	public function notification_helper($message, $type){

        if(!is_admin()) {
			return false;
		}
	
		// todo: check these are valid
		if(!in_array($type, array('error', 'info', 'success', 'warning'))) {
			return false;
		}
	
		// Store/retrieve a transient associated with the current logged in user
		$transientName = 'a3_robots_notification_'.get_current_user_id();
	
		// Check if this transient already exists. We can use this to add
		// multiple notifications during a single pass through our code
		$notifications = get_transient($transientName);
	
		if(!$notifications) {
			$notifications = array(); // initialise as a blank array
		}
	
		$notifications[md5($message)] = array(
			'message' => $message,
			'type' => $type
		);
	
		set_transient($transientName, $notifications);  // no need to provide an expiration, will
														// be removed immediately
	}
	
	/**
	 * The handler to output our admin notification messages
	 */
	public function ip_change_admin_notice_handler() {

		if(!is_admin() || !(current_user_can('administrator') || current_user_can('editor') || current_user_can('manage_woocommerce') ) ) {
			// Only process this when in admin context
			return;
		}

		$transientName = 'a3_robots_notification_'.get_current_user_id();

		// Check if there are any notices stored
		$notifications = get_transient($transientName);				

		if($notifications):
			foreach($notifications as $notification):
				?>

					<div class="notice notice-custom notice-<?= $notification['type']?> is-dismissible">
						<p><?= $notification['message']?></p>
					</div>

				<?php
			endforeach;			
		endif;

		// Clear away our transient data, it's not needed any more
		delete_transient($transientName);

	}

	public function a3_option_updated ( $option_name, $old_value, $value ) {		
		if($option_name === 'blog_public') {
			$transientName = 'a3_robots_notification_'.get_current_user_id();

			delete_transient($transientName);
		}
	}
}

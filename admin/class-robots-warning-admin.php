<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    Robots_Warning
 * @subpackage Robots_Warning/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Robots_Warning
 * @subpackage Robots_Warning/admin
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class Robots_Warning_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Robots_Warning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Robots_Warning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/robots-warning-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Robots_Warning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Robots_Warning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/robots-warning-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Init function
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {		
		$notifications = new A3_Robots_Notifications();
		$ip_changed = A3_Robots_Detection::has_ip_changed();
		$reading_settings_link = admin_url('options-reading.php');
		$reading_settings = __('Reading Settings');
		$blog_public = get_option('blog_public');
		$confirmation_text = __("I confirm, this server <b>" . ($blog_public == 0 ? "should not" : "should") . "</b> be public" );
		$button_text = __('Confirm this IP');
		$mail_sent_ip = get_option('a3_ip_change_mail_sent');
		$server_ip = A3_Robots_Detection::server_ip();

		if($ip_changed){
			$message1 = "Your IP has changed, please check your Search Engine Visibility (<a href=\"$reading_settings_link\">$reading_settings</a>). <br/>";
			$message2 .= "$confirmation_text. <button id=\"a3_confirm_seo_new_ip\" class=\"button button-primary\">$button_text</button>";			

			$notifications->notification_helper($message1 . $message2, 'warning');	

			if($server_ip != $mail_sent_ip && (current_user_can('administrator') || current_user_can('editor') || current_user_can('manage_woocommerce') ) ) {
				$user = wp_get_current_user();				
				$headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail($user->user_email, "The IP address on site " . get_site_url() . " has changed", $message1, $headers);

				update_option('a3_ip_change_mail_sent', $server_ip);
			}
		}			
	}

	/**
	 * Acknowledge IP address change, stop nagging about search engine visibility setting
	 *
	 * @since    1.0.0
	 */
	public function a3_confirm_seo_new_ip() {		
		$server_ip = file_get_contents("http://ipecho.net/plain");

		update_option('a3_server_ip', $server_ip); 
		
		$transientName = 'a3_robots_notification_'.get_current_user_id();

		// delete notifications
		delete_transient($transientName);	
		
		wp_send_json(['success' => true]);
	}

}

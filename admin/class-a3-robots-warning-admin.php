<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/admin
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning_Admin {

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
		 * defined in A3_Robots_Warning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The A3_Robots_Warning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/a3-robots-warning-admin.css', array(), $this->version, 'all' );

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
		 * defined in A3_Robots_Warning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The A3_Robots_Warning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/a3-robots-warning-admin.js' );

		$params = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'ajax_nonce' => wp_create_nonce('a3-nononcense-a3'),
		  );
		wp_localize_script( $this->plugin_name, 'a3_ajax_object', $params );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/a3-robots-warning-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Check IP function
	 *
	 * @since    1.0.0
	 */
	public function check_ip() {				
		$notifications = new A3_Robots_Warning_Notifications();
		$ip_changed = A3_Robots_Warning_Detection::has_ip_changed();	
		$server_ip = A3_Robots_Warning_Detection::server_ip();	
		$reading_settings_link = admin_url('options-reading.php');
		$reading_settings = __('Reading Settings');
		$blog_public = get_option('blog_public');
		$confirmation_text = __("I confirm, this server <b>" . ($blog_public == 0 ? "should not" : "should") . "</b> be public" );
		$button_text = __('Confirm this IP');
		$mail_sent_ip = get_option('a3rw_ip_change_mail_sent');			

		$message1 = "This site's server IP address has changed, please check your Search Engine Visibility (<a href=\"$reading_settings_link\">$reading_settings</a>). <br/>";
		$message2 = "$confirmation_text. <button id=\"a3rw_confirm_seo_new_ip\" class=\"button button-primary\">$button_text</button>";			
		
		A3_Robots_Warning_Logging::write_log(json_encode(["check_ip()"=>$ip_changed]));					

		if( $ip_changed ){			
			$notifications->create_notification($message1 . $message2, 'warning');				
		}				

		if( $server_ip && $server_ip != $mail_sent_ip ) {
			$admin_email = get_bloginfo('admin_email');				
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$message1 .= "<br>Old IP: " . $mail_sent_ip . ", New IP: " . $server_ip;

			wp_mail($admin_email, "The IP address on site " . get_site_url() . " has changed", $message1, $headers);

			update_option('a3rw_ip_change_mail_sent', $server_ip);
		}
	}

	/**
	 * Admin Init function
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {			
		$cron_scheduled = get_option('a3rw_check_ip_cron');		
		
		if($cron_scheduled == 0){
			$this->check_ip();
			update_option('a3rw_check_ip_cron', 1);
		}						
	}

	/**
	 * Acknowledge IP address change, stop nagging about search engine visibility setting
	 *
	 * @since    1.0.0
	 */
	public function confirm_seo_new_ip() {				
		check_ajax_referer( 'a3-nononcense-a3', 'security' );

		$server_ip = A3_Robots_Warning_Detection::server_ip();
		
		update_option('a3rw_server_ip', $server_ip); 
		
		$optionName = 'a3rw_robots_notification'; // . get_current_user_id();

		// delete notifications
		delete_option($optionName);				

		wp_send_json(['success' => $server_ip != null]);
	}

	public function add_custom_cron_schedule( $schedules ) {
		$schedules['every_minute'] = array(
		  'interval' => 60,
		  'display' => __( 'Every minute', $this->plugin_name )
		);
	  
		return $schedules;
	  }

}

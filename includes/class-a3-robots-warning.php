<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://appandapp.net/isvaljek
 * @since      1.0.0
 *
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    A3_Robots_Warning
 * @subpackage A3_Robots_Warning/includes
 * @author     Ivan Švaljek <ivan.svaljek@gmail.com>
 */
class A3_Robots_Warning {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      A3_Robots_Warning_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'A3_ROBOTS_WARNING_VERSION' ) ) {
			$this->version = A3_ROBOTS_WARNING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'a3-robots-warning';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - A3_Robots_Warning_Loader. Orchestrates the hooks of the plugin.
	 * - A3_Robots_Warning_i18n. Defines internationalization functionality.
	 * - A3_Robots_Warning_Admin. Defines all hooks for the admin area.
	 * - A3_Robots_Warning_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-a3-robots-warning-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-a3-robots-warning-i18n.php';

		/**
		 * The class responsible for site movement detection
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/detection/class-a3-robots-warning-detection.php';

		/**
		 * The class responsible for admin notifications
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/notifications/class-a3-robots-warning-notifications.php';

		/**
		 * The class responsible for logging
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/logging/class-a3-robots-warning-logging.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-a3-robots-warning-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-a3-robots-warning-public.php';

		$this->loader = new A3_Robots_Warning_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the A3_Robots_Warning_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new A3_Robots_Warning_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new A3_Robots_Warning_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_notify = new A3_Robots_Warning_Notifications( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'add_custom_cron_schedule' );				
		$this->loader->add_action( 'a3rw_check_ip', $plugin_admin, 'check_ip' );		
		//$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_a3rw_confirm_seo_new_ip', $plugin_admin, 'confirm_seo_new_ip' );
		$this->loader->add_action( 'admin_notices', $plugin_notify, 'ip_change_admin_notice_handler' );
		$this->loader->add_action( 'updated_option', $plugin_notify, 'option_updated', 10, 3);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new A3_Robots_Warning_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();		
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    A3_Robots_Warning_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

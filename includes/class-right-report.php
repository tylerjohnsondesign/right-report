<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rightreport.com
 * @since      1.0.0
 *
 * @package    Right_Report
 * @subpackage Right_Report/includes
 */
class Right_Report {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Right_Report_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		if ( defined( 'RIGHT_REPORT_VERSION' ) ) {
			$this->version = RIGHT_REPORT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'right-report';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_customer_hooks();
		$this->define_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Right_Report_Loader. Orchestrates the hooks of the plugin.
	 * - Right_Report_i18n. Defines internationalization functionality.
	 * - Right_Report_Admin. Defines all hooks for the admin area.
	 * - Right_Report_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Loader.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-right-report-loader.php';

		/**
		 * Internationalization functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-right-report-i18n.php';

		/**
		 * Admin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		/**
		 * Public.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';

		/**
		 * Customers.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-customer.php';

		/**
		 * API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-api.php';

		/**
		 * Helpers.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-right-report-helpers.php';

		/**
		 * Widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-widget.php';

		$this->loader = new Right_Report_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Right_Report_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Right_Report_i18n();

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

		$plugin_admin = new Right_Report_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_rr_register_user', $plugin_admin, 'rr_register_user' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'rr_add_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'rr_save_metabox' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Right_Report_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the customer functionality.
	 * 
	 * @since	1.0.0
	 * @access	private
	 */
	private function define_customer_hooks() {

		$plugin_customer = new Right_Report_Customer( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_create_customer', $plugin_customer, 'create_customer' );

	}

	/**
	 * Register all of the hooks related to the API functionality.
	 * 
	 * @since	1.0.0
	 * @access	private
	 */
	private function define_api_hooks() {

		$plugin_api = new Right_Report_API( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'save_post', $plugin_api, 'prepare_post', 10, 2 );
		$this->loader->add_action( 'wp_trash_post', $plugin_api, 'trash_post', 1, 1 );

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
	 * @return    Right_Report_Loader    Orchestrates the hooks of the plugin.
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

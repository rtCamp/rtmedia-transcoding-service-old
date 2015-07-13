<?php
/*
 * This is file is main plugin class.
 * @since 1.0
 * @package     RTMedia_Transcoding
 * @subpackage  RTMedia_Transcoding/includes
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
 * @since      1.0
 * @package    RTMedia_Transcoding
 * @subpackage RTMedia_Transcoding/includes
 * @author     Ritesh Patel <ritesh.patel@rtcamp.com>
 */
class RTMedia_Transcoding {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      RTMedia_Transcoding_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;


	/*
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since   1.0
	 */
	public function __construct() {
		$this->plugin_name = 'rtmedia-transcoding';
		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	/*
	 * Load the required dependencies for this plugin.
	 *
	 * @since   1.0
	 * @access  private
	 */
	private function load_dependencies() {

		require_once( RTMEDIA_TRANSCODING_PATH . 'includes/rtmedia-transcoding-functions.php' );
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once( RTMEDIA_TRANSCODING_PATH . 'includes/class-rtmedia-transcoding-loader.php' );

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once( RTMEDIA_TRANSCODING_PATH . 'admin/class-rtmedia-transcoding-admin.php' );

		/*
		 * The class responsible for settings page content
		 */
		require_once( RTMEDIA_TRANSCODING_PATH . 'admin/class-rtmedia-transcoding-admin-settings.php' );

		/*
		 * The class responsible for all the core functionality
		 */
		require_once( RTMEDIA_TRANSCODING_PATH . 'includes/class-rtmedia-transcoding-process.php' );

		$this->loader = new RTMedia_Transcoding_Loader();
	}

	/*
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new RTMedia_Transcoding_Admin( $this->get_plugin_name(), RTMEDIA_TRANSCODING_VERSION );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'rtmedia_transcoding_add_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin->settings_page, 'save_api_key' );
		$this->loader->add_action( 'wp_ajax_rtmedia_free_encoding_subscribe', $plugin_admin->settings_page, 'free_encoding_subscribe' );
		$this->loader->add_action( 'wp_ajax_rtm_disable_transcoding', $plugin_admin->settings_page, 'disable_encoding' );
		$this->loader->add_action( 'wp_ajax_rtm_enable_transcoding', $plugin_admin->settings_page, 'enable_encoding' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	private function define_process_hooks(){
		$process = new RTMedia_Transcoding_Process();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    RTMedia_Transcoding_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
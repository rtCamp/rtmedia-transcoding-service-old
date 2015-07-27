<?php

/*
 * The admin-specific functionality of the plugin.
 *
 * @since 1.0
 *
 * @package     RTMedia_Transcoding
 * @subpackage RTMedia_Transcoding/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RTMedia_Transcoding
 * @subpackage RTMedia_Transcoding/admin
 * @author     Ritesh Patel <ritesh.patel@rtcamp.com>
 */
class RTMedia_Transcoding_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/*
	 * Settings page class object
	 *
	 * @since   1.0
	 * @access  public
	 * @var     object @settings_page
	 */
	public $settings_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string $plugin_name The name of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_page = new RTMedia_Transcoding_Admin_Settings();
	}

	/*
	 * Add plugin settings page
	 *
	 * @since   1.0
	 */
	public function rtmedia_transcoding_add_page() {
		add_options_page( __( 'Transcoding Settings', RTMEDIA_TRANSCODING_TEXT_DOMAIN ), __( 'Transcoding Settings', RTMEDIA_TRANSCODING_TEXT_DOMAIN ), 'manage_options', 'rtmedia-transcoding-settings', array( $this, 'settings_page' ) );
	}

	/*
	 * admin_init functions
	 * register settings and add settings sections and fields
	 *
	 * @since   1.0
	 */
	public function admin_init() {

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && constant( 'SCRIPT_DEBUG' ) === true ) ? '' : '.min';
		wp_enqueue_style( $this->plugin_name, RTMEDIA_TRANSCODING_URL . 'admin/css/rtmedia-transcoding-admin' . $suffix . '.css', array(), $this->version );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && constant( 'SCRIPT_DEBUG' ) === true ) ? '' : '.min';
		wp_enqueue_script( $this->plugin_name, RTMEDIA_TRANSCODING_URL . 'admin/js/rtmedia-transcoding-admin' . $suffix . '.js', array( 'jquery' ), $this->version, false );
	}

	/*
	 * Settings page content
	 *
	 * @since   1.0
	 * @access  public
	 */
	public function settings_page() {
		$this->settings_page->render();
	}
}

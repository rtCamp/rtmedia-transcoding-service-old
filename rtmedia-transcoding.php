<?php

/*
  Plugin Name: rtMedia Transcoding
  Plugin URI: http://rtcamp.com/rtmedia-transcoding/?utm_source=dashboard&utm_medium=plugin&utm_campaign=rtmedia-transcoding
  Description: This plugin will transcode audio and video files into suitable format for web browsers.
  Version: 0.1
  Author: rtCamp
  Text Domain: rtmedia
  Author URI: http://rtcamp.com/?utm_source=dashboard&utm_medium=plugin&utm_campaign=rtmedia-transcoding
 */

/*
 * Plugin main file
 * @package RTMedia_Transcoding
 * @since   1.0
 */

if( !defined( 'ABSPATH' ) ){
	die();
}

if ( ! defined( 'RTMEDIA_TRANSCODING_PATH' ) ) {

	/**
	 * Define server file system path to the plugin directory
	 *
	 * @since 1.0
	 */
	define( 'RTMEDIA_TRANSCODING_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_URL' ) ) {

	/**
	 * Define url to the plugin directory
	 *
	 * @since 1.0
	 */
	define( 'RTMEDIA_TRANSCODING_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_BASE_NAME' ) ) {

	/**
	 * Define base name of plugin
	 *
	 * @since 1.0
	 */
	define( 'RTMEDIA_TRANSCODING_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_VERSION' ) ) {

	/**
	 * Define base name of plugin
	 *
	 * @since 1.0
	 */
	define( 'RTMEDIA_TRANSCODING_VERSION', '0.1' );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_TEXT_DOMAIN' ) ) {

	/**
	 * Define base name of plugin
	 *
	 * @since 1.0
	 */
	define( 'RTMEDIA_TRANSCODING_TEXT_DOMAIN', 'rtmedia-transcoding' );
}


/*
 * include plugin main class file
 */
require_once RTMEDIA_TRANSCODING_PATH . 'includes/class-rtmedia-transcoding.php';

/*
 * Let's rock n roll !
 *
 * @since 1.0
 */
function run_rtmedia_transcoding() {
	$plugin = new RTMedia_Transcoding();
	$plugin->run();
}

run_rtmedia_transcoding();
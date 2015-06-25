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

if ( ! defined( 'RTMEDIA_TRANSCODING_PATH' ) ) {

	/**
	 *  The server file system path to the plugin directory
	 *
	 */
	define( 'RTMEDIA_TRANSCODING_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_URL' ) ) {

	/**
	 * The url to the plugin directory
	 *
	 */
	define( 'RTMEDIA_TRANSCODING_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'RTMEDIA_TRANSCODING_BASE_NAME' ) ) {

	/**
	 * The url to the plugin directory
	 *
	 */
	define( 'RTMEDIA_TRANSCODING_BASE_NAME', plugin_basename( __FILE__ ) );
}
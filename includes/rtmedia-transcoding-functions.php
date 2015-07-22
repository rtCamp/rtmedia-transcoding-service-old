<?php

/*
 * Define all the helper functions
 *
 * @since       1.0
 * @package     RTMedia_Transcoding
 * @subpackage  RTMedia_Transcoding/includes
 * @author      Ritesh Patel <ritesh.patel@rtcamp.com>
 */

/*
 * Get plugin option
 *
 * @since   1.0
 * @param   string  $option_key
 * @param   mixed   $default
 *
 * @return  mixed
 */
function rtmedia_transcoding_get_option( $option_key, $default = false ){
	return get_site_option( $option_key, $default );
}

/*
 * Update plugin option
 *
 * @since   1.0
 * @param   string  $option_key
 * @param   string  $option_value
 *
 * @return boolean
 */
function rtmedia_transcoding_update_option( $option_key, $option_value ){
	return update_site_option( $option_key, $option_value );
}

/*
 * Get API key ID for transcoding service
 *
 * @since   1.0
 *
 * @return  string
 */
function rtmedia_transcoding_get_api_key_id(){
	return 'rtmedia-encoding-api-key';
}

/*
 * Get API key for transcoding service
 *
 * @since   1.0
 * @param   mixed   $default
 *
 * @return  mixed
 */
function rtmedia_transcoding_get_api_key( $default = false ){
	return rtmedia_transcoding_get_option( rtmedia_transcoding_get_api_key_id(), $default );
}

/*
 * Get API key for transcoding service
 *
 * @since   1.0
 * @param   mixed   $default
 *
 * @return  mixed
 */
function rtmedia_transcoding_update_api_key( $key = '' ){
	return rtmedia_transcoding_update_option( rtmedia_transcoding_get_api_key_id(), $key );
}

/*
 * Get meta key of video thumb
 *
 * @since   1.0
 *
 * @return  string
 */
function rtmedia_transcoding_get_video_thumb_meta_key(){
	return 'rtmedia_transcoding_thumbs';
}

/*
 * Get thumbnails of video
 *
 * @since   1.0
 * @param   integer $post_id
 *
 * @return  array
 */
function rtmedia_transcoding_get_video_thumbs( $post_id ){
	return maybe_unserialize( get_post_meta( $post_id, rtmedia_transcoding_get_video_thumb_meta_key(), true ) );
}
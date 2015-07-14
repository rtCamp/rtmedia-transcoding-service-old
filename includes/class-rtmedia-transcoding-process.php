<?php

/*
 * This file will hold the main functionality of the plugin.
 *
 * @since       1.0
 * @package     RTMedia_Transcoding
 * @subpackage  RTMedia_Transcoding/includes
 */

/*
 * This file will handle all the processing for file transcoding.
 *
 * @package     RTMedia_Transcoding
 * @subpackage  RTMedia_Transcoding/includes
 * @author      Ritesh Patel <ritesh.patel@rtcamp.com>
 */

class RTMedia_Transcoding_Process {

	/*
	 * URL of transcoding service
	 *
	 * @since   1.0
	 * @access  protected
	 */

	protected $api_url = 'http://api.rtcamp.com/';

	/*
	 * Send file to transcoding server
	 *
	 * @since   1.0
	 */
	function do_transcoding( $data, $post_id ) {

		// get api key
		$api_key = rtmedia_transcoding_get_api_key();
		if ( $api_key ) {

			// check for usage quota
			if ( $this->is_under_usage_quota( $api_key ) ) {

				// get file and mime type
				$attchment_url = wp_get_attachment_url( $post_id );
				$mime_type = get_post_mime_type( $post_id );

				$file_exploded = explode( '.', $attchment_url );
				$file_type = $file_exploded[ sizeof( $file_exploded ) - 1 ];

				$black_list_types = array( 'mp3', );
				$black_list_mime_types = array( 'audio/mp3', );

				preg_match( '/video|audio/i', $mime_type, $type_array );

				// check whether current file is valid or not to do transcoding
				if ( ! empty( $type_array ) && ! in_array( $file_type, $black_list_types ) && ! in_array( $mime_type, $black_list_mime_types ) ) {
					$format = ( $type_array[ 0 ] == 'video' ) ? 'mp4' : 'mp3';
					$total_thumbs = 3;  // todo provide admin option for this

					// build parameters to send
					$query_args = array(
						'url' =>            urlencode( $attchment_url ),       // Public URL of media file
						'callbackurl' =>    urlencode( trailingslashit( home_url() ) . "index.php" ),  // callback URL to send transcoded file
						'force' =>          0,
						'formats' =>        $format,  // format in which file need to convert
						'thumbs' =>         $total_thumbs,   // number of thumbs to generate for videos
						'rt_id' =>          $post_id   // WordPress post id of attachment
					);
					$transoding_url = $this->api_url . 'job/new/';
					$upload_url = add_query_arg( $query_args, $transoding_url . $api_key );

					// send file to server
					$upload_res = wp_remote_get( $upload_url );

					// save response in post meta
					if ( ! is_wp_error( $upload_res ) && wp_remote_retrieve_header( $upload_res, 'status' ) == '200' ) {
						$upload_info = wp_remote_retrieve_body( $upload_res );
						if ( isset( $upload_info->status ) && $upload_info->status && isset( $upload_info->job_id ) && $upload_info->job_id ) {
							$job_id = $upload_info->job_id;
							update_post_meta( $post_id, 'rtmedia-encoding-job-id', $job_id );
						}
					}

					// update usage quota
					$this->update_usage_quota( $api_key );
				}
			}
		}

		return $data;
	}

	/*
	 * Save transcoded file from transcoding server and update post meta
	 *
	 * @since   1.0
	 */
	function handle_callback() {

	}

	/*
	 * Check current usage quota
	 *
	 * @since   1.0
	 * @return  boolean
	 */
	function is_under_usage_quota( $api_key = false ) {

		$under_quota = false;
		$usage_info = rtmedia_transcoding_get_option( 'rtmedia-encoding-usage' );

		if ( ! $api_key ) {
			$api_key = rtmedia_transcoding_get_api_key();
		}

		if ( isset( $usage_info[ $api_key ]->status ) && $usage_info[ $api_key ]->status ) {
			if ( isset( $usage_info[ $api_key ]->remaining ) && $usage_info[ $api_key ]->remaining > 0 ) {
				$under_quota = true;
			}
		}

		return $under_quota;
	}

	/*
	 * Update transcoding service usage quota
	 *
	 * @since   1.0
	 * @return string
	 */
	function update_usage_quota( $api_key = false ) {
		if ( ! $api_key ) {
			$api_key = rtmedia_transcoding_get_api_key();
		}

		$usage_url = trailingslashit( $this->api_url ) . 'api/usage/' . $api_key;
		$usage_res = wp_remote_get( $usage_url, array( 'timeout' => 20 ) );

		if ( ! is_wp_error( $usage_res ) && wp_remote_retrieve_header( $usage_res, 'status' ) == '200' ) {
			$usage_info = wp_remote_retrieve_body( $usage_res );
		} else {
			$usage_info = NULL;
		}

		update_site_option( 'rtmedia-encoding-usage', array( $api_key => $usage_info ) );

		return $usage_info;
	}
}
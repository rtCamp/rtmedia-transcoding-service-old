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
	 * It will be in use while handling the callback from transcoding server to store the already attached file info
	 *
	 * @since   1.0
	 * @access  public
	 */
	public $post_obj;

	/*
	 * Send file to transcoding server
	 *
	 * @since   1.0
	 */
	public function do_transcoding( $data, $post_id ) {

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
	public function handle_callback() {
		// Check if request is from transcoding server or not
		if ( isset( $_REQUEST[ 'job_id' ] ) && isset( $_REQUEST[ 'rt_id' ] ) && isset( $_REQUEST[ 'download_url' ] ) ) {

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$post_id = $_REQUEST[ 'rt_id' ];

			//todo check for valid url
			//todo why use "urldecode" 2 times? Need to look into transcoding server response
			$download_url = urldecode( urldecode( $_REQUEST[ 'download_url' ] ) );
			$thumbs = ( isset( $_REQUEST[ 'thumbs' ] ) ) ? $_REQUEST[ 'thumbs' ] : array();
			$request_type = isset( $_REQUEST[ 'format' ] ) ? $_REQUEST[ 'format' ] : false;
			$file_bits = false;

			// remove all filters for attachment url and attachment file
			remove_all_filters( 'wp_get_attachment_url' );
			remove_all_filters( 'get_attached_file' );

			// save post object in member variable for future reference
			$this->post_obj = get_post( $post_id );
			$this->post_obj->attached_file = get_attached_file( $post_id );
			$this->post_obj->file_url = wp_get_attachment_url( $post_id );

			// save thumbs
			$this->save_media_thumbnails( $post_id, $thumbs );

			// If request is for thumb than no need to proceed
			if ( $request_type && $request_type == 'thumbnails' ) {
				die();
			}
			// get file type and path info
			$file_type = wp_check_filetype( $download_url );
			$file_path_info = pathinfo( $download_url );

			// download transcoded file
			try {
				$file_bits = file_get_contents( $download_url );
			} catch( Exception $e ) {
				$flag = $e->getMessage();
			}

			if ( $file_bits ) {
				// delete attached file
				$this->delete_old_attached_file( $post_id );
				// change upload dir
				add_filter( 'upload_dir', array( $this, 'modify_upload_dir' ) );

				// upload the file
				$upload_info = wp_upload_bits( $file_path_info[ 'basename' ], null, $file_bits );

				// update post
				$post_update_array = array(
					'ID' => $post_id,
					'guid' => $upload_info[ 'url' ],
					'post_mime_type' => $file_type[ 'type' ],
				);
				wp_update_post( $post_update_array );

				// save video thumbs and update post meta
				$old_wp_attached_file = get_post_meta( $post_id, '_wp_attached_file', true );
				$old_wp_attached_file_pathinfo = pathinfo( $old_wp_attached_file );
				update_post_meta( $post_id, '_wp_attached_file', str_replace( $old_wp_attached_file_pathinfo[ 'basename' ], $file_path_info[ 'basename' ], $old_wp_attached_file ) );

				// remove upload_dir filter added previously
				remove_filter( 'upload_dir', array( $this, 'modify_upload_dir' ) );

				do_action( 'after_rtmedia_transcoding_done', $post_id );
			} else {
				error_log( 'rtMedia Transcoding: Could not read the file: ' . $post_id );
			}

			// update usage quota
			$this->update_usage_quota();
		}
	}

	/*
	 * Check current usage quota
	 *
	 * @since   1.0
	 * @return  boolean
	 */
	public function is_under_usage_quota( $api_key = false ) {

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
	public function update_usage_quota( $api_key = false ) {
		if ( ! $api_key ) {
			$api_key = rtmedia_transcoding_get_api_key();
		}

		$usage_url = trailingslashit( $this->api_url ) . 'api/usage/' . $api_key;
		$usage_res = wp_remote_get( $usage_url, array( 'timeout' => 20 ) );

		//todo why need to check for both the "200" and "200 OK" header status ?
		if ( ! is_wp_error( $usage_res )
			&& ( wp_remote_retrieve_header( $usage_res, 'status' ) == '200' || wp_remote_retrieve_header( $usage_res, 'status' ) == '200 OK' )
		) {
			$usage_info = json_decode( wp_remote_retrieve_body( $usage_res ) );
		} else {
			$usage_info = NULL;
		}

		update_site_option( 'rtmedia-encoding-usage', array( $api_key => $usage_info ) );

		return $usage_info;
	}

	/*
	 * Delete attached file
	 *
	 * @since   1.0
	 */
	public function delete_old_attached_file( $post_id ) {

		// Call of action before deleting attached file
		do_action( 'rtmedia_transcoding_before_delete_attached_file', $post_id );

		unlink( $this->post_obj->attached_file );

		// Call of action after deleting attached file
		do_action( 'rtmedia_transcoding_after_delete_attached_file', $post_id );
	}

	/*
	 * Modify upload directory as per the old file
	 * Hooked into 'upload_dir'
	 *
	 * @since   1.0
	 * @return  string
	 */
	public function modify_upload_dir( $up_dir ) {
		/*
		 * replace path and url with the new value
		 * Basically, remove file name from path and URL
		 */
		$up_dir[ 'path' ] = str_replace( basename( $this->post_obj->attached_file ), '', $this->post_obj->attached_file );
		$up_dir[ 'url' ] = str_replace( basename( $this->post_obj->file_url ), '', $this->post_obj->file_url );

		return $up_dir;
	}

	/*
	 * Save thumbnails of media from transcoding server
	 *
	 * @since   1.0
	 */
	public function save_media_thumbnails( $post_id, $thumbs ) {
		// those thumbs may be in serialize form
		$thumbs = maybe_unserialize( $thumbs );

		if ( ! empty( $thumbs ) && is_array( $thumbs ) ) {

			$post_thumbs = array();

			// loop through each thumb and save them
			foreach( $thumbs as $single_thumb ) {

				// get thumb from remote
				$remote_thumb = wp_remote_get( $single_thumb );
				$thumb_body = wp_remote_retrieve_body( $remote_thumb );

				// generate thumb file name
				$thumb_info = pathinfo( $single_thumb );
				$thumb_file_name = basename( urldecode( $thumb_info[ 'basename' ] ) );

				// upload thumb file
				$thumb_upload_info = wp_upload_bits( $thumb_file_name, null, $thumb_body );

				$post_thumbs[] = $thumb_upload_info[ 'url' ];
			}

			// save media thumb details into post meta
			update_post_meta( $post_id, 'rtmedia_transcoding_thumbs', $post_thumbs );
		}
	}
}
<?php

/*
 * Admin settings page for transcoding
 *
 * @since 1.0
 *
 * @package     RTMedia_Transcoding
 * @subpackage  RTMedia_Transcoding/admin
 * @author      Ritesh Patel <ritesh.patel@rtcamp.com>
 */


class RTMedia_Transcoding_Admin_Settings {

	/**
	 * API url to which file will sent for transcoding
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $api_url = 'http://api.rtcamp.com/';

	/**
	 * Paypal sandbox testing flag
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $sandbox_testing = 0;

	/**
	 * Paypal metchant email id
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $merchant_id = 'paypal@rtcamp.com';

	/**
	 * API key for transcoding service
	 *
	 * @since   1.0
	 * @access  public
	 */
	public $api_key = false;

	/**
	 * Stored API key used for reference purpose
	 */
	public $stored_api_key = false;


	/**
	 * Initialize class variables
	 */
	public function __construct() {
		$this->api_key = rtmedia_transcoding_get_api_key();
		$this->stored_api_key = rtmedia_transcoding_get_option( 'rtmedia-encoding-api-key-stored' );

		if ( $this->api_key ) {
			// store api key as different db key if user disable encoding service
			if ( ! $this->stored_api_key ) {
				$this->stored_api_key = $this->api_key;
				rtmedia_transcoding_update_option( 'rtmedia-encoding-api-key-stored', $this->stored_api_key );
			}
		}
	}

	/**
	 * Render settings page
	 */
	public function render() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Audio/Video encoding service', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></h2>

			<div class="wrap rtm-transcoding-settings">
				<?php
				wp_nonce_field( 'rtm_transcoding_settings_nonce', 'rtm_transcoding_settings_nonce' );
				?>
				<div class="rtm-transcoding-api-key">
					<label for="new-api-key"><?php _e( 'Enter API KEY', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></label>
					<input id="new-api-key" type="text" name="new-api-key" value="<?php echo esc_attr( $this->stored_api_key ); ?>"
					       size="60"/>
					<input type="submit" id="api-key-submit" name="api-key-submit"
					       value="<?php esc_attr_e( 'Save Key', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?>"
					       class="button-primary"/>

					<div class="rtm-transcoding-new-key-spinner spinner"></div>
				</div>

				<div class="rtm-transcoding-key-action">
					<?php
					$enable_btn_style = 'style="display:none;"';
					$disable_btn_style = 'style="display:none;"';
					if ( $this->api_key ) {
						$enable_btn_style = 'style="display:inline-block;"';
					} else if ( $this->stored_api_key ) {
						$disable_btn_style = 'style="display:inline-block;"';
					}
					?>
					<input type="submit" name="rtm-disable-transcoding" value="Disable Transcoding"
					       class="button-secondary rtm-disable-transcoding" <?php echo $enable_btn_style; ?> />
					<input type="submit" name="rtm-enable-transcoding" value="Enable Transcoding"
					       class="button-secondary rtm-enable-transcoding" <?php echo $disable_btn_style; ?> />

					<div class="rtm-enable-disable-spinner-transcoding spinner"></div>
				</div>

				<!-- Results table headers -->
				<table class="fixed widefat rtm-encoding-table">
					<thead>
					<tr>
						<th><?php _e( 'Feature\Plan', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<th><?php _e( 'Free', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<th><?php _e( 'Silver', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<th><?php _e( 'Gold', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<th><?php _e( 'Platinum', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
					</tr>
					</thead>

					<tbody>
					<tr>
						<th><?php _e( 'File Size Limit', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td>200MB (
							<del>20MB</del>
							)
						</td>
						<td colspan="3" class="column-posts">16GB (
							<del>2GB</del>
							)
						</td>
					</tr>
					<tr>
						<th><?php _e( 'Bandwidth (monthly)', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td>10GB (
							<del>1GB</del>
							)
						</td>
						<td>100GB</td>
						<td>1TB</td>
						<td>10TB</td>
					</tr>
					<tr>
						<th><?php _e( 'Overage Bandwidth', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td><?php _e( 'Not Available', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td>$0.10 per GB</td>
						<td>$0.08 per GB</td>
						<td>$0.05 per GB</td>
					</tr>
					<tr>
						<th><?php _e( 'Amazon S3 Support', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td><?php _e( 'Not Available', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td colspan="3"
						    class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
					</tr>
					<tr>
						<th><?php _e( 'HD Profile', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td><?php _e( 'Not Available', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td colspan="3"
						    class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
					</tr>
					<tr>
						<th><?php _e( 'Webcam Recording', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td colspan="4"
						    class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
					</tr>
					<tr>
						<th><?php _e( 'Pricing', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
						<td><?php _e( 'Free', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td><?php _e( '$9/month', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td><?php _e( '$99/month', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
						<td><?php _e( '$999/month', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<td>
						<?php
						$usage_details = get_site_option( 'rtmedia-encoding-usage' );
						if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == 'free' ) ) {
							echo '<button disabled="disabled" type="submit" class="rtm-transcoding-try-now button button-primary">' . __( 'Current Plan', RTMEDIA_TRANSCODING_TEXT_DOMAIN ) . '</button>';
						} else {
							?>
							<form id="rtm-transcoding-try-now-form" method="get">
							<button type="submit"
							        class="rtm-transcoding-try-now button button-primary"><?php _e( 'Try Now', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></button>
							</form><?php
						}
						?>
						</td>
						<td><?php echo $this->encoding_subscription_form( 'silver', 9.0 ) ?></td>
						<td><?php echo $this->encoding_subscription_form( 'gold', 99.0 ) ?></td>
						<td><?php echo $this->encoding_subscription_form( 'platinum', 999.0 ) ?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	<?php
	}

	public function encoding_subscription_form( $name = 'No Name', $price = '0', $force = false ) {
		if ( $this->api_key ) {
			$this->update_usage( $this->api_key );
		}
		$action = $this->sandbox_testing ? 'https://sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$return_page = esc_url( add_query_arg( array( 'page' => 'rtmedia-addons' ), ( is_multisite() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) ) ) );

		$usage_details = get_site_option( 'rtmedia-encoding-usage' );
		if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == strtolower( $name ) ) && $usage_details[ $this->api_key ]->sub_status && ! $force ) {
			$form = '<button data-plan="' . $name . '" data-price="' . $price . '" type="submit" class="button rtm-transcoding-unsubscribe">' . __( 'Unsubscribe', RTMEDIA_TRANSCODING_TEXT_DOMAIN ) . '</button>';
			$form .= '<div class="rtm-transcoding-unsubscribe-spinner spinner"></div>';
			$form .= '<div id="rtm-transcoding-unsubscribe-dialog" title="Unsubscribe">
						  <p>' . __( 'Just to improve our service we would like to know the reason for you to leave us.' ) . '</p>
						  <p><textarea rows="3" id="rtm-transcoding-unsubscribe-note"></textarea></p>
					</div>';
		} else {
			$form = '<form method="post" action="' . esc_attr( $action ). '" class="paypal-button" target="_top">
                        <input type="hidden" name="button" value="subscribe">
                        <input type="hidden" name="item_name" value="' . esc_attr( ucfirst( $name ) ) . '">

                        <input type="hidden" name="currency_code" value="USD">


                        <input type="hidden" name="a3" value="' . esc_attr( $price ) . '">
                        <input type="hidden" name="p3" value="1">
                        <input type="hidden" name="t3" value="M">

                        <input type="hidden" name="cmd" value="_xclick-subscriptions">

                        <!-- Merchant ID -->
                        <input type="hidden" name="business" value="' . esc_attr( $this->merchant_id ) . '">


                        <input type="hidden" name="custom" value="' . esc_attr( $return_page ) . '">

                        <!-- Flag to no shipping -->
                        <input type="hidden" name="no_shipping" value="1">

                        <input type="hidden" name="notify_url" value="' . esc_attr( trailingslashit( $this->api_url ) ) . 'subscribe/paypal">

                        <!-- Flag to post payment return url -->
                        <input type="hidden" name="return" value="' . esc_attr( trailingslashit( $this->api_url ) ) . 'payment/process">


                        <!-- Flag to post payment data to given return url -->
                        <input type="hidden" name="rm" value="2">

                        <input type="hidden" name="src" value="1">
                        <input type="hidden" name="sra" value="1">

                        <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_subscribe_SM.gif" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
                    </form>';
		}
		return $form;
	}

	public function update_usage( $key ) {
		$usage_url = trailingslashit( $this->api_url ) . 'api/usage/' . $key;
		$usage_page = wp_remote_get( $usage_url, array( 'timeout' => 20 ) );
		if ( ! is_wp_error( $usage_page ) ) {
			$usage_info = json_decode( $usage_page['body'] );
		} else {
			$usage_info = null;
		}
		update_site_option( 'rtmedia-encoding-usage', array( $key => $usage_info ) );
		return $usage_info;
	}

	public function save_api_key() {
		if ( current_user_can( 'manage_options' ) ) {
			if ( isset( $_GET['api_key_updated'] ) && $_GET['api_key_updated'] ) {
				if ( is_multisite() ) {
					add_action( 'network_admin_notices', array( $this, 'successfully_subscribed_notice' ) );
				}

				add_action( 'admin_notices', array( $this, 'successfully_subscribed_notice' ) );
			}

			if ( isset( $_GET['apikey'] ) ) {
				$apikey = $this->validate_key( $_GET['apikey'] );

				if ( $apikey && isset( $_GET['page'] ) && ( 'rtmedia-transcoding-settings' === $_GET['page'] ) ) {
					if ( $this->api_key && ! ( isset( $_GET['update'] ) && $_GET['update'] ) ) {
						$unsubscribe_url = trailingslashit( $this->api_url ) . 'api/cancel/' . $this->api_key;
						wp_remote_post( $unsubscribe_url, array( 'timeout' => 120, 'body' => array( 'note' => 'Direct URL Input (API Key: ' . $apikey . ')' ) ) );
					}

					rtmedia_transcoding_update_api_key( $apikey );

					$usage_info = $this->update_usage( $apikey );
					$return_page = esc_url( add_query_arg( array( 'page' => 'rtmedia-addons', 'api_key_updated' => $usage_info->plan->name ), admin_url( 'admin.php' ) ) );

					wp_safe_redirect( esc_url_raw( $return_page ) );

					die();
				}
			}
		}
	}

	/**
	 * Check and validate api key
	 *
	 * @param $key
	 * @return bool|string
	 */
	public function validate_key( $key ) {
		$key = sanitize_text_field( $key );

		if ( $this->is_valid_key( $key ) ) {
			return $key;
		} else {
			return false;
		}
	}

	public function is_valid_key( $key ) {
		$validate_url = trailingslashit( $this->api_url ) . 'api/validate/' . $key;
		$validation_page = wp_remote_get( $validate_url, array( 'timeout' => 20 ) );
		$status = false;
		if ( ! is_wp_error( $validation_page ) ) {
			$validation_info = json_decode( $validation_page['body'] );
			$status = $validation_info->status;
			if ( 'true' === $validation_info->status ) {
				$status = true;
			}
		}
		return $status;
	}

	public function successfully_subscribed_notice() {
		?>
		<div class="updated">
			<p><?php printf( __( 'You have successfully subscribed for the <strong>%s</strong> plan', RTMEDIA_TRANSCODING_TEXT_DOMAIN ), sanitize_text_field( $_GET['api_key_updated'] ) ); ?></p>
		</div>
	<?php
	}

	public function disable_encoding() {
		if ( wp_verify_nonce( $_POST['nonce'], 'rtm_transcoding_settings_nonce' ) ) {
			rtmedia_transcoding_update_api_key( '' );
			_e( 'Transcoding service has been disabled successfully.', RTMEDIA_TRANSCODING_TEXT_DOMAIN );
		} else {
			$this->nonce_verification_fail_message();
		}

		die();
	}

	function enable_encoding() {
		if ( wp_verify_nonce( $_POST['nonce'], 'rtm_transcoding_settings_nonce' ) ) {
			rtmedia_transcoding_update_api_key( $this->stored_api_key );
			_e( 'Transcoding enabled successfully.', RTMEDIA_TRANSCODING_TEXT_DOMAIN );
		} else {
			$this->nonce_verification_fail_message();
		}
		die();
	}

	public function free_encoding_subscribe() {
		if ( wp_verify_nonce( $_POST['nonce'], 'rtm_transcoding_settings_nonce' ) ) {
			$email = get_site_option( 'admin_email' );
			$usage_details = get_site_option( 'rtmedia-encoding-usage' );
			if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == 'free' ) ) {
				echo wp_json_encode( array( 'error' => 'Your free subscription is already activated.' ) );
			} else {
				$free_subscription_url = esc_url_raw( add_query_arg( array( 'email' => urlencode( $email ) ), trailingslashit( $this->api_url ) . 'api/free/' ) );
				if ( $this->api_key ) {
					$free_subscription_url = esc_url_raw( add_query_arg( array( 'email' => urlencode( $email ), 'apikey' => $this->api_key ), $free_subscription_url ) );
				}
				$free_subscribe_page = wp_remote_get( $free_subscription_url, array( 'timeout' => 120 ) );
				if ( ! is_wp_error( $free_subscribe_page ) && ( ! isset( $free_subscribe_page['headers']['status'] ) || ( isset( $free_subscribe_page['headers']['status'] ) && ( 200 === $free_subscribe_page['headers']['status'] ) ) ) ) {
					$subscription_info = json_decode( $free_subscribe_page['body'] );
					if ( isset( $subscription_info->status ) && $subscription_info->status ) {
						echo wp_json_encode( array( 'apikey' => $subscription_info->apikey ) );
					} else {
						echo wp_json_encode( array( 'error' => $subscription_info->message ) );
					}
				} else {
					echo wp_json_encode( array( 'error' => __( 'Something went wrong please try again.' ) ) );
				}
			}
		} else {
			echo wp_json_encode( array( 'error' => $this->nonce_verification_fail_message() ) );
		}
		die();
	}

	/**
	 * Nonce verification fail message
	 *
	 * @since   1.0
	 */
	public function nonce_verification_fail_message() {
		return __( 'Cheating huh !', RTMEDIA_TRANSCODING_TEXT_DOMAIN );
	}

	/**
	 * Unsubscribe transcoding service
	 *
	 * @since   1.0
	 */
	public function unsubscribe_service() {
		$res_array = array( array( 'error' => __( 'Something went wrong please try again.', RTMEDIA_TRANSCODING_TEXT_DOMAIN ) ) );
		if ( wp_verify_nonce( $_GET['nonce'], 'rtm_transcoding_settings_nonce' ) && current_user_can( 'manage_options' ) ) {
			$note = sanitize_text_field( sanitize_text_field( $_GET['note'] ) );
			$unsubscribe_url = trailingslashit( $this->api_url ) . 'api/cancel/' . $this->api_key;
			$unsubscribe_page = wp_remote_post( $unsubscribe_url, array( 'timeout' => 120, 'body' => array( 'note' => $note ) ) );
			if ( ! is_wp_error( $unsubscribe_page ) && ( ! isset( $unsubscribe_page['headers']['status'] ) || ( isset( $unsubscribe_page['headers']['status'] ) && ( 200 === $unsubscribe_page['headers']['status'] ) ) ) ) {
				$subscription_info = wp_json_encode( $unsubscribe_page['body'] );
				$plan = sanitize_text_field( $_GET['plan'] );
				$price = intval( $_GET['price'] );
				if ( isset( $subscription_info->status ) && $subscription_info->status ) {
					$res_array = array(
						'updated' => __( 'Your subscription was cancelled successfully', RTMEDIA_TRANSCODING_TEXT_DOMAIN ),
						'form' => $this->encoding_subscription_form( $plan, $price ),
					);
				}
			}
		} else {

		}
		echo wp_json_encode( $res_array );
		die();
	}

}

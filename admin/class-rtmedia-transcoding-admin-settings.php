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

/*
 *
 */

class RTMedia_Transcoding_Admin_Settings {

	/*
	 * API url to which file will sent for transcoding
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $api_url = 'http://api.rtcamp.com/';

	/*
	 * Paypal sandbox testing flag
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $sandbox_testing = 0;

	/*
	 * Paypal metchant email id
	 *
	 * @since   1.0
	 * @access  protected
	 */
	protected $merchant_id = 'paypal@rtcamp.com';

	/*
	 * API key for transcoding service
	 *
	 * @since   1.0
	 * @access  public
	 */
	public $api_key = false;

	/*
	 * Stored API key used for reference purpose
	 */
	public $stored_api_key = false;


	/*
	 * Initialize class variables
	 */
	public function __construct() {
		$this->api_key = get_site_option( 'rtmedia-encoding-api-key' );
		$this->stored_api_key = get_site_option( 'rtmedia-encoding-api-key-stored' );

		if ( $this->api_key ) {
			// store api key as different db key if user disable encoding service
			if ( ! $this->stored_api_key ) {
				$this->stored_api_key = $this->api_key;
				update_site_option( 'rtmedia-encoding-api-key-stored', $this->stored_api_key );
			}
		}
	}

	/*
	 * Render settings page
	 */
	public function render() {
		?>
		<h2><?php _e( 'Audio/Video encoding service', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></h2>
		<div class="wrap rtm-transcoding-settings">
			<div>
				<label for="new-api-key"><?php _e( 'Enter API KEY', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></label>
				<input id="new-api-key" type="text" name="new-api-key" value="<?php echo $this->stored_api_key; ?>" size="60"/>
				<input type="submit" id="api-key-submit" name="api-key-submit" value="<?php echo __( 'Save Key', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?>" class="button-primary"/>
				<div class="rtm-transcoding-new-key-spinner spinner"></div>
			</div>

			<div>
				<?php
				$enable_btn_style = 'style="display:none;"';
				$disable_btn_style = 'style="display:none;"';
				if ( $this->api_key ) {
					$enable_btn_style = 'style="display:inline-block;"';
				} else if ( $this->stored_api_key ) {
					$disable_btn_style = 'style="display:inline-block;"';
				}
				?>
				<input type="submit" name="rtm-disable-transcoding" value="Disable Transcoding" class="button-secondary rtm-disable-transcoding" <?php echo $enable_btn_style; ?> />
				<input type="submit" name="rtm-enable-transcoding" value="Enable Transcoding" class="button-secondary rtm-enable-transcoding" <?php echo $disable_btn_style; ?> />
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
					<td>200MB ( <del>20MB</del> ) </td>
					<td colspan="3" class="column-posts">16GB ( <del>2GB</del> ) </td>
				</tr>
				<tr>
					<th><?php _e( 'Bandwidth (monthly)', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
					<td>10GB ( <del>1GB</del> ) </td>
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
					<td colspan="3" class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
				</tr>
				<tr>
					<th><?php _e( 'HD Profile', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
					<td><?php _e( 'Not Available', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
					<td colspan="3" class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
				</tr>
				<tr>
					<th><?php _e( 'Webcam Recording', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></th>
					<td colspan="4" class="column-posts"><?php _e( 'Coming Soon', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></td>
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
					<td><?php
						$usage_details = get_site_option( 'rtmedia-encoding-usage' );
						if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == 'free' ) ) {
							echo '<button disabled="disabled" type="submit" class="rtm-transcoding-try-now button button-primary">' . __( 'Current Plan', RTMEDIA_TRANSCODING_TEXT_DOMAIN ) . '</button>';
						} else {
							?>
							<form id="rtm-transcoding-try-now-form" method="get">
							<button type="submit"
							        class="rtm-transcoding-try-now button button-primary"><?php _e( 'Try Now', RTMEDIA_TRANSCODING_TEXT_DOMAIN ); ?></button>
							</form><?php }
						?>
					</td>
					<td><?php echo $this->encoding_subscription_form( 'silver', 9.0 ) ?></td>
					<td><?php echo $this->encoding_subscription_form( 'gold', 99.0 ) ?></td>
					<td><?php echo $this->encoding_subscription_form( 'platinum', 999.0 ) ?></td>
				</tr>
				</tbody>
			</table>
		</div>
	<?php
	}

	public function encoding_subscription_form( $name = 'No Name', $price = '0', $force = false ) {
		if ( $this->api_key ){
			$this->update_usage( $this->api_key );
		}
		$action = $this->sandbox_testing ? 'https://sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$return_page = esc_url( add_query_arg( array( 'page' => 'rtmedia-addons' ), ( is_multisite() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) ) ) );

		$usage_details = get_site_option( 'rtmedia-encoding-usage' );
		if ( isset( $usage_details[ $this->api_key ]->plan->name )
			&& ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == strtolower( $name ) )
			&& $usage_details[ $this->api_key ]->sub_status && ! $force ) {
			$form = '<button data-plan="' . $name . '" data-price="' . $price . '" type="submit" class="button bpm-unsubscribe">' . __( 'Unsubscribe', RTMEDIA_TRANSCODING_TEXT_DOMAIN ) . '</button>';
			$form .= '<div id="bpm-unsubscribe-dialog" title="Unsubscribe">
  <p>' . __( 'Just to improve our service we would like to know the reason for you to leave us.' ) . '</p>
  <p><textarea rows="3" cols="36" id="bpm-unsubscribe-note"></textarea></p>
</div>';
		} else {
			$form = '<form method="post" action="' . $action . '" class="paypal-button" target="_top">
                        <input type="hidden" name="button" value="subscribe">
                        <input type="hidden" name="item_name" value="' . ucfirst( $name ) . '">

                        <input type="hidden" name="currency_code" value="USD">


                        <input type="hidden" name="a3" value="' . $price . '">
                        <input type="hidden" name="p3" value="1">
                        <input type="hidden" name="t3" value="M">

                        <input type="hidden" name="cmd" value="_xclick-subscriptions">

                        <!-- Merchant ID -->
                        <input type="hidden" name="business" value="' . $this->merchant_id . '">


                        <input type="hidden" name="custom" value="' . $return_page . '">

                        <!-- Flag to no shipping -->
                        <input type="hidden" name="no_shipping" value="1">

                        <input type="hidden" name="notify_url" value="' . trailingslashit( $this->api_url ) . 'subscribe/paypal">

                        <!-- Flag to post payment return url -->
                        <input type="hidden" name="return" value="' . trailingslashit( $this->api_url ) . 'payment/process">


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
			$usage_info = json_decode( $usage_page[ 'body' ] );
		} else {
			 $usage_info = NULL;
	    }
		update_site_option( 'rtmedia-encoding-usage', array( $key => $usage_info ) );
		return $usage_info;
	}

	public function save_api_key() {
		if ( isset( $_GET[ 'api_key_updated' ] ) && $_GET[ 'api_key_updated' ] ) {
			if ( is_multisite() ) {
				add_action( 'network_admin_notices', array( $this, 'successfully_subscribed_notice' ) );
			}

			add_action( 'admin_notices', array( $this, 'successfully_subscribed_notice' ) );
		}

		if ( isset( $_GET[ 'apikey' ] ) && is_admin() && isset( $_GET[ 'page' ] ) && ( $_GET[ 'page' ] == 'rtmedia-transcoding-settings' ) && $this->is_valid_key( $_GET[ 'apikey' ] ) ) {
			if ( $this->api_key && ! ( isset( $_GET[ 'update' ] ) && $_GET[ 'update' ] ) ) {
				$unsubscribe_url = trailingslashit( $this->api_url ) . 'api/cancel/' . $this->api_key;
				wp_remote_post( $unsubscribe_url, array( 'timeout' => 120, 'body' => array( 'note' => 'Direct URL Input (API Key: ' . $_GET[ 'apikey' ] . ')' ) ) );
			}

			update_site_option( 'rtmedia-encoding-api-key', $_GET[ 'apikey' ] );

			$usage_info = $this->update_usage( $_GET[ 'apikey' ] );
			$return_page = esc_url( add_query_arg( array( 'page' => 'rtmedia-addons', 'api_key_updated' => $usage_info->plan->name ), admin_url( 'admin.php' ) ) );

			wp_safe_redirect( esc_url_raw( $return_page ) );

			die();
		}
	}

	public function is_valid_key( $key ) {
		$validate_url = trailingslashit( $this->api_url ) . 'api/validate/' . $key;
		$validation_page = wp_remote_get( $validate_url, array( 'timeout' => 20 ) );
		if ( ! is_wp_error( $validation_page ) ) {
			$validation_info = json_decode( $validation_page[ 'body' ] );
			$status = $validation_info->status;
		} else {
			$status = false;
		}
		return $status;
	}

	public function successfully_subscribed_notice() {
		?>
			<div class="updated">
				<p><?php printf( __( 'You have successfully subscribed for the <strong>%s</strong> plan', RTMEDIA_TRANSCODING_TEXT_DOMAIN ), $_GET[ 'api_key_updated' ] ); ?></p>
			</div>
		<?php
	}

	public function disable_encoding() {
		update_site_option( 'rtmedia-encoding-api-key', '' );
		_e( 'Encoding disabled successfully.', RTMEDIA_TRANSCODING_TEXT_DOMAIN );
		die();
	}

	function enable_encoding(){
		update_site_option( 'rtmedia-encoding-api-key', $this->stored_api_key );
		_e( 'Encoding enabled successfully.', RTMEDIA_TRANSCODING_TEXT_DOMAIN );
		die();
	}

	public function free_encoding_subscribe() {
		$email = get_site_option( 'admin_email' );
		$usage_details = get_site_option( 'rtmedia-encoding-usage' );
		if ( isset( $usage_details[ $this->api_key ]->plan->name ) && (strtolower( $usage_details[ $this->api_key ]->plan->name ) == 'free') ) {
			echo json_encode( array( 'error' => 'Your free subscription is already activated.' ) );
		} else {
			$free_subscription_url = esc_url_raw( add_query_arg( array( 'email' => urlencode( $email ) ), trailingslashit( $this->api_url ) . 'api/free/' ) );
			if ( $this->api_key ) {
				$free_subscription_url = esc_url_raw( add_query_arg( array( 'email' => urlencode( $email ), 'apikey' => $this->api_key ), $free_subscription_url ) );
			}
			$free_subscribe_page = wp_remote_get( $free_subscription_url, array( 'timeout' => 120 ) );
			if ( ! is_wp_error( $free_subscribe_page ) && ( ! isset( $free_subscribe_page[ 'headers' ][ 'status' ] ) || (isset( $free_subscribe_page[ 'headers' ][ 'status' ] ) && ($free_subscribe_page[ 'headers' ][ 'status' ] == 200))) ) {
				$subscription_info = json_decode( $free_subscribe_page[ 'body' ] );
				if ( isset( $subscription_info->status ) && $subscription_info->status ) {
					echo json_encode( array( 'apikey' => $subscription_info->apikey ) );
				} else {
					echo json_encode( array( 'error' => $subscription_info->message ) );
				}
			} else {
				echo json_encode( array( 'error' => __( 'Something went wrong please try again.' ) ) );
			}
		}
		die();
	}

}
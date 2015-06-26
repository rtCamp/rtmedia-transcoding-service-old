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

	}

	/*
	 * Render settings page
	 */
	public function render() {
		?>
		<h3 class="rtm-option-title"><?php _e( 'Audio/Video encoding service', 'rtmedia' ); ?></h3>

		<p><?php _e( 'rtMedia team has started offering an audio/video encoding service.', 'rtmedia' ); ?></p>

		<p>
			<label for="new-api-key"><?php _e( 'Enter API KEY', 'rtmedia' ); ?></label>
			<input id="new-api-key" type="text" name="new-api-key" value="<?php echo $this->stored_api_key; ?>" size="60"/>
			<input type="submit" id="api-key-submit" name="api-key-submit" value="<?php echo __( 'Save Key', 'rtmedia' ); ?>" class="button-primary"/>
		</p>

		<p>
			<?php
			$enable_btn_style = 'style="display:none;"';
			$disable_btn_style = 'style="display:none;"';
			if ( $this->api_key ) {
				$enable_btn_style = 'style="display:block;"';
			} else if ( $this->stored_api_key ) {
				$disable_btn_style = 'style="display:block;"';
			}
			?>
			<input type="submit" id="disable-encoding" name="disable-encoding" value="Disable Encoding" class="button-secondary" <?php echo $enable_btn_style; ?> />
			<input type="submit" id="enable-encoding" name="enable-encoding" value="Enable Encoding" class="button-secondary" <?php echo $disable_btn_style; ?> />
		</p>

		<!-- Results table headers -->
		<table class="bp-media-encoding-table fixed widefat rtm-encoding-table">
			<thead>
			<tr>
				<th><?php _e( 'Feature\Plan', 'rtmedia' ); ?></th>
				<th><?php _e( 'Free', 'rtmedia' ); ?></th>
				<th><?php _e( 'Silver', 'rtmedia' ); ?></th>
				<th><?php _e( 'Gold', 'rtmedia' ); ?></th>
				<th><?php _e( 'Platinum', 'rtmedia' ); ?></th>
			</tr>
			</thead>

			<tbody>
			<tr>
				<th><?php _e( 'File Size Limit', 'rtmedia' ); ?></th>
				<td>200MB ( <del>20MB</del> ) </td>
				<td colspan="3" class="column-posts">16GB ( <del>2GB</del> ) </td>
			</tr>
			<tr>
				<th><?php _e( 'Bandwidth (monthly)', 'rtmedia' ); ?></th>
				<td>10GB ( <del>1GB</del> ) </td>
				<td>100GB</td>
				<td>1TB</td>
				<td>10TB</td>
			</tr>
			<tr>
				<th><?php _e( 'Overage Bandwidth', 'rtmedia' ); ?></th>
				<td><?php _e( 'Not Available', 'rtmedia' ); ?></td>
				<td>$0.10 per GB</td>
				<td>$0.08 per GB</td>
				<td>$0.05 per GB</td>
			</tr>
			<tr>
				<th><?php _e( 'Amazon S3 Support', 'rtmedia' ); ?></th>
				<td><?php _e( 'Not Available', 'rtmedia' ); ?></td>
				<td colspan="3" class="column-posts"><?php _e( 'Coming Soon', 'rtmedia' ); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'HD Profile', 'rtmedia' ); ?></th>
				<td><?php _e( 'Not Available', 'rtmedia' ); ?></td>
				<td colspan="3" class="column-posts"><?php _e( 'Coming Soon', 'rtmedia' ); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Webcam Recording', 'rtmedia' ); ?></th>
				<td colspan="4" class="column-posts"><?php _e( 'Coming Soon', 'rtmedia' ); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Pricing', 'rtmedia' ); ?></th>
				<td><?php _e( 'Free', 'rtmedia' ); ?></td>
				<td><?php _e( '$9/month', 'rtmedia' ); ?></td>
				<td><?php _e( '$99/month', 'rtmedia' ); ?></td>
				<td><?php _e( '$999/month', 'rtmedia' ); ?></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td><?php
					$usage_details = get_site_option( 'rtmedia-encoding-usage' );
					if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == 'free' ) ) {
						echo '<button disabled="disabled" type="submit" class="encoding-try-now button button-primary">' . __( 'Current Plan', 'rtmedia' ) . '</button>';
					} else {
						?>
						<form id="encoding-try-now-form" method="get">
						<button type="submit"
						        class="encoding-try-now button button-primary"><?php _e( 'Try Now', 'rtmedia' ); ?></button>
						</form><?php }
					?>
				</td>
				<td><?php echo $this->encoding_subscription_form( 'silver', 9.0 ) ?></td>
				<td><?php echo $this->encoding_subscription_form( 'gold', 99.0 ) ?></td>
				<td><?php echo $this->encoding_subscription_form( 'platinum', 999.0 ) ?></td>
			</tr>
			</tbody>
		</table>
	<?php
	}

	public function encoding_subscription_form( $name = 'No Name', $price = '0', $force = false ) {
		if ( $this->api_key )
			$this->update_usage( $this->api_key );
		$action = $this->sandbox_testing ? 'https://sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$return_page = esc_url( add_query_arg( array( 'page' => 'rtmedia-addons' ), ( is_multisite() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) ) ) );

		$usage_details = get_site_option( 'rtmedia-encoding-usage' );
		if ( isset( $usage_details[ $this->api_key ]->plan->name ) && ( strtolower( $usage_details[ $this->api_key ]->plan->name ) == strtolower( $name ) ) && $usage_details[ $this->api_key ]->sub_status && ! $force ) {
			$form = '<button data-plan="' . $name . '" data-price="' . $price . '" type="submit" class="button bpm-unsubscribe">' . __( 'Unsubscribe', 'rtmedia' ) . '</button>';
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
		if ( ! is_wp_error( $usage_page ) )
			$usage_info = json_decode( $usage_page[ 'body' ] ); else
			$usage_info = NULL;
		update_site_option( 'rtmedia-encoding-usage', array( $key => $usage_info ) );
		return $usage_info;
	}

}
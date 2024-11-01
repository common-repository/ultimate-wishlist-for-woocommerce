<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */
class Ultimate_Wishlist_For_Woocommerce_Helper {

	/**
	 * Get options settings from db.
	 *
	 * @since    1.0.0
	 */
	public static function get_settings() {

		global $wpdb;
		$option_table = $wpdb->prefix . 'options';
		$response     = $wpdb->get_results( "SELECT `option_name`, `option_value`, `option_id` FROM {$option_table} WHERE `option_name` LIKE '%wfw-%'", ARRAY_A );

		if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {

			$result = array(
				'status'  => 400,
				'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html( 'Settings Not Found. Please Save settings once again.', 'ultimate-wishlist-for-woocommerce' ),
			);
		} else {

			$formatted_result = self::format_sql_result( $response );

			$result = array(
				'status'  => 200,
				'message' => $formatted_result,
			);
		}

		return $result;
	}

	/**
	 * Get Wishlist Strings.
	 *
	 * @since    1.0.0
	 */
	public static function get_strings() {
		return array(
			'popup_title'           => apply_filters( 'wfw_popup_title', esc_html__( 'New Item Added in Wishlist', 'ultimate-wishlist-for-woocommerce' ) ),
			'view_text'             => apply_filters( 'mwf_view_text', esc_html__( 'View Wishlist', 'ultimate-wishlist-for-woocommerce' ) ),
			'processing_text'       => apply_filters( 'mwf_processing_text', esc_html__( 'Processing', 'ultimate-wishlist-for-woocommerce' ) ),
			'add_to_cart'           => apply_filters( 'mwf_add_to_cart', esc_html__( 'Buy Now', 'ultimate-wishlist-for-woocommerce' ) ),
			'login_required'        => apply_filters( 'mwf_login_required', esc_html__( 'Please Login to your account first.', 'ultimate-wishlist-for-woocommerce' ) ),
			'add_to_wishlist'       => apply_filters( 'mwb_wfw_wishlist_accept_text', esc_html__( 'Add to Wishlist', 'ultimate-wishlist-for-woocommerce' ) ),
			'remove_from_wishlist'  => apply_filters( 'mwb_wfw_wishlist_remove_text', esc_html__( 'Remove from Wishlist', 'ultimate-wishlist-for-woocommerce' ) ),
			'uwfw_get_checkout_url' => wc_get_checkout_url(),
		);
	}
	/**
	 * Get options settings from db.
	 *
	 * @param array $result The resultive data.
	 * @since    1.0.0
	 */
	public static function format_sql_result( $result = array() ) {

		if ( ! empty( $result ) && is_array( $result ) ) {

			$formatted_result = array();
			foreach ( $result as $key => $option ) {
				$formatted_result[ str_replace( '-', '_', $option['option_name'] ) ] = $option['option_value'];
			}
			return $formatted_result;
		}
	}

	/**
	 * Get encrypt/decrypt form of strings.
	 *
	 * @param string $string The string to encryt.
	 * @param string $action action to perform.
	 * @since    1.0.0
	 */
	public static function encrypter( $string, $action = 'e' ) {

		// Keys to encrypt.
		$secret_key = 'mwb-wfw-encrypt-base';
		$secret_iv  = 'mwb-wfw-decrypt-base';

		$output         = false;
		$encrypt_method = 'AES-256-CBC';
		$key            = hash( 'sha256', $secret_key );
		$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		switch ( $action ) {
			case 'e':
				$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
				break;

			case 'd':
				$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
				break;

			default:
				$output = false;
				break;
		}

		return $output;
	}

	// End of class.
}

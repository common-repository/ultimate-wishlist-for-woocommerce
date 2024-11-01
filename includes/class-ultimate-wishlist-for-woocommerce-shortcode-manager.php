<?php
/**
 * The shortcode management for wishlist plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * Fired during Woo plugin activation.
 *
 * This class defines all code necessary to run shortcode management for wishlist plugin.
 *
 * @since      1.0.0
 * @package    wishlist-for-woo
 * @subpackage Wishlist_For_Woo/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Ultimate_Wishlist_For_Woocommerce_Shortcode_Manager {

	/**
	 * The base path of public class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_path    The ID of this plugin.
	 */
	public static $base_path;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $base_path       The name of the plugin.
	 */
	public function __construct( $base_path ) {
		self::$base_path = $base_path;
	}

	/**
	 * Callback function for shortcode.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		// Init properties.
		$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance();
		$user             = wp_get_current_user();

		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-helper.php';
		// Check for Wishlists by url id.
		$current_ref = ! empty( $_GET['wl-ref'] ) ? sanitize_text_field( wp_unslash( $_GET['wl-ref'] ) ) : false;
		$current_id  = ! empty( $current_ref ) ? Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $current_ref, 'd' ) : false;
		if ( ! empty( $current_id ) ) {

			$wishlist_manager->id = $current_id;
			$owner                = $wishlist_manager->get_prop( 'owner' );
			$collaborators        = $wishlist_manager->get_prop( 'collaborators' );

			if ( $owner == $user->user_email || ( is_array( $collaborators ) && in_array( $user->user_email, $collaborators ) ) ) {
				$access = 'edit';
			} else {
				$access = 'view';
			}
		}

		if ( ! empty( $access ) && 'view' == $access ) {
			$get_wishlists = $wishlist_manager->retrieve();
			if ( 200 == $get_wishlists['status'] ) {
				$owner_lists = ! empty( $get_wishlists['message'] ) ? $get_wishlists['message'] : array();
			}
		} else {

			// Check for Wishlists by owner email.
			if ( ! empty( $user->user_email ) && is_email( $user->user_email ) ) {
				$get_wishlists = $wishlist_manager->retrieve( 'owner', $user->user_email );
				if ( 200 == $get_wishlists['status'] ) {
					$access      = 'edit';
					$owner_lists = ! empty( $get_wishlists['message'] ) ? $get_wishlists['message'] : array();
				}
			}
		}

		ob_start();
		wc_get_template(
			'partials/ultimate-wishlist-for-woocommerce-shortcode-view.php',
			array(
				'owner_lists'       => ! empty( $owner_lists ) ? $owner_lists : array(),
				'access'            => ! empty( $access ) ? $access : false,
				'wishlist_manager'  => $wishlist_manager,
				'wid_to_show'       => $current_id,
			),
			'',
			self::$base_path
		);

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	// End of class.
}

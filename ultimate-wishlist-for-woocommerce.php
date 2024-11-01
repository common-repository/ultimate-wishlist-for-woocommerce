<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://makewebbetter.com/
 * @since   1.0.0
 * @package Ultimate_Wishlist_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:             Ultimate Wishlist for WooCommerce
 * Plugin URI:              https://makewebbetter.com/product/ultimate-wishlist-for-woocommerce/
 * Description:             Wishes and purchases are like ZERO and ONE of the shopping journeys respectively. This plugin helps you to turn this ZERO into ONE.
 * Version:                 1.0.4
 * WP Requires at least:    5.1.0
 * WP Tested up to :        5.8.2
 * WC requires at least:    3.0
 * WC tested up to:         5.9.0
 * Requires PHP:            7.0 or Higher
 * Author:                  MakeWebBetter
 * Author URI:              https://makewebbetter.com/
 * Text Domain:             ultimate-wishlist-for-woocommerce
 * Domain Path:             /languages
 * License:                 GNU General Public License v3.0
 * License URI:             http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Active Detection.
 *
 * @since    1.0.0
 * @param    string $plugin_slug index file of plugin.
 */
function mwb_wfw_is_plugin_active( $plugin_slug = '' ) {

	if ( empty( $plugin_slug ) ) {

		return false;
	}

	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {

		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

	}

	return in_array( $plugin_slug, $active_plugins ) || array_key_exists( $plugin_slug, $active_plugins );

}

/**
 * The code that runs during plugin validation.
 * This action is checks for WooCommerce Dependency.
 *
 * @since    1.0.0
 */
function mwb_wfw_plugin_activation() {

	$activation['status']  = true;
	$activation['message'] = '';
	// Dependant plugin.
	if ( ! mwb_wfw_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

		$activation['status']  = false;
		$activation['message'] = 'woo_inactive';

	}

	return $activation;
}

$mwb_wfw_plugin_activation = mwb_wfw_plugin_activation();
if ( true === $mwb_wfw_plugin_activation['status'] ) {

	/**
	 * Define plugin constants.
	 *
	 * @since 1.0.0
	 */
	function define_ultimate_wishlist_for_woocommerce_constants() {

		ultimate_wishlist_for_woocommerce_constants( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_VERSION', '1.0.4' );
		ultimate_wishlist_for_woocommerce_constants( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		ultimate_wishlist_for_woocommerce_constants( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		ultimate_wishlist_for_woocommerce_constants( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
		ultimate_wishlist_for_woocommerce_constants( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Ultimate Wishlist for WooCommerce' );
	}

	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param String $key   Key for contant.
	 * @param String $value value for contant.
	 * @since 1.0.0
	 */
	function ultimate_wishlist_for_woocommerce_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-ultimate-wishlist-for-woocommerce-activator.php
	 *
	 * @param mixed $network_wide network_wide.
	 */
	function activate_ultimate_wishlist_for_woocommerce( $network_wide ) {
		if ( ! wp_next_scheduled( 'mwb_uwfw_check_license_daily' ) ) {
			wp_schedule_event( time(), 'daily', 'mwb_uwfw_check_license_daily' );
		}

		include_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-wishlist-for-woocommerce-activator.php';
		Ultimate_Wishlist_For_Woocommerce_Activator::ultimate_wishlist_for_woocommerce_activate( $network_wide );
		$mwb_uwfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_uwfw_active_plugin ) && ! empty( $mwb_uwfw_active_plugin ) ) {
			$mwb_uwfw_active_plugin['ultimate-wishlist-for-woocommerce'] = array(
				'plugin_name' => __( 'Ultimate Wishlist for WooCommerce', 'ultimate-wishlist-for-woocommerce' ),
				'active'      => '1',
			);
		} else {
			$mwb_uwfw_active_plugin                                      = array();
			$mwb_uwfw_active_plugin['ultimate-wishlist-for-woocommerce'] = array(
				'plugin_name' => __( 'Ultimate Wishlist for WooCommerce', 'ultimate-wishlist-for-woocommerce' ),
				'active'      => '1',
			);
		}
		update_option( 'mwb_all_plugins_active', $mwb_uwfw_active_plugin );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-ultimate-wishlist-for-woocommerce-deactivator.php
	 */
	function deactivate_ultimate_wishlist_for_woocommerce() {
		include_once plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-wishlist-for-woocommerce-deactivator.php';
		Ultimate_Wishlist_For_Woocommerce_Deactivator::ultimate_wishlist_for_woocommerce_deactivate();
		$mwb_uwfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_uwfw_deactive_plugin ) && ! empty( $mwb_uwfw_deactive_plugin ) ) {
			foreach ( $mwb_uwfw_deactive_plugin as $mwb_uwfw_deactive_key => $mwb_uwfw_deactive ) {
				if ( 'ultimate-wishlist-for-woocommerce' === $mwb_uwfw_deactive_key ) {
					$mwb_uwfw_deactive_plugin[ $mwb_uwfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option( 'mwb_all_plugins_active', $mwb_uwfw_deactive_plugin );
	}

	register_activation_hook( __FILE__, 'activate_ultimate_wishlist_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_ultimate_wishlist_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-ultimate-wishlist-for-woocommerce.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 1.0.0
	 */
	function run_ultimate_wishlist_for_woocommerce() {
		define_ultimate_wishlist_for_woocommerce_constants();
		$uwfw_plugin_standard = new Ultimate_Wishlist_For_Woocommerce();
		$uwfw_plugin_standard->uwfw_run();
		$GLOBALS['uwfw_mwb_uwfw_obj'] = $uwfw_plugin_standard;
	}
	run_ultimate_wishlist_for_woocommerce();


	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ultimate_wishlist_for_woocommerce_settings_link' );

	/**
	 * Settings link.
	 *
	 * @since 1.0.0
	 * @param Array $links Settings link array.
	 */
	function ultimate_wishlist_for_woocommerce_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=ultimate_wishlist_for_woocommerce_menu' ) . '">' . __( 'Settings', 'ultimate-wishlist-for-woocommerce' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}

	/**
	 * Adding custom setting links at the plugin activation list.
	 *
	 * @param  array  $links_array array containing the links to plugin.
	 * @param  string $plugin_file_name plugin file name.
	 * @return array
	 */
	function ultimate_wishlist_for_woocommerce_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
		if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
			$links_array[] = '<a href="https://demo.makewebbetter.com/ultimate-wishlist-for-woocommerce/?utm_source=MWB-ultimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-wishlist-org" target="_blank"><img src="' . esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Demo.svg" class="mwb-info-img" alt="Demo image">' . __( 'Demo', 'ultimate-wishlist-for-woocommerce' ) . '</a>';
			$links_array[] = '<a href="https://docs.makewebbetter.com/wishlist-for-woocommerce/?utm_source=MWB-ultimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-wishlist-org" target="_blank"><img src="' . esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Documentation.svg" class="mwb-info-img" alt="documentation image">' . __( 'Documentation', 'ultimate-wishlist-for-woocommerce' ) . '</a>';
			$links_array[] = '<a href="https://support.makewebbetter.com/wordpress-plugins-knowledge-base/category/wishlist-for-woocommerce-kb/?utm_source=MWB-ulimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-org" target="_blank"><img src="' . esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Support.svg" class="mwb-info-img" alt="support image">' . __( 'Support', 'ultimate-wishlist-for-woocommerce' ) . '</a>';
		}
		return $links_array;
	}
	add_filter( 'plugin_row_meta', 'ultimate_wishlist_for_woocommerce_custom_settings_at_plugin_tab', 10, 2 );
} else {

	add_action( 'admin_init', 'mwb_wfw_plugin_activation_failure' );

	/**
	 * Deactivate this plugin.
	 *
	 * @since    1.0.0
	 */
	function mwb_wfw_plugin_activation_failure() {

		// To hide Plugin activated notice.
		if ( ! empty( $_GET['activate'] ) ) {

			unset( $_GET['activate'] );
		}

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	if ( is_multisite() ) {
		add_action( 'network_admin_notices', 'mwb_wfw_activation_admin_notice' );
	} else {
		add_action( 'admin_notices', 'mwb_wfw_activation_admin_notice' );
	}
	/**
	 * This function is used to display plugin activation error notice.
	 *
	 * @since    1.0.0
	 */
	function mwb_wfw_activation_admin_notice() {

		global $mwb_wfw_plugin_activation;

		?>

		<?php if ( 'woo_inactive' == $mwb_wfw_plugin_activation['message'] ) : ?>

			<div class="notice notice-error is-dismissible mwb-notice">
				<p><strong><?php esc_html_e( 'WooCommerce', 'ultimate-wishlist-for-woocommerce' ); ?></strong><?php esc_html_e( ' is not activated, Please activate WooCommerce first to activate ', 'ultimate-wishlist-for-woocommerce' ); ?><strong><?php esc_html_e( 'Ultimate Wishlist for WooCommerce', 'ultimate-wishlist-for-woocommerce' ); ?></strong><?php esc_html_e( '.', 'ultimate-wishlist-for-woocommerce' ); ?></p>
			</div>

			<?php
		endif;
	}
}

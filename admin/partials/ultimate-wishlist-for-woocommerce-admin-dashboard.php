<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $uwfw_mwb_uwfw_obj;
$uwfw_default_tabs = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_default_tabs();
if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
	$callname_lic         = Wishlist_For_Woocommerce_Pro::$lic_callback_function;
	$callname_lic_initial = Wishlist_For_Woocommerce_Pro::$lic_ini_callback_function;
	$day_count            = Wishlist_For_Woocommerce_Pro::$callname_lic_initial();
	if ( $day_count > 0 ) {
		$uwfw_active_tab   = isset( $_GET['uwfw_tab'] ) ? sanitize_key( $_GET['uwfw_tab'] ) : 'ultimate-wishlist-for-woocommerce-overview';
		$documentaion_link = 'https://docs.makewebbetter.com/wishlist-for-woocommerce-pro/?utm_source=MWB-wishlist-probackend&utm_medium=MWB-site&utm_campaign=MWB-Wishlist-Site';
		$support_link      = 'https://support.makewebbetter.com/wordpress-plugins-knowledge-base/category/wishlist-for-woocommerce-pro/?utm_source=MWB-wishlist-probackend&utm_medium=MWB-site&utm_campaign=MWB-Wishlist-Site';
		if ( ! get_option( 'mwb_wfwp_license_check', 0 ) ) {
			$day_count_warning = floor( $day_count );
			/* translators: %s: file path */
			$day_string = sprintf( _n( '%s day', '%s days', $day_count_warning, 'ultimate-wishlist-for-woocommerce' ), number_format_i18n( $day_count_warning ) );
			$day_string = '<span id="mwb-wfwp-day-count" >' . $day_string . '</span>';
			?>
		<div class="thirty-days-notice mwb-header-container mwb-bg-white mwb-r-8">
			<h1 class="update-message notice">
				<p>
					<strong><a href="?page=ultimate_wishlist_for_woocommerce_menu&uwfw_tab=wishlist-for-woocommerce-pro-license"><?php /* translators: %s: file path */ esc_html_e( 'Activate', 'ultimate-wishlist-for-woocommerce' ); ?></a><?php printf( esc_attr__( ' the license key before %s or you may risk losing data and the plugin will also become disfunctional.', 'ultimate-wishlist-for-woocommerce' ), wp_kses_post( $day_string ) ); ?></strong>
				</p>
			</h1>
		</div>
			<?php
		}
	} else {
		if ( Wishlist_For_Woocommerce_Pro::$callname_lic() ) {
			$uwfw_active_tab   = isset( $_GET['uwfw_tab'] ) ? sanitize_key( $_GET['uwfw_tab'] ) : 'ultimate-wishlist-for-woocommerce-overview';
			$documentaion_link = 'https://docs.makewebbetter.com/wishlist-for-woocommerce-pro/?utm_source=MWB-wishlist-probackend&utm_medium=MWB-site&utm_campaign=MWB-Wishlist-Site';
			$support_link      = 'https://support.makewebbetter.com/wordpress-plugins-knowledge-base/category/wishlist-for-woocommerce-pro/?utm_source=MWB-wishlist-probackend&utm_medium=MWB-site&utm_campaign=MWB-Wishlist-Site';
		} else {
			$documentaion_link = 'https://docs.makewebbetter.com/wishlist-for-woocommerce/?utm_source=MWB-ultimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-wishlist-org';
			$support_link      = 'https://support.makewebbetter.com/wordpress-plugins-knowledge-base/category/wishlist-for-woocommerce-kb/?utm_source=MWB-ulimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-org';
			$uwfw_active_tab   = isset( $_GET['uwfw_tab'] ) ? sanitize_key( $_GET['uwfw_tab'] ) : 'wishlist-for-woocommerce-pro-license';
			?>
		<div class="thirty-days-notice mwb-header-container mwb-bg-white mwb-r-8">
			<h1 class="mwb-header-title">
				<p>
					<strong><?php esc_html_e( ' Your trial period is over please activate license to use the features.', 'ultimate-wishlist-for-woocommerce' ); ?></strong>
				</p>
			</h1>
		</div>
			<?php
		}
	}
} else {
	$uwfw_active_tab   = isset( $_GET['uwfw_tab'] ) ? sanitize_key( $_GET['uwfw_tab'] ) : 'ultimate-wishlist-for-woocommerce-overview';
	$documentaion_link = 'https://docs.makewebbetter.com/wishlist-for-woocommerce/?utm_source=MWB-ultimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-wishlist-org';
	$support_link      = 'https://support.makewebbetter.com/wordpress-plugins-knowledge-base/category/wishlist-for-woocommerce-kb/?utm_source=MWB-ulimate-wishlist-orgbackend&utm_medium=MWB-ORG&utm_campaign=MWB-org';
}

?>
<header>
	<?php
		// desc - This hook is used for trial.
		do_action( 'mwb_uwfw_settings_saved_notice' );
	?>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
	<h1 class="mwb-header-title"><?php echo esc_html( 'ULTIMATE WISHLIST FOR WOOCOMMERCE', 'ultimate-wishlist-for-woocommerce' ); ?></h1>
		<a href="<?php echo esc_url( $documentaion_link ); ?>" target="_blank" class="mwb-link"><?php esc_html_e( 'Documentation', 'ultimate-wishlist-for-woocommerce' ); ?></a>
		<span>|</span>
		<a href="<?php echo esc_url( $support_link ); ?>" target="_blank" class="mwb-link"><?php esc_html_e( 'Support', 'ultimate-wishlist-for-woocommerce' ); ?></a>
	</div>
</header>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $uwfw_default_tabs ) && ! empty( $uwfw_default_tabs ) ) {
				foreach ( $uwfw_default_tabs as $uwfw_tab_key => $uwfw_default_tabs ) {

					$uwfw_tab_classes = 'mwb-link ';
					if ( ! empty( $uwfw_active_tab ) && $uwfw_active_tab === $uwfw_tab_key ) {
						$uwfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $uwfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=ultimate_wishlist_for_woocommerce_menu' ) . '&uwfw_tab=' . esc_attr( $uwfw_tab_key ) ); ?>" class="<?php echo esc_attr( $uwfw_tab_classes ); ?>"><?php echo esc_html( $uwfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="mwb-section">
		<div>
			<?php
				// desc - This hook is used for trial.
				do_action( 'mwb_uwfw_before_general_settings_form' );
				// if submenu is directly clicked on woocommerce.
			if ( empty( $uwfw_active_tab ) ) {
				$uwfw_active_tab = 'mwb_uwfw_plug_general';
			}

				// look for the path based on the tab id in the admin templates.
				$uwfw_default_tabs     = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_default_tabs();
				$uwfw_tab_content_path = $uwfw_default_tabs[ $uwfw_active_tab ]['file_path'];
				$uwfw_mwb_uwfw_obj->mwb_uwfw_plug_load_template( $uwfw_tab_content_path );
				// desc - This hook is used for trial.
				do_action( 'mwb_uwfw_after_general_settings_form' );
			?>
		</div>
	</section>

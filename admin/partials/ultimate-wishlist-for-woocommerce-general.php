<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $uwfw_mwb_uwfw_obj;
$uwfw_genaral_settings =
// desc - filter for trial .
apply_filters( 'uwfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-uwfw-gen-section-form">
	<div class="uwfw-secion-wrap">
		<?php
		$uwfw_general_html = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_generate_html( $uwfw_genaral_settings );
		echo esc_html( $uwfw_general_html );
		wp_nonce_field( 'admin_save_data', 'mwb_tabs_nonce' );
		?>
	</div>
</form>

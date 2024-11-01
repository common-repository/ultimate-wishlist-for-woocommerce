<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $uwfw_mwb_uwfw_obj;
$uwfw_default_status = $uwfw_mwb_uwfw_obj->mwb_uwfw_plug_system_status();
$uwfw_wordpress_details = is_array( $uwfw_default_status['wp'] ) && ! empty( $uwfw_default_status['wp'] ) ? $uwfw_default_status['wp'] : array();
$uwfw_php_details = is_array( $uwfw_default_status['php'] ) && ! empty( $uwfw_default_status['php'] ) ? $uwfw_default_status['php'] : array();
?>
<div class="mwb-uwfw-table-wrap">
	<div class="mwb-col-wrap">
		<div id="mwb-uwfw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-uwfw-table mdc-data-table__table mwb-table" id="mwb-uwfw-wp">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Variables', 'ultimate-wishlist-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Values', 'ultimate-wishlist-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $uwfw_wordpress_details ) && ! empty( $uwfw_wordpress_details ) ) { ?>
							<?php foreach ( $uwfw_wordpress_details as $wp_key => $wp_value ) { ?>
								<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
									<tr class="mdc-data-table__row">
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_key ); ?></td>
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_value ); ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="mwb-col-wrap">
		<div id="mwb-uwfw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-uwfw-table mdc-data-table__table mwb-table" id="mwb-uwfw-sys">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'System Variables', 'ultimate-wishlist-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'System Values', 'ultimate-wishlist-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $uwfw_php_details ) && ! empty( $uwfw_php_details ) ) { ?>
							<?php foreach ( $uwfw_php_details as $php_key => $php_value ) { ?>
								<tr class="mdc-data-table__row">
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_key ); ?></td>
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_value ); ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

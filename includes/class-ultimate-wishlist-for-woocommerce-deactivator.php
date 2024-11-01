<?php
/**
 * Fired during plugin deactivation
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */
class Ultimate_Wishlist_For_Woocommerce_Deactivator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function ultimate_wishlist_for_woocommerce_deactivate() {
		global $wpdb;
		if ( is_multisite() ) {
			if ( is_plugin_active_for_network( 'ultimate-wishlist-for-woocommerce/ultimate-wishlist-for-woocommerce.php' ) ) {
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

				foreach ( $blogids as $blog_id ) {

					switch_to_blog( $blog_id );
					$args = array(
						'post_type'   => 'page',
						'post_status' => 'publish',
						'meta_key'    => '_mwb_wfw_default_page',
					);
					$quote_page       = get_posts( $args );
					$wishlist_page_id = $quote_page[0]->ID;
					wp_update_post(
						array(
							'ID'          => $wishlist_page_id,
							'post_status' => 'draft',
						)
					);
					restore_current_blog();
				}
			} else {
				$args = array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'meta_key'    => '_mwb_wfw_default_page',
				);
				$quote_page       = get_posts( $args );
				$wishlist_page_id = $quote_page[0]->ID;
				wp_update_post(
					array(
						'ID'          => $wishlist_page_id,
						'post_status' => 'draft',
					)
				);

			}

			// Validate license daily cron.

		} else {
			$args = array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'meta_key'    => '_mwb_wfw_default_page',
			);

			$quote_page       = get_posts( $args );
			$wishlist_page_id = $quote_page[0]->ID;
			wp_update_post(
				array(
					'ID'          => $wishlist_page_id,
					'post_status' => 'draft',
				)
			);

		}
	}

}

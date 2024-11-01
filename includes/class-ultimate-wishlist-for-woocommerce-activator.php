<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
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
class Ultimate_Wishlist_For_Woocommerce_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @throws  Exception If something interesting cannot happen.
	 * @since    1.0.0
	 * @param mixed $network_wide network_wide.
	 */
	public static function ultimate_wishlist_for_woocommerce_activate( $network_wide ) {
		global $wpdb;
		if ( is_multisite() || $network_wide ) {
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			foreach ( $blogids as $blog_id ) {

				switch_to_blog( $blog_id );
				self::uwfw_on_activation_create_table();
				restore_current_blog();
			}
		} else {
			self::uwfw_on_activation_create_table();
		}

	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function uwfw_on_activation_create_table() {
		$timestamp = get_option( 'mwb_uwfw_activated_timestamp', 'not_set' );

		if ( 'not_set' === $timestamp ) {

			$current_time = current_time( 'timestamp' );
			$thirty_days  = strtotime( '+30 days', $current_time );
			update_option( 'mwb_uwfw_activated_timestamp', $thirty_days );
		}

		global $wpdb;
		$table_name   = $wpdb->prefix . 'wishlist_datastore';
		$result       = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) );
		$table_name_s = $wpdb->prefix . 'wishlist_datastore_list';
		$result_s     = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_s ) ) );

		if ( empty( $result_s ) || $result_s != $table_name_s ) {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_name_s    = $wpdb->prefix . 'wishlist_datastore_list';

			$sql_new = "CREATE TABLE {$table_name_s} (
			`id` int unsigned zerofill NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`prod_id` bigint(20) NOT NULL,
			`quantity` int(11) NOT NULL,
			`user_id` bigint(20) NOT NULL,
			`wishlist_id` bigint(20) NOT NULL,
			`orignal_price` decimal(9,3) NOT NULL,
			`orignal_currency` 	char(3) NOT NULL,
			`on_sale` 	tinyint(4) NOT NULL,
			`stock` 	varchar(500) NOT NULL
		){$charset_collate};";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql_new );
		}

		if ( empty( $result ) || $result != $table_name ) {
			try {
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name      = $wpdb->prefix . 'wishlist_datastore';

				$sql = "CREATE TABLE {$table_name} (
				`id` int unsigned zerofill NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`title` varchar(500) NOT NULL,
				`createdate` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
				`modifieddate` timestamp NOT NULL,
				`owner` varchar(100) NOT NULL,
				`status` varchar(10) NOT NULL,
				`collaborators` varchar(100) NOT NULL,
				`properties` varchar(1000) NOT NULL,
				`user_id` bigint(20) NOT NULL,
				`session_id` varchar(500) NULL,
				`wishlist_token` varchar(500) NULL
			) {$charset_collate};";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta( $sql );
			} catch ( \Throwable $th ) {
				throw new Exception( $th->getMessage(), 1 );
			}
		}

		/**
		 * Search and Insert default quote page if not avaiable.
		 */

		$args = array(
			'post_type'   => 'page',
			'post_status' => 'draft',
			'meta_key'    => '_mwb_wfw_default_page',
		);

		$quote_page = get_posts( $args );
		if ( empty( $quote_page ) ) {
			$default_page     = array(
				'post_title'   => __( 'Wishlist', 'ultimate-wishlist-for-woocommerce' ),
				'post_status'  => 'publish',
				'post_content' => esc_html( '[mwb_wfw_wishlist]' ),
				'post_author'  => get_current_user_id(),
				'post_type'    => 'page',
				'meta_input'   => array(
					'_mwb_wfw_default_page' => true,
				),
			);
			$wishlist_page_id = wp_insert_post( $default_page );

			update_option( 'wfw-selected-page', $wishlist_page_id );
		} else {
			$wishlist_page_id = $quote_page[0]->ID;
			wp_update_post(
				array(
					'ID'          => $wishlist_page_id,
					'post_status' => 'publish',
				)
			);
			update_option( 'wfw-selected-page', $wishlist_page_id );
		}
	}
}

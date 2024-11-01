<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/admin
 */
class Ultimate_Wishlist_For_Woocommerce_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function uwfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_ultimate_wishlist_for_woocommerce_menu' === $screen->id ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'mwb-uwfw-select2-css', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/ultimate-wishlist-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-uwfw-meterial-css', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-uwfw-meterial-css2', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-uwfw-meterial-lite', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-uwfw-meterial-icons-css', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-admin-min-css', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-datatable-css', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area .
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function uwfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_ultimate_wishlist_for_woocommerce_menu' === $screen->id ) {
			wp_enqueue_script( 'mwb-uwfw-select2', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/ultimate-wishlist-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-uwfw-metarial-js', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-uwfw-metarial-js2', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-uwfw-metarial-lite', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-uwfw-datatable', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/js/jquery.dataTables.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-uwfw-datatable-btn', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/dataTables.buttons.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-uwfw-datatable-btn-2', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/buttons.html5.min.js', array(), time(), false );
			wp_register_script( $this->plugin_name . 'admin-js', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/ultimate-wishlist-for-woocommerce-admin.js', array( 'jquery', 'mwb-uwfw-select2', 'mwb-uwfw-metarial-js', 'mwb-uwfw-metarial-js2', 'mwb-uwfw-metarial-lite', 'wp-color-picker' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'uwfw_admin_param',
				array(
					'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
					'reloadurl'                 => admin_url( 'admin.php?page=ultimate_wishlist_for_woocommerce_menu' ),
					'uwfw_gen_tab_enable'       => get_option( 'uwfw_radio_switch_demo' ),
					'uwfw_admin_param_location' => ( admin_url( 'admin.php' ) . '?page=ultimate_wishlist_for_woocommerce_menu&uwfw_tab=ultimate-wishlist-for-woocommerce-general' ),
					'view_type'                 => get_option( 'wfw-view-type', 'icon' ),
					'data_table_language'       => array(
						'nodata'         => esc_attr__( 'No data available in table', 'ultimate-wishlist-for-woocommerce' ),
						'next'           => esc_attr__( 'Next', 'ultimate-wishlist-for-woocommerce' ),
						'previous'       => esc_attr__( 'Previous', 'ultimate-wishlist-for-woocommerce' ),
						'last'           => esc_attr__( 'Last', 'ultimate-wishlist-for-woocommerce' ),
						'sortAscending'  => esc_attr__( ': activate to sort column ascending', 'ultimate-wishlist-for-woocommerce' ),
						'sortDescending' => esc_attr__( ': activate to sort column descending', 'ultimate-wishlist-for-woocommerce' ),
						'first'          => esc_attr__( 'First', 'ultimate-wishlist-for-woocommerce' ),
						'zeroRecords'    => esc_attr__( 'No matching records found', 'ultimate-wishlist-for-woocommerce' ),
						'search'         => esc_attr__( 'Search:', 'ultimate-wishlist-for-woocommerce' ),
						'processing'     => esc_attr__( 'Processing...', 'ultimate-wishlist-for-woocommerce' ),
						'loadingRecords' => esc_attr__( 'Loading...', 'ultimate-wishlist-for-woocommerce' ),
						'lengthMenu'     => esc_attr__( 'Show _MENU_ entries', 'ultimate-wishlist-for-woocommerce' ),
						'infoFiltered'   => esc_attr__( '(filtered from _MAX_ total entries)', 'ultimate-wishlist-for-woocommerce' ),
						'infoEmpty'      => esc_attr__( 'Showing 0 to 0 of 0 entries', 'ultimate-wishlist-for-woocommerce' ),
						'info'           => esc_attr__( 'Showing _START_ to _END_ of _TOTAL_ entries', 'ultimate-wishlist-for-woocommerce' ),
					),
				)
			);
			wp_enqueue_script( $this->plugin_name . 'admin-js' );
			wp_enqueue_script( 'mwb-admin-min-js', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-admin.min.js', array(), time(), false );
		}
	}

	/**
	 * Adding settings menu for Ultimate Wishlist for WooCommerce.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( esc_html( 'MakeWebBetter' ), esc_html( 'MakeWebBetter' ), 'manage_options', 'mwb-plugins', array( $this, 'uwfw_plugins_listing_page' ), ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/MWB_Grey-01.svg', 15 );
			$uwfw_menus =
			// desc - filter for trial.
			apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $uwfw_menus ) && ! empty( $uwfw_menus ) ) {
				foreach ( $uwfw_menus as $uwfw_key => $uwfw_value ) {
					add_submenu_page( 'mwb-plugins', $uwfw_value['name'], $uwfw_value['name'], 'manage_options', $uwfw_value['menu_link'], array( $uwfw_value['instance'], $uwfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}

	/**
	 * Ultimate Wishlist for WooCommerce uwfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function uwfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => esc_html( 'Ultimate Wishlist for WooCommerce' ),
			'slug'      => 'ultimate_wishlist_for_woocommerce_menu',
			'menu_link' => 'ultimate_wishlist_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'uwfw_options_menu_html',
		);
		return $menus;
	}

	/**
	 * Ultimate Wishlist for WooCommerce uwfw_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_plugins_listing_page() {
		$active_marketplaces =
		// desc - filter for trial.
		apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			include ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * Ultimate Wishlist for WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_options_menu_html() {

		include_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-admin-dashboard.php';
	}

	/**
	 * Ultimate Wishlist for WooCommerce uwfw_developer_admin_hooks_listing.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_developer_admin_hooks_listing() {
		$admin_hooks = array();
		$val         = self::uwfw_developer_hooks_function( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' );
		if ( ! empty( $val['hooks'] ) ) {
			$admin_hooks[] = $val['hooks'];
			unset( $val['hooks'] );
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::uwfw_developer_hooks_function( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' . $v . '/' );
				if ( ! empty( $helo['hooks'] ) ) {
					$admin_hooks[] = $helo['hooks'];
					unset( $helo['hooks'] );
				}
				if ( ! empty( $helo ) ) {
					$data[] = $helo;
				}
			}
		}
		return $admin_hooks;
	}

	/**
	 * Ultimate Wishlist for WooCommerce uwfw_developer_public_hooks_listing
	 *
	 * @since 1.0.0
	 */
	public function uwfw_developer_public_hooks_listing() {

		$public_hooks = array();
		$val          = self::uwfw_developer_hooks_function( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'public/' );

		if ( ! empty( $val['hooks'] ) ) {
			$public_hooks[] = $val['hooks'];
			unset( $val['hooks'] );
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::uwfw_developer_hooks_function( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'public/' . $v . '/' );
				if ( ! empty( $helo['hooks'] ) ) {
					$public_hooks[] = $helo['hooks'];
					unset( $helo['hooks'] );
				}
				if ( ! empty( $helo ) ) {
					$data[] = $helo;
				}
			}
		}
		return $public_hooks;
	}

	/**
	 * Ultimate Wishlist for WooCommerce uwfw_developer_hooks_function.
	 *
	 * @since 1.0.0
	 * @param array $path file path.
	 */
	public function uwfw_developer_hooks_function( $path ) {
		$all_hooks = array();
		$scan      = scandir( $path );
		$response  = array();
		foreach ( $scan as $file ) {
			if ( strpos( $file, '.php' ) ) {
				$myfile = file( $path . $file );
				foreach ( $myfile as $key => $lines ) {
					if ( preg_match( '/do_action/i', $lines ) && ! strpos( $lines, 'str_replace' ) && ! strpos( $lines, 'preg_match' ) ) {
						$all_hooks[ $key ]['action_hook'] = $lines;
						$all_hooks[ $key ]['desc']        = $myfile[ $key - 1 ];
					}
					if ( preg_match( '/apply_filters/i', $lines ) && ! strpos( $lines, 'str_replace' ) && ! strpos( $lines, 'preg_match' ) ) {
						$all_hooks[ $key ]['filter_hook'] = $lines;
						$all_hooks[ $key ]['desc']        = $myfile[ $key - 1 ];
					}
				}
			} elseif ( strpos( $file, '.' ) == '' && strpos( $file, '.' ) !== 0 ) {
				$response['files'][] = $file;
			}
		}
		if ( ! empty( $all_hooks ) ) {
			$response['hooks'] = $all_hooks;
		}
		return $response;
	}

	/**
	 * Ultimate Wishlist for WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @param array $uwfw_settings_general Settings fields.
	 */
	public function uwfw_admin_general_settings_page( $uwfw_settings_general ) {
		global $wpdb;

		$shortcode  = 'mwb_wfw_wishlist';
		$post_table = $wpdb->prefix . 'posts';

		$wishlist_page = $wpdb->get_results( $wpdb->prepare( "SELECT `ID` FROM %1s WHERE `post_content` LIKE '%2s' AND `post_type` = 'page' AND `post_status` = 'publish'", $post_table, '%[' . $wpdb->esc_like( $shortcode ) . ']%' ) );
		$page_option   = array();
		if ( $wishlist_page ) {

			foreach ( $wishlist_page as $key => $post ) {
				$page_option[ $post->ID ] = get_the_title( $post->ID );
			}
		}
		$uwfw_settings_general = array(
			array(
				'title' => esc_html__( 'General Settings', 'ultimate-wishlist-for-woocommerce' ),
				'type'  => 'heading',
				'id'    => 'general_settings_heading',
			),
			array(
				'title'       => esc_html__( 'Enable /Disable Plugin', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-plugin' ),
				'id'          => 'wfw-enable-plugin',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the complete plugin functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Enable Wishlist Popup', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-popup' ),
				'id'          => 'wfw-enable-popup',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Show Item added in wishlist as popup after adding in list.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title' => esc_html__( 'Wishlist Position And Preview', 'ultimate-wishlist-for-woocommerce' ),
				'type'  => 'heading',
				'id'    => 'position_settings_heading',
			),
			array(
				'title'       => esc_html__( 'Wishlist View Type', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select how you want to show the wishlist for customers.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-view-type',
				'value'       => get_option( 'wfw-view-type', 'icon' ),
				'class'       => 'uwfw-select-class',
				'options'     => array(
					''       => esc_html__( 'No options Selected', 'ultimate-wishlist-for-woocommerce' ),
					'icon'   => esc_html__( 'Icon over Product Image', 'ultimate-wishlist-for-woocommerce' ),
					'button' => esc_html__( 'Add to Wishlist button', 'ultimate-wishlist-for-woocommerce' ),
				),
			),
			array(
				'title'       => esc_html__( 'Wishlist button color', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Select the custom color for the wishlist button( Note: This setting will change the colours of all the buttons in the popup as well).', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-add-to-wishlist-button-color',
				'value'       => get_option( 'wfw-add-to-wishlist-button-color', '#59B0F6' ),
			),
			array(
				'title'       => esc_html__( 'Wishlist Interface Icon', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select which icon you want for the wishlist interface.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-icon-view',
				'value'       => get_option( 'wfw-icon-view', 'heart' ),
				'class'       => 'uwfw-select-class',
				'options'     => array(
					''         => esc_html__( 'No options Selected', 'ultimate-wishlist-for-woocommerce' ),
					'heart'    => esc_html__( 'Heart Icon', 'ultimate-wishlist-for-woocommerce' ),
					'shopping' => esc_html__( 'Shopping Icon', 'ultimate-wishlist-for-woocommerce' ),
					'cart'     => esc_html__( 'Cart Icon', 'ultimate-wishlist-for-woocommerce' ),
					'star'     => esc_html__( 'Star Icon', 'ultimate-wishlist-for-woocommerce' ),
					'tag'      => esc_html__( 'Tag Icon', 'ultimate-wishlist-for-woocommerce' ),
					'thumbsup' => esc_html__( 'Like Icon', 'ultimate-wishlist-for-woocommerce' ),
					'bell'     => esc_html__( 'Bell Icon', 'ultimate-wishlist-for-woocommerce' ),
					'eye'      => esc_html__( 'Eye Icon', 'ultimate-wishlist-for-woocommerce' ),
				),
			),
			array(
				'title'       => esc_html__( 'Wishlist on Product Page', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select where wishlist button should be shown on woocommerce products page.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-product-button-view',
				'value'       => get_option( 'wfw-product-button-view', 'before_add_to_cart' ),
				'class'       => 'uwfw-select-class',
				'options'     => array(
					''                    => esc_html__( 'No options Selected', 'ultimate-wishlist-for-woocommerce' ),
					'before_add_to_cart'  => esc_html__( 'Before Add To Cart Button', 'ultimate-wishlist-for-woocommerce' ),
					'after_add_to_cart'   => esc_html__( 'After Add To Cart Button', 'ultimate-wishlist-for-woocommerce' ),
					'before_product_name' => esc_html__( 'Before Product Title', 'ultimate-wishlist-for-woocommerce' ),
					'after_product_name'  => esc_html__( 'After Product Title', 'ultimate-wishlist-for-woocommerce' ),
					'after_product_price' => esc_html__( 'After Product Price', 'ultimate-wishlist-for-woocommerce' ),
				),
			),
			array(
				'title'       => esc_html__( 'Wishlist on Loops', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select where wishlist button should be shown on woocommerce loops or shops.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-loop-button-view',
				'value'       => get_option( 'wfw-loop-button-view', 'before_product_name' ),
				'class'       => 'uwfw-select-class',
				'options'     => array(
					''                    => esc_html__( 'No options Selected', 'ultimate-wishlist-for-woocommerce' ),
					'before_product_name' => esc_html__( 'Before Product Title', 'ultimate-wishlist-for-woocommerce' ),
					'after_product_name'  => esc_html__( 'After Product Title', 'ultimate-wishlist-for-woocommerce' ),
					'before_add_to_cart'  => esc_html__( 'Before Add To Cart Button', 'ultimate-wishlist-for-woocommerce' ),
					'before_product_loop' => esc_html__( 'Before Product Section', 'ultimate-wishlist-for-woocommerce' ),
				),
			),
			array(
				'title' => esc_html__( 'Wishlist Page', 'ultimate-wishlist-for-woocommerce' ),
				'type'  => 'heading',
				'id'    => 'wishlist_page_heading',
			),
			array(
				'title'       => esc_html__( 'Wishlist Page', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Select the page where view wishlist button should redirect. Wishlist page requires shortcode as [mwb_wfw_wishlist]', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-selected-page',
				'value'       => get_option( 'wfw-selected-page', '' ),
				'class'       => 'uwfw-select-class',
				'options'     => $page_option,
			),
			array(
				'type'        => 'button',
				'id'          => 'uwfw_button_demo',
				'button_text' => esc_html__( 'Save Settings', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'uwfw-button-class',
			),
		);
		return $uwfw_settings_general;
	}
	/**
	 * Ultimate Wishlist for WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @param array $uwfw_settings_social_sharing Settings fields.
	 */
	public function uwfw_admin_social_sharing_settings_page( $uwfw_settings_social_sharing ) {

		$uwfw_settings_social_sharing = array(
			array(
				'title'       => esc_html__( 'Icon size', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'select',
				'description' => esc_html__( 'Input Pinterest icon size.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-enable-icon-size',
				'value'       => get_option( 'wfw-enable-icon-size', '15px' ),
				'class'       => 'uwfw-select-class',
				'options'     => array(
					'15px' => esc_html__( 'Small', 'ultimate-wishlist-for-woocommerce' ),
					'20px' => esc_html__( 'Medium', 'ultimate-wishlist-for-woocommerce' ),
					'25px' => esc_html__( 'Large', 'ultimate-wishlist-for-woocommerce' ),
				),
			),
			array(
				'title'       => esc_html__( 'Facebook share', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-fb-share' ),
				'id'          => 'wfw-enable-fb-share',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the Facebook share functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Facebook Icon color', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Input Facebook icon color.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-enable-fb-color',
				'value'       => get_option( 'wfw-enable-fb-color', '1877f2' ),
				'class'       => 'uwfw-select-icon-color-class',
				'placeholder' => esc_html__( 'Input Facebook icon color', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'WhatsApp share', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-whatsapp-share' ),
				'id'          => 'wfw-enable-whatsapp-share',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the WhatsApp share functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'WhatsApp Icon color', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Input WhatsApp icon color.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-enable-whatsapp-color',
				'value'       => get_option( 'wfw-enable-whatsapp-color', '25D366' ),
				'class'       => 'uwfw-select-icon-color-class',
				'placeholder' => esc_html__( 'Input WhatsApp icon color.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Twitter share', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-twitter-share' ),
				'id'          => 'wfw-enable-twitter-share',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the Twitter share functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Twitter Icon color', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Input Twitter icon color.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-enable-twitter-color',
				'value'       => get_option( 'wfw-enable-twitter-color', '25D366' ),
				'class'       => 'uwfw-select-icon-color-class',
				'placeholder' => esc_html__( 'Input Twitter icon color.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Pinterest share', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'radio-switch',
				'value'       => get_option( 'wfw-enable-pinterest-share' ),
				'id'          => 'wfw-enable-pinterest-share',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the Pinterest share functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Pinterest Icon color', 'ultimate-wishlist-for-woocommerce' ),
				'type'        => 'text',
				'description' => esc_html__( 'Input Pinterest icon color.', 'ultimate-wishlist-for-woocommerce' ),
				'id'          => 'wfw-enable-pinterest-color',
				'value'       => get_option( 'wfw-enable-pinterest-color', '25D366' ),
				'class'       => 'uwfw-select-icon-color-class',
				'placeholder' => esc_html__( 'Input Pinterest icon color.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'uwfw_save_social_sharing',
				'button_text' => esc_html__( 'Save Settings', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'uwfw-button-class',
			),
		);
		return $uwfw_settings_social_sharing;
	}
	/**
	 * Ultimate Wishlist for WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @param array $uwfw_advanced_features Settings fields.
	 */
	public function uwfw_advanced_features_page( $uwfw_advanced_features ) {
		$uwfw_advanced_features = array(
			array(
				'title'       => esc_html__( 'Multiple Wishlist', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'pro_feature',
				'value'       => get_option( 'wfw-enable-multi-wishlist' ),
				'id'          => 'wfw-enable-multi-wishlist',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the multiple wishlist functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Create API routing', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'pro_feature',
				'value'       => get_option( 'wfw-enable-api-route' ),
				'id'          => 'wfw-enable-api-route',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the API routing functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'Automated Emails', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'pro_feature',
				'value'       => get_option( 'wfw-enable-automated-mail' ),
				'id'          => 'wfw-enable-automated-mail',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the automated emails functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
			array(
				'title'       => esc_html__( 'In-stock Notification', 'ultimate-wishlist-for-woocommerce' ),
				'class'       => 'mwb-wfw-toggle-checkbox',
				'type'        => 'pro_feature',
				'value'       => get_option( 'wfw-enable-instock-notif' ),
				'id'          => 'wfw-enable-instock-notif',
				'options'     => array(
					'yes' => esc_html__( 'YES', 'ultimate-wishlist-for-woocommerce' ),
					'no'  => esc_html__( 'NO', 'ultimate-wishlist-for-woocommerce' ),
				),
				'description' => esc_html__( 'Enable/Disable the in-stock notification functionality.', 'ultimate-wishlist-for-woocommerce' ),
			),
		);
		return $uwfw_advanced_features;
	}

	/**
	 * Ultimate Wishlist for WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_admin_save_tab_settings() {
		global $uwfw_mwb_uwfw_obj;

		if ( isset( $_POST['uwfw_button_demo'] )
			&& ( ! empty( $_POST['mwb_tabs_nonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_tabs_nonce'] ) ), 'admin_save_data' ) )
		) {
			$mwb_uwfw_gen_flag     = false;
			$uwfw_genaral_settings =
			// desc - filter for trial.
			apply_filters( 'uwfw_general_settings_array', array() );
			$uwfw_button_index = array_search( 'submit', array_column( $uwfw_genaral_settings, 'type' ), true );
			if ( isset( $uwfw_button_index ) && ( null === $uwfw_button_index || '' === $uwfw_button_index ) ) {
				$uwfw_button_index = array_search( 'button', array_column( $uwfw_genaral_settings, 'type' ), true );
			}
			if ( isset( $uwfw_button_index ) && '' !== $uwfw_button_index ) {
				unset( $uwfw_genaral_settings[ $uwfw_button_index ] );
				if ( is_array( $uwfw_genaral_settings ) && ! empty( $uwfw_genaral_settings ) ) {
					foreach ( $uwfw_genaral_settings as $uwfw_genaral_setting ) {
						if ( isset( $uwfw_genaral_setting['id'] ) && '' !== $uwfw_genaral_setting['id'] ) {
							if ( isset( $_POST[ $uwfw_genaral_setting['id'] ] ) ) {
								if ( is_array( $_POST[ $uwfw_genaral_setting['id'] ] ) && ! empty( $_POST[ $uwfw_genaral_setting['id'] ] ) ) {
									$mwb_uwfw_id = map_deep( wp_unslash( $_POST[ $uwfw_genaral_setting['id'] ] ), 'sanitize_text_field' );
								} else {
									$mwb_uwfw_id = sanitize_text_field( wp_unslash( $_POST[ $uwfw_genaral_setting['id'] ] ) );
								}
								update_option( $uwfw_genaral_setting['id'], $mwb_uwfw_id );
							} else {
								update_option( $uwfw_genaral_setting['id'], '' );
							}
						} else {
							$mwb_uwfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_uwfw_gen_flag ) {
					$mwb_uwfw_error_text = esc_html__( 'Id of some field is missing', 'ultimate-wishlist-for-woocommerce' );
					$uwfw_mwb_uwfw_obj->mwb_uwfw_plug_admin_notice( $mwb_uwfw_error_text, 'error' );
				} else {
					$mwb_uwfw_error_text = esc_html__( 'Settings saved !', 'ultimate-wishlist-for-woocommerce' );
					$uwfw_mwb_uwfw_obj->mwb_uwfw_plug_admin_notice( $mwb_uwfw_error_text, 'success' );
				}
			}
		}
		if ( isset( $_POST['uwfw_save_social_sharing'] )
		&& ( ! empty( $_POST['mwb_tabs_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_tabs_nonce'] ) ), 'admin_save_data' ) )
		) {
			$mwb_uwfw_gen_flag     = false;
			$uwfw_genaral_settings =
			// desc - filter for trial.
			apply_filters( 'uwfw_social_sharing_settings_array', array() );
			$uwfw_button_index = array_search( 'submit', array_column( $uwfw_genaral_settings, 'type' ), true );
			if ( isset( $uwfw_button_index ) && ( null == $uwfw_button_index || '' == $uwfw_button_index ) ) {
				$uwfw_button_index = array_search( 'button', array_column( $uwfw_genaral_settings, 'type' ), true );
			}
			if ( isset( $uwfw_button_index ) && '' !== $uwfw_button_index ) {
				unset( $uwfw_genaral_settings[ $uwfw_button_index ] );
				if ( is_array( $uwfw_genaral_settings ) && ! empty( $uwfw_genaral_settings ) ) {
					foreach ( $uwfw_genaral_settings as $uwfw_genaral_setting ) {
						if ( isset( $uwfw_genaral_setting['id'] ) && '' !== $uwfw_genaral_setting['id'] ) {
							if ( isset( $_POST[ $uwfw_genaral_setting['id'] ] ) ) {
								if ( is_array( $_POST[ $uwfw_genaral_setting['id'] ] ) && ! empty( $_POST[ $uwfw_genaral_setting['id'] ] ) ) {
									$mwb_uwfw_id = map_deep( wp_unslash( $_POST[ $uwfw_genaral_setting['id'] ] ), 'sanitize_text_field' );
								} else {
									$mwb_uwfw_id = sanitize_text_field( wp_unslash( $_POST[ $uwfw_genaral_setting['id'] ] ) );
								}
								update_option( $uwfw_genaral_setting['id'], $mwb_uwfw_id );
							} else {
								update_option( $uwfw_genaral_setting['id'], '' );
							}
						} else {
							$mwb_uwfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_uwfw_gen_flag ) {
					$mwb_uwfw_error_text = esc_html__( 'Id of some field is missing', 'ultimate-wishlist-for-woocommerce' );
					$uwfw_mwb_uwfw_obj->mwb_uwfw_plug_admin_notice( $mwb_uwfw_error_text, 'error' );
				} else {
					$mwb_uwfw_error_text = esc_html__( 'Settings saved !', 'ultimate-wishlist-for-woocommerce' );
					$uwfw_mwb_uwfw_obj->mwb_uwfw_plug_admin_notice( $mwb_uwfw_error_text, 'success' );
				}
			}
		}
	}

	/**
	 * Sanitation for an array.
	 *
	 * @param array $mwb_input_array Array return.
	 */
	public function uwfw_sanitize_array( $mwb_input_array ) {
		foreach ( $mwb_input_array as $key => $value ) {
			$key   = sanitize_text_field( wp_unslash( $key ) );
			$value = sanitize_text_field( wp_unslash( $value ) );
		}
		return $mwb_input_array;
	}
	/**
	 * Activation of a new site.
	 *
	 * @param array $new_site Array return.
	 */
	public function uwfw_standard_plugin_on_create_new_blog( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		// check if the plugin has been activated on the network.
		if ( is_plugin_active_for_network( 'ultimate-wishlist-for-woocommerce/ultimate-wishlist-for-woocommerce.php' ) ) {
			$blog_id = $new_site->blog_id;
			 // switch to newly created site.
			switch_to_blog( $blog_id );
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-activator.php';
			Ultimate_Wishlist_For_Woocommerce_Activator::uwfw_on_activation_create_table();
			// code to be executed when site is created, call any function from activation file.
			restore_current_blog();
		}
	}

}

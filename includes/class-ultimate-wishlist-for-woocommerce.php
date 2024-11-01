<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */
class Ultimate_Wishlist_For_Woocommerce {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 1.0.0
	 * @var   Ultimate_Wishlist_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $uwfw_onboard    To initializsed the object of class onboard.
	 */
	protected $uwfw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( defined( 'ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_VERSION' ) ) {

			$this->version = ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '1.0.4';
		}

		$this->plugin_name = 'ultimate-wishlist-for-woocommerce';

		$this->ultimate_wishlist_for_woocommerce_dependencies();
		$this->ultimate_wishlist_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->ultimate_wishlist_for_woocommerce_admin_hooks();
		} else {
			$this->ultimate_wishlist_for_woocommerce_public_hooks();
		}
		$this->ultimate_wishlist_for_woocommerce_common_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ultimate_Wishlist_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Ultimate_Wishlist_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Ultimate_Wishlist_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Ultimate_Wishlist_For_Woocommerce_Common. Defines all hooks for the common area.
	 * - Ultimate_Wishlist_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function ultimate_wishlist_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ultimate-wishlist-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'Ultimate_Wishlist_For_Woocommerce_Onboarding_Steps' ) ) {
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-onboarding-steps.php';
			}

			if ( class_exists( 'Ultimate_Wishlist_For_Woocommerce_Onboarding_Steps' ) ) {
				$uwfw_onboard_steps = new Ultimate_Wishlist_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ultimate-wishlist-for-woocommerce-public.php';

		}

		/**
		 * The class responsible for defining all helper functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-helper.php';

		/**
		 * The class responsible for defining all public wishlist templates.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-renderer.php';

		/**
		 * The class responsible for defining all wishlist operation.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';

		/**
		 * The class responsible for defining all wishlist operation.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-wishlist-for-woocommerce-shortcode-manager.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-ultimate-wishlist-for-woocommerce-common.php';

		$this->loader = new Ultimate_Wishlist_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ultimate_Wishlist_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function ultimate_wishlist_for_woocommerce_locale() {

		$plugin_i18n = new Ultimate_Wishlist_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define the name of the hook to save admin notices for this plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_saved_notice_hook_name() {
		$mwb_plugin_name                            = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
		$mwb_plugin_settings_saved_notice_hook_name = $mwb_plugin_name . '_settings_saved_notice';
		return $mwb_plugin_settings_saved_notice_hook_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function ultimate_wishlist_for_woocommerce_admin_hooks() {
		$uwfw_plugin_admin = new Ultimate_Wishlist_For_Woocommerce_Admin( $this->uwfw_get_plugin_name(), $this->uwfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $uwfw_plugin_admin, 'uwfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $uwfw_plugin_admin, 'uwfw_admin_enqueue_scripts' );

		// Add settings menu for Ultimate Wishlist for WooCommerce.
		$this->loader->add_action( 'admin_menu', $uwfw_plugin_admin, 'uwfw_options_page' );
		$this->loader->add_action( 'admin_menu', $uwfw_plugin_admin, 'uwfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $uwfw_plugin_admin, 'uwfw_admin_submenu_page', 15 );
		$this->loader->add_filter( 'uwfw_template_settings_array', $uwfw_plugin_admin, 'uwfw_admin_template_settings_page', 10 );
		$this->loader->add_filter( 'uwfw_general_settings_array', $uwfw_plugin_admin, 'uwfw_admin_general_settings_page', 10 );
		$this->loader->add_filter( 'uwfw_social_sharing_settings_array', $uwfw_plugin_admin, 'uwfw_admin_social_sharing_settings_page', 10 );
		$this->loader->add_filter( 'uwfw_advanced_features_array', $uwfw_plugin_admin, 'uwfw_advanced_features_page', 10 );

		// Saving tab settings.
		$this->loader->add_action( 'mwb_uwfw_settings_saved_notice', $uwfw_plugin_admin, 'uwfw_admin_save_tab_settings' );

		// Developer's Hook Listing.
		$this->loader->add_action( 'uwfw_developer_admin_hooks_array', $uwfw_plugin_admin, 'uwfw_developer_admin_hooks_listing' );
		$this->loader->add_action( 'uwfw_developer_public_hooks_array', $uwfw_plugin_admin, 'uwfw_developer_public_hooks_listing' );
		$this->loader->add_action( 'wp_initialize_site', $uwfw_plugin_admin, 'uwfw_standard_plugin_on_create_new_blog', 900 );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function ultimate_wishlist_for_woocommerce_common_hooks() {

		$uwfw_plugin_common = new Ultimate_Wishlist_For_Woocommerce_Common( $this->uwfw_get_plugin_name(), $this->uwfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $uwfw_plugin_common, 'uwfw_common_enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $uwfw_plugin_common, 'uwfw_common_enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_UpdateWishlist', $uwfw_plugin_common, 'uwfw_update_wishlist' );
		$this->loader->add_action( 'wp_ajax_UpdateWishlistMeta', $uwfw_plugin_common, 'uwfw_update_wishlist_meta' );
		$this->loader->add_action( 'wp_ajax_nopriv_UpdateWishlist', $uwfw_plugin_common, 'uwfw_update_wishlist' );
		$this->loader->add_action( 'wp_ajax_nopriv_UpdateWishlistMeta', $uwfw_plugin_common, 'uwfw_update_wishlist_meta' );
		$this->loader->add_action( 'wp_ajax_InvitationEmail', $uwfw_plugin_common, 'uwfw_invitation_email' );
		$this->loader->add_action( 'wp_ajax_nopriv_InvitationEmail', $uwfw_plugin_common, 'uwfw_invitation_email' );
		$this->loader->add_action( 'wp_ajax_wfw_get_item_details', $uwfw_plugin_common, 'uwfw_get_item_details' );
		$this->loader->add_action( 'wp_ajax_nopriv_wfw_get_item_details', $uwfw_plugin_common, 'uwfw_get_item_details' );
		$this->loader->add_action( 'wp_ajax_add_to_cart_wish_prod', $uwfw_plugin_common, 'uwfw_add_to_cart_wish_prod' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_to_cart_wish_prod', $uwfw_plugin_common, 'uwfw_add_to_cart_wish_prod' );
		$this->loader->add_action( 'wp_ajax_go_to_checkout_wish_prod', $uwfw_plugin_common, 'uwfw_go_to_checkout_wish_prod' );
		$this->loader->add_action( 'wp_ajax_nopriv_go_to_checkout_wish_prod', $uwfw_plugin_common, 'uwfw_go_to_checkout_wish_prod' );
		$this->loader->add_action( 'wp_ajax_delete_wish_prod', $uwfw_plugin_common, 'uwfw_delete_wish_prod' );
		$this->loader->add_action( 'wp_ajax_nopriv_delete_wish_prod', $uwfw_plugin_common, 'uwfw_delete_wish_prod' );
		$this->loader->add_action( 'wp_ajax_delete_current_wishlist', $uwfw_plugin_common, 'uwfw_delete_current_wishlist' );
		$this->loader->add_action( 'wp_ajax_nopriv_delete_current_wishlist', $uwfw_plugin_common, 'uwfw_delete_current_wishlist' );
		$this->loader->add_action( 'wp_ajax_wishlist_set_default', $uwfw_plugin_common, 'uwfw_wishlist_set_default' );
		$this->loader->add_action( 'wp_ajax_nopriv_wishlist_set_default', $uwfw_plugin_common, 'uwfw_wishlist_set_default' );
		// license validation.
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function ultimate_wishlist_for_woocommerce_public_hooks() {
		$is_plugin_enabled = get_option( 'wfw-enable-plugin', 'on' );
		if ( 'on' !== $is_plugin_enabled ) {
			return;
		}
		$uwfw_plugin_public = new Ultimate_Wishlist_For_Woocommerce_Public( $this->uwfw_get_plugin_name(), $this->uwfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $uwfw_plugin_public, 'uwfw_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $uwfw_plugin_public, 'uwfw_public_enqueue_scripts' );
		// public hooks.
		$this->loader->add_action( 'wp_head', $uwfw_plugin_public, 'uwfw_wishlist_init' );
		$this->loader->add_action( 'mwb_wishlist_before_add_to_add_to_cart', $uwfw_plugin_public, 'uwfw_mwb_wqv_quickview_button_and_modal', 10, 2 );
		$this->loader->add_action( 'wp_head', $uwfw_plugin_public, 'uwfw_wishlist_button_custom_color' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function uwfw_run() {
		$this->loader->uwfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
	public function uwfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Ultimate_Wishlist_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function uwfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Ultimate_Wishlist_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function uwfw_get_onboard() {
		return $this->uwfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function uwfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_uwfw_plug tabs.
	 *
	 * @return Array       An key=>value pair of Ultimate Wishlist for WooCommerce tabs.
	 */
	public function mwb_uwfw_plug_default_tabs() {

			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-overview']              = array(
				'title'     => esc_html__( 'Overview', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-overview',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-overview.php',
			);
			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-general']               = array(
				'title'     => esc_html__( 'General Setting', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-general',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-general.php',
			);
			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-social-sharing']        = array(
				'title'     => esc_html__( 'Social Sharing', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-social-sharing',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-social-sharing.php',
			);
			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-advanced-features']     = array(
				'title'     => esc_html__( 'Advanced Features', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-advanced-features',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-advanced-features.php',
			);
			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-performance-analytics'] = array(
				'title'     => esc_html__( 'Performance Analytics', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-performance-analytics',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-performance-analytics.php',
			);
			$uwfw_default_tabs =
			// desc - filter for trial.
			apply_filters( 'mwb_uwfw_plugin_standard_admin_settings_tabs', $uwfw_default_tabs );
			$uwfw_default_tabs['ultimate-wishlist-for-woocommerce-system-status'] = array(
				'title'     => esc_html__( 'System Status', 'ultimate-wishlist-for-woocommerce' ),
				'name'      => 'ultimate-wishlist-for-woocommerce-system-status',
				'file_path' => ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/ultimate-wishlist-for-woocommerce-system-status.php',
			);

			return $uwfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since 1.0.0
	 * @param string $path   path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_uwfw_plug_load_template( $path, $params = array() ) {

		// $uwfw_file_path = ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . $path;

		if ( file_exists( $path ) ) {

			include $path;
		} else {

			/* translators: %s: file path */
			$uwfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'ultimate-wishlist-for-woocommerce' ), $path );
			$this->mwb_uwfw_plug_admin_notice( $uwfw_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param string $uwfw_message Message to display.
	 * @param string $type        notice type, accepted values - error/update/update-nag.
	 * @since 1.0.0
	 */
	public static function mwb_uwfw_plug_admin_notice( $uwfw_message, $type = 'error' ) {

		$uwfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$uwfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$uwfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$uwfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$uwfw_classes .= 'notice-error is-dismissible';
		}

		$uwfw_notice  = '<div class="' . esc_attr( $uwfw_classes ) . '">';
		$uwfw_notice .= '<p>' . esc_html( $uwfw_message ) . '</p>';
		$uwfw_notice .= '</div>';

		echo wp_kses_post( $uwfw_notice );
	}


	/**
	 * Show WordPress and server info.
	 *
	 * @return Array $uwfw_system_data       returns array of all WordPress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_uwfw_plug_system_status() {
		global $wpdb;
		$uwfw_system_status    = array();
		$uwfw_wordpress_status = array();
		$uwfw_system_data      = array();

		// Get the web server.
		$uwfw_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$uwfw_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get the server's IP address.
		$uwfw_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$uwfw_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$uwfw_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'ultimate-wishlist-for-woocommerce' );

		// Get the server path.
		$uwfw_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'ultimate-wishlist-for-woocommerce' );

		// Get the OS.
		$uwfw_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get WordPress version.
		$uwfw_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get and count active WordPress plugins.
		$uwfw_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// See if this site is multisite or not.
		$uwfw_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'ultimate-wishlist-for-woocommerce' ) : __( 'No', 'ultimate-wishlist-for-woocommerce' );

		// See if WP Debug is enabled.
		$uwfw_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'ultimate-wishlist-for-woocommerce' ) : __( 'No', 'ultimate-wishlist-for-woocommerce' );

		// See if WP Cache is enabled.
		$uwfw_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'ultimate-wishlist-for-woocommerce' ) : __( 'No', 'ultimate-wishlist-for-woocommerce' );

		// Get the total number of WordPress users on the site.
		$uwfw_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get the number of published WordPress posts.
		$uwfw_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'ultimate-wishlist-for-woocommerce' );

		// Get PHP memory limit.
		$uwfw_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get the PHP error log path.
		$uwfw_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'ultimate-wishlist-for-woocommerce' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$uwfw_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get PHP max post size.
		$uwfw_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$uwfw_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$uwfw_system_status['php_architecture'] = '64-bit';
		} else {
			$uwfw_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$uwfw_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Show the number of processes currently running on the server.
		$uwfw_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'ultimate-wishlist-for-woocommerce' );

		// Get the memory usage.
		$uwfw_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$uwfw_system_status['is_windows']        = true;
			$uwfw_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'ultimate-wishlist-for-woocommerce' );
		}

		// Get the memory limit.
		$uwfw_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get the PHP maximum execution time.
		$uwfw_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'ultimate-wishlist-for-woocommerce' );

		// Get outgoing IP address.
		global $wp_filesystem;
		WP_Filesystem();
		$file_data = $wp_filesystem->get_contents( 'http://ipecho.net/plain' );

		$uwfw_system_status['outgoing_ip'] = ! empty( $file_data ) ? $file_data : esc_html__( 'N/A (File data not set.)', 'ultimate-wishlist-for-woocommerce' );

		$uwfw_system_data['php'] = $uwfw_system_status;
		$uwfw_system_data['wp']  = $uwfw_wordpress_status;

		return $uwfw_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param string $uwfw_components html to display.
	 * @since 1.0.0
	 */
	public function mwb_uwfw_plug_generate_html( $uwfw_components = array() ) {
		if ( is_array( $uwfw_components ) && ! empty( $uwfw_components ) ) {
			foreach ( $uwfw_components as $uwfw_component ) {
				if ( ! empty( $uwfw_component['type'] ) && ! empty( $uwfw_component['id'] ) ) {
					switch ( $uwfw_component['type'] ) {

						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
						<div class="mwb-form-group mwb-uwfw-<?php echo esc_attr( $uwfw_component['type'] ); ?>">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
							<?php if ( 'number' != $uwfw_component['type'] ) { ?>
												<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?></span>
						<?php } ?>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input
									class="mdc-text-field__input <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>" 
									name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"
									type="<?php echo esc_attr( $uwfw_component['type'] ); ?>"
									value="<?php echo ( isset( $uwfw_component['value'] ) ? esc_attr( $uwfw_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?>"
									>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'password':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input 
									class="mdc-text-field__input <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?> mwb-form__password" 
									name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"
									type="<?php echo esc_attr( $uwfw_component['type'] ); ?>"
									value="<?php echo ( isset( $uwfw_component['value'] ) ? esc_attr( $uwfw_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?>"
									>
									<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb-form-label" for="<?php echo esc_attr( $uwfw_component['id'] ); ?>"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea" for="text-field-hero-input">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label"><?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?></span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<span class="mdc-text-field__resizer">
										<textarea class="mdc-text-field__input <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>" id="<?php echo esc_attr( $uwfw_component['id'] ); ?>" placeholder="<?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $uwfw_component['value'] ) ? esc_textarea( $uwfw_component['value'] ) : '' ); // WPCS: XSS ok. ?></textarea>
									</span>
								</label>
							</div>
						</div>
							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb-form-label" for="<?php echo esc_attr( $uwfw_component['id'] ); ?>"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<div class="mwb-form-select">
									<select id="<?php echo esc_attr( $uwfw_component['id'] ); ?>" name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?><?php echo ( 'multiselect' === $uwfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $uwfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
							<?php
							foreach ( $uwfw_component['options'] as $uwfw_key => $uwfw_val ) {
								?>
									<option value="<?php echo esc_attr( $uwfw_key ); ?>"
										<?php
										if ( is_array( $uwfw_component['value'] ) ) {
											selected( in_array( (string) $uwfw_key, $uwfw_component['value'], true ), true );
										} else {
													selected( $uwfw_component['value'], (string) $uwfw_key );
										}
										?>
										>
										<?php echo esc_html( $uwfw_val ); ?>
									</option>
								<?php
							}
							?>
								</select>
									<label class="mdl-textfield__label" for="<?php echo esc_attr( $uwfw_component['id'] ); ?>"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>

							<?php
							break;

						case 'checkbox':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control mwb-pl-4">
								<div class="mdc-form-field">
									<div class="mdc-checkbox">
										<input 
										name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"
										type="checkbox"
										class="mdc-checkbox__native-control <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>"
										value="<?php echo ( isset( $uwfw_component['value'] ) ? esc_attr( $uwfw_component['value'] ) : '' ); ?>"
							<?php checked( $uwfw_component['value'], '1' ); ?>
										/>
										<div class="mdc-checkbox__background">
											<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
												<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
											</svg>
											<div class="mdc-checkbox__mixedmark"></div>
										</div>
										<div class="mdc-checkbox__ripple"></div>
									</div>
									<label for="checkbox-1"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control mwb-pl-4">
								<div class="mwb-flex-col">
							<?php
							foreach ( $uwfw_component['options'] as $uwfw_radio_key => $uwfw_radio_val ) {
								?>
										<div class="mdc-form-field">
											<div class="mdc-radio">
												<input
												name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
												value="<?php echo esc_attr( $uwfw_radio_key ); ?>"
												type="radio"
												class="mdc-radio__native-control <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>"
												<?php checked( $uwfw_radio_key, $uwfw_component['value'] ); ?>
												>
												<div class="mdc-radio__background">
													<div class="mdc-radio__outer-circle"></div>
													<div class="mdc-radio__inner-circle"></div>
												</div>
												<div class="mdc-radio__ripple"></div>
											</div>
											<label for="radio-1"><?php echo esc_html( $uwfw_radio_val ); ?></label>
										</div>
								<?php
							}
							?>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio-switch':
							?>

						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<div>
									<div class="mdc-switch">
										<div class="mdc-switch__track"></div>
										<div class="mdc-switch__thumb-underlay">
											<div class="mdc-switch__thumb"></div>
											<input name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>" type="checkbox" id="<?php echo esc_html( $uwfw_component['id'] ); ?>" value="on" class="mdc-switch__native-control <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>" role="switch" aria-checked="
											<?php
											if ( 'on' == $uwfw_component['value'] ) {
												echo 'true';
											} else {
												echo 'false';
											}
											?>
											"
											<?php checked( $uwfw_component['value'], 'on' ); ?>
											>
										</div>
									</div>
								</div>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'button':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label"></div>
							<div class="mwb-form-group__control">
								<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $uwfw_component['button_text'] ) ? esc_html( $uwfw_component['button_text'] ) : '' ); ?></span>
								</button>
							</div>
						</div>

							<?php
							break;

						case 'multi':
							?>
							<div class="mwb-form-group mwb-uwfw-<?php echo esc_attr( $uwfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
									</div>
									<div class="mwb-form-group__control">
							<?php
							foreach ( $uwfw_component['value'] as $component ) {
								?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
								<?php if ( 'number' != $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?></span>
							<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( $component['type'] ); ?>"
												value="<?php echo ( isset( $uwfw_component['value'] ) ? esc_attr( $uwfw_component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $uwfw_component['placeholder'] ) ? esc_attr( $uwfw_component['placeholder'] ) : '' ); ?>"
								<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : '' ); ?>
												>
											</label>
							<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'color':
						case 'date':
						case 'file':
							?>
							<div class="mwb-form-group mwb-uwfw-<?php echo esc_attr( $uwfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $uwfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label>
										<input 
										class="<?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>"
										name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $uwfw_component['type'] ); ?>"
										value="<?php echo ( isset( $uwfw_component['value'] ) ? esc_attr( $uwfw_component['value'] ) : '' ); ?>"
									<?php echo esc_html( ( 'date' === $uwfw_component['type'] ) ? 'max=' . gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . 'min=' . gmdate( 'Y-m-d' ) . '' : '' ); ?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'heading':
							?>
							<div class="mwb-form-group mwb-uwfw-<?php echo esc_attr( $uwfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<h5><b><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); ?></b></h5>
								</div>
								<div class="mwb-form-group__control">
								</div>
							</div>
							<?php
							break;

						case 'submit':
							?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo ( isset( $uwfw_component['name'] ) ? esc_html( $uwfw_component['name'] ) : esc_html( $uwfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $uwfw_component['id'] ); ?>"
								class="<?php echo ( isset( $uwfw_component['class'] ) ? esc_attr( $uwfw_component['class'] ) : '' ); ?>"
								value="<?php echo esc_attr( $uwfw_component['button_text'] ); ?>"
								>
							</td>
						</tr>
							<?php
							break;

						case 'pro_feature':
							?>

							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="" class="mwb-form-label"><?php echo ( isset( $uwfw_component['title'] ) ? esc_html( $uwfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
								</div>
								<div class="mwb-form-group__control">
								
										<div class="pro_feature_class">
											<?php echo esc_html__( 'PRO', 'ultimate-wishlist-for-woocommerce' ); ?>
										</div>
									
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $uwfw_component['description'] ) ? esc_attr( $uwfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;

						default:
							break;
					}
				}
			}
		}
	}

}

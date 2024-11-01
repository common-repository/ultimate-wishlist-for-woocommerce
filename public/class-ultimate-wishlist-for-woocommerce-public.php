<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace ultimate_wishlist_for_woocommerce_public.
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/public
 */
class Ultimate_Wishlist_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The path of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $public_path    The public class location.
	 */
	public $public_path;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->public_path = plugin_dir_path( __FILE__ );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-renderer.php';
		$this->render = Ultimate_Wishlist_For_Woocommerce_Renderer::get_instance();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function uwfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'public/css/ultimate-wishlist-for-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function uwfw_public_enqueue_scripts() {

		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-helper.php';
		$settings = Ultimate_Wishlist_For_Woocommerce_Helper::get_settings();
		$settings = 200 == $settings['status'] ? $settings['message'] : $settings;

		$strings = Ultimate_Wishlist_For_Woocommerce_Helper::get_strings();
		wp_register_script( $this->plugin_name, ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'public/js/ultimate-wishlist-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'mwb_wfw_obj',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'mobile_view' => wp_is_mobile(),
				'auth_nonce'  => wp_create_nonce( 'mwb_wfw_nonce' ),
				'strings'     => $strings ? $strings : array(),
				'settings'    => $settings ? $settings : array(),
				'user'        => get_current_user_id(),
				'permalink'   => ! empty( get_option( 'wfw-selected-page', '' ) ) ? get_page_link( get_option( 'wfw-selected-page', '' ) ) : false,
			)
		);
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name . '-swal-alert', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/swal.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-ui-dialog' );
		add_thickbox();
	}

	/**
	 *  Initiate all functionalities after woocommerce is initiated.
	 *
	 * @throws Some_Exception_Class If something interesting cannot happen.
	 * @author MakeWebBetter <plugins@makewebbetter.com>
	 */
	public function uwfw_wishlist_init() {
		$is_plugin_enabled = get_option( 'wfw-enable-plugin', 'on' );
		if ( 'on' !== $is_plugin_enabled ) {
			return;
		}

		// Enable wishlist popup.
		$this->uwfw_enable_wishlist_popup();

		// Enable wishlist at Public View.
		$this->uwfw_enable_wishlist_on_site();

		// Initiate a wishlist shortcode.
		$this->uwfw_init_shortcodes();
	}

	/**
	 *  Enable a wishlist dynamic popup to manage newly added items.
	 *
	 * @throws Exception If something interesting cannot happen.
	 * @author MakeWebBetter <plugins@makewebbetter.com>
	 */
	public function uwfw_enable_wishlist_popup() {

		if ( 'on' === get_option( 'wfw-enable-popup', 'on' ) ) {
			// Wishlist popup view html.
			if ( is_shop() || is_single() || is_cart() ) {
				add_action( 'wp_footer', array( $this, 'uwfw_render_wishlist_html' ) );
			}
		}
		do_action( 'enable_wishlist_popup' );

	}

	/**
	 *  Adds a wishlist dynamic popup to manage newly added items.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function uwfw_render_wishlist_html() {

		wc_get_template(
			'partials/ultimate-wishlist-for-woocommerce-processor.php',
			array(),
			'',
			$this->public_path
		);
	}

	/**
	 *  Enable wishlist at Public View.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function uwfw_enable_wishlist_on_site() {

		// Check if wishlist needs to be added on current view.
		$is_wishlist_enabled = get_option( 'wfw-view-type', 'icon' );
		if ( empty( $is_wishlist_enabled ) ) {
			return;
		}

		$shop_hook   = array();
		$single_hook = array();
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-renderer.php';
		switch ( $is_wishlist_enabled ) {
			case 'icon':
				$shop_hook   = Ultimate_Wishlist_For_Woocommerce_Renderer::get_icons_hooks( 'loop' );
				$shop_func   = 'return_wishlist_icon';
				$single_hook = Ultimate_Wishlist_For_Woocommerce_Renderer::get_icons_hooks( 'single' );
				$single_func = 'return_wishlist_icon';
				break;

			case 'button':
				$position  = get_option( 'wfw-loop-button-view', 'before_product_name' );
				$shop_hook = Ultimate_Wishlist_For_Woocommerce_Renderer::get_button_hooks( 'loop', $position );
				$shop_func = 'return_wishlist_button';

				$position    = get_option( 'wfw-product-button-view', 'before_add_to_cart' );
				$single_hook = Ultimate_Wishlist_For_Woocommerce_Renderer::get_button_hooks( 'single', $position );
				$single_func = 'return_wishlist_button';
				break;
		}
		do_action( 'enable_wishlist_on_site', $is_wishlist_enabled );

		if ( ! empty( $shop_hook ) && is_array( $shop_hook ) ) {
			add_action( $shop_hook['hook'], array( $this->render, $shop_func ), $shop_hook['priority'] );
		}

		if ( ! empty( $single_hook ) && is_array( $single_hook ) ) {
			add_action( $single_hook['hook'], array( $this->render, $single_func ), $single_hook['priority'] );
		}
	}

	/**
	 *  Enable wishlist shortcodes.
	 *
	 * @throws Exception If something interesting cannot happen.
	 * @author MakeWebBetter <plugins@makewebbetter.com>
	 */
	public function uwfw_init_shortcodes() {
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-shortcode-manager.php';
		// Init shortcode class.
		$shortcode = new Ultimate_Wishlist_For_Woocommerce_Shortcode_Manager( $this->public_path );
		do_action( 'init_shortcodes' );

		// wishlist page view.
		add_shortcode( 'mwb_wfw_wishlist', array( $shortcode, 'init' ) );
	}

	/**
	 * Function contains button and modal.
	 *
	 * @return void
	 * @param mixed $_id product id.
	 * @param mixed $wid_to_show wishlist id to show.
	 */
	public function uwfw_mwb_wqv_quickview_button_and_modal( $_id, $wid_to_show ) {

		$mwb_wqv_plugin_functionality_enabled = get_option( 'mwb_wqv_functionality_enabled' );
		$mwb_wqv_option_settings              = apply_filters( 'wqv_options_array', array() );
		$mwb_wqv_option_setting_array         = array();
		if ( is_array( $mwb_wqv_option_settings ) && ! empty( $mwb_wqv_option_settings ) ) {
			foreach ( $mwb_wqv_option_settings as $mwb_wqv_option_setting ) {
				$mwb_wqv_option_setting_array[ $mwb_wqv_option_setting['id'] ] = get_option( $mwb_wqv_option_setting['id'], 'no_value' );
			}
		}
		if ( ( 'on' !== $mwb_wqv_plugin_functionality_enabled ) || ( ( ( 'on' === $mwb_wqv_option_setting_array['mwb_wqv_exclude_product'] ) && ( in_array( strval( $_id ), (array) $mwb_wqv_option_setting_array['wqv_excluded_products_array'], true ) ) ) || ( ( 'on' === $mwb_wqv_option_setting_array['mwb_wqv_exclude_category'] ) && ( $this->uwfw_mwb_wqv_is_category_excluded( $_id ) ) ) ) ) {
			return;
		}
		global $post;
		if ( has_shortcode( $post->post_content, 'mwb_wfw_wishlist' ) ) {
			$mwb_wqv_general_settings      = apply_filters( 'mwqv_general_settings_array', array() );
			$mwb_wqv_general_setting_array = array();
			if ( is_array( $mwb_wqv_general_settings ) && ! empty( $mwb_wqv_general_settings ) ) {
				foreach ( $mwb_wqv_general_settings as $mwb_wqv_general_setting ) {
					$mwb_wqv_general_setting_array[ $mwb_wqv_general_setting['id'] ] = get_option( $mwb_wqv_general_setting['id'], 'no_value' );
				}
			}

			if ( 'link' === $mwb_wqv_general_setting_array['mwb_wqv_button_type'] ) {
				?>
				<p>
				<a data-mwb_wqv_prod_id="<?php echo esc_attr( $_id ); ?>" class="mwb_wqv_quickview_modal_link mwb_wqv_quickview_modal_button" data-wishid="<?php echo esc_html( $wid_to_show ); ?>" href="#"><?php esc_html_e( 'Quick View', 'ultimate-wishlist-for-woocommerce' ); ?></a>
				</p>
				<?php
			} elseif ( 'button' === $mwb_wqv_general_setting_array['mwb_wqv_button_type'] ) {
				?>
				<p>
				<button data-mwb_wqv_prod_id="<?php echo esc_attr( $_id ); ?>" class="mwb_wqv_quickview_modal_button" type="button" data-wishid="<?php echo esc_html( $wid_to_show ); ?>" style="color:<?php echo ( '' !== $mwb_wqv_general_setting_array['mwb_wqv_text_color_quickview_button'] && 'no_value' !== $mwb_wqv_general_setting_array['mwb_wqv_text_color_quickview_button'] ) ? esc_attr( $mwb_wqv_general_setting_array['mwb_wqv_text_color_quickview_button'] ) : ''; ?>;background-color: <?php echo ( '' !== $mwb_wqv_general_setting_array['mwb_wqv_button_bg_color'] && 'no_value' !== $mwb_wqv_general_setting_array['mwb_wqv_button_bg_color'] ) ? esc_attr( $mwb_wqv_general_setting_array['mwb_wqv_button_bg_color'] ) : ''; ?>;"><?php echo ( ( '' !== $mwb_wqv_general_setting_array['mwb_wqv_quickview_button_text'] ) ? esc_html( $mwb_wqv_general_setting_array['mwb_wqv_quickview_button_text'] ) : esc_html__( 'Quick View', 'ultimate-wishlist-for-woocommerce' ) ); ?></button>
				</p>
				<?php
			}
		}
	}

	/**
	 * Function to check category excluded or not.
	 *
	 * @param int $mwb_wqv_current_prod_id contains current product id.
	 * @return boolean
	 */
	public function uwfw_mwb_wqv_is_category_excluded( $mwb_wqv_current_prod_id ) {
		$mwb_wqv_flag                             = 0;
		$mwb_wqv_current_product_categories_array = wp_get_post_terms( $mwb_wqv_current_prod_id, 'product_cat', array( 'fields' => 'ids' ) );
		$mwb_wqv_option_settings                  = apply_filters( 'wqv_options_array', array() );
		$mwb_wqv_option_setting_array             = array();
		if ( is_array( $mwb_wqv_option_settings ) && ! empty( $mwb_wqv_option_settings ) ) {
			foreach ( $mwb_wqv_option_settings as $mwb_wqv_option_setting ) {
				$mwb_wqv_option_setting_array[ $mwb_wqv_option_setting['id'] ] = get_option( $mwb_wqv_option_setting['id'], 'no_value' );
			}
		}

		if ( is_array( $mwb_wqv_option_setting_array['wqv_excluded_categories_array'] ) ) {
			foreach ( $mwb_wqv_option_setting_array['wqv_excluded_categories_array'] as $wqv_excluded_categories_arraykey => $wqv_excluded_categories_array_value ) {
				if ( in_array( (int) $wqv_excluded_categories_array_value, $mwb_wqv_current_product_categories_array, true ) ) {
					$mwb_wqv_flag++;
				}
			}
		}
		if ( 0 < $mwb_wqv_flag ) {
			return true;
		} elseif ( 0 === $mwb_wqv_flag ) {
			return false;
		}
	}

	/**
	 *  Wishlist button color at Public View.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function uwfw_wishlist_button_custom_color() {

		// Check if wishlist needs to be added on current view.
		$is_wishlist_enabled = get_option( 'wfw-view-type', 'icon' );
		if ( empty( $is_wishlist_enabled ) ) {
			return;
		}
		$mwb_wqv_wishlist_button_color = get_option( 'wfw-add-to-wishlist-button-color', '#59B0F6' );
		if ( 'button' === $is_wishlist_enabled ) {
			?>
			<style>

				.add-to-wishlist.mwb-wfw-loop-text-button, .related .add-to-wishlist.mwb-wfw-loop-text-button {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}

				.add-to-wishlist.mwb-wfw-loop-text-button, .related .add-to-wishlist.mwb-wfw-loop-text-button {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}
				.add-to-wishlist.mwb-wfw-loop-text-button:hover {
					border: 2px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}
				.add-to-wishlist.mwb-wfw-single-text-button {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}

				.add-to-wishlist.mwb-wfw-single-text-button:hover {
					border: 2px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}
				#wfw_email_invite_form input[name='wfw_invite_send_button'],
				.wfw_comment_cancel,
				.mwb-wfw-wishlist-item .mwb-wfw-wishlist-action-buttons a,
				.wfw_comment_save {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					border: 2px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}
				.mwb-wfw-wishlist-item .mwb-wfw-wishlist-action-buttons a.mwb-wfw-action-button:last-child {
					border: 2px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}
				.action_button,
				.action_delete {
					border: 1px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}

				.action_button {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					border: 1px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: #ffffff;
				}

				.action_button:hover {
					border: 1px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}

				.action_delete:hover {
					background-color: <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
					border: 1px solid <?php echo esc_html( $mwb_wqv_wishlist_button_color ); ?>;
				}

			</style>
			<?php
		}
	}
	// End of class.
}

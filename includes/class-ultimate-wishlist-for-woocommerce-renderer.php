<?php
/**
 * The wishlist public facing templates are handled here.
 *
 * @since      1.0.0
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * The wishlist public facing templates are handled here.
 *
 * @since      1.0.0
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */
class Ultimate_Wishlist_For_Woocommerce_Renderer {

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var Ultimate_Wishlist_For_Woocommerce_Renderer   The single instance of the Ultimate_Wishlist_For_Woocommerce_Renderer
	 */
	protected static $_instance = null;

	/**
	 * Main Ultimate_Wishlist_For_Woocommerce_Renderer Instance.
	 *
	 * Ensures only one instance of Ultimate_Wishlist_For_Woocommerce_Renderer is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Ultimate_Wishlist_For_Woocommerce_Renderer - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Get hooks for Icons implementation on shop.
	 *
	 * @param   Place $template  Hooks for woo template needs to be returned.
	 * @throws Exception If something interesting cannot happen.
	 * @author MakeWebBetter <plugins@makewebbetter.com>
	 * @return array $hooks all hooks to be implemented.
	 */
	public static function get_icons_hooks( $template = '' ) {

		$loop = array(
			'hook'     => 'woocommerce_after_shop_loop_item',
			'priority' => '1',
		);

		$single = array(
			'hook'     => 'woocommerce_product_thumbnails',
			'priority' => '10',
		);

		/**
		 * Product Image hooks.
		 */
		$all_hooks = array(
			'loop'   => $loop,
			'single' => $single,
		);

		return ! empty( $all_hooks[ $template ] ) ? $all_hooks[ $template ] : array();
	}

	/**
	 *  Get hooks for Buttons implementation on shop.
	 *
	 * @param   array $template  Hooks for woo template needs to be returned.
	 * @param   array $option    Options to be fetched.
	 * @throws  Exception If something interesting cannot happen.
	 * @return  array $hooks all hooks to be implemented.
	 */
	public static function get_button_hooks( $template = '', $option = '' ) {

		/**
		 * All options available for loops.
		 */
		$loop_hooks = array(

			'before_product_loop' => array(
				'hook'     => 'woocommerce_before_shop_loop_item',
				'priority' => '2',
			),
			'before_product_name' => array(
				'hook'     => 'woocommerce_shop_loop_item_title',
				'priority' => '1',
			),
			'after_product_name'  => array(
				'hook'     => 'woocommerce_shop_loop_item_title',
				'priority' => '10',
			),
			'before_add_to_cart'  => array(
				'hook'     => 'woocommerce_after_shop_loop_item',
				'priority' => '1',
			),
		);

		/**
		 * All options available for Product page.
		 */
		$single_hooks = array(
			'before_add_to_cart'  => array(
				'hook'     => 'woocommerce_before_add_to_cart_form',
				'priority' => '10',
			),
			'after_add_to_cart'   => array(
				'hook'     => 'woocommerce_after_add_to_cart_button',
				'priority' => '10',
			),
			'before_product_name' => array(
				'hook'     => 'woocommerce_single_product_summary',
				'priority' => '1',
			),
			'after_product_name'  => array(
				'hook'     => 'woocommerce_single_product_summary',
				'priority' => '5',
			),
			'after_product_price' => array(
				'hook'     => 'woocommerce_single_product_summary',
				'priority' => '10',
			),
		);

		// Merged Output.
		$all_hooks = array(
			'loop'   => $loop_hooks,
			'single' => $single_hooks,
		);

		return ! empty( $all_hooks[ $template ][ $option ] ) ? $all_hooks[ $template ][ $option ] : array();
	}

	/**
	 *  Returns HTML for wishlist Text Button.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function return_wishlist_button() {

		global $product;
		$new_list      = apply_filters( 'check_for_product_existence', $product->get_id() );
		$search_result = $this->does_wishlist_includes_product( $product->get_id() );
		$accept_text   = apply_filters( 'mwb_wfw_wishlist_accept_text', esc_html__( 'Add to Wishlist', 'ultimate-wishlist-for-woocommerce' ) );
		$remove_text   = apply_filters( 'mwb_wfw_wishlist_remove_text', esc_html__( 'Remove from Wishlist', 'ultimate-wishlist-for-woocommerce' ) );
		$image_id      = $product->get_image_id();
		$image_url     = '';

		if ( empty( $image_id ) ) {
			$image_url = wc_placeholder_img_src();
		} else {
			$image_url = wp_get_attachment_image_url( $image_id );
		}
		if ( 200 == $search_result['status'] ) {
			$wishlist  = reset( $search_result['message'] );
			$wid       = $wishlist['id'] ? $wishlist['id'] : '';
			$is_active = $wid ? 'active-wishlist' : '';
			$text      = ! empty( $wid ) ? $remove_text : $accept_text;
		} else {
			$is_active = '';
			$wid       = '';
			$text      = $accept_text;
		}

		$default_attr = apply_filters(
			'mwb_wfw_wishlist_attr',
			array(
				'text'        => $text,
				'extra_class' => '',
				'style'       => '',
			)
		);

			$cate = get_queried_object();
		if ( is_shop() ) {
			$default_attr['extra_class'] = 'mwb-wfw-loop-button';
			?>
			<style>
				.add-to-wishlist.mwb-wfw-loop-text-button {
				margin: 10px auto;
				}
				</style>
			<?php
		}

		?>
		<a href="javascript:void(0);" data-wishlist-id="<?php echo esc_attr( $wid ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" style="<?php echo esc_attr( $default_attr['style'] ); ?>" class="add-to-wishlist <?php echo esc_attr( $is_active ); ?> mwb-wfw-loop-text-button mwb-<?php echo esc_html( str_replace( '_', '-', current_action() ) ); ?>-loop <?php echo esc_attr( $default_attr['extra_class'] ); ?>"><?php echo esc_attr( $default_attr['text'] ); ?></a>
		<input type="hidden" class="mwb-wfw-product-image" value="<?php echo esc_url( $image_url ); ?>">
		<a href="javascript:void(0);"class="processing-button"><img class="mwb-wfw-processing-icon" src="<?php echo esc_url( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'public/icons/processing.gif' ); ?>"></a>
		<?php
	}

	/**
	 *  Returns HTML for wishlist icon button.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function return_wishlist_icon() {

		$default_attr = apply_filters(
			'mwb_wfw_wishlist_attr',
			array(
				'text'        => apply_filters( 'mwb_wfw_wishlist_icon', get_option( 'wfw-icon-view', 'heart' ) ),
				'extra_class' => '',
				'style'       => '',
			)
		);

		global $product;
		$search_result = $this->does_wishlist_includes_product( $product->get_id() );
		$image_id      = $product->get_image_id();
		$image_url     = '';

		if ( empty( $image_id ) ) {
			$image_url = wc_placeholder_img_src();
		} else {
			$image_url = wp_get_attachment_image_url( $image_id );
		}
		if ( is_product() ) {
			?>
		<style>
			figure.woocommerce-product-gallery__wrapper a.add-to-wishlist.mwb-wfw-loop-icon-button {
			top: 10px;
			left: 10px;
		}
		</style>
			<?php
		}

		// Wishlist Exists?
		if ( 200 == $search_result['status'] ) {

			$wishlist  = reset( $search_result['message'] );
			$wid       = $wishlist['id'] ? $wishlist['id'] : '';
			$is_active = $wid ? 'active-wishlist' : '';
		} else {
			$wid       = '';
			$is_active = '';
		}
		?>
			<input type="hidden" class="mwb-wfw-product-image" value="<?php echo esc_url( $image_url ); ?>">
			<a href="javascript:void(0);" data-wishlist-id="<?php echo esc_attr( $wid ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" style="<?php echo esc_attr( $default_attr['style'] ); ?>" class="add-to-wishlist <?php echo esc_attr( $is_active ); ?> mwb-wfw-loop-icon-button mwb-<?php echo esc_html( str_replace( '_', '-', current_action() ) ); ?>-icon <?php echo esc_attr( $default_attr['extra_class'] ); ?>">
			<img class="mwb-wfw-icon" src="<?php echo esc_url( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'public/icons/' . $default_attr['text'] . '.svg' ); ?>" >
			</a>
		<?php
	}

	/**
	 *  Returns HTML for wishlist icon button.
	 *
	 * @throws Exception If something interesting cannot happen.
	 * @param array $html Icon html.
	 */
	public function return_wishlist_icon_single_page( $html ) {

		$default_attr = apply_filters(
			'mwb_wfw_wishlist_attr',
			array(
				'text'        => apply_filters( 'mwb_wfw_wishlist_icon', get_option( 'wfw-icon-view', 'heart' ) ),
				'extra_class' => '',
				'style'       => '',
			)
		);

		global $product;
		$search_result = $this->does_wishlist_includes_product( $product->get_id() );
		$image_id      = $product->get_image_id();
		$image_url     = '';

		if ( empty( $image_id ) ) {
			$image_url = wc_placeholder_img_src();
		} else {
			$image_url = wp_get_attachment_image_url( $image_id );
		}

		// Wishlist Exists?
		if ( 200 == $search_result['status'] ) {

			$wishlist  = reset( $search_result['message'] );
			$wid       = $wishlist['id'] ? $wishlist['id'] : '';
			$is_active = $wid ? 'active-wishlist' : '';
		} else {
			$wid       = '';
			$is_active = '';
		}

		$html = $html . '<input type="hidden" class="mwb-wfw-product-image" value="' . esc_url( $image_url ) . '" >
			<a href="javascript:void(0);" data-wishlist-id="' . esc_attr( $wid ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '" style="' . esc_attr( $default_attr['style'] ) . '" class="add-to-wishlist ' . esc_attr( $is_active ) . ' mwb-wfw-loop-icon-button mwb-' . esc_html( str_replace( '_', '-', current_action() ) ) . '-icon ' . esc_attr( $default_attr['extra_class'] ) . ' >
			<img class="mwb-wfw-icon" src="' . esc_url( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'public/icons/' . $default_attr['text'] . '.svg' ) . '" >
			</a>';
		return $html;
	}
	/**
	 * Checks if any current user wishlist have this product or not.
	 *
	 * @param  string $product_id string product id to search.
	 * @throws Exception If something interesting cannot happen.
	 * @return bool true|false
	 */
	public function does_wishlist_includes_product( $product_id = false ) {
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';

		$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance();

		$user_id = get_current_user_id();
		return $wishlist_manager->retrieve_second( 'prod_id', $product_id, $user_id, array( 'products' => $product_id ) );
	}

	// End of class.
}

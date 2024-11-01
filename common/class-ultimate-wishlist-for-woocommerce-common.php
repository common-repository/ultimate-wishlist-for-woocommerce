<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/common
 */

/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace ultimate_wishlist_for_woocommerce_common.
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/common
 */
class Ultimate_Wishlist_For_Woocommerce_Common {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	}

	/**
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function uwfw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'common/css/ultimate-wishlist-for-woocommerce-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function uwfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'common/js/ultimate-wishlist-for-woocommerce-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'common', 'uwfw_common_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'common' );
	}

	/**
	 *  Ajax Callback :: Adds a product to wishlist.
	 *
	 * @throws Exception If something interesting cannot happen.
	 */
	public function uwfw_update_wishlist() {
		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-renderer.php';
		$formdata = ! empty( $_POST ) ? map_deep( wp_unslash( $_POST ), 'sanitize_text_field' ) : array();
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		unset( $formdata['nonce'] );
		$wishlist_manager   = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance();
		$product_data       = wc_get_product( $formdata['productId'] );
		$product_data_price = $product_data->get_price();
		$currency           = get_woocommerce_currency();
		$product            = wc_get_product( $formdata['productId'] );
		if ( $product->get_stock_quantity() > 0 ) {
			$stock = 'in';
		} else {
			$stock = 'out';
		}
		if ( ! array_key_exists( 'list', $formdata ) ) {
			$formdata['list'] = 'Wishlist #1';
		}
		if ( 'add' == $formdata['task'] ) {
			if ( is_user_logged_in() ) {
				$pid = ! empty( $formdata['productId'] ) ? $formdata['productId'] : '';
				if ( empty( $pid ) ) {
					$result = array(
						'status'  => 404,
						'message' => esc_html__( 'Invalid Request', 'ultimate-wishlist-for-woocommerce' ),
					);
					return json_encode( $result );
				}
					$user = wp_get_current_user();

					$wishlist_email = $wishlist_manager->retrieve_email_admin( 'title', 'owner', $user->user_email );
				if ( 200 == $wishlist_email['status'] && count( $wishlist_email['message'] ) ) {
					$wishlist_admin_list = array();
					$wishlist_email      = $wishlist_email['message'];
					foreach ( $wishlist_email as $key => $value ) {
						array_push( $wishlist_admin_list, $value['title'] );
					}
				}
				if ( in_array( $formdata['list'], $wishlist_admin_list ) ) {

					$wishlist_query = $wishlist_manager->retrieve_current_wishlist_id( 'id', $user->user_email, $formdata['list'], array( 'properties' => array( 'default' => true ) ) );
					if ( 200 == $wishlist_query['status'] && count( $wishlist_query['message'] ) ) {

						$wishlist = reset( $wishlist_query['message'] );

						$wid = ! empty( $wishlist['id'] ) ? $wishlist['id'] : '';
						if ( empty( $wid ) ) {
							$result = array(
								'status'  => 404,
								'message' => esc_html__( 'Invalid Request', 'ultimate-wishlist-for-woocommerce' ),
							);
							return json_encode( $result );
						} else {
							$renderer_obj = new Ultimate_Wishlist_For_Woocommerce_Renderer();

							$check_product_exist = $renderer_obj->does_wishlist_includes_product( $formdata['productId'] );
							$args_list           = array(
								'prod_id'          => $formdata['productId'],
								'quantity'         => 1,
								'user_id'          => get_current_user_id(),
								'wishlist_id'      => $wid,
								'orignal_price'    => $product_data_price,
								'orignal_currency' => $currency,
								'on_sale'          => $product_data->is_on_sale(),
								'stock'            => $stock,
							);
							if ( 200 == $check_product_exist['status'] ) {
								$result = $check_product_exist;
							} else {
								$result = $wishlist_manager->create_list( $args_list );
							}
						}
					}
				} else {
					$wishlist_query = $wishlist_manager->retrieve( 'owner', $user->user_email, array( 'properties' => array( 'default' => true ) ) );
					if ( 200 == $wishlist_query['status'] && count( $wishlist_query['message'] ) ) {
						$arr = array( 'default' => false );
					} else {
						$arr = array( 'default' => true );
					}
					if ( 'Wishlist #1' != $formdata['list'] ) {
						$privacy = get_user_meta( get_current_user_id(), $formdata['list'], true );
					} else {
						$privacy = 'private';
					}
					$args       = array(
						'title'          => $formdata['list'],
						'createdate'     => gmdate( 'Y-m-d h:i:s' ),
						'modifieddate'   => gmdate( 'Y-m-d h:i:s' ),
						'owner'          => $user->user_email,
						'status'         => $privacy,
						'collaborators'  => array(),
						'properties'     => $arr,
						'user_id'        => get_current_user_id(),
						'session_id'     => 'NONE',
						'wishlist_token' => 'abc',
					);
					$result     = $wishlist_manager->create( $args );
					$args_list  = array(
						'prod_id'          => $formdata['productId'],
						'quantity'         => 1,
						'user_id'          => get_current_user_id(),
						'wishlist_id'      => $result['id'],
						'orignal_price'    => $product_data_price,
						'orignal_currency' => $currency,
						'on_sale'          => $product_data->is_on_sale(),
						'stock'            => $stock,
					);
					$result_two = $wishlist_manager->create_list( $args_list );

				}
				do_action( 'update_wishlist_add' );
			} else {
				$mwb_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';
				global $wpdb;
				$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';
				$response_fir    = $wpdb->get_results( $wpdb->prepare( "SELECT `id` FROM %1s WHERE `session_id` = '%2s'", $mwb_first_table, $mwb_key ), ARRAY_A );
				$response_fir    = reset( $response_fir );
				$wid             = $response_fir['id'];
				if ( $wid ) {
					$renderer_obj        = new Ultimate_Wishlist_For_Woocommerce_Renderer();
					$check_product_exist = $renderer_obj->does_wishlist_includes_product( $formdata['productId'] );
					$args_list           = array(
						'prod_id'          => $formdata['productId'],
						'quantity'         => 1,
						'user_id'          => get_current_user_id(),
						'wishlist_id'      => $wid,
						'orignal_price'    => $product_data_price,
						'orignal_currency' => $currency,
						'on_sale'          => $product_data->is_on_sale(),
						'stock'            => $stock,
					);
					if ( 200 == $check_product_exist['status'] ) {
						$result = $check_product_exist;
					} else {
						$result = $wishlist_manager->create_list( $args_list );
					}
				} else {
					if ( 'Wishlist #1' != $formdata['list'] ) {
						$privacy = get_user_meta( get_current_user_id(), $formdata['list'], true );
					} else {
						$privacy = 'private';
					}
					$mwb_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';
					if ( $mwb_key ) {
						$session_id = $mwb_key;
					} else {
						$random_cookie = substr( md5( microtime() ), wp_rand( 0, 26 ), 15 );
						setcookie( 'mwb_cookie_data', $random_cookie, time() + ( 86400 * 4 ), '/' );
						$session_id = $random_cookie;
						WC()->session->set( 'mwb_session_id', $random_cookie );
					}
					$args       = array(
						'title'          => $formdata['list'],
						'createdate'     => gmdate( 'Y-m-d h:i:s' ),
						'modifieddate'   => gmdate( 'Y-m-d h:i:s' ),
						'owner'          => '',
						'status'         => $privacy,
						'collaborators'  => array(),
						'properties'     => array( 'default' => true ),
						'user_id'        => '',
						'session_id'     => $session_id,
						'wishlist_token' => 'abc',
					);
					$result     = $wishlist_manager->create( $args );
					$args_list  = array(
						'prod_id'          => $formdata['productId'],
						'quantity'         => 1,
						'user_id'          => get_current_user_id(),
						'wishlist_id'      => $result['id'],
						'orignal_price'    => $product_data_price,
						'orignal_currency' => $currency,
						'on_sale'          => $product_data->is_on_sale(),
						'stock'            => $stock,
					);
					$result_two = $wishlist_manager->create_list( $args_list );
				}
			}
		} elseif ( 'remove' === $formdata['task'] ) {

			$wid = ! empty( $formdata['wishlistId'] ) ? $formdata['wishlistId'] : '';
			$pid = ! empty( $formdata['productId'] ) ? $formdata['productId'] : '';

			if ( empty( $wid ) || ! is_numeric( $wid ) ) {

				return array(
					'status'  => 404,
					'message' => esc_html__( 'Invalid Request', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
			$wishlist_manager->id = $wid;
			if ( $wid ) {
				$result = $wishlist_manager->delete_second( $wid );
			}
			do_action( 'update_wishlist_remove' );
		}
		$result['id'] = $wishlist_manager->id;
		echo wp_json_encode( $result );
		wp_die();
	}

	/**
	 * Ajax callback for Meta Updates.
	 */
	public function uwfw_update_wishlist_meta() {
		do_action( 'mwb_updatewishlistmeta' );

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );

		$result = array();

		$formdata = ! empty( $_POST['formData'] ) ? map_deep( wp_unslash( $_POST['formData'] ), 'sanitize_text_field' ) : false;

		$formdata = $this->uwfw_parse_serialised_data( $formdata );

		$wishlist_manager       = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance();
		$wishlist_manager->id   = $formdata['wid'] ? $formdata['wid'] : false;
		$properties             = $wishlist_manager->get_prop( 'properties' );
		$properties             = ! is_array( $properties ) ? json_decode( json_encode( $properties ), true ) : $properties;
		$properties['comments'] = $properties['comments'] ? $properties['comments'] : array();

		$properties['comments'][ $formdata['product'] ] = $formdata;

		unset( $properties['comments'][ $formdata['wid'] ]['wid'] );
		unset( $properties['comments'][ $formdata['wid'] ]['product'] );

		$args['properties'] = $properties;

		$response = $wishlist_manager->update( $args );

		if ( 200 == $response['status'] ) {

			// $properties
			$result = array(
				'status'  => true,
				'message' => esc_html__( 'Comment added successfully.', 'ultimate-wishlist-for-woocommerce' ),
			);
		} else {
			$result = array(
				'status'  => false,
				'message' => esc_html__( 'There\'s some problem adding comment, try again.', 'ultimate-wishlist-for-woocommerce' ),
			);
		}

		wp_send_json( $result );
	}

	/**
	 * Ajax callback for Email Invitation.
	 */
	public function uwfw_invitation_email() {
		do_action( 'mwb_invitation_email' );

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		$result = array();

		$email = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : false;
		$id    = ! empty( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';

		if ( empty( $email ) || empty( $id ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Email field cannot be empty.', 'ultimate-wishlist-for-woocommerce' ),
			);
		}

		if ( false != $email && ! empty( $id ) ) {

			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $id );

			$wishlist_title = $wishlist_manager->get_prop( 'title' );
			$collaborators  = $wishlist_manager->get_prop( 'collaborators' );

			$link = get_permalink( get_option( 'wfw-selected-page', '' ) );
			require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-helper.php';
			if ( ! empty( $link ) ) {
				$link = add_query_arg(
					array(
						'wl-ref' => Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $id ),
					),
					$link
				);

			}

			$subject = apply_filters( 'wfw_invite_email_subject', 'Join as a collaborator' );
			$message = apply_filters( 'wfw_invite_email_messgae', 'You are now added as a collaborator to this wishlist ' . $wishlist_title . '. Visit the page here ' . $link );

			if ( ! function_exists( 'wp_mail' ) ) {

				$result = array(
					'status'  => false,
					'message' => esc_html__( 'At the moment, you are not allowed to send this mail', 'ultimate-wishlist-for-woocommerce' ),
				);
			}

			wp_mail(
				$email,
				$subject,
				$message,
				array(
					'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>',
				)
			);
			array_push( $collaborators, $email );

			$args['collaborators'] = $collaborators;

			$update = $wishlist_manager->update( $args );
			if ( 200 == $update['status'] ) {

				$result = array(
					'status'  => true,
					'message' => esc_html__( 'Invite sent successfully', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Invitation failed', 'ultimate-wishlist-for-woocommerce' ),
				);
			}

			wp_send_json( $result );
		}
	}

	/**
	 * Details popup modal content.
	 */
	public function uwfw_get_item_details() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid     = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';
		$prod_id = ! empty( $_POST['pro_id'] ) ? sanitize_text_field( wp_unslash( $_POST['pro_id'] ) ) : '';

		$result = array();

		if ( empty( $wid ) || empty( $prod_id ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'No data found', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) && ! empty( $prod_id ) ) {

			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );

			$properties = $wishlist_manager->get_prop( 'properties' );

			if ( ! empty( $properties ) ) {
				if ( isset( $properties->comments ) ) {

					if ( ! empty( $properties->comments->$prod_id ) ) {

						if ( ! empty( $properties->comments->$prod_id->comment ) ) {

							$result = array(
								'status'  => true,
								'message' => $properties->comments->$prod_id->comment,
							);
						} else {
							$result = array(
								'status'  => false,
								'message' => esc_html__( 'No comments found for this product. ', 'ultimate-wishlist-for-woocommerce' ),
							);
						}
					} else {
						$result = array(
							'status'  => false,
							'message' => esc_html__( 'No comments found for this product. ', 'ultimate-wishlist-for-woocommerce' ),
						);
					}
				} else {
					$result = array(
						'status'  => false,
						'message' => esc_html__( 'No comments found.', 'ultimate-wishlist-for-woocommerce' ),
					);
				}
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'No properties found.', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
		}

		do_action( 'mwb_wfw_get_item_details', $result );

		wp_send_json( $result );

	}

	/**
	 * Add to cart wish list product.
	 */
	public function uwfw_add_to_cart_wish_prod() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid     = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';
		$prod_id = ! empty( $_POST['pro_id'] ) ? sanitize_text_field( wp_unslash( $_POST['pro_id'] ) ) : '';

		$result = array();

		if ( empty( $wid ) || empty( $prod_id ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) && ! empty( $prod_id ) ) {
			$product = wc_get_product( $prod_id );

			if ( $product ) {

				if ( 'instock' == $product->get_stock_status() || $product->backorders_allowed() ) {

					if ( $product->is_type( 'variable' ) ) {

						$result = array(
							'status'   => true,
							'variable' => true,
							'message'  => get_permalink( $prod_id ),
						);

					} elseif ( function_exists( 'WC' ) ) {
						if ( WC()->cart->add_to_cart( $prod_id, 1 ) != '' ) {
							$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );

							$response = $wishlist_manager->get_prop_delete( $wid, $prod_id );
							wc_add_to_cart_message( array( $prod_id => 1 ), true );
							$result = array(
								'status'   => true,
								'variable' => false,
								'message'  => wc_get_checkout_url(),
							);
						}
					}
				}
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Product does not exists', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
		}
		do_action( 'add_to_cart_wish_prod', $result );

		wp_send_json( $result );

	}

	/**
	 * Remove product and go to checkout.
	 */
	public function uwfw_go_to_checkout_wish_prod() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid     = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';
		$prod_id = ! empty( $_POST['pro_id'] ) ? sanitize_text_field( wp_unslash( $_POST['pro_id'] ) ) : '';

		$result = array();

		if ( empty( $wid ) || empty( $prod_id ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) && ! empty( $prod_id ) ) {
			global $wpdb;
			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );
			$response         = $wishlist_manager->get_prop_delete( $wid, $prod_id );
			if ( 200 == $response['status'] ) {
				$result = array(
					'status'  => true,
					'message' => wc_get_checkout_url(),
				);
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Something went wrong', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
		}
		do_action( 'go_to_checkout_wish_prod', $result );
		wp_send_json( $result );

	}

	/**
	 * Delete product from wishlist.
	 */
	public function uwfw_delete_wish_prod() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid     = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';
		$prod_id = ! empty( $_POST['pro_id'] ) ? sanitize_text_field( wp_unslash( $_POST['pro_id'] ) ) : '';

		$result = array();

		if ( empty( $wid ) || empty( $prod_id ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) && ! empty( $prod_id ) ) {

			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );

			$response = $wishlist_manager->get_prop_delete( $wid, $prod_id );
			if ( 200 == $response['status'] ) {

				$result = array(
					'status'  => true,
					'message' => esc_html__( 'Product deleted successfully', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Something went wrong', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Delete current wish list.
	 */
	public function uwfw_delete_current_wishlist() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';

		$result = array();

		if ( empty( $wid ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) ) {

			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );

			$response = $wishlist_manager->get_prop_delete_bulk( $wid );

			if ( 200 == $response['status'] ) {

				$result = array(
					'status'  => true,
					'reload'  => get_permalink( get_option( 'wfw-selected-page', '' ) ),
					'message' => esc_html__( 'Wishlist deleted successfully', 'ultimate-wishlist-for-woocommerce' ),
				);
			} elseif ( 400 == $response['status'] ) {

				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => false,
					'message' => esc_html__( 'Technical error, try reloading the page.', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
		}
		do_action( 'delete_current_wishlist', $result );

		wp_send_json( $result );
	}

	/**
	 * Set wishlist as default.
	 */
	public function uwfw_wishlist_set_default() {

		// Nonce verification.
		check_ajax_referer( 'mwb_wfw_nonce', 'nonce' );
		require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-crud-manager.php';
		$wid = ! empty( $_POST['wId'] ) ? sanitize_text_field( wp_unslash( $_POST['wId'] ) ) : '';

		$result = array();

		if ( empty( $wid ) ) {

			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Something went wrong, try again', 'ultimate-wishlist-for-woocommerce' ),
			);

		}

		if ( ! empty( $wid ) ) {

			$wishlist_manager = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance( $wid );

			$result = array(
				'status'  => true,
				'message' => esc_html__( 'Already the default list', 'ultimate-wishlist-for-woocommerce' ),
			);
		}
		do_action( 'wishlist_set_default', $result );

		wp_send_json( $result );

	}

	/**
	 * Parse Serialised data.
	 *
	 * @param array $array    dataset to serialize.
	 */
	public function uwfw_parse_serialised_data( $array = array() ) {
		$result = array();
		if ( ! empty( $array ) && is_array( $array ) ) {
			foreach ( $array as $key => $value ) {
				$result[ $value['name'] ] = $value['value'];
			}
		}
		$result = apply_filters( 'set_wc_one_result', $result );

		return $result;
	}
}

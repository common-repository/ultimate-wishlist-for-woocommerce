<?php
/**
 * The complete management for the Wishlist Objects through out the site.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 */

/**
 * The complete management for the Wishlist Objects.
 *
 * This class defines all code necessary to run CRUD operations.
 * API Docs here : https://docs.google.com/document/d/17K510j0YKeqwQc03a5kjP6e_OI88SSTUTqGygDNhfR8/edit?usp=sharing
 *
 * @since      1.0.0
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Ultimate_Wishlist_For_Woocommerce_Crud_Manager {

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var Ultimate_Wishlist_For_Woocommerce_Crud_Manager   The single instance of the Ultimate_Wishlist_For_Woocommerce_Crud_Manager
	 */
	protected static $_instance = null;

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var Ultimate_Wishlist_For_Woocommerce_Crud_Manager   The id of the Wishlist Object.
	 */
	public $id = null;

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var Ultimate_Wishlist_For_Woocommerce_Crud_Manager   The table name of the Wishlist db.
	 */
	private $table_name = null;

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var Ultimate_Wishlist_For_Woocommerce_Crud_Manager   The array type entries of the Wishlist Object.
	 */
	private $array_entries = array( 'products', 'collaborators', 'properties' );

	/**
	 * Main Ultimate_Wishlist_For_Woocommerce_Crud_Manager Instance.
	 *
	 * Ensures only one instance of Ultimate_Wishlist_For_Woocommerce_Crud_Manager is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @param string $id The id of wishlist.
	 * @static
	 * @return Ultimate_Wishlist_For_Woocommerce_Crud_Manager - Main instance.
	 */
	public static function get_instance( $id = null ) {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self( $id );
		}

		return self::$_instance;
	}
	/**
	 * Create new wishlist.
	 *
	 * @since 1.0.0
	 * @param array $atts The attributes array of wishlist.
	 * @return array $result the result of insertion query.
	 */
	public function create_list( $atts = array() ) {

		$args = self::parse_query_args( $atts );

		if ( empty( $args ) ) {
			return array(
				'status'  => 404,
				'message' => esc_html__( 'Invalid Arguments', 'ultimate-wishlist-for-woocommerce' ),
			);
		}

		global $wpdb;
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';

		$results = $wpdb->insert( $mwb_second_table, $args );
		if ( ! empty( $wpdb->last_error ) ) {

			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {

			// Assign id property.
			$this->id = $wpdb->insert_id;
			$result   = array(
				'status' => 200,
				'id'     => $wpdb->insert_id,
			);
		}

		return $result;
	}
	/**
	 * The constructor of the object.
	 *
	 * @since 1.0.0
	 * @static
	 * @param string $id The id of wishlist.
	 * @return void.
	 */
	public function __construct( $id = null ) {
		// Assign id property.
		$this->id = $id;
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'wishlist_datastore';
	}

	/**
	 * Create new wishlist.
	 *
	 * @since 1.0.0
	 * @param array $atts The attributes array of wishlist.
	 * @return array $result the result of insertion query.
	 */
	public function create( $atts = array() ) {

		$args = self::parse_query_args( $atts );

		if ( empty( $args ) ) {
			return array(
				'status'  => 404,
				'message' => esc_html__( 'Invalid Arguments', 'ultimate-wishlist-for-woocommerce' ),
			);
		}

		global $wpdb;
		$results = $wpdb->insert( $this->table_name, $args );
		if ( ! empty( $wpdb->last_error ) ) {

			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {

			// Assign id property.
			$this->id = $wpdb->insert_id;
			$result   = array(
				'status' => 200,
				'id'     => $wpdb->insert_id,
			);
		}

		return $result;
	}
	/**
	 * Delete new wishlist.
	 *
	 * @param string $id id.
	 * @since 1.0.0
	 * @return array $id the result of deletion query.
	 */
	public function delete_second( $id = '' ) {
		if ( empty( $id ) || ! is_numeric( $id ) ) {
			$result = array(
				'status'  => 404,
				'message' => esc_html__( 'Invalid ID', 'ultimate-wishlist-for-woocommerce' ),
			);

			return $result;
		}

		global $wpdb;
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';

		$results = $wpdb->delete( $mwb_second_table, array( 'id' => $id ) );
		if ( ! empty( $wpdb->last_error ) ) {
			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {
			$result = array(
				'status'  => 200,
				'message' => $results,
			);
		}

		return $result;
	}

	/**
	 * Delete new wishlist.
	 *
	 * @since 1.0.0
	 * @return array $result the result of deletion query.
	 */
	public function delete() {

		if ( empty( $this->id ) || ! is_numeric( $this->id ) ) {

			$result = array(
				'status'  => 404,
				'message' => esc_html__( 'Invalid ID', 'ultimate-wishlist-for-woocommerce' ),
			);

			return $result;
		}

		global $wpdb;
		$results = $wpdb->delete( $this->table_name, array( 'ID' => $this->id ) );

		if ( ! empty( $wpdb->last_error ) ) {

			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {

			$result = array(
				'status'  => 200,
				'message' => $results,
			);
		}

		return $result;
	}
	/**
	 * Create new wishlist.
	 *
	 * @since 1.0.0
	 * @param array $atts The attributes array of wishlist.
	 * @return array $result the result of insertion query.
	 */
	public function update( $atts = array() ) {

		if ( empty( $this->id ) || ! is_numeric( $this->id ) ) {

			$result = array(
				'status'  => 404,
				'message' => esc_html__( 'Invalid ID', 'ultimate-wishlist-for-woocommerce' ),
			);

			return $result;
		}

		$is_row_exists = $this->retrieve();

		if ( 200 != $is_row_exists['status'] ) {

			return $is_row_exists;
		}

		// Never update create date.
		unset( $atts['createdate'] );

		// Add last modified date.
		$atts['modifieddate'] = gmdate( 'Y-m-d h:i:s' );

		$args = self::parse_query_args( $atts );

		global $wpdb;

		$response = $wpdb->update(
			$this->table_name,
			$args,
			array( 'ID' => $this->id )
		);

		// (int|false) The number of rows updated, or false on error.
		if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {
			$result = array(
				'status'  => 200,
				'message' => $response,
			);
		}

		return $result;
	}
	/**
	 * Parse data in required format.
	 *
	 * @param string $obtain The key for query.
	 * @param string $key The value for query.
	 * @param array  $value The args for query optional.
	 * @param array  $additional The array.
	 * @since 1.0.0
	 * @return array $result the parsed data from query.
	 */
	public function retrieve_current_wishlist_id( $obtain = '', $key = '', $value = '', $additional = array() ) {

		global $wpdb;
		if ( ! empty( $key ) && ! empty( $value ) ) {

			$operator        = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';
			$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';
			$get_query       = $wpdb->prepare( "SELECT %1s FROM %2s WHERE `owner` = '%3s' AND `title` = '%4s'", $obtain, $mwb_first_table, $key, $value );
		} else {
			if ( empty( $this->id ) ) {
				return array(
					'status'  => 404,
					'message' => esc_html__( 'Id Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			}

			// Get all current users wishlists.
			$get_query = $wpdb->prepare( "SELECT * FROM %1s WHERE `id` = '%2s'", $this->table_name, $this->id );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}

		return false;
	}
	/**
	 * Parse data in required format.
	 *
	 * @param string $obtain The key for query.
	 * @param string $key The value for query.
	 * @param array  $value The args for query optional.
	 * @param array  $additional The array.
	 * @since 1.0.0
	 * @return array $result the parsed data from query.
	 */
	public function retrieve_email_admin( $obtain = '', $key = '', $value = '', $additional = array() ) {

		global $wpdb;
		if ( ! empty( $key ) && ! empty( $value ) ) {

			$operator = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';

			$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';

			$get_query = $wpdb->prepare( "SELECT %1s FROM %2s WHERE %3s = '%4s'", $obtain, $mwb_first_table, $key, $value );
		} else {
			if ( empty( $this->id ) ) {
				return array(
					'status'  => 404,
					'message' => esc_html__( 'Id Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			}

			// Get all current users wishlists.
			$get_query = $wpdb->prepare( "SELECT * FROM %1s WHERE `id` = '%2s'", $this->table_name, $this->id );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}

		return false;
	}
	/**
	 * Parse data in required format.
	 *
	 * @param string $obtain The key for query.
	 * @param string $key The value for query.
	 * @param array  $value  The args for query optional.
	 * @param array  $additional The array.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data from query.
	 */
	public function retrieve_second_admin( $obtain = '', $key = '', $value = '', $additional = array() ) {
		global $wpdb;
		// If required wishlist must have this parameters as properties.
		if ( ! empty( $key ) && ! empty( $value ) ) {

			$operator         = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';
			$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
			$get_query        = $wpdb->prepare( "SELECT %1s FROM %2s WHERE %3s = '%4s'", $obtain, $mwb_second_table, $key, $value );
		} else {

			if ( empty( $this->id ) ) {
				return array(
					'status'  => 404,
					'message' => esc_html__( 'Id Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			}

			// Get all current users wishlists.
			$get_query = $wpdb->prepare( "SELECT * FROM %1s WHERE `id` = '%2s'", $this->table_name, $this->id );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}

		return false;
	}
	/**
	 * Parse data in required format.
	 *
	 * @param string $key      The key for query.
	 * @param string $value    The value for query.
	 * @param string $user_id      The args for query optional.
	 * @param array  $additional array.
	 * @since 1.0.0
	 * @return array $result the parsed data from query.
	 */
	public function retrieve_second( $key = '', $value = '', $user_id = '', $additional = array() ) {

		global $wpdb;
		// If required wishlist must have this parameters as properties.
		if ( ! empty( $key ) && ! empty( $value ) ) {

			$operator         = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';
			$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
			$get_query        = $wpdb->prepare( "SELECT id FROM %1s WHERE `user_id` = '%2s' AND %3s = '%4s'", $mwb_second_table, $user_id, $key, $value );

		} else {

			if ( empty( $this->id ) ) {
				return array(
					'status'  => 404,
					'message' => esc_html__( 'Id Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
			// Get all current users wishlists.
			$get_query = $wpdb->prepare( "SELECT * FROM %1s WHERE `id` = '%2s'", $this->table_name, $this->id );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );
			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}

		return false;
	}

	/**
	 * Parse data in required format.
	 *
	 * @param string $key      The key for query.
	 * @param string $value    The value for query.
	 * @param array  $additional      The args for query optional.
	 * @since 1.0.0
	 * @return array $result the parsed data from query.
	 */
	public function retrieve( $key = '', $value = '', $additional = array() ) {

		global $wpdb;
		// If required wishlist must have this parameters as properties.
		if ( ! empty( $key ) && ! empty( $value ) ) {

			$operator  = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';
			$get_query = $wpdb->prepare( "SELECT id FROM %1s WHERE %2s = '%3s'", $this->table_name, $key, $value );
			if ( ! empty( $additional ) && is_array( $additional ) ) {

				foreach ( $additional as $key => $value ) {
					$operator = in_array( $key, $this->array_entries ) ? 'REGEXP' : '=';

					$value      = 'properties' == $key ? json_encode( $value ) : $value;
					$value      = str_replace( array( '{', '}' ), '', $value );
					$get_query .= " AND `$key` $operator '$value' ";
				}
			}
		} else {

			if ( empty( $this->id ) ) {
				return array(
					'status'  => 404,
					'message' => esc_html__( 'Id Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			}
			// Get all current users wishlists.
			$get_query = $wpdb->prepare( "SELECT * FROM %1s WHERE `id` = '%2s'", $this->table_name, $this->id );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}

		return false;
	}

	/**
	 * Parse data in required format.
	 *
	 * @param array $args   The arguments.
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	private function parse_query_args( $args = array() ) {

		if ( ! empty( $args ) && is_array( $args ) ) {

			$result = array();
			foreach ( $args as $key => $arg ) {
				$result[ $key ] = ! empty( $arg ) && is_array( $arg ) ? json_encode( array_unique( $arg ) ) : $arg;
			}

			return $result;
		}

		return false;
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param string $wid The key to fetch.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_prop_delete_bulk( $wid = '' ) {
		global $wpdb;
		$user_id          = get_current_user_id();
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
		$get_query        = $wpdb->prepare( "DELETE  FROM %1s WHERE `wishlist_id` = '%2s' AND `user_id` = '%3s'", $mwb_second_table, $wid, $user_id );
		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );
			$result   = array(
				'status'  => 200,
				'message' => $response,
			);
		}
		if ( 200 == $result['status'] ) {
			$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';

			$get_query = $wpdb->prepare( "DELETE  FROM %1s WHERE `id` = '%2s'", $mwb_first_table, $wid );
			$response  = $wpdb->get_results( $get_query, ARRAY_A );
			$result    = array(
				'status'  => 200,
				'message' => $response,
			);
			return $result;
		}
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param string $wid SQL result.
	 * @param string $pid The key to fetch.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_prop_delete( $wid = '', $pid = '' ) {
		global $wpdb;
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
		$get_query        = $wpdb->prepare( "DELETE  FROM %1s WHERE `prod_id` = '%2s' AND `wishlist_id` = '%3s'", $mwb_second_table, $pid, $wid );

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );
			$result   = array(
				'status'  => 200,
				'message' => $response,
			);

			return $result;
		}
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param array  $fetch SQL result.
	 * @param string $checkbox  The key to fetch.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_prop_second( $fetch = '', $checkbox = '' ) {
		global $wpdb;
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
		$get_query        = $wpdb->prepare( "SELECT %1s FROM %2s WHERE `wishlist_id` = '%3s'", $fetch, $mwb_second_table, $checkbox );

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param string $checkbox The key to fetch.
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_wishlist_id( $checkbox, $userid = '' ) {
		$user_id = ( '' === $userid ) ? get_current_user_id() : $userid;
		global $wpdb;
		$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';
		$get_query       = $wpdb->prepare( "SELECT id FROM %1s WHERE `user_id` = '%2s' AND `title` = '%3s'", $mwb_first_table, $user_id, $checkbox );
		$response_fir    = $wpdb->get_results( $get_query, ARRAY_A );
		if ( ! empty( $response_fir ) ) {
			$response_fir = reset( $response_fir );
			return $response_fir;
		}
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param array  $fetch SQL result.
	 * @param string $checkbox  The key to fetch.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_prop_second_front( $fetch = '', $checkbox = '', $userid = '' ) {
		$user_id = ( '' === $userid ) ? get_current_user_id() : $userid;
		// $user_id = get_current_user_id();
		global $wpdb;
		$mwb_first_table = $wpdb->prefix . 'wishlist_datastore';
		$get_query       = $wpdb->prepare( "SELECT id FROM %1s WHERE `user_id` = '%2s' AND `title` = '%3s'", $mwb_first_table, $user_id, $checkbox );
		$response_fir    = $wpdb->get_results( $get_query, ARRAY_A );

		if ( ! empty( $response_fir ) ) {
			$response_fir = reset( $response_fir );
			$response_fir = $response_fir['id'];

			$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';

			$get_query = $wpdb->prepare( "SELECT %1s FROM %2s WHERE `user_id` = '%3s' AND `wishlist_id` = '%4s'", $fetch, $mwb_second_table, $user_id, $response_fir );
		}

		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$result = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}
	}
		/**
		 * Get Selected data from Sql Query Obj.
		 *
		 * @param array  $fetch SQL result.
		 * @param string $checkbox  The key to fetch.
		 * @param string $pid pid.
		 * @since 1.0.0
		 * @return array $result the parsed data form for query.
		 */
	public function get_prop_final_price( $fetch = '', $checkbox = '', $pid = '' ) {
		$user_id = get_current_user_id();
		global $wpdb;
		$mwb_first_table  = $wpdb->prefix . 'wishlist_datastore';
		$mwb_second_table = $wpdb->prefix . 'wishlist_datastore_list';
		$get_query        = $wpdb->prepare( "SELECT id FROM %1s WHERE `user_id` = '%2s' AND `title` = '%3s'", $mwb_first_table, $user_id, $checkbox );
		$response_fir     = $wpdb->get_results( $get_query, ARRAY_A );
		if ( ! empty( $response_fir ) ) {
			$response_fir = reset( $response_fir );
			$response_fir = $response_fir['id'];
			$get_query    = $wpdb->prepare( "SELECT %1s FROM %2s WHERE `user_id` = '%3s' AND `prod_id` = '%4s'", $fetch, $mwb_second_table, $user_id, $pid );
		}
		if ( ! empty( $get_query ) ) {
			$response = $wpdb->get_results( $get_query, ARRAY_A );

			if ( ! empty( $wpdb->last_error ) || empty( $response ) ) {
				$result = array(
					'status'  => 400,
					'message' => ! empty( $wpdb->last_error ) ? $wpdb->last_error : esc_html__( 'Row Not Found', 'ultimate-wishlist-for-woocommerce' ),
				);
			} else {
				$response = reset( $response );
				$result   = array(
					'status'  => 200,
					'message' => $response,
				);
			}

			return $result;
		}
	}
	/**
	 * Get Selected data from Sql Query Obj.
	 *
	 * @param string $fetch  The key to fetch.
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_prop( $fetch = '' ) {
		// title
		// Get Wishlist object first.
		$query_result = $this->retrieve();
		if ( 200 !== $query_result['status'] ) {
			return $query_result;
		} else {
			$obj = ! empty( $query_result['message'] ) ? $query_result['message'] : array();
		}

		if ( ! empty( $obj ) && is_array( $obj ) ) {

			foreach ( $obj as $key => $wishlist ) {

				if ( empty( $fetch ) ) {
					return $wishlist;
				} else {

					$value  = $wishlist[ $fetch ] ? $wishlist[ $fetch ] : false;
					$result = array();
					if ( ! empty( $value ) ) {
						$result = in_array( $fetch, $this->array_entries ) ? json_decode( $value ) : $value;
					}
					return $result;
				}
			}
		}
		return false;
	}

	/**
	 * Get all the wishlist for user.
	 *
	 * @since 1.0.0
	 * @return array $result the parsed data form for query.
	 */
	public function get_all() {

		if ( ! is_admin() ) {
			return false;
		}

		// Get all current users wishlists.
		global $wpdb;
		$get_query = $wpdb->prepare( 'SELECT * FROM %1s', $this->table_name );
		$response  = $wpdb->get_results( $get_query, ARRAY_A );

		if ( ! empty( $wpdb->last_error ) ) {

			$result = array(
				'status'  => 400,
				'message' => $wpdb->last_error,
			);
		} else {

			$result = array(
				'status'   => 200,
				'response' => apply_filters( 'mwb_wfw_all_wishlists', $response ),
			);
		}

		return $result;
	}
	// End of Class.
}

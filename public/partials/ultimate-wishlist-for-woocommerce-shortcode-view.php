<?php
/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/public/partials
 */

?>

<main class="wfw_main">
	<header class="wfw_main_header">
		<ul class="wfw_navbar">
			<li class="wfw_navlink <?php echo esc_html( 'edit' === $access ? 'active' : '' ); ?>"><a href="?"><?php esc_html_e( 'Your Lists', 'ultimate-wishlist-for-woocommerce' ); ?></a></li>
			<?php
			if ( 'edit' !== $access ) :
				?>
			<li class="wfw_navlink <?php echo esc_html( 'view' === $access ? 'active' : '' ); ?>"><?php esc_html_e( 'Your Friend\'s Wishlist', 'ultimate-wishlist-for-woocommerce' ); ?></li>
			<?php endif; ?>
		</ul>
	</header>

	<section class="wfw_content">

		<ul class="wfw_content-left">
			<?php

				require_once ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-ultimate-wishlist-for-woocommerce-helper.php';
			// Wishlist Exists conditions start.
			if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
				$callname_lic         = Wishlist_For_Woocommerce_Pro::$lic_callback_function;
				$callname_lic_initial = Wishlist_For_Woocommerce_Pro::$lic_ini_callback_function;
				$day_count            = Wishlist_For_Woocommerce_Pro::$callname_lic_initial();
				if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {
					if ( ! empty( $owner_lists ) && is_array( $owner_lists ) ) :

						$default = '';
						foreach ( $owner_lists as $key => $obj ) :

							$_id     = ! empty( $obj['id'] ) ? $obj['id'] : false;
							$default = ! empty( $default ) ? $default : $_id;

							$wishlist_manager->id = $_id;

							$wishlist_title  = $wishlist_manager->get_prop( 'title' );
							$wishlist_status = $wishlist_manager->get_prop( 'status' );
							$properties      = $wishlist_manager->get_prop( 'properties' );
							if ( ! is_array( $wishlist_status ) ) {
								?>

							<li class="wfw_content-left_list">
								<div class="wfw_content-list_des"><a href="?wl-ref=<?php echo esc_html( Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $_id ) ); ?>"><?php echo esc_html( $wishlist_title ); ?></a><span><?php echo ( 'edit' === $access ) ? esc_html( ucwords( $wishlist_status ) ) : ''; ?></span></div><p><?php ( 'edit' === $access ) && true == $properties->default ? esc_html_e( 'Default list', 'ultimate-wishlist-for-woocommerce' ) : ''; ?></p>
							</li>

								<?php
							}
						endforeach;
					endif;
				} else {
					$default = '';
					if ( 'edit' === $access ) :
						$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1' );
					else :
						if ( ! empty( $owner_lists ) && is_array( $owner_lists ) ) :
							$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1', $owner_lists[0]['user_id'] );
						else :
							$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1' );
						endif;
					endif;
						$_id     = $_id['id'];
						$default = ! empty( $default ) ? $default : $_id;

						$wishlist_manager->id = $_id;

						$wishlist_title  = $wishlist_manager->get_prop( 'title' );
						$wishlist_status = $wishlist_manager->get_prop( 'status' );
						$properties      = $wishlist_manager->get_prop( 'properties' );
					if ( ! is_array( $wishlist_status ) ) :
						?>

						<li class="wfw_content-left_list">
							<div class="wfw_content-list_des"><a href="?wl-ref=<?php echo esc_html( Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $_id ) ); ?>"><?php echo esc_html( $wishlist_title ); ?></a><span><?php echo ( 'edit' === $access ) ? esc_html( ucwords( $wishlist_status ) ) : ''; ?></span></div><p><?php ( 'edit' === $access ) && true == $properties->default ? esc_html_e( 'Default list', 'ultimate-wishlist-for-woocommerce' ) : ''; ?></p>
						</li>

						<?php
					endif;
				}
			} else {
				$default = '';
				if ( 'edit' === $access ) :
					$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1' );
				else :
					if ( ! empty( $owner_lists ) && is_array( $owner_lists ) ) :
						$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1', $owner_lists[0]['user_id'] );
					else :
						$_id = $wishlist_manager->get_wishlist_id( 'Wishlist #1' );
					endif;
				endif;
					$_id     = $_id['id'];
					$default = ! empty( $default ) ? $default : $_id;

					$wishlist_manager->id = $_id;

					$wishlist_title  = $wishlist_manager->get_prop( 'title' );
					$wishlist_status = $wishlist_manager->get_prop( 'status' );
					$properties      = $wishlist_manager->get_prop( 'properties' );
				if ( ! is_array( $wishlist_status ) ) :
					?>

						<li class="wfw_content-left_list">
							<div class="wfw_content-list_des"><a href="?wl-ref=<?php echo esc_html( Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $_id ) ); ?>"><?php echo esc_html( $wishlist_title ); ?></a><span><?php echo ( 'edit' === $access ) ? esc_html( ucwords( $wishlist_status ) ) : ''; ?></span></div><p><?php ( 'edit' === $access ) && true == $properties->default ? esc_html_e( 'Default list', 'ultimate-wishlist-for-woocommerce' ) : ''; ?></p>
						</li>

					<?php
				endif;
			}
			// Wishlist Exists conditions ends.
			?>
		</ul>

		<?php
			// Wishlist to show content.
			$default              = ! empty( $default ) ? $default : false;
			$wid_to_show          = ! empty( $wid_to_show ) ? $wid_to_show : $default;
			$wishlist_manager->id = $wid_to_show;
			$search_wishlist      = $wishlist_manager->retrieve();

			// Wishlist Not Found.
		if ( ! is_user_logged_in() ) {
			$search_wishlist['status'] = 200;
		}
		if ( 200 != $search_wishlist['status'] ) :
			?>

			<div class="error-404 not-found">
				<div class="page-content">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That wishlist can&rsquo;t be found.', 'ultimate-wishlist-for-woocommerce' ); ?></h1>
					</header>
					<p><?php esc_html_e( 'Nothing was found at this location or you might not have access to this content.', 'ultimate-wishlist-for-woocommerce' ); ?></p>

				</div>
			</div>

			<?php
			else :
				do_action( 'before_wishlist_listing' );
				$user_id         = get_current_user_id();
				$wishlist_title  = $wishlist_manager->get_prop( 'title' );
				$wishlist_status = $wishlist_manager->get_prop( 'status' );
				$properties      = $wishlist_manager->get_prop( 'properties' );
				if ( 'edit' === $access ) :
					if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
						if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {
							$checkbox = get_user_meta( $user_id, 'get_check_box_details', true );
						} else {
							$checkbox = 'Wishlist #1';
						}
					} else {
						$checkbox = 'Wishlist #1';
					}
					$wid_to_show = $wishlist_manager->get_wishlist_id( $checkbox );
					$wid_to_show = $wid_to_show['id'];
					$products    = $wishlist_manager->get_prop_second_front( 'prod_id', $checkbox );
				else :
					if ( ! empty( $owner_lists ) && is_array( $owner_lists ) ) :
						$checkbox    = $owner_lists[0]['title'];
						$wid_to_show = $wishlist_manager->get_wishlist_id( $checkbox, $owner_lists[0]['user_id'] );
						$wid_to_show = $wid_to_show['id'];
						$products    = $wishlist_manager->get_prop_second_front( 'prod_id', $checkbox, $owner_lists[0]['user_id'] );
					else :
						$checkbox    = 'Wishlist #1';
						$wid_to_show = $wishlist_manager->get_wishlist_id( $checkbox );
						$wid_to_show = $wid_to_show['id'];
						$products    = $wishlist_manager->get_prop_second_front( 'prod_id', $checkbox );
					endif;

				endif;

				if ( 200 === $products['status'] ) {
					$products = $products['message'];
				} else {
					$products = array();
				}

				?>
				<div class="wfw_content-right_wrapper" data-wId="<?php echo esc_html( $wid_to_show ); ?>">
					<div class="wfw_content-right">
						<div class="wfw_content-right_head">
							<div class="wfw_content-list_des">
							<?php ( 'edit' === $access ) ? do_action( 'add_wishlist_dropdown', $wishlist_title, $_id ) : ''; ?>
							</div>
							<?php if ( 'edit' === $access ) : ?>
							<p class="wfw_invite-icon">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="wfw_user">
								<path d="M11 0C4.92339 0 0 4.92339 0 11C0 17.0766 4.92339 22 11 22C17.0766 22 22 17.0766 22 11C22 4.92339 17.0766 0 11 0ZM11 4.25806C13.1556 4.25806 14.9032 6.00565 14.9032 8.16129C14.9032 10.3169 13.1556 12.0645 11 12.0645C8.84435 12.0645 7.09677 10.3169 7.09677 8.16129C7.09677 6.00565 8.84435 4.25806 11 4.25806ZM11 19.5161C8.39637 19.5161 6.06331 18.3363 4.50202 16.4911C5.33589 14.921 6.96814 13.8387 8.87097 13.8387C8.97742 13.8387 9.08387 13.8565 9.18589 13.8875C9.7625 14.0738 10.3657 14.1935 11 14.1935C11.6343 14.1935 12.2419 14.0738 12.8141 13.8875C12.9161 13.8565 13.0226 13.8387 13.129 13.8387C15.0319 13.8387 16.6641 14.921 17.498 16.4911C15.9367 18.3363 13.6036 19.5161 11 19.5161Z" fill="black"/>
								</svg>

								<a title="<?php esc_html_e( 'Invite Collaborator' ); ?>" class="thickbox mwb-wfw-invite" href="#TB_inline?&inlineId=wfw_invite_popup">
								<span>+</span>
								<?php esc_html_e( 'Invite', 'ultimate-wishlist-for-woocommerce' ); ?></a>
								<?php do_action( 'thickbox_for_add_wishlist' ); ?>
							<div id="wfw_invite_popup">
								<div id="wfw_invite_content_wrapper">
									<h3 class="wfw-invite_form"><?php esc_html_e( 'Enter your friends email here!', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
									<form id="wfw_email_invite_form" action="" method="post">
										<input type="email" placeholder="Enter email here" name="wfw_invite_email" id="wfw_invite_email" value="" >
										<input type="hidden" name="wfw_toshow_id" id="wfw_toshow_id" value="<?php echo esc_html( $wishlist_manager->id ); ?>" >
										<input type="submit" name="wfw_invite_send_button" value="<?php esc_html_e( 'Send', 'ultimate-wishlist-for-woocommerce' ); ?>">
									</form>

								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="wfw_more_links">
						<?php
						if ( 'edit' === $access ) :

							$page_link = get_permalink( get_option( 'wfw-selected-page', '' ) );
							if ( ! empty( $page_link ) ) {
								$page_link = add_query_arg(
									array(
										'wl-ref' => Ultimate_Wishlist_For_Woocommerce_Helper::encrypter( $_id ),
									),
									$page_link
								);

							}
							if ( ! empty( get_option( 'wfw-enable-fb-share' ) ) && 'on' == get_option( 'wfw-enable-fb-share' ) ) {
								?>

							<a class="mwb-wfw-share" id="mwb-wfw-share-fb" data-location="facebook" href="http://www.facebook.com/sharer.php?s=100&p[url]=<?php echo esc_url( $page_link ); ?>&p[title]=<?php echo esc_html( $wishlist_title ); ?>">
								<svg width="15" height="15" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="16" cy="16" r="16" fill="#1A3365" class="wfw_facebook"/>
									<path d="M21 6H18C16.6739 6 15.4021 6.52678 14.4645 7.46447C13.5268 8.40215 13 9.67392 13 11V14H10V18H13V26H17V18H20L21 14H17V11C17 10.7348 17.1054 10.4804 17.2929 10.2929C17.4804 10.1054 17.7348 10 18 10H21V6Z" fill="white"/>
								</svg>
							</a>
								<?php
							} if ( ! empty( get_option( 'wfw-enable-whatsapp-share' ) ) && 'on' == get_option( 'wfw-enable-whatsapp-share' ) ) {
								?>
							<a class="mwb-wfw-share" id="mwb-wfw-share-wa" data-location="whatsapp" href="https://wa.me/?text=<?php echo rawurlencode( $page_link ); ?>">
								<svg width="15" height="15" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="wfw_whatsapp">
										<circle cx="16" cy="16" r="16" fill="#25D366"/>
										<path d="M22.304 9.61562C20.6205 7.92812 18.3786 7 15.996 7C11.0781 7 7.07634 11.0018 7.07634 15.9196C7.07634 17.4906 7.48616 19.0254 8.26562 20.3795L7 25L11.729 23.7585C13.0308 24.4696 14.4973 24.8433 15.992 24.8433H15.996C20.9098 24.8433 25 20.8415 25 15.9237C25 13.5411 23.9875 11.3031 22.304 9.61562V9.61562ZM15.996 23.3406C14.6621 23.3406 13.3562 22.983 12.2192 22.308L11.95 22.1473L9.14554 22.8826L9.89286 20.1464L9.71607 19.8652C8.97277 18.6839 8.58304 17.3219 8.58304 15.9196C8.58304 11.8335 11.9098 8.5067 16 8.5067C17.9808 8.5067 19.8411 9.27812 21.2393 10.6804C22.6375 12.0826 23.4973 13.9429 23.4933 15.9237C23.4933 20.0138 20.0821 23.3406 15.996 23.3406V23.3406ZM20.0621 17.7879C19.8411 17.6754 18.7442 17.1371 18.5393 17.0647C18.3344 16.9884 18.1857 16.9522 18.0371 17.1772C17.8884 17.4022 17.4625 17.9004 17.3299 18.0531C17.2013 18.2018 17.0688 18.2219 16.8478 18.1094C15.5379 17.4545 14.6781 16.9402 13.8143 15.4576C13.5853 15.0638 14.0433 15.092 14.4692 14.2402C14.5415 14.0915 14.5054 13.9629 14.4491 13.8504C14.3929 13.7379 13.9469 12.6411 13.7621 12.1951C13.5813 11.7612 13.3964 11.8214 13.2598 11.8134C13.1313 11.8054 12.9826 11.8054 12.8339 11.8054C12.6853 11.8054 12.4442 11.8616 12.2393 12.0826C12.0344 12.3076 11.4598 12.846 11.4598 13.9429C11.4598 15.0397 12.2594 16.1004 12.3679 16.2491C12.4804 16.3978 13.9388 18.6478 16.1768 19.6161C17.5911 20.2268 18.1455 20.279 18.8527 20.1746C19.2826 20.1103 20.1705 19.6362 20.3554 19.1138C20.5402 18.5915 20.5402 18.1455 20.4839 18.0531C20.4317 17.9527 20.283 17.8964 20.0621 17.7879Z" fill="white"/>
								</svg>
							</a>
								<?php
							} if ( ! empty( get_option( 'wfw-enable-twitter-share' ) ) && 'on' == get_option( 'wfw-enable-twitter-share' ) ) {
								?>

							<a class="mwb-wfw-share" id="mwb-wfw-share-tt" data-location="twitter" href="http://twitter.com/share?text=<?php echo esc_url( $page_link ); ?>&url=<?php echo esc_url( $page_link ); ?>">
								<svg width="15" height="15" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="wfw_twitter">
										<circle cx="16" cy="16" r="16" fill="#1DA1F2"/>
										<path d="M27 7.01006C26.0424 7.68553 24.9821 8.20217 23.86 8.54006C23.2577 7.84757 22.4573 7.35675 21.567 7.13398C20.6767 6.91122 19.7395 6.96725 18.8821 7.29451C18.0247 7.62177 17.2884 8.20446 16.773 8.96377C16.2575 9.72309 15.9877 10.6224 16 11.5401V12.5401C14.2426 12.5856 12.5013 12.1959 10.931 11.4055C9.36074 10.6151 8.01032 9.44869 7 8.01006C7 8.01006 3 17.0101 12 21.0101C9.94053 22.408 7.48716 23.109 5 23.0101C14 28.0101 25 23.0101 25 11.5101C24.9991 11.2315 24.9723 10.9537 24.92 10.6801C25.9406 9.67355 26.6608 8.40277 27 7.01006V7.01006Z" fill="white"/>
								</svg>
							</a>
								<?php
							} if ( ! empty( get_option( 'wfw-enable-pinterest-share' ) ) && 'on' == get_option( 'wfw-enable-pinterest-share' ) ) {
								?>
							<a class="mwb-wfw-share" id="mwb-wfw-share-pt" data-location="pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( $page_link ); ?>&description=<?php echo esc_html( $wishlist_title ); ?>">
								<svg width="15" height="15" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="wfw_pinterest">
										<circle cx="16" cy="16" r="16" fill="#E60023"/>
										<path d="M16.5 8C12.225 8 8 10.3298 8 14.1003C8 16.4982 9.65 17.8606 10.65 17.8606C11.0625 17.8606 11.3 16.9205 11.3 16.6548C11.3 16.3381 10.3125 15.6637 10.3125 14.3455C10.3125 11.607 12.8625 9.66557 16.1625 9.66557C19 9.66557 21.1 10.9837 21.1 13.4054C21.1 15.2141 20.2125 18.6065 17.3375 18.6065C16.3 18.6065 15.4125 17.9934 15.4125 17.1147C15.4125 15.8272 16.5125 14.5805 16.5125 13.2522C16.5125 10.9973 12.6 11.4061 12.6 14.1309C12.6 14.7032 12.6875 15.3367 13 15.8578C12.425 17.881 11.25 20.8954 11.25 22.9799C11.25 23.6237 11.3625 24.2572 11.4375 24.901C11.5792 25.0304 11.5083 25.0168 11.725 24.9521C13.825 22.6019 13.75 22.142 14.7 19.0663C15.2125 19.8634 16.5375 20.2925 17.5875 20.2925C22.0125 20.2925 24 16.7672 24 13.5894C24 10.2071 20.425 8 16.5 8Z" fill="white"/>
								</svg>
							</a>
								<?php
							}
							?>

							<span href="" class="wfw_content_more">
								<i class="fas fa-ellipsis-h"></i> <span><?php esc_html_e( 'More', 'ultimate-wishlist-for-woocommerce' ); ?></span>
								<ul class="wfw_content_more_link">
									<li><a class="mwb-wfw-delete" data-wId="<?php echo esc_html( $wid_to_show ); ?>" href="javascript:void(0);"><?php esc_html_e( 'Delete List', 'ultimate-wishlist-for-woocommerce' ); ?></a></li>
								</ul>
							</span>
						<?php endif; ?>
						</div>
					</div>
					<ul class="wfw_content-right_item_list">
						<?php $products = ! is_array( $products ) ? json_decode( json_encode( $products ), true ) : $products; ?>
						<?php if ( ! empty( $products ) && count( $products ) ) : ?>
							<?php ( 'edit' === $access ) ? do_action( 'add_select_box' ) : ''; ?>
							<?php $cart_total = 0; ?>
							<?php foreach ( $products as $key => $products ) { ?>
								<?php foreach ( $products as $key => $_id ) : ?>
									<?php
									$_product      = wc_get_product( $_id );
									$price_single  = $_product->get_price();
									$image_id      = $_product->get_image_id();
									$image_url     = wp_get_attachment_image_url( $image_id );
									$product_url   = $_product->get_permalink();
									$name          = $_product->get_name();
									$ratings_count = wp_count_comments( $_id );
									$rating        = $_product->get_average_rating();
									$count         = $_product->get_rating_count();
									$review_html   = wc_get_rating_html( $rating, $count );
									$price_html    = $_product->get_price_html();
									$description   = $_product->get_short_description() ? $_product->get_short_description() : $_product->get_description();
									// for currency switcher compatibilty.
									if ( class_exists( 'Mwb_Multi_Currency_Switcher_For_Woocommerce_Common' ) ) {
										$obj           = new Mwb_Multi_Currency_Switcher_For_Woocommerce_Common( '', '' );
										$fixed_regular = '';
										$sales_regular = '';

										if ( $_product->is_type( 'simple' ) || $_product->is_type( 'external' ) ) {

											$mwb_regular = floatval( $_product->get_regular_price() );
											$mwb_sales   = floatval( $_product->get_sale_price() );

											if ( function_exists( 'mwb_wmcs_fixed_price_for_simple_regular_price' ) ) {
												$fixed_regular = mwb_wmcs_fixed_price_for_simple_regular_price( $_product->get_id() );
											}
											if ( function_exists( 'mwb_wmcs_fixed_price_for_simple_sales_price' ) ) {
												$fixed_sales = mwb_wmcs_fixed_price_for_simple_sales_price( $_product->get_id() );
											}
											$from_price_switcher = ! empty( $fixed_regular ) ? $fixed_regular : $mwb_regular;
											$to_price_switcher   = ! empty( $mwb_sales ) ? $mwb_sales : '';

											if ( ! empty( $fixed_regular ) ) {
												if ( ! empty( $fixed_sales ) ) {
													$to_price_switcher = $fixed_sales;
												} else {
													$to_price_switcher = '';
												}
											}


											if ( empty( $fixed_regular ) ) {
												if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$from_price_switcher = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price_switcher );
													$to_price_switcher   = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price_switcher );
												}
											}
											if ( empty( $to_price_switcher ) ) {
												$price_single = $from_price_switcher;
											} else {
												$price_single = $to_price_switcher;
											}
											$price_single = round( $price_single );
											$price_html   = $obj->add_position_to_the_currency( $_product, $from_price_switcher, $to_price_switcher, $custom_product_type = '' );

										} elseif ( $_product->is_type( 'variable' ) || $_product->is_type( 'grouped' ) ) {

											$price_single = $_product->get_price();
											if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
												$price_single = round( mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price_single ) );
											}
											$all_prices = array();
											foreach ( $_product->get_children() as $child_id ) {
												$all_prices[] = get_post_meta( $child_id, '_price', true );
											}
											$max_fixed_price = '';
											$min_fixed_price = '';

											if ( ! empty( $all_prices ) ) {
												$max_price = max( $all_prices );
												$min_price = min( $all_prices );
											} else {
												$max_price = '';
												$min_price = '';
											}
											if ( function_exists( 'mwb_wmcs_fixed_price_for_variable_regular_price' ) ) {
												$max_fixed_price = mwb_wmcs_fixed_price_for_variable_regular_price( $_product->get_id() );
											}
											if ( function_exists( 'mwb_wmcs_fixed_price_for_variable_sales_price' ) ) {
												$min_fixed_price = mwb_wmcs_fixed_price_for_variable_sales_price( $_product->get_id() );
											}

											if ( ! empty( $max_fixed_price ) ) {
												$max_price = $max_fixed_price;
											} else {
												if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$max_price = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $max_price );
												}
											}
											if ( ! empty( $min_fixed_price ) ) {
												$min_price = $min_fixed_price;
											} else {
												if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$min_price = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $min_price );
												}
											}
											if ( $max_price > $min_price ) {
												$price_html = $obj->add_position_to_the_currency( $_product, $min_price, $max_price, 'variable' );
											} else {
												$price_html = $obj->add_position_to_the_currency( $_product, $max_price, $min_price, 'variable' );
											}
										}
									}

									// for membership compatible.
									$is_purc = true;
									if ( class_exists( 'Membership_For_Woocommerce_Public' ) ) {
										$obj     = new Membership_For_Woocommerce_Public( '', '' );
										$is_purc = $obj->mwb_membership_make_membership_product_purchasable( $is_purc, $_product );
									}
									?>

								<li class="wfw_content-right_item wfw_list_item_<?php echo esc_html( $_id ); ?> wfw_content-right_item_blur">
									<?php
									if ( false === $is_purc ) {
										?>
									<div class="uwfw_out_of_stock"> <span><?php echo esc_html__( 'Membership Product', 'ultimate-wishlist-for-woocommerce' ); ?> </span><p><a href='<?php echo esc_url( $_product->get_permalink() ); ?>'><?php echo esc_html__( 'Read More...', 'ultimate-wishlist-for-woocommerce' ); ?></a></p>	</div><span class='uwfw_delete_outofstock' id='wfw_del_outofstock_prod_frm_wishlist' data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>">&#10005;</span>
										<?php
										if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
											if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {
												if ( 'edit' === $access ) :
													?>
													<div class="mwb-check-product">
														<input type="checkbox" disabled class="mwb-checkbox-product" value="<?php echo esc_html( $_id ); ?>">
													</div>
													<?php
												endif;
											}
										}
									} elseif ( ! $_product->managing_stock() && ! $_product->is_in_stock() ) {
										?>
									<div class="uwfw_out_of_stock"><?php echo esc_html__( 'The product in your wishlist is Out Of Stock.', 'ultimate-wishlist-for-woocommerce' ); ?></div><span class='uwfw_delete_outofstock' id='wfw_del_outofstock_prod_frm_wishlist' data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>">&#10005;</span>
										<?php
										if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
											if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {

												if ( 'edit' === $access ) :
													?>
													<div class="mwb-check-product">
														<input type="checkbox" disabled class="mwb-checkbox-product" value="<?php echo esc_html( $_id ); ?>">
													</div>
													<?php
												endif;
											}
										}
									} elseif ( 'external' === $_product->get_type() ) {
										?>
									<div class="uwfw_out_of_stock"><?php echo esc_html__( 'External product', 'ultimate-wishlist-for-woocommerce' ); ?></div><span class='uwfw_delete_outofstock' id='wfw_del_outofstock_prod_frm_wishlist' data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>">&#10005;</span>
										<?php
										if ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
											if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {

												if ( 'edit' === $access ) :
													?>
													<div class="mwb-check-product">
														<input type="checkbox" disabled class="mwb-checkbox-product" value="<?php echo esc_html( $_id ); ?>">
													</div>
													<?php
												endif;
											}
										}
									} elseif ( class_exists( 'Wishlist_For_Woocommerce_Pro' ) ) {
										if ( Wishlist_For_Woocommerce_Pro::$callname_lic() || $day_count > 0 ) {

											if ( 'edit' === $access ) :
												?>
												<div class="mwb-check-product">
													<input type="checkbox" class="mwb-checkbox-product" value="<?php echo esc_html( $_id ); ?>">
												</div>
												<?php
											endif;
										}
									}
									?>

									<div class="wfw_content-right_item-img">
										<a class="mwb-wfw-product-img" href="<?php echo esc_url( $product_url ); ?>">
											<?php if ( ! empty( $image_url ) ) : ?>
												<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_html( $name ); ?>">
											<?php else : ?>
												<i class="fas fa-file-image"></i>
											<?php endif; ?>
										</a>
									</div>
									<div class="wfw_content-right_item-desc">
										<p class="item_title">
											<a class="mwb-wfw-product-name" href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $name ); ?></a>
										</p>
										<p class="item_rating">
											<?php if ( $ratings_count->total_comments ) : ?>
												<a class="mwb-wfw-reviews" href="<?php echo esc_url( $product_url ); ?>#reviews" class="rate_count"><?php echo wp_kses_post( wpautop( wptexturize( $review_html ) ) ); ?>(<?php echo esc_html( $ratings_count->total_comments ); ?>)</a>
											<?php endif; ?>
										</p>
										<?php do_action( 'add_quantity_box', $_id, $price_single, $checkbox ); ?>

										<p class="item_price">
											<span class="price_value">
											<?php echo wp_kses_post( wpautop( wptexturize( $price_html ) ) ); ?>
											</span>
											<span class="item_detail">
												<a id="wfw_get_details" href="javascript:void(0)" data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>"><?php esc_html_e( 'Comments', 'ultimate-wishlist-for-woocommerce' ); ?></a>
											</span>
										</p>
										<?php do_action( 'add_final_price_box', $_id, $price_single, $wid_to_show, $checkbox ); ?>
										<p class="item_desc">
											<?php echo esc_html( strlen( $description ) > 50 ? substr( $description, 0, 50 ) . '...' : $description ); ?>
										</p>
									</div>
									<div class="wfw_content-right_item-action">
									<?php
									if ( class_exists( 'Mwb_Wqv_Quick_View' ) ) {
										do_action( 'mwb_wishlist_before_add_to_add_to_cart', $_id, $wid_to_show );
									}
									?>
										<p>
											<a  id="wfw_add_to_cart" href="javascript:void(0);" data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>" class="action_button"><?php esc_html_e( 'Add to cart', 'ultimate-wishlist-for-woocommerce' ); ?></a>
										</p>
										<p>
											<a  id="wfw_go_to_checkout<?php echo esc_html( $_id ); ?>" href="javascript:void(0);" data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>" class="action_button wfw_go_to_checkout"><?php esc_html_e( 'Add to cart', 'ultimate-wishlist-for-woocommerce' ); ?></a>
										</p>

										<?php if ( 'edit' == $access ) : ?>
										<p class="action_delete" id="wfw_del_prod_frm_wishlist" data-wId="<?php echo esc_html( $wid_to_show ); ?>" data-prod="<?php echo esc_html( $_id ); ?>"  >
											<?php esc_html_e( 'Delete', 'ultimate-wishlist-for-woocommerce' ); ?>
										</p>
										<?php endif; ?>
										<p><a href="javascript:void(0);" data-wId=<?php echo esc_html( $wid_to_show ); ?> data-prod=<?php echo esc_html( $_id ); ?> class="wfw-action-comment"><?php esc_html_e( 'Add comment & priority', 'ultimate-wishlist-for-woocommerce' ); ?></a></p>
										
									</div>
								</li>
									<?php
									if ( 'external' !== $_product->get_type() && $_product->is_in_stock() && true === $is_purc ) {
										$final_output = apply_filters( 'cart_total_wishlist', $cart_total, $_id, $price_single, $wid_to_show, $checkbox );
										$cart_total   = $cart_total + $final_output;
									}
									?>
							<?php endforeach; ?>
							<?php } ?>
								<?php ( 'edit' === $access ) ? do_action( 'add_option_after_wp', $cart_total ) : ''; ?>
							<?php else : ?>
							<span class="data-missing"><?php esc_html_e( 'No Products Added', 'ultimate-wishlist-for-woocommerce' ); ?></span>
						<?php endif; ?>
					</ul>
				</div>

				<div class="wfw_comment_wrapper">
					<div class="wfw_comment">
						<div class="wfw_comment_head">
							<span><?php esc_html_e( 'Add comment & priority', 'ultimate-wishlist-for-woocommerce' ); ?></span>
							<a href="javascript:void(0);" class="wfw_comment_close">+</a>
						</div>
						<form class="add-meta-to-wishlist" action="#" method="post">
							<ul class="wfw_comment_content">
								<li class="wfw_comment_message">
									<label for="comments" class="wfw_comment_label"><?php esc_html_e( 'Comment', 'ultimate-wishlist-for-woocommerce' ); ?>
									</label>
									<p><textarea name="comment" required="required" cols="4" rows="2"></textarea>
									<input type="hidden" class="comment_product" name="product">
									<input type="hidden" class="comment_wid" name="wid">
									</p>
									<div class="wfw_comment_priority">
										<span>
											<label for="comments" class="wfw_comment_label"><?php esc_html_e( 'Priority', 'ultimate-wishlist-for-woocommerce' ); ?>
											</label>
											<p>
											<select name="priority">
												<option value="medium"><?php esc_html_e( 'Medium', 'ultimate-wishlist-for-woocommerce' ); ?></option>
												<option value="high"><?php esc_html_e( 'High', 'ultimate-wishlist-for-woocommerce' ); ?></option>
												<option value="low"><?php esc_html_e( 'Low', 'ultimate-wishlist-for-woocommerce' ); ?></option>
											</select>
											</p>
										</span>
									</div>
								</li>
							</ul>
							<p class="wfw_comment_buttons">
							<button class="wfw_comment_cancel"><?php esc_html_e( 'Cancel', 'ultimate-wishlist-for-woocommerce' ); ?></button>
							<button class="wfw_comment_save"><?php esc_html_e( 'Save', 'ultimate-wishlist-for-woocommerce' ); ?></button>
							</p>
						</form>
					</div>
				</div>
				<?php
			endif;
			?>
	</section>
</main>

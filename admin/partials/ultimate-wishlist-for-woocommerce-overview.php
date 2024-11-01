<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Ultimate_Wishlist_For_Woocommerce
 * @subpackage Ultimate_Wishlist_For_Woocommerce/admin/partials
 */

?>
<div class="mwb-overview__wrapper">
	<div class="mwb-overview__banner">
		<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL ); ?>admin/image/bannerwish.png" alt="Overview banner image">
	</div>
	<div class="mwb-overview__content">
		<div class="mwb-overview__content-description">
			<h2><?php echo esc_html_e( 'What is a "Ultimate Wishlist for WooCommerce"?', 'ultimate-wishlist-for-woocommerce' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'"Ultimate Wishlist for WooCommerce" is a powerful plugin that boosts customer engagement by providing the Wishlist
					 feature on your WooCommerce store. With this plugin, your logged-in buyers can save products that they are interested in purchasing
					  but still are not ready to add it in the Cart. Customers can also invite family and friends, and give them access to add or view the wishlist,
					   or purchase the wishlist, making the gifting more convincing.
					 Overall, this plugin encourages and helps your buyers to wish for their desired products more and better. ',
					'ultimate-wishlist-for-woocommerce'
				);
				?>
			</p>
			<h3><?php esc_html_e( 'How Does This Plugin help?', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
			<ul class="mwb-overview__features">
				<li><?php esc_html_e( 'Improve user\'s experience by letting them save products as wishlists.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Help customers to save their products research for future reference.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Give reason to the customers to return to your eStore.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Easily find out the interest of the individual buyers based on their wishlist products.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Discover the most converting product by tracking the most wished products.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Enhance your retargeting marketing with better customer\'s data.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Sharing Wishlist feature helps to indirectly promote your store on customer\'s social media handle.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Customize the color and size of share buttons.', 'ultimate-wishlist-for-woocommerce' ); ?></li>
			</ul>
		</div>
		<h2> <?php esc_html_e( 'The Free Plugin Benefits', 'ultimate-wishlist-for-woocommerce' ); ?></h2>
		<div class="mwb-overview__keywords">
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Buyers.jpg' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( ' Buyers Can Create Wishlist ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'Create one wishlist and add as many products that buyers wish for. Edit, remove, share and purchase the products added to the wishlist.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Shareable.jpg' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( ' Shareable Wishlist ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'The created wishlist can be shared on social media such as Facebook, Twitter, Whatsapp, etc. Further, buyers can add their family and friends to their wishlist.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Set.jpg' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( ' Set Wishlist Popup ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'Set popup notification, that appears as a confirmation, when a product is added to the wishlist.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Customize.jpg' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( ' Customize Wishlist Button/Icon ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'Set the icon or button of how the "wishlist" feature is shown on the product listing or product page. Can be cart, heart, eye, tag, etc.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Purchase.jpg' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( ' Purchase Directly from Wishlist ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'The products added to the wishlist have an "Add to Cart" button, that the users can use, and directly purchase the product from the wishlist.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Icons_Purchase.jpg' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( ' More Useful Features with Pro Plugin ', 'ultimate-wishlist-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e( 'Allow multiple Wishlist, send automated emails, send in-stock notification, create API routing, and other features with Wishlist Pro Plugin.', 'ultimate-wishlist-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="mwb-overview__content-description">
			<h2><?php echo esc_html_e( 'Exclusive Support', 'ultimate-wishlist-for-woocommerce' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Receive dedicated 24*7 Phone, Email & Skype support. Our Support is ready to assist you regarding any query, issue, or feature request and if that doesn\'t work, our Technical team will connect with you personally and have your query resolved.',
					'ultimate-wishlist-for-woocommerce'
				);
				?>
			</p>
			<div class="wfw-overview_support-icon">
			<a href="tel:+1(888)575-2397"><img src="<?php echo esc_url( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/phone-call.png' ); ?>" alt="phone"></a>
			<a href="mailto:ticket@makewebbetter.com"><img src="<?php echo esc_url( ULTIMATE_WISHLIST_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/mail.png' ); ?>" alt="mail"></a>
		</div>
		</div>
	</div>
</div>

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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$manager       = Ultimate_Wishlist_For_Woocommerce_Crud_Manager::get_instance();
$response      = $manager->get_all();
$all_wishlists = 200 == $response['status'] ? $response['response'] : false;
?>
	<div class="uwfw-secion-wrap">
		<table border="1" id="uwfw-performance-analytics-table" width="100%" class="mwb-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Id', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Title', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Products', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Create Date', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Last Modified', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Owner', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Status', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Collaborators', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Properties', 'ultimate-wishlist-for-woocommerce' ); ?></th>
				<?php do_action( 'table_th_wishlist_listing' ); ?>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $all_wishlists ) && is_array( $all_wishlists ) ) : ?>
				<?php foreach ( $all_wishlists as $key => $value ) : ?>
					<tr>
						<?php
							$value['products']      = ! empty( $value['products'] ) ? json_decode( $value['products'] ) : array();
							$value['collaborators'] = ! empty( $value['collaborators'] ) ? json_decode( $value['collaborators'] ) : array();
							$value['properties']    = ! empty( $value['properties'] ) ? json_decode( $value['properties'] ) : array();
						?>
						 
						<td><?php echo esc_html( $value['id'] ); ?></td>
						<td><?php echo esc_html( $value['title'] ); ?></td>

						<td>
							<?php $products = $manager->get_prop_second( 'prod_id', $value['id'] ); ?>
							<?php
							if ( 200 === $products['status'] ) {
								$products = $products['message'];
							} else {
								$products = '';
							}
							if ( ! empty( $products ) && is_array( $products ) ) :
								foreach ( $products as $key => $products ) {
									foreach ( $products as $key => $_id ) :
										?>
									<p><?php echo esc_html( '- ' . get_the_title( $_id ) ); ?><p>
										<?php
							endforeach;
								}
							else :
								?>
								<p><?php echo esc_html__( '- Wishlist is Empty ', 'ultimate-wishlist-for-woocommerce' ); ?><p>
								<?php
							endif;
							?>
						</td>
						<td><?php echo esc_html( $value['createdate'] ); ?></td>
						<td><?php echo esc_html( $value['modifieddate'] ); ?></td>
						<td>
						<?php
						$user = get_user_by( 'email', $value['owner'] );
						echo esc_html( $user->user_nicename ? $user->user_nicename : $value['owner'] );
						?>
						</td>
						<td><?php echo esc_html( $value['status'] ); ?></td>
						<td>
							<?php foreach ( $value['collaborators'] as $key => $email ) : ?>
								<p>
								<?php
									$user = get_user_by( 'email', $email );
								if ( false == $user ) {
									echo esc_html( $email );
								} else {
									echo esc_html( $user->user_nicename );
								}
								?>
									<p>
							<?php endforeach; ?>
						</td>
						<td>
							<?php foreach ( $value['properties'] as $key => $prop ) : ?>
								<?php if ( 'default' == $key ) : ?>
									<?php if ( true == $prop ) : ?>
										<span class="wfw-default-wishlist"><?php esc_html_e( 'Is Default', 'ultimate-wishlist-for-woocommerce' ); ?> <span class="wfw-tick">&#10003;</span></span>
										<?php else : ?>
										<span class="wfw-default-wishlist"><?php esc_html_e( 'Is Default', 'ultimate-wishlist-for-woocommerce' ); ?> <span class="wfw-cross">&#10007;</span></span>
									<?php endif; ?>
									
								<?php elseif ( 'comments' == $key && ! empty( $prop ) ) : ?>
									<?php
										$prop = ! is_array( $prop ) ? json_decode( json_encode( $prop ), true ) : $prop;
									if ( ! empty( $prop ) && is_array( $prop ) ) :
										?>
											<?php foreach ( $prop as $pid => $_comments ) : ?>
												<hr><p class="wfw-comments"><?php echo esc_html( get_the_title( $pid ) ); ?><p>
												<span class="wfw-comments"> <span class="wfw-tick">&#10003;</span> <?php esc_html_e( 'Comment', 'ultimate-wishlist-for-woocommerce' ); ?> : <?php echo esc_html( $_comments['comment'] ); ?></span><br>
												<span class="wfw-comments"> <span class="wfw-tick">&#10003;</span> <?php esc_html_e( 'Priority', 'ultimate-wishlist-for-woocommerce' ); ?> : <?php echo esc_html( $_comments['priority'] ); ?></span><br>
											<?php endforeach; ?>
										<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</td>
						<?php do_action( 'table_td_wishlist_listing' ); ?>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td style="text-align:center;" colspan="10"><?php esc_html_e( 'No Wishlists Found.', 'ultimate-wishlist-for-woocommerce' ); ?></td>
				</tr>
			<?php endif; ?>

		</tbody>
	</table>
	</div>

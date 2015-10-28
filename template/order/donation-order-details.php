<?php
/**
 * Donation Details
 * 
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/order
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$order = wc_get_order( $order_id );
?>
<h2><?php _e( 'Donation Details', WC_QD_TXT ); ?></h2>
<table class="shop_table order_details">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Project', WC_QD_TXT ); ?></th>
			<th class="product-total"><?php _e( 'Amount', WC_QD_TXT ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $order->get_items() as $item_id => $item ) {
				wc_get_template( 'order/order-details-item.php', array(
					'order'   => $order,
					'item_id' => $item_id,
					'item'    => $item,
					'product' => apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item )
				) );
			}
		?>
		<?php do_action( 'woocommerce_order_items_table', $order ); ?>
	</tbody>
	<tfoot>
		<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
                    if('cart_subtotal' == $key ||  'order_total' == $key){continue;}
				?>
				<tr>
					<th scope="row"><?php echo $total['label']; ?></th>
					<td><?php echo $total['value']; ?></td>
				</tr>
				<?php
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<?php wc_get_template( 'order/order-details-customer.php', array( 'order' =>  $order ) ); ?>
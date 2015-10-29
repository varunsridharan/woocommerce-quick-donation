<?php
/**
 * Donation Item Details
 * 
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/order
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
	<td class="product-name">
		<?php
			$is_visible = $product && $product->is_visible();
            $project_id = WC_QD()->db()->get_project_id($order->id); 
			echo sprintf( '<a href="%s">%s</a>', get_permalink( $project_id ), get_the_title($project_id ));
		?>
	</td>
	<td class="product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>
</tr>
<?php if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $product->id, '_purchase_note', true ) ) ) : ?>
<tr class="product-purchase-note">
	<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
</tr>
<?php endif; ?>

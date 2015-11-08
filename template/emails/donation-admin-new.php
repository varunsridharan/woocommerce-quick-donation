<?php
/**
 * Customer invoice email
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/emails
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_donation_email_header', $email_heading ); ?>

<?php if ( $order->has_status( 'pending' ) ) : ?>

	<p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', WC_QD_TXT ), get_bloginfo( 'name', 'display' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', WC_QD_TXT ) . '</a>' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php printf( __( 'Donation ID #%s', WC_QD_TXT ), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Project', WC_QD_TXT ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Price', WC_QD_TXT ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			switch ( $order->get_status() ) {
				case "completed" :
					echo $order->email_order_items_table( false, false, true );
				break;
				case "processing" :
					echo $order->email_order_items_table( false, true, true );
				break;
				default :
					echo $order->email_order_items_table( false, true, false );
				break;
			}
		?>
	</tbody>
	<tfoot>
		<?php

			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
                 
				foreach ( $totals as $subKey => $total ) {
					$i++;
                    
                    if($subKey == 'cart_subtotal' || $subKey == 'order_total'){continue;}
					?><tr>
						<td class="td" colspan="1" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px !important;'; ?>"><?php echo $total['label']; ?></td>
						<td class="td" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px !important;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_donation_email_footer' ); ?>

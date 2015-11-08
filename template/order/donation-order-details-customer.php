<?php
/**
 * Donor Details
 * 
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/order
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?> 
    <div class="col1-set addresses">
        <div class="col-1">
            <header class="title"> <h3><?php _e( 'Billing Address', WC_QD_TXT ); ?></h3> </header>
            <address style="margin-bottom: 10px;"> 
                <?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', WC_QD_TXT ); ?> 
            </address>

            <?php if ( $order->customer_note ) : ?>
                <p style="margin-bottom: 10px;"><strong><?php _e( 'Note:', WC_QD_TXT ); ?></strong> <?php echo wptexturize( $order->customer_note ); ?></p>
            <?php endif; ?>
            <?php if ( $order->billing_email ) : ?>
                <p style="margin-bottom: 10px;"><strong><?php _e( 'Email:', WC_QD_TXT ); ?></strong> <?php echo esc_html( $order->billing_email ); ?></p>
            <?php endif; ?>            
            
            <?php if ( $order->billing_phone ) : ?>
                <p style="margin-bottom: 10px;"><strong><?php _e( 'Telephone:', WC_QD_TXT ); ?></strong> <?php echo esc_html( $order->billing_phone ); ?></p>
            <?php endif; ?>
            <?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

        </div><!-- /.col-1 -->
    </div><!-- /.col2-set -->
 
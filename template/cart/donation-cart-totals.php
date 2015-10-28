<?php
/**
 * Cart totals
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/cart
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class=" <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="wc-proceed-to-checkout">

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>

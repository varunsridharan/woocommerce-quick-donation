<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
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

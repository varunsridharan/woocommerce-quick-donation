<?php
/**
 * Cart errors page
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/checkout
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<p><?php _e( 'There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.', WC_QD_TXT ) ?></p>

<?php do_action( 'woocommerce_cart_has_errors' ); ?>

<p><a class="button wc-backward" href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>"><?php _e( 'Return To Cart', WC_QD_TXT ) ?></a></p>

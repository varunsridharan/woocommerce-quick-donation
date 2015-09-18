<?php
/**
 * functionality of the plugin.
 *
 * @link       @TODO
 * @since      1.0
 *
 * @package    @TODO
 * @subpackage @TODO
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Shortcode {

    public function __construct() {
        add_shortcode( 'wc_quick_donation', array($this, 'shortcode_handler' ));
    }
    
    
    public function shortcode_handler($settings){
        global $donation_box,$donation_price,$currency;
        $settings = shortcode_atts( array(
        'type' => 'radio',
        'grouped' => true,
        ), $settings );
        
        $donation_box = WC_QD()->f()->generate_donation_selbox($settings['grouped'],$settings['type']);
        $donation_price =  WC_QD()->f()->generate_price_box();
        $currency = get_woocommerce_currency_symbol();
        WC_QD()->f()->load_template('donation_form.php',WC_QD_TEMPLATE);
    }

}
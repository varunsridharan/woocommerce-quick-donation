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
        'type' => 'select',
        'grouped' => false,
        ), $settings );
        
        $donation_box = WC_QD()->f()->generate_donation_selbox($settings['grouped'],$settings['type']);
        $donation_price =  WC_QD()->f()->generate_price_box();
        $currency = get_woocommerce_currency_symbol();
        $return_value = '';
        ob_start();
        do_action('wc_quick_donation_before_doantion_form',$return_value , $settings['type'],$settings['grouped']);
        WC_QD()->f()->load_template('donation-form.php',WC_QD_TEMPLATE);
        do_action('wc_quick_donation_after_doantion_form',$return_value , $settings['type'],$settings['grouped']);
        
        return ob_get_clean();
    }

}
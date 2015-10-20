<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Admin_Order_Page_Functions {
    public function __construct(){
        add_action('add_meta_boxes_shop_order',array($this,'remove_metabox'),99,2);
    }
     
    public function remove_metabox($post){ 
        $is_donation = WC_QD()->db()->_is_donation($post->ID);
        if($is_donation){
            remove_meta_box('woocommerce-order-items','shop_order','normal');
            remove_meta_box('woocommerce-order-downloads','shop_order','normal');
            
        }
    }
    
}
?>
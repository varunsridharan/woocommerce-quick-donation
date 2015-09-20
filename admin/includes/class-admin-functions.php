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

class WooCommerce_Quick_Donation_Admin_Function {
    
    public function __construct(){
        add_action( 'post_row_actions', array($this,'protect_donation_product'),99,2);
        
    } 
    
	/**
	* Protects Donation Product By 
	* @filter_user post_row_actions
	* @param  Array $actions Refer WP.org
	* @param  Array $post    Refer WP.org
	* @return Array Refer WP.org
	* @since 1.0
	*/
	public function protect_donation_product($actions,$post) {
        
		if('product' == $post->post_type) {  
			if($post->ID == WC_QD_ID){
				unset($actions['inline hide-if-no-js']);
				unset($actions['trash']);
				unset($actions['duplicate']);
				$actions['trash'] = '<a href="javascript:alert(\'Remove Woocommerce Quick Donation Plugin To Remove This Product \');"> Trash </a>';
				
			}
		}
        return $actions;
	}    
}
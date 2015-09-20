<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Process extends WooCommerce_Quick_Donation  {

    public $is_donation_exists = false;
        
    function __construct(){
        parent::__construct();
       // add_action( 'wp_loaded',array($this,'on_wp_loaded'),20); 
        add_filter( 'woocommerce_get_price', array($this,'get_price'),10,2);
    }
    
    public function on_wp_loaded(){
        if($this->check_donation_exists_cart()){ $this->is_donation_exists = true; } 
        
        $this->process_donation(); 
    }
    
     
    public function process_donation(){
        if(isset($_POST['donation_add'])){
            global $woocommerce;
            $donation = isset($_POST['wc_qd_donate_project_price']) ? $_POST['wc_qd_donate_project_price'] : false;
			$projects = isset($_POST['wc_qd_donate_project_name']) && !empty($_POST['wc_qd_donate_project_name']) ? $_POST['wc_qd_donate_project_name'] : false;
            $donate_price = floatval($donation);
            $woocommerce->session->jc_donation = $donate_price;
            $woocommerce->session->projects = $projects;
            $woocommerce->cart->add_to_cart(self::$donation_id);
            
        }
    }
    
    /**
     * Checks If Donation Product Exists In Cart
     */
    public function check_donation_exists_cart(){
        global $woocommerce;
        $found = false;
        if( sizeof($woocommerce->cart->get_cart()) > 0){
            foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
                $_product = $values['data'];
                if($_product->id == self::$donation_id)
                    $found = true;
            }

        }
        return $found;
    }   
    
    
	/**
	 * Gets Donation Current Price
	 * @param  $price 
	 * @param  $product 
	 * @returns 0 | price
	 */
	public function get_price($price, $product){
		global $woocommerce;
        
        if($this->is_donation_exists){
            if($product->id == self::$donation_id){ 
                return isset($woocommerce->session->jc_donation) ? floatval($woocommerce->session->jc_donation) : 0; 
            }
        }
		return $price;
	}	    
}




    
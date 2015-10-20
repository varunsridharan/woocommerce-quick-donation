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
        add_action( 'wp_loaded',array($this,'on_wp_loaded'),20);
        add_filter( 'woocommerce_get_price', array($this,'get_price'),10,2);
        
        
    }
    
    public function on_wp_loaded(){
        if($this->check_donation_exists_cart()){ 
            $this->is_donation_exists = true; 
            add_action('woocommerce_add_order_item_meta',array($this,'add_order_meta'),99,3);
            add_action( 'woocommerce_checkout_update_order_meta',array($this,'update_order_meta'));
            add_action( 'woocommerce_checkout_update_order_meta',  array($this,'save_order_id_db'));
            add_action( 'woocommerce_email',array($this,'remove_email_actions'));
        } 
        $this->process_donation(); 
    }
    
    
    public function remove_email_actions($email_class ){
            // New order emails
            remove_action('woocommerce_order_status_pending_to_processing_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));
            remove_action('woocommerce_order_status_pending_to_completed_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));
            remove_action('woocommerce_order_status_pending_to_on-hold_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));
            remove_action('woocommerce_order_status_failed_to_processing_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));
            remove_action('woocommerce_order_status_failed_to_completed_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));
            remove_action('woocommerce_order_status_failed_to_on-hold_notification',
                            array($email_class->emails['WC_Email_New_Order'],'trigger'));

            // Processing order emails
            remove_action('woocommerce_order_status_pending_to_processing_notification',
                            array($email_class->emails['WC_Email_Customer_Processing_Order'],'trigger'));
            remove_action('woocommerce_order_status_pending_to_on-hold_notification',
                            array($email_class->emails['WC_Email_Customer_Processing_Order'],'trigger'));

            // Completed order emails
            remove_action('woocommerce_order_status_completed_notification',
                            array($email_class->emails['WC_Email_Customer_Completed_Order'],'trigger'));

        
             // New order emails
            add_action('woocommerce_order_status_pending_to_processing_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_pending_to_completed_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_pending_to_on-hold_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_failed_to_processing_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_failed_to_completed_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_failed_to_on-hold_notification',
                            array($email_class->emails[WC_QD_DB.'new_donation_email'],'trigger'));       
        
        
        
        
    }
     
    public function process_donation(){
        if(isset($_POST['donation_add'])){
            global $woocommerce;
            $donateprice = isset($_POST['wc_qd_donate_project_price']) ? $_POST['wc_qd_donate_project_price'] : false;
			$projects = isset($_POST['wc_qd_donate_project_name']) && !empty($_POST['wc_qd_donate_project_name']) ? $_POST['wc_qd_donate_project_name'] : false;

            $check_donation_price = $this->check_donation_price_status($donateprice);
            if( ! $check_donation_price){return false;}

            $donate_price = floatval($donateprice);
            
            $price_check = $this->check_min_max($projects,$donate_price);
            if(!$price_check){return false;}
            
            $woocommerce->session->donation_price = $donate_price;
            $woocommerce->session->projects = $projects;
            
            $donation_added = $woocommerce->cart->add_to_cart(self::$donation_id);
            
            if($donation_added){
                $this->is_donation_exists = true;
                $this->redirect_cart();
                wc_add_notice('Success','success');
                
                
            }
            
        }
    }
    
    
    public function check_donation_price_status($price){
    
        if(empty($price)){ 
            $message = WC_QD()->f()->get_message(WC_QD_DB.'empty_donation_msg');
            wc_add_notice($message,'error');
            return false;
        }
        
        if(empty($price) || ! is_int($price) && $price == 0){
            $id = WC_QD_DB.'invalid_donation_msg';
            $search_replace = array('{donation_amount}' => $price);
            $message = WC_QD()->f()->get_message($id,$search_replace);
            wc_add_notice($message,'error');
            return false;
        }
        
        return true;
    }
    
    
    public function check_min_max($project_id,$price){
        $min_required = $this->f()->min_project($project_id);
        $max_required = $this->f()->max_project($project_id);
        $price = intval($price);
        
        if($min_required){
            $min_required = intval($min_required);
            if($price < $min_required){
                $id = WC_QD_DB.'min_rda_msg';
                $search_replace = array('{donation_amount}' => $price, '{min_amount}' => $min_required);
                $message = WC_QD()->f()->get_message($id,$search_replace);
                wc_add_notice($message,'error');
                return false;
            }
        }
        
        
        if($max_required){
            $max_required = intval($max_required);
            if($price > $max_required){
                $id = WC_QD_DB.'max_rda_msg';
                $search_replace = array('{donation_amount}' => $price, '{max_amount}' => $max_required);
                $message = WC_QD()->f()->get_message($id,$search_replace);
                wc_add_notice($message,'error');
                return false;
            }
        }
        return true;
    }
    
    public function redirect_cart(){
        if($this->is_donation_exists){
            $redirect = WC_QD()->settings()->get_option(WC_QD_DB.'redirect_user');
            $url = '';
            if($redirect == 'cart'){
               $url = WC()->cart->get_cart_url(); 
            } else if($redirect == 'checkout'){
                $url = WC()->cart->get_checkout_url(); 
            }            
            wp_safe_redirect($url);
            exit;
        }
    }

    public function update_order_meta($order_id){
        global $woocommerce;

        update_post_meta( $order_id,"_is_donation",true);
        update_post_meta( $order_id,"_project_details",$woocommerce->session->projects);
    }
    
    public function add_order_meta($item_id, $values, $cart_item_key){
        global $woocommerce;
        wc_add_order_item_meta( $item_id, "_project_details",$woocommerce->session->projects);	
        wc_add_order_item_meta( $item_id, "_is_donation",true);
    }
    
    
    public function save_order_id_db($order_id){
        global $woocommerce;
        $project_id = intval($woocommerce->session->projects);
        $user_id = get_current_user_id();
        
        WC_QD()->db()->add_db_option($order_id,$project_id,$user_id);
        
    }
    
	/**
	 * Gets Donation Current Price
	 * @param  $price 
	 * @param  $product 
	 * @returns 0 | price
	 */
	public function get_price($price, $product){
		global $woocommerce;
         
        if($product->id == self::$donation_id){ 
            return isset($woocommerce->session->donation_price) ? floatval($woocommerce->session->donation_price) : 0; 
        } 
		return $price;
    }


}
                       
                       ?>
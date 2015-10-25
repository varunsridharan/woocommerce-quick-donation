<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_MyAccount_Fuctions  {
    private $order_ids;

    public function __construct(){
        $order_ids = WC_QD()->db()->get_user_donations_ids();
        $this->order_ids = WC_QD()->db()->extract_donation_id($order_ids);
        
        add_filter( 'woocommerce_my_account_my_orders_query',array($this,'change_myaccount_query'));
        add_action( 'woocommerce_before_my_account' , array($this,'show_recent_donations'));
        
        
    }
    
    
    public function change_myaccount_query($query){
        $query['exclude'] = $this->order_ids;
        return $query;
    }

    
    public function show_recent_donations(){
        wc_get_template( 'myaccount/my-donations.php' , array( 'donation_ids' => $this->order_ids ) );
    }
}
?>
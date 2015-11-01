<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Emails_Functions  {
    public $remove_emails;
    private $remove_status;
    
    function __construct(){
        add_action('woocommerce_order_status_on-hold',array($this,'check_order'),1);
        add_action( 'woocommerce_email',array($this,'remove_email_actions'),1);
    }
    
    public function remove_email_actions($e){
        if($this->remove_emails){ 
            $this->remove_default_new_email($e);
            $this->remove_default_processing_email($e);
            
             // New order emails
            add_action('woocommerce_order_status_pending_to_processing_notification',
                            array($e->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_pending_to_completed_notification',
                            array($e->emails[WC_QD_DB.'new_donation_email'],'trigger'));
            add_action('woocommerce_order_status_pending_to_on-hold_notification',
                            array($e->emails[WC_QD_DB.'new_donation_email'],'trigger'));
        }
    }
    
    public function wca_onhold(){
        var_dump(4333);
    }
    
    public function check_order($order_id){
        if(!WC_QD()->db()->_is_donation($order_id)){return;} 
        $this->remove_emails = true;
        $this->remove_status = 'onhold';
        //exit;
    }
    
    private function remove_default_processing_email($e){
        remove_action('woocommerce_order_status_pending_to_processing_notification',
              array($e->emails['WC_Email_Customer_Processing_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification',
              array($e->emails['WC_Email_Customer_Processing_Order'],'trigger'));
    }
    
    private function remove_default_new_email($e){
        remove_action( 'woocommerce_order_status_pending_to_processing_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action( 'woocommerce_order_status_pending_to_completed_notification', 
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', 
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action( 'woocommerce_order_status_failed_to_processing_notification', 
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action( 'woocommerce_order_status_failed_to_completed_notification', 
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', 
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_processing_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_completed_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_processing_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_completed_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_on-hold_notification',
                      array($e->emails['WC_Email_New_Order'],'trigger'));
    }
    
}

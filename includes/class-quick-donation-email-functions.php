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
        add_action('woocommerce_order_status_pending',array($this,'check_order'),1);
        add_action('woocommerce_order_status_processing',array($this,'check_order'),1);
        add_action('woocommerce_order_status_completed',array($this,'check_order'),1);
        add_action('woocommerce_order_status_failed',array($this,'check_order'),1);
        add_action( 'woocommerce_email',array($this,'remove_email_actions'),1);
        
        add_action( 'woocommerce_donation_email_header', array( $this, 'email_header' ) );
        add_action( 'woocommerce_donation_email_footer', array( $this, 'email_footer' ) );        
        //add_filter( 'woocommerce_template_directory',    array( $this, 'change_dir'),2,2);
    }
    
    
	/**
	 * Get the email header.
	 *
	 * @param mixed $email_heading heading for the email
	 */
	public function email_header( $email_heading ) {
		wc_get_template( 'emails/donation-email-header.php', array( 'email_heading' => $email_heading ) );
	}

	/**
	 * Get the email footer.
	 */
	public function email_footer() {
		wc_get_template( 'emails/donation-email-footer.php');
	}    
    
    public function change_dir($dir,$emailtemplate){ 
        $template = WC_QD()->f()->get_template_list();
        foreach($template as $temp){
            if(in_array($emailtemplate,$temp)){
                $dir = $dir.'/donation'; 
            }
        }

        return $dir; 
    }
    public function remove_email_actions($e){
        if($this->remove_emails){ 
            $this->remove_default_new_email($e->emails);
            $this->remove_default_processing_email($e->emails);
            $this->add_donation_new_email($e->emails);
            $this->add_donation_processing_email($e->emails);
        }
    }
    
    public function check_order($order_id){ 
        if(! WC_QD()->db()->_is_donation($order_id)){return;} 
        $this->remove_emails = true;
    }
    
    private function remove_default_processing_email($email_class){ 
        remove_action('woocommerce_order_status_pending_to_processing_notification',
                      array( $email_class['WC_Email_Customer_Processing_Order'], 'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification',
                      array( $email_class['WC_Email_Customer_Processing_Order'], 'trigger'));    
    }
    
    private function remove_default_new_email($email_class){
        remove_action('woocommerce_order_status_pending_to_processing_notification', 
                      array( $email_class['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_completed_notification',  
                      array( $email_class['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_pending_to_on-hold_notification',    
                      array( $email_class['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_processing_notification',  
                      array( $email_class['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_completed_notification',   
                      array( $email_class['WC_Email_New_Order'],'trigger'));
        remove_action('woocommerce_order_status_failed_to_on-hold_notification',     
                      array( $email_class['WC_Email_New_Order'],'trigger'));        
    }
    
    private function add_donation_new_email($email_class){
        add_action('woocommerce_order_status_pending_to_processing_notification', 
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
        add_action('woocommerce_order_status_pending_to_completed_notification',  
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
        add_action('woocommerce_order_status_pending_to_on-hold_notification',    
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
        add_action('woocommerce_order_status_failed_to_processing_notification',  
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
        add_action('woocommerce_order_status_failed_to_completed_notification',   
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
        add_action('woocommerce_order_status_failed_to_on-hold_notification',     
                   array($email_class[WC_QD_DB.'new_donation_email'],'trigger'));
    }  
    
    
    private function add_donation_processing_email($email_class){ 
        add_action('woocommerce_order_status_pending_to_processing_notification',
                   array($email_class[WC_QD_DB.'donation_processing_email'], 'trigger'));
        add_action('woocommerce_order_status_pending_to_on-hold_notification',
                   array($email_class[WC_QD_DB.'donation_processing_email'], 'trigger'));
    }    
}



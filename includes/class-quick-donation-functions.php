<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Functions  {
    protected static $project_db_list = null;
    public static $search_template =   array(
        'general' => array(
            'donation-form.php' => 'donation-form.php',
            'field-radio.php' => 'fields/field-radio.php',
            'field-select.php' => 'fields/field-select.php',
            'field-text.php' => 'fields/field-text.php',
            'myaccount/my-donations.php' => 'myaccount/my-donations.php',
            
            'emails/donation-customer-invoice.php' => 'emails/donation-customer-invoice.php',
            'emails/plain/donation-customer-invoice.php' => 'emails/plain/donation-customer-invoice.php',
        ),

        'is_donation' => array( 
            'cart/cart-item-data.php' => 'cart/donation-cart-item-data',
            'cart/cart-shipping.php' => 'cart/donation-cart-shipping.php',
            'cart/cart-totals.php' => 'cart/donation-cart-totals.php',
            'cart/cart.php' => 'cart/donation-cart.php',
            'cart/proceed-to-checkout-button.php' => 'cart/donation-proceed-to-checkout-button.php',
            'checkout/cart-errors.php' => 'checkout/donation-cart-errors.php',
            'checkout/form-billing.php' => 'checkout/donation-form-billing.php',
            'checkout/form-checkout.php' => 'checkout/donation-form-checkout.php',
            'checkout/form-coupon.php' => 'checkout/donation-form-coupon.php',
            'checkout/form-login.php' => 'checkout/donation-form-login.php',
            'checkout/form-pay.php' => 'checkout/donation-form-pay.php',
            'checkout/form-shipping.php' => 'checkout/donation-form-shipping.php',
            'checkout/payment-method.php' => 'checkout/donation-payment-method.php',
            'checkout/payment.php' => 'checkout/donation-payment.php',
            'checkout/review-order.php' => 'checkout/donation-review-order.php',
        ),
        
        'after_order' => array(
            'order/order-details.php' => 'order/donation-order-details.php',
            'checkout/thankyou.php' => 'checkout/donation-thankyou.php',
            'myaccount/view-order.php' => 'myaccount/view-donation.php',
            'order/order-details-item.php' => 'order/order-details-item.php',
            'order/order-details-customer.php' => 'order/order-details-customer.php',

            
            'emails/email-addresses.php' => 'emails/donation-email-addresses.php',
            'emails/email-footer.php' => 'emails/donation-email-footer.php',
            'emails/email-header.php' => 'emails/donation-email-header.php',
            'emails/email-order-items.php' => 'emails/donation-email-order-items.php',
            'emails/email-styles.php' => 'emails/donation-email-styles.php',
            
            
            'emails/plain/email-addresses.php' => 'emails/plain/donation-email-addresses.php',
            'emails/plain/email-order-items.php' => 'emails/plain/donation-email-order-items.php',
        )
        
        
        );    
    
    function __construct(){
        add_filter( 'wc_get_template',array($this,'get_template'),10,5);
        add_filter( 'woocommerce_email_classes',  array($this,'add_email_classes'));
        add_action( 'woocommerce_available_payment_gateways',array($this,'remove_gateway'));
        add_filter( 'woocommerce_locate_template' , array($this,'wc_locate_template'),10,3);
        add_filter( 'the_title', array($this,'wc_page_endpoint_title' ),10,2);
    }
    
    
    public function wc_page_endpoint_title($title = '', $id = ''){
        if(is_page($id)){
            global $wp_query;

            if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_page() && is_wc_endpoint_url() ) {

                $endpoint = WC()->query->get_current_endpoint();

                if('order-received' == $endpoint){
                    $order_id = $wp_query->query['order-received'];
                    if(WC_QD()->db()->_is_donation($order_id)){
                        $title = 'Donation Received';

                    }
                }

                if('view-order' == $endpoint){
                    $order_id = $wp_query->query['view-order'];
                    if(WC_QD()->db()->_is_donation($order_id)){
                        $title = 'Donation #'.$order_id;
                        remove_filter( 'the_title', 'wc_page_endpoint_title' );
                    }
                }
            }
        }
        return $title;    
    }
    
    public function add_email_classes($email_classes){
        $email_classes[WC_QD_DB.'new_donation_email'] = require(WC_QD_INC.'emails/class-new-email.php');
        //$email_classes[WC_QD_DB.'processing_donation_email'] = require(WC_QD_INC.'emails/class-processing-email.php');
        //$email_classes[WC_QD_DB.'completed_donation_email'] = require(WC_QD_INC.'emails/class-completed-email.php');
        return $email_classes;
    }
    
    /**
     * Get Donation Project List From DB
     */
    public function get_donation_project_list(){
        if(self::$project_db_list != null || self::$project_db_list != ''){
            return self::$project_db_list;
        }
        $args = array(
            'posts_per_page'   => 0,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => WC_QD_PT,
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'post_status'      => 'publish',
            'suppress_filters' => true 
        );
        self::$project_db_list = get_posts($args);
        return self::$project_db_list;
    }
    
    public function get_porject_list($grouped = false){
        $list = $this->get_donation_project_list();
        $projects = array();
        foreach($list as $project){
            if($grouped){
                $term = get_the_terms( $project->ID, WC_QD_CAT );
                $projects[$term[0]->name][$project->ID] = $project->post_title;
            } else {
                $projects[$project->ID] = $project->post_title;
            } 
        }
        return $projects;
    }
    
     
    public function generate_donation_selbox($grouped = false,$type = 'select'){
        global $id, $name, $class, $field_output, $is_grouped, $project_list,$attributes;
        $field_output = '';
        $id = 'donation_project';
        $name = 'wc_qd_donate_project_name';
        $class = apply_filters('wcqd_project_name_'.$type.'_class',array(),$type);
        $custom_attributes = apply_filters('wcqd_project_name_'.$type.'_attribute',array(),$type);
        $is_grouped = $grouped;
        $project_list = $this->get_porject_list($grouped);
        
        $class = implode(' ',$class);
        $attributes = '';
        foreach($custom_attributes as $attr_key => $attr_val) {
            $attributes .= $attr_key.'="'.$attr_val.'" ';
        }

        $field_output = $this->load_template('field-'.$type.'.php', WC_QD_TEMPLATE.'fields/' , array('id' => $id, 
                                                                                     'name' => $name, 
                                                                                     'class' => $class, 
                                                                                     'field_output' => $field_output, 
                                                                                     'is_grouped' => $is_grouped, 
                                                                                     'project_list' => $project_list, 
                                                                                     'attributes' => $attributes));
        return $field_output;
    }

    
    public function generate_price_box(){
        global $id, $name, $class, $field_output,$attributes,$value;
        $field_output = '';
        $id = 'donation_price';
        $name = 'wc_qd_donate_project_price';
        $class = apply_filters('wcqd_project_price_text_class',array(),'text');
        $custom_attributes = apply_filters('wcqd_project_price_text_attribute',array(),'text');
        $value = '';
        $class = implode(' ',$class);
        $attributes = '';
        foreach($custom_attributes as $attr_key => $attr_val) {
            $attributes .= $attr_key.'="'.$attr_val.'" ';
        }
        
        

        $field_output = $this->load_template('field-text.php',WC_QD_TEMPLATE . 'fields/' ,array('id' => $id,
                                                                                'name' => $name,
                                                                                'class' => $class,
                                                                                'field_output' => $field_output,
                                                                                'attributes' => $attributes,
                                                                                'value' => $value));        
        
        return $field_output;
    }
    
    public function load_template($file,$path,$args = array()){
        $field_output = '';
        $wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
        ob_start();
        $wc_get_template( $file,$args, '', $path); 
        $field_output = ob_get_clean(); 
        ob_flush();
        return $field_output;
    }
    
    public function locate_template($template){
        $default_path = WC_QD_TEMPLATE;
        $template_path = WC_CORE_TEMPLATE.'donation/';
        $template = $template;
        $locate = wc_locate_template($template,$template_path, $default_path); 
        return $locate;
    }
    
    public function wc_locate_template($template_full_path,$template_name,$template_dir){
        
        if(file_exists($template_full_path)){ return $template_full_path; }
        
        $template_full_path = $template_full_path;

        if(isset(self::$search_template['general'][$template_name])){
            $template_full_path = WC_QD_TEMPLATE.self::$search_template['general'][$template_name];
        } 

        return $template_full_path;
    }
    
    
    public function remove_gateway($gateways){
        if(WC_QD()->check_donation_exists_cart()){
           // var_dump($gateway);
           $allowed_gateway = WC_QD()->settings()->get_option(WC_QD_DB.'payment_gateway');
           foreach($gateways as $gateway){
                if(! in_array($gateway->id,$allowed_gateway)){
                    unset($gateways[$gateway->id]);
                }
            }
        }
        
        return $gateways;
    }
 
    public function get_admin_pay_gate(){
        $gateway = $this->get_payment_gateways();
        if(! empty($gateway)){
            return $gateway;
        } else {
            wc_qd_notice(__('No Payment Gateway Configured In WooCommerce. Kindly Configure One',WC_QD_TXT),'error');
            
        }
        return array();
    }
    
    public function get_payment_gateways(){
        $payment = WC()->payment_gateways->payment_gateways();
		$gateways = array();

		foreach($payment as $gateway){
			if ( $gateway->enabled == 'yes' ){
				$gateways[$gateway->id] = $gateway->title;
			}
		}
        
		return $gateways;
    }
    
    public function get_template($located, $template_name, $args, $template_path, $default_path ){
        $file = $located; 
        $order_id = 0;
        
        if(isset($args['order_id'])){ $order_id = $args['order_id']; }
        if(isset($args['order']->id)){ $order_id = $args['order']->id; }

        if(isset(self::$search_template['general'][$template_name])){
            $file = WC_QD()->f()->locate_template(self::$search_template['general'][$template_name]);
        }
        
        if(WC_QD()->check_donation_exists_cart()){
            if(isset(self::$search_template['is_donation'][$template_name])){
                $file = WC_QD()->f()->locate_template(self::$search_template['is_donation'][$template_name]);
            } 
        } 
        
    
        if(WC_QD()->db()->_is_donation($order_id)){
            if(isset(self::$search_template['after_order'][$template_name])){
                $file = WC_QD()->f()->locate_template(self::$search_template['after_order'][$template_name]);
            } 
        }
        
        return $file;
    }    
}

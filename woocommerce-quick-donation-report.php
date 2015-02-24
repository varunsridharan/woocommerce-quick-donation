<?php
/**
 * Package : WooCommerce Quick Donation
 * Since : 1.0
 * Usage : Custom Report Page For Donation
 */

class wc_quick_donation_report {
    private $donation_orderids;
    private $status_count;
    private $menu_name;
    private $menu_slug;
    /**
     * Setup WC Quick Donation Report Page
     */
    function __construct(){
        $this->status_count = 0;
        $this->donation_orderids = json_decode(get_option('wc_quick_donation_ids')); 
        $this->get_status_count();
        $this->menu_name = 'Donations';
        $this->menu_slug = 'wc-quick-donations-orders';
        add_action('admin_menu', array($this,'register_menu'));
        add_action('admin_menu',  array($this,'add_donation_notification_bubble'),99);
        add_filter('woocommerce_screen_ids',array($this,'set_wc_screen_ids'));
    }
 
    public function set_wc_screen_ids($screen){
        $screen[] = 'woocommerce_page_wc-quick-donations-orders';
        return $screen;
    }
    
    
    /**
     * Registers A Menu In Admin
     */
    public function register_menu(){ 
        add_submenu_page( 'woocommerce', $this->menu_name,$this->menu_name, 'view_woocommerce_reports', $this->menu_slug, array( $this, 'donation_orders_listing' ) );
    }
    
    public function add_donation_notification_bubble()  {
        global $submenu; 
        if(isset($submenu['woocommerce'])){
            
        foreach($submenu['woocommerce'] as $menuK => $menu){
            if($menu[2] === $this->menu_slug ){
                $submenu['woocommerce'][$menuK][0] .=  "<span class='update-plugins count-1'><span class='update-count'>$this->status_count </span></span>"; 
            }
        }
        }
        

    }
    
    public function donation_orders_listing(){
        global $wpdb;
        $args = array(   'post_type' => 'shop_order', 'post_status' =>  array_keys(wc_get_order_statuses()),'post__in' => $this->donation_orderids );
        $wp_query = new WP_Query($args);
        require('wc_quick_donation_listing_table.php');
        tt_render_list_page($wp_query);
    }
    
    private function get_status_count(){
        if(!empty($this->donation_orderids)){
            foreach($this->donation_orderids as $id){
                $order_status = get_post_status($id);
                if($order_status == 'wc-on-hold' || $order_status == 'wc-processing'){
                    $this->status_count++;
                }
            }
            return $this->status_count;
        }
        return '';
    }
    
    private function generate_data(){
        foreach($this->donation_orderids as $id){
            $order_details = $this->get_order_info($id);
            $order_meta = $this->get_order_meta($id);
            $order_info = array_merge($order_details,$order_meta);
            unset($order_details,$order_meta);
            $user_details = $this->get_user_info($order_info['by_user']);
            $order = array_merge($order_info,$user_details);
        }
    }  
    
   /* private function _generate_data(){
        require(wc_qd_p.'views/report_tbl_header.php');
        global $i;
        $i = 1;
        foreach($this->donation_orderids as $id){
            $order_details = $this->get_order_info($id);
            $order_meta = $this->get_order_meta($id);
            $order_info = array_merge($order_details,$order_meta);
            unset($order_details,$order_meta);
            $user_details = $this->get_user_info($order_info['by_user']);
            global $order;
            $order = array_merge($order_info,$user_details);
            require(wc_qd_p.'views/report_tbl_content.php');
            $i++;
        }
        require(wc_qd_p.'views/report_tbl_footer.php');
    }*/
    
    /**
     * Gets Order Details
     * @param   INT $id Order Post ID
     * @returns [[Type]] [[Description]]
     */
    private function get_order_info($id){
        global $wc_quick_donation;
        $return_details = array();
        $order = new WC_Order($id);
        $items = $order->get_items();
        $order_details = $order->post; 
        foreach($items as $item){
            if($wc_quick_donation->donation_id == $item['product_id']){
                $return_details['amount'] = floatval($item['item_meta']['_line_total'][0]);
            }
        }
        $return_details['ID'] = $order_details->ID;
        $return_details['by_user'] = $order->user_id;
        $return_details['date_gmt'] = $order_details->post_date_gmt;
        $return_details['date'] = $order_details->post_date; 
        $return_details['address'] = $order->get_formatted_billing_address();
        unset($order);
        return $return_details;        
    }
    
    /**
     * Gets Order Meta Data Like pay_method, currency
     * @param [[Type]] $id [[Description]]
     * @since 1.0
     */
    private function get_order_meta($id){
        $return_details = array();
        $meta = get_post_meta($id);
        $return_details['currency'] = $meta['_order_currency'][0];
        $return_details['pay_method'] = $meta['_payment_method'][0];
        $return_details['pay_method_title'] = $meta['_payment_method_title'][0];
        $return_details['order_amount'] = $meta['_order_total'][0];
        #$return_details['project_details'] = $meta['project_details'];
        #$return_details['is_donation'] = $meta['is_donation'];
        unset($meta);
        return $return_details;
    }
    
    /**
     * Gets User Info By User ID
     * @param   USERID $id 
     * @returns Array User INFO
     * @since 1.0
     */
    private function get_user_info($id){
        $return_details = array();
        $user = get_user_by('id',$id);
        $userM = get_user_meta($id);
        $return_details['uname'] = $user->data->user_login;
        $return_details['email'] = $user->data->user_email;
        $return_details['dname'] = $user->data->display_name;
        $return_details['nickname'] = $userM['nickname'][0];
        $return_details['fname'] = $userM['first_name'][0];
        $return_details['lname'] = $userM['last_name'][0]; 
        unset($user,$userM);
        return $return_details;
    }
}

new wc_quick_donation_report;
?>
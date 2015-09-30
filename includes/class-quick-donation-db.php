<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_DB  {
    
    public function add_db_option($order_id,$project_id,$user_id){
        global $wpdb; 
        $data_c = array('date' => current_time( 'mysql' ),'userid' => $user_id,'donationid' => $order_id,'projectid' => $project_id); 
        $wpdb->insert(WC_QD_TB, $data_c);
    }
 
    
    public function get_donation_order_ids(){
        global $wpdb;
        $db_request = $wpdb->get_results("SELECT donationid FROM ".WC_QD_TB,ARRAY_N);
        if(!empty($db_request)){
            return $db_request;
        }
        return array();
    }
    
    public function extract_donation_id($ids){
        $return_ids = array();
        foreach($ids as $i){
            if(is_array($i)){
                foreach($i as $d){
                    $return_ids[] = $d;
                }
            } else {
                $return_ids[] = $i;
            }
        }
        return $return_ids;
    }
}
?>
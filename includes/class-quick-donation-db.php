<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_DB  {
    
    /**
     * Internal Post Meta Function
     */
    protected function post_meta($id,$meta_key,$single = false){
        return get_post_meta($id,$meta_key,$single);
    }
    
    
    public function get_message($id,$search_replace = array()){
        if($id == null){ return false; }
        $text = WC_QD()->settings()->get_option($id);
        $replaced_text = str_replace(array_keys($search_replace),array_values($search_replace),$text);
        return $replaced_text;
    }

    
    public function min_project($id){
        $value = intval($this->post_meta($id,'_'.WC_QD_DB.'min_req_donation',true));
        if($value > 0){ return $value;}
        return false;
    }
    
    public function max_project($id){
        $value = intval($this->post_meta($id,'_'.WC_QD_DB.'max_req_donation',true));
        if($value > 0){ return $value;}
        return false;
    }
    
    public function project_status($id){
        return $this->post_meta($id,'_'.WC_QD_DB . 'visibility_project',true);
    }    
    
    public function _is_donation($id){
        $status = intval($this->post_meta($id,'_is_donation',true));
        if($status == 1){return true;}
        return false;
    }
    
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
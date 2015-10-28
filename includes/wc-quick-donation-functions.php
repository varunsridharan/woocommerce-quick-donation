<?php

if(! function_exists('qd_get_message')){
    function get_message($id,$search_replace = array()){
        $message = WC_QD()->db()->get_message($id,$search_replace);
        return $message;
    }
        
}

if(! function_exists('qd_min_amount_project')){
    function qd_min_amount_project($id = ''){
        $min = WC_QD()->db()->min_project($id);
        return $min;
    }
}

   
if(! function_exists('qd_max_amount_project')){
    function qd_max_amount_project($id = ''){
        $max = WC_QD()->db()->max_project($id);
        return $max;
    }
}
   
/**
 * Get all order statuses
 *
 * @since 2.2
 * @return array
 */
function wc_qd_order_statuses() {
    $wc_status = wc_get_order_statuses();
    $order_statuses = array();
    $order_statuses['donation-completed'] = _x( 'Donation Completed', 'Donation Completed', WC_QD_TXT );
    $order_statuses['donation-refunded'] = _x( 'Donation Refunded', 'Order status', WC_QD_TXT);
    $order_statuses['donation-on-hold'] = _x( 'Donation On Hold', 'Order status', WC_QD_TXT);
    $order_statuses = array_merge($order_statuses,$wc_status);
	return $order_statuses;
}      
?>
<?php

if(! function_exists('qd_get_message')){
    function get_message($id,$search_replace = array()){
        $message = WC_QD()->db()->get_message($id,$search_replace);
        return $message;
    }
        
}

if(! function_exists('qd_min_amount_project'){
    function qd_min_amount_project($id = ''){
        $min = WC_QD()->db()->min_project($id);
        return $min;
    }
}

   
if(! function_exists('qd_max_amount_project'){
    function qd_max_amount_project($id = ''){
        $max = WC_QD()->db()->max_project($id);
        return $max;
    }
}
?>
<?php

if(! function_exists('wcqd_get_message')){
    function wcqd_get_message($id,$search_replace = array()){
        $message = WC_QD()->db()->get_message($id,$search_replace);
        return $message;
    }
} 

if(! function_exists('wcqd_project_limit')){
    function wcqd_project_limit($project_id = 0, $type = 'min'){
        if(empty($project_id)){return false; }
        if($type !== 'min' && $type !== 'max'){return false;}
        $function_toCal = $type.'_project';
        $limit = WC_QD()->db()->{$function_toCal}($project_id);
        return $limit;
}
}

if(! function_exists('wcqd_get_project')){
    function wcqd_get_project($order_id = ''){ 
        $is_donation = WC_QD()->db()->_is_donation($order_id);
        if($is_donation){
            $project = WC_QD()->db()->get_project_id($order_id);
            return $project;
        } 
        return false;
    }
}

if(! function_exists('wcqd_get_project_name')){
    function wcqd_get_project_name($order_id = '', $default_title = ''){  
        $project_id = wcqd_get_project($order_id); 
        $title = get_the_title($project_id);
        if(empty($title)){return $default_title;}
        return $title;
    }
}

   
?>
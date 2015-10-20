<?php
global $id, $name, $class, $field_output, $is_grouped, $project_list,$attributes;

foreach($project_list as $id => $val){
    
    if(is_array($val)){
        $field_output .= ' <fieldset>     <legend>'.$id.'</legend>';
        foreach($val as $k =>$v){
            $field_output .= $v.' : <input type="radio" name="'.$name.'" value="'.$k.'" >';
        }   
        $field_output .= '</fieldset>';
    } else {
        $field_output .= $val.' : <input type="radio" name="'.$name.'" value="'.$id.'" >';
    }
    
}

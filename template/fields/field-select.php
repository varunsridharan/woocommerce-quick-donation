<?php
global $id, $name, $class, $field_output, $is_grouped, $project_list,$attributes;

$field_output = '<select id="'.$id.'" name="'.$name.'" class="'.$class.'" '.$attributes.'>';

foreach($project_list as $id => $val){
    
    if(is_array($val)){
        $field_output .= ' <optgroup label="'.$id.'">';
        foreach($val as $k =>$v){
            $field_output .= '<option value="'.$k.'">'.$v.'</option>';
        }   
        $field_output .= ' </optgroup>';
    } else {
        $field_output .= '<option value="'.$id.'">'.$val.'</option>';
    }
    
}
$field_output .= '</select>';
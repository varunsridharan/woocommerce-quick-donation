<?php
/**
 * HTML Radio Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.1
 */

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
echo $field_output;
?>
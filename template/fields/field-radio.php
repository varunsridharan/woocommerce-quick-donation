<?php
/**
 * HTML Radio Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.2
 */

foreach($project_list as $id => $val){
    
    if(is_array($val)){
		
        $field_output .= ' <fieldset>     <legend>'.$id.'</legend>';
        foreach($val as $k =>$v){
			$attr = '';
			if($pre_selected == $k){$attr = 'checked';}
            $field_output .= $v.' : <input type="radio" name="'.$name.'" value="'.$k.'" '.$attr.'>';
        }   
        $field_output .= '</fieldset>';
    } else {
		$attr = '';
		if($pre_selected == $id){$attr = 'checked';}
        $field_output .= $val.' : <input type="radio" name="'.$name.'" value="'.$id.'" '.$attr.'>';
    }
    
}
echo $field_output;
?>
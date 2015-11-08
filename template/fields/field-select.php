<?php
/**
 * HTML Select Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.2
 */
?>

<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class.' '.$attributes;?>">
<?php

foreach($project_list as $id => $val){
    $attr = '';
    if(is_array($val)){
        $field_output .= ' <optgroup label="'.$id.'">';
        foreach($val as $k =>$v){
			$attr = '';
			if($pre_selected == $k){$attr = 'selected';}
            $field_output .= '<option value="'.$k.'" '.$attr.'>'.$v.'</option>';
        }   
        $field_output .= ' </optgroup>';
    } else {
		if($pre_selected == $k){$attr = 'selected';}
        $field_output .= '<option value="'.$id.'" '.$attr.'>'.$val.'</option>';
    }
    
}
echo $field_output;
?>
</select>
<?php
/**
 * HTML Select Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.1
 */
?>

<select id="<?php echo $id; ?>" name="<?php echo $name; ?>" class="<?php echo $class.' '.$attributes;?>">
<?php
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
echo $field_output;
?>
</select>
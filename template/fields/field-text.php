<?php
/**
 * HTML Text Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.1
 */ 
?>
<input 
       type="<?php echo $field_type; ?>" 
       id="<?php echo $id; ?>" 
       name="<?php echo $name; ?>" 
       class="<?php echo $class.' '.$attributes;  ?>"
       value="<?php echo $value; ?>" />
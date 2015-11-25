<?php
/**
 * HTML Hidden Field Template
 *
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates/fields
 * @version 0.1
 */


$field_output = '<input 
					id="'.$id.'" 
					type="hidden" 
					name="'.$name.'"
					class="'.$class.'" 
					value="'.$pre_selected.'" 
					'.$attributes.' />';
echo $field_output;
?>
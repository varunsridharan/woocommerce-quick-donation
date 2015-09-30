<?php
global $send_fields;
//$send_fields = $fields['settings_general']['general'];

if (empty($vfields['wc_qd_payment_gateway'])) {
    add_settings_error('wc_qd_payment_gateway','', __( 'Error: Please Select Atlest 1 Payment Gateway.', WC_QD_TXT ),'error');
}        
    

return $fields;

?>
    
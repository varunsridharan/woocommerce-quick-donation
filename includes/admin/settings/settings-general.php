<?php
global $options;
$options[ ] = array( 'name' => __( 'General',WC_QD_TXT), 'type' => 'heading' );


$options[ ] = array(
	'name'     => __( 'Redirect User To', WC_QD_TXT),
	'desc'     => __( 'After Donation Added To Cart',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'redirect_user',
    'tip'      => 'Select Payment gateway for users to pay for donation' ,
	'css'      => 'width:75%;',
	'type'     => 'select', 
    'select2'  => true,
    'options' => array('cart' => 'Cart Page' , 'checkout' => 'Checkout Page')
);

$options[ ] = array(
	'name'     => __( 'Donation Payment Gateway', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When Empty Donation Entered',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'payment_gateway',
    'tip'      => 'Select Payment gateway for users to pay for donation' ,
	'css'      => 'width:75%;',
	'type'     => 'select',
    'multiple' => true,
    'select2'  => true,
    'options' => WC_QD()->f()->get_admin_pay_gate()
);

?>
<?php
global $fields;

/** General Settings **/
$fields['settings_general']['general'][] = array(
    'id'      =>  WC_QD_DB.'redirect_user',
    'type'    => 'select',
    'label'   => __( 'Redirect User To', WC_QD_TXT),
    'desc'    => __( 'After Donation Added To Cart',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('cart' => 'Cart Page' , 'checkout' => 'Checkout Page'),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')
);

/** General Settings **/
$fields['settings_general']['general'][] = array(
    'id'      =>  WC_QD_DB.'already_exist_redirect_user',
    'type'    => 'select',
    'label'   => __( 'Donation Exist Redirect', WC_QD_TXT),
    'desc'    => __( 'Redirect User When Donation Already Exist In Cart',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('' => 'None', 'cart' => 'Cart Page' , 'checkout' => 'Checkout Page'),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')
);

$fields['settings_general']['general'][] = array(
	'label'    => __( 'Donation Payment Gateway', WC_QD_TXT),
	'desc'     => __( 'Select Payment gateway for users to pay for donation',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'payment_gateway',
    'type'     => 'select',
    'attr'     => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;','multiple'=> 'multiple'),
    'multiple' => true, 
    'options'  => WC_QD()->f()->get_admin_pay_gate()
); 

/** Message Settings **/


  
$fields['settings_message']['message'][] =  array(
	'desc'  => __( '<div class="decs"> <span> Add <code>{donation_amount}</code> To Get Ented Amount By User.  </span> 
               <span> Add <code>{min_amount}</code> To Get Minimum Required Amount From Selected Project </span> 
               <span> Add <code>{max_amount}</code> To Get Minimum Required Amount From Selected Project  </span> </div>',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'empty_donation_msg_1',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'content'
);


$fields['settings_message']['message'][] =  array(
	'label' => __( 'Donation Conflict', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When User Trying To Add Donation With Other Products',WC_QD_TXT),
	'id'    =>   WC_QD_DB.'donation_with_other_products',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] =  array(
	'label' => __( 'Empty Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Empty Donation Entered',WC_QD_TXT),
	'id'    =>   WC_QD_DB.'empty_donation_msg',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] = array(
	'label'     => __( 'Donation Already Exist', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When User Trying To Add Another Donation To Cart',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'donation_already_exist',
	'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'     => 'textarea',
	
);

$fields['settings_message']['message'][] =  array(
	'label' => __( 'Invalid Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Invalid Donation Entered',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'invalid_donation_msg',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);
 
$fields['settings_message']['message'][] =  array(
	'label' => __( 'Minimum Required Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Minimum Required Donation Not Entered',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'min_rda_msg',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] =  array(
	'label' =>__( 'Maximum Required Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Maximum Required Donation Not Entered ',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'max_rda_msg',
    'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);
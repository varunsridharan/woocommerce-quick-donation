<?php
global $fields;

/** General Settings **/
$fields['settings_general']['general'][] = array(
    'id'      =>  WC_QD_DB.'redirect_user',
    'type'    => 'select',
    'label'   => __( 'Redirect User To', WC_QD_TXT),
    'desc'    => __( 'After Donation Added To Cart',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('cart' => __('Cart Page',WC_QD_TXT) , 'checkout' => __('Checkout Page',WC_QD_TXT)),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')
);

/** General Settings **/
$fields['settings_general']['general'][] = array(
    'id'      =>  WC_QD_DB.'already_exist_redirect_user',
    'type'    => 'select',
    'label'   => __( 'Donation Exist Redirect', WC_QD_TXT),
    'desc'    => __( 'Redirect User When Donation Already Exist In Cart',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('cart' => __('Cart Page',WC_QD_TXT) , 'checkout' => __('Checkout Page',WC_QD_TXT)),
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
	'desc'  => sprintf(__( '<span> Add <code>%s</code> To Get Ented Amount By User.</span>  <br/>
               <span> Add <code>%s</code> To Get Minimum Required Amount From Selected Project </span>   <br/>
               <span> Add <code>%s</code> To Get Minimum Required Amount From Selected Project  </span>',WC_QD_TXT),'{donation_amount}','{min_amount}','{max_amount}'),
	'id'    =>  WC_QD_DB.'empty_donation_msg_1',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'content'
);


$fields['settings_message']['message'][] =  array(
	'label' => __( 'Donation Conflict', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When User Trying To Add Donation With Other Products',WC_QD_TXT),
	'id'    =>   WC_QD_DB.'donation_with_other_products',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] =  array(
	'label' => __( 'Empty Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Empty Donation Entered',WC_QD_TXT),
	'id'    =>   WC_QD_DB.'empty_donation_msg',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] = array(
	'label'     => __( 'Donation Already Exist', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When User Trying To Add Another Donation To Cart',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'donation_already_exist',
	'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'     => 'textarea',
	
);

$fields['settings_message']['message'][] =  array(
	'label' => __( 'Invalid Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Invalid Donation Entered',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'invalid_donation_msg',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);
 
$fields['settings_message']['message'][] =  array(
	'label' => __( 'Minimum Required Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Minimum Required Donation Not Entered',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'min_rda_msg',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);

$fields['settings_message']['message'][] =  array(
	'label' =>__( 'Maximum Required Donation Amount', WC_QD_TXT),
	'desc'  => __( 'Custom Message To Show When Maximum Required Donation Not Entered ',WC_QD_TXT),
	'id'    =>  WC_QD_DB.'max_rda_msg',
    'attr'  => array('style' => 'min-width:50%; width:auto;max-width:75%;'),
	'type'  => 'textarea'
);


/** Shortcode Settings **/
$fields['settings_shortcode']['shortcode'][] = array(
	'id'      =>  WC_QD_DB.'default_render_type',
    'type'    => 'select',
    'label'   => __( 'Pre Selected Project Name', WC_QD_TXT),
    'desc'    => __( 'default project render type',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('select' => __('Select Box',WC_QD_TXT), 'radio' => __('Radio Button',WC_QD_TXT)),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')		
);

$fields['settings_shortcode']['shortcode'][] = array(
	'id'      =>  WC_QD_DB.'shortcode_show_errors',
    'type'    => 'select',
    'label'   => __( 'Show Errors', WC_QD_TXT),
    'desc'    => __( 'Set to hide errors when <code> wc_print_notice</code> called before loading dontion form',WC_QD_TXT),
    'size '   => 'small',
    'options' => array('true' => __('Show Errors',WC_QD_TXT), 'false' => __('Hide Errors',WC_QD_TXT)),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')		
);


$fields['settings_shortcode']['shortcode'][] = array(
	'id'      =>  WC_QD_DB.'pre_selected_project',
    'type'    => 'select',
    'label'   => __( 'Pre Selected Project Name', WC_QD_TXT),
    'desc'    => __( 'this value will be selected in donation project box',WC_QD_TXT),
    'size '   => 'small',
    'options' => WC_QD()->f()->get_porject_list(),
    'attr'    => array('class' => 'wc-enhanced-select','style' => 'width:auto;max-width:35%;')		
);

$fields['settings_shortcode']['shortcode'][] = array(
	'id'      =>  WC_QD_DB.'pre_defined_amount',
    'type'    => 'textarea',
    'label'   => __( 'Pre Defined Project Amount', WC_QD_TXT),
    'desc'    => __( 'Enter Donation Amount Like <code> 10|20|30|40 </code>',WC_QD_TXT),
    'size '   => 'small', 
	'attr'  => array('style' => 'min-width:35%; width:auto;max-width:75%;')	
);
<?php
global $options;
$options[ ] = array( 'name' => __( 'Message',WC_QD_TXT), 'type' => 'heading' );



/**  Settings For Donation Error Message */
$options[ ] = array( 'name' => __( 'Donation Error : ',WC_QD_TXT), 'type' => 'title', 
                    'desc' => '
                    <span> Add <code>{donation_amount}</code> To Get Ented Amount By User.  </span>
                    <span> Add <code>{min_amount}</code> To Get Minimum Required Amount From Selected Project </span>
                    <span> Add <code>{max_amount}</code> To Get Minimum Required Amount From Selected Project  </span>
                    ' );



$options[ ] = array(
	'name'     => __( 'Empty Donation Amount', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When Empty Donation Entered',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'empty_donation_msg',
	'css'      => 'width:75%;',
	'type'     => 'textarea',
	
);

$options[ ] = array(
	'name'     => __( 'Invalid Donation Amount', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When Invalid Donation Entered',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'invalid_donation_msg',
	'css'      => 'width:75%;',
	'type'     => 'textarea',
	
);



$options[ ] = array(
	'name'     => __( 'Minimum Required Donation Amount', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When Minimum Required Donation Not Entered',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'min_rda_msg',
	'css'      => 'width:75%;',
	'type'     => 'textarea',
	
);
$options[ ] = array(
	'name'     => __( 'Maximum Required Donation Amount', WC_QD_TXT),
	'desc'     => __( 'Custom Message To Show When Maximum Required Donation Not Entered ',WC_QD_TXT),
	'id'       =>  WC_QD_DB.'max_rda_msg',
	'css'      => 'width:75%;',
	'type'     => 'textarea',
	
);




?>
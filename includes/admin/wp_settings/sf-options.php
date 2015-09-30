<?php
$options = array();

$options[ ] = array( 'name' => __( 'General', 'wcvendors' ), 'type' => 'heading' );

$options[ ] = array( 'name' => __( 'General options', 'wcvendors' ), 'type' => 'title', 'desc' => '' );

$options[ ] = array(
	'name'     => __( 'Default commission (%)', 'wcvendors' ),
	'desc'     => __( 'The default rate you pay each vendor for a product sale. If a product has a commission rate already set, this value will be ignored for that product.', 'wcvendors' ),
	'id'       => 'default_commission',
	'css'      => 'width:70px;',
	'type'     => 'number',
	'restrict' => array(
		'min' => 0,
		'max' => 100
	)
);

$options[ ] = array(
	'name' => __( 'Registration', 'wcvendors' ),
	'desc' => __( 'Allow users or guests to apply to become a vendor', 'wcvendors' ),
	'tip'  => __( 'This will show a checkbox on the My Account page\'s registration form asking if the user would like to apply to be a vendor. Also, on the Vendor Dashboard, users can still apply to become a vendor even if this is disabled.', 'wcvendors' ),
	'id'   => 'show_vendor_registration',
	'type' => 'checkbox',
	'std'  => true,
);

$options[ ] = array(
	'desc' => __( 'Approve vendor applications manually', 'wcvendors' ),
	'tip'  => __( 'With this unchecked, all vendor applications are automatically accepted. Otherwise, you must approve each manually.', 'wcvendors' ),
	'id'   => 'manual_vendor_registration',
	'type' => 'checkbox',
	'std'  => true,
);

$options[ ] = array(
	'name' => __( 'Taxes', 'wcvendors' ),
	'desc' => __( 'Give vendors any tax collected per-product', 'wcvendors' ),
	'tip'  => __( 'The tax collected on a vendor\'s product will be given in its entirety', 'wcvendors' ),
	'id'   => 'give_tax',
	'type' => 'checkbox',
	'std'  => false,
);

$options[ ] = array(
	'name' => __( 'Shipping', 'wcvendors' ),
	'desc' => __( 'Give vendors any shipping collected per-product', 'wcvendors' ),
	'tip'  => __( 'The shipping collected on a vendor\'s product will be given in its entirety', 'wcvendors' ),
	'id'   => 'give_shipping',
	'type' => 'checkbox',
	'std'  => true,
);

$options[ ] = array( 'name' => __( 'Shop options', 'wcvendors' ), 'type' => 'title', 'desc' => '' );

$options[ ] = array(
	'name' => __( 'Shop HTML', 'wcvendors' ),
	'desc' => __( 'Enable HTML for a vendor\'s shop description by default.  You can enable or disable this per vendor by editing the vendors username.', 'wcvendors' ),
	'id'   => 'shop_html_enabled',
	'type' => 'checkbox',
	'std'  => true,
);

$options[ ] = array(
	'name' => __( 'Vendor shop page', 'wcvendors' ),
	'desc' => __( 'Eg: <code>yoursite.com/[your_setting_here]/[vendor_name_here]</code>', 'wcvendors' ),
	'id'   => 'vendor_shop_permalink',
	'type' => 'text',
	'std'  => 'vendors/',
);

$options[ ] = array(
	'name' => __( 'Shop Headers', 'wcvendors' ),
	'desc' => __( 'Enable vendor shop headers', 'wcvendors' ),
	'tip'  => __( 'This will override the HTML Shop description output on product-archive pages.  In order to customize the shop headers visit wcvendors.com and read the article in the Knowledgebase titled Changing the Vendor Templates.', 'wcvendors' ),
	'id'   => 'shop_headers_enabled',
	'type' => 'checkbox',
	'std'  => false,
);

$options[ ] = array(
	'name'    => __( 'Vendor Display Name', 'wcvendors' ),
	'desc'    => __( 'Select what will be displayed for the sold by text throughout the store.', 'wcvendors' ),
	'id'      => 'vendor_display_name',
	'type'    => 'select',
	'options' => array(
		'display_name' 	=> __( 'Display Name', 'wcvendors'), 
		'shop_name'		=> __( 'Shop Name', 'wcvendors'), 
		'user_login' 	=> __( 'User Login', 'wcvendors'), 
		'user_email' 	=> __( 'User Email', 'wcvendors'), 
	), 
	'std'	=> 'shop_name'

);

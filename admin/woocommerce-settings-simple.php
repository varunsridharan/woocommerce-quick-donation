<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce_Simple_Settings' ) ) :

/**
 * WC_Admin_Settings_General
 */
class WooCommerce_Simple_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'wc_intergation_simple';
		$this->label = __( 'WC Simple Intergation', 'woocommerce' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = array(

			array( 
                'title' => __( 'Simple Options', 'woocommerce' ), 
                'type' => 'title', 
                'desc' => '', 
                'id' => 'wc_simple_intergation' 
            ),

			array(
				'title'    => __( 'Text Box', 'woocommerce' ),
				'desc'     => __( 'This is a simple textbox', 'woocommerce' ),
				'id'       =>'wc_simple_textbox',
				'css'      => 'min-width:350px;',
				'default'  => 'GB',
				'type'     => 'text',
				'desc_tip' =>  true,
			),

            array(
				'title'    => __( 'TextArea', 'woocommerce' ),
				'desc'     => '',
				'id'       => 'wc_simple_textarea',
				'default'  => __( 'SOme Value.', 'woocommerce' ),
				'type'     => 'textarea',
				'css'     => 'width:350px; height: 65px;',
				'autoload' => false
			),
            
			array(
				'title'    => __( 'Radio Buttons', 'woocommerce' ),
				'desc'     => __( 'A Simple Radio Button', 'woocommerce' ),
				'id'       => 'wc_simple_radio',
				'default'  => 'all',
				'type'     => 'radio',  
				'desc_tip' =>  true,
				'options'  => array(
					'all'      => __( 'Sell to all countries', 'woocommerce' ),
					'specific' => __( 'Sell to specific countries only', 'woocommerce' )
				)
			),

			  

			array(
				'title'   => __( 'Checbox', 'woocommerce' ),
				'desc'    => __( 'A Simple Checkbox', 'woocommerce' ),
				'id'      => 'wc_simple_checkboxx',
				'default' => 'no',
				'type'    => 'checkbox'
			),



			array( 'type' => 'sectionend', 'id' => 'wc_simple_intergation'),

			     

		);

		return $settings;
	}
 

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new WooCommerce_Simple_Settings();

<?php
class WC_Quick_Donation_Settings extends WC_Settings_Page {

    /**
     * Constructor
     */
    public function __construct() {
        $this->id    = 'wc_quick_donation';
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    /**
     * Add plugin options tab
     *
     * @return array
     */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs[$this->id] = 'WC Quick Donation';
        return $settings_tabs;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            'general' => __( 'General Settings', $this->id ),
            'donation' => __( 'Donation Settings', $this->id ),
            'message' => __( 'Message Settings', $this->id )
             
        );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }


    /**
     * Get sections
     *
     * @return array
     */
    public function get_settings( $section = null ) {
		global $wc_quick_donation;
        $width = "width:50% !important;";

        switch( $section ){

            case 'general' :
			case '' :
               $settings = array(
					'section_title' => array(
						'name' => 'Woocommerce Quick Donation Settings',
						'type' => 'title',
						'desc' => 'Just Call <code><strong>[wc_quick_donation]</strong></code> short code any where in your page,post,widgets or template <br/>
							To Customize the <strong>Donation Form</strong> copy the template file from <code>woocommerce-quick-donation/template/donation_form.php</code> to your <code>theme/woocommerce</code> folder.
						',
						'id' => 'wc_quick_donation_general_start'
					), 			

					'remove_cart_items' => array(
						'name' => 'Auto Remove Cart Items',
						'desc' => 'Removes Other Cart Items If Donation Aded To Cart.',
						'id' => 'wc_quick_donation_cart_remove',
						'type' => 'select', 
						'class' =>'chosen_select',
                        'css'     => $width,
						'options' => array('false' => 'Keep All Items','true'=>'Remove All Items')
					),	
				    			

					'auto_hide_donation_form' => array(
						'name' => 'Hide Donation Form When Donation Exist In Cart',
						'desc' => '',
						'id' => 'wc_quick_donation_hide_form',
						'type' => 'select', 
						'class' =>'chosen_select',
                        'css'     => $width,
						'options' => array('false' => 'Yes','true'=>'No')
					),	
                   
                   			

					'hide_order_notes' => array(
						'name' => 'Hide Order Notes When Donation Checkout',
						'desc' => '',
						'id' => 'wc_quick_donation_hide_order_notes',
						'type' => 'select', 
						'class' =>'chosen_select',
                        'css'     => $width,
						'options' => array('true' => 'Yes','false'=>'No')
					),	
					'redirect' => array(
						'name' => 'Redirect User To',
						'desc' => 'After  Donation Added To Cart.',
						'id' => 'wc_quick_donation_redirect',
						'type' => 'select', 
						'class' =>'chosen_select',
                        'css'     => $width,
						'options' => array('checkout' => 'Checkout Page','cart'=>'Cart Page','false' => 'None')
					),
                   
                   
					'payment_gateway' => array(
						'name' => 'Allowed Payment Gateway\'s',
						'desc' => 'Select Payment Gateway for donation..',
						'id' => 'wc_quick_donation_payment_gateway',
						'type' => 'multiselect', 
						'class' =>'chosen_select',
                        'css'     => $width,
						'options' => $wc_quick_donation->get_payments_gateway()
					), 				
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'wc_quick_donation_general_end'
					)
				);
            break;
            case 'donation' :
                $settings = array(
					'section_title' => array(
						'name' => 'Donation Releated Settings',
						'type' => 'title',
						'desc' => '',
						'id' => 'wc_quick_donation_donation_start'
					), 	
					'project_names' => array(
						'name' => 'Project Names',
						'type' => 'textarea',
						'desc_tip' => 'Add Names By <code>,</code> Seperated ',
                        'css'     => 'width:50%; height: 105px;',
                        'std'     => '', // for woocommerce < 2.0 
						'id' => 'wc_quick_donation_projects', 
						'default' => 'Project1,Project2'  // for woocommerce >= 2.0

					),
                    'min_required_donation' => array(
						'name' => 'Minium Required Amount',
						'type' => 'text',
						'desc' => 'Minium Required Amount For Donation. Enter Only Numerical Eg : 100', 
						'id' => 'wc_quick_donation_min_required_donation', 
						'default' =>'50'

					),
                    'max_required_donation' => array(
						'name' => 'Maximum Allowed Amount',
						'type' => 'text',
						'desc' => 'Maximum Allowed Amount For Donation. Enter Only Numerical Eg : 1000', 
						'id' => 'wc_quick_donation_max_required_donation', 
						'default' =>'1000'

					),
					'order_project_title' => array(
						'name' => 'Order Project Title',
						'type' => 'text',
						'desc_tip' => 'Title to view in order edit page',
                        'css'     => $width,
						'id' => 'wc_quick_donation_project_section_title', 
						'default' =>'For Project'

					),
					'order_notes_title' => array(
						'name' => 'Order Notes Title',
						'type' => 'text',
						'desc_tip' => 'to display project name use <code>Project Name : %s</code>',
						'id' => 'wc_quick_donation_order_notes_title',
                         'css'     => $width,
						'default' =>'Project Name %s'

					),					
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'wc_quick_donation_donation_end'
					)
				);            
                
            break;
            case 'message' : 
                $settings = array(
                        'section_title' => array(
                            'name' => 'Plugin Message Text',
                            'type' => 'title',
                            'desc' => '',
                            'id' => 'wc_quick_donation_message_start'
                        ), 
					 'donation_exist' => array(
                            'name' => 'Donation Exist Error Message',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When Donation Already Exist In Cart',
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_donation_exist', 
                            'default' =>'<h2> Donation Already Exist </h2>'
                        ),
                        'project_invalid_message' => array(
                            'name' => 'Invalid / No Project Selected',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When No Project Is Selected | HTML Tags Allowed',
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_project_invalid', 
                            'default' =>'<h2> No Project Selected </h2>'
                        ), 
                        'donation_amount_empty_message' => array(
                            'name' => 'Empty Donation Amount',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When No Donation Entered | HTML Tags Allowed', 
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_amount_empty', 
                            'default' =>'<h2> Please Enter A Donation Amount </h2>'
                        ), 
                        'donation_amount_invalid_message' => array(
                            'name' => 'Invalid Donation Amount',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When Invalid Donation Entered | HTML Tags Allowed',
                            'desc' => 'Add <code>{donation_amount}</code> For Entered Donation Amount',
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_amount_invalid', 
                            'default' =>'<h2> Invalid Donation Amount [{donation_amount}]  </h2>'
                        ),  
                        'donation_min_required' => array(
                            'name' => 'Minium Required Donation Amount',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When Donation Amount Is Less than required amount | HTML Tags Allowed',
                            'desc' => 'Add <code>{donation_amount}</code> For Entered Donation Amount And Add <code>{min_amount}</code> For Minium Required Amount ',
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_amount_min_required', 
                            'default' =>'<h2> Minium Required is {min_amount} And you have entered is {donation_amount}  </h2>'
                        ), 
                    
                        'donation_max_allowed' => array(
                            'name' => 'Maximum Allowed Donation Amount',
                            'type' => 'textarea',
                            'desc_tip' => 'Message Displayed When Donation Amount Is Greater than allowed amount | HTML Tags Allowed',
                            'desc' => 'Add <code>{donation_amount}</code> For Entered Donation Amount And Add <code>{max_amount}</code> For Maximum Allowed Amount ',
                            'css' => 'width:75%; height:75px;',
                            'id' => 'wc_quick_donation_msg_amount_max_allowed', 
                            'default' =>'<h2> Maximum Allowed Is {max_amount} And you have entered is {donation_amount}  </h2>'
                        ), 
                    
                    
                        'section_end' => array(
                            'type' => 'sectionend',
                            'id' => 'wc_quick_donation_message_end'
                        )
                    );            

            break;
        }

        return apply_filters( 'wc_settings_tab_'.$this->id.'_settings', $settings, $section );

    }

    /**
     * Output the settings
     */
    public function output() {
        global $current_section;
        $settings = $this->get_settings( $current_section );  
        WC_Admin_Settings::output_fields( $settings );
    }


    /**
     * Save settings
     */
    public function save() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings );
    }

}

return new WC_Quick_Donation_Settings();

?>
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
            'general'         => __( 'General Settings', $this->id )
             
        );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }


    /**
     * Get sections
     *
     * @return array
     */
    public function get_settings( $section = null ) {
		global $wc_quick_buy;
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
						'id' => 'wc_quick_donation_section_title'
					), 			

					'remove_cart_items' => array(
						'name' => 'Auto Remove Cart Items',
						'desc' => 'Removes Other Cart Items If Donation Aded To Cart.',
						'id' => 'wc_quick_donation_cart_remove',
						'type' => 'select', 
						'class' =>'chosen_select',
						'options' => array('false' => 'Keep All Items','true'=>'Remove All Items')
					),	
                   
                   
					'redirect' => array(
						'name' => 'Redirect User To',
						'desc' => 'After  Donation Added To Cart.',
						'id' => 'wc_quick_donation_redirect',
						'type' => 'select', 
						'class' =>'chosen_select',
						'options' => array('checkout' => 'Checkout Page','cart'=>'Cart Page','false' => 'None')
					),
                   
                   
					'payment_gateway' => array(
						'name' => 'Allowed Payment Gateway\'s',
						'desc' => 'Select Payment Gateway for donation..',
						'id' => 'wc_quick_donation_payment_gateway',
						'type' => 'multiselect', 
						'class' =>'chosen_select',
						'options' => $wc_quick_buy->get_payments_gateway()
					),
					'project_names' => array(
						'name' => 'Project Names',
						'type' => 'textarea',
						'desc' => 'Add Names By <code>,</code> Seperated ',
						'id' => 'wc_quick_donation_projects', 
						'default' => 'Project1,Project2'

					),
					'order_project_title' => array(
						'name' => 'Order Project Title',
						'type' => 'text',
						'desc' => 'Title to view in order edit page',
						'id' => 'wc_quick_donation_project_section_title', 
						'default' =>'For Project'

					),
					'order_notes_title' => array(
						'name' => 'Order Notes Title',
						'type' => 'text',
						'desc' => 'to display project name use <code>Project Name : %s</code>',
						'id' => 'wc_quick_donation_order_notes_title', 
						'default' =>'Project Name %s'

					),			 		
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'wc_settings_tab_demo_section_end'
					)
				);
            break;
            case 'email_template':
				  
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
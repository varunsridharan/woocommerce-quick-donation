<?php

// Example plugin class.
class WooCommerce_Quick_Donation_Admin_Settings {

	private $page_hook;
	private $settings;
    private $settings_pages;
    private $settings_section;
    private $settings_fields;

	function __construct($page_hook) {
        $this->page_hook = $page_hook;
        $this->settings_pages = array();
        $this->settings_section = array();
        $this->settings_fields = array();
        $this->settings = new WooCommerce_Quick_Donation_Settings_Page();
    }

    
    /**
     * Gets Settings Tab For Settings Page
     * @return [[Type]] [[Description]]
     */
    public function get_settings_page(){
        $this->settings_pages[] = array('name' => __( 'Tab 2',WC_QD_TXT), 'type' => 'heading');
        $this->settings_pages[] = array('name' => __( 'Tab 2',WC_QD_TXT), 'type' => 'heading');
        $this->settings_pages = apply_filters('wc_quick_donation_settings_tab',$this->settings_pages);
        return $this->settings_pages;
    }

    
    private function get_settings_section(){
        $this->settings_section['example_page'] = array(
                                                    array('id'=> 'first_section',
                                                          'title'=> __('First Section',WC_QD_TXT),
                                                          'validate_callback' => array($this,'validate_section')
                                                     ),
                                                    array('id'=> 'second_section', 
                                                          'title' => __( 'Second Section.', WC_QD_TXT )
                                                     ),
			                                     );
        
        $this->settings_section['second_page'] = array(
                                                        array('id'=> 'second_page_first_section', 
                                                          'title' => __( 'Welcome to the second page', WC_QD_TXT)
                                                         )
                                                );
        
        $this->settings_section = apply_filters('wc_quick_donation_settings_section',$this->settings_section);
        return $this->settings_section;
            
    }
    
    
    
    private function get_settings_fields(){
        $this->settings_fields['example_page']['first_section'] = array(array(
                                                                'id'      => 'section_one_text', // required
                                                                'type'    => 'text', // required
                                                                'label'   => __( 'Text field label (required)', WC_QD_TXT),
                                                                'default' => 'default text',
                                                                'desc'    => __( 'This is a required field.', WC_QD_TXT),
                                                                'attr'    => array( 'class' => 'my_class' )
                                                            ));
        
        $this->settings_fields = apply_filters('wc_quick_donation_settings_fields',$this->settings_fields);
        return $this->settings_fields;
    }
    
	function admin_init() {
        $pages = $this->settings->add_pages($this->get_settings_page());
        $sections = $this->get_settings_section();
        $fields  = $this->get_settings_fields();
        foreach($sections as $section_id => $section){ 
            $pages = $this->settings->add_sections($section_id,$section);
        }
        
        foreach($fields as $page_id => $fields){
            foreach($fields as $section_id => $field){
                $pages = $this->settings->add_fields($page_id, $section_id, $field );
            } 
        }
		$this->settings->init( $pages, $this->page_hook );
	}


	function admin_page() {
		echo '<div class="wrap">';
		settings_errors();
		$this->settings->render_header( __( 'WP Settings Example', 'plugin-text-domain' ) );
		//echo $this->settings->debug;
		// Use the function get_settings() to get all the settings.
		$settings = $this->settings->get_settings(); 
		// Use the function get get_current_admin_page() to check what page you're on
		// $page         = $this->settings->get_current_admin_page();
		// $current_page = $page['id'];
		// Display the form(s).
		$this->settings->render_form();
		echo '</div>';
	}


	function validate_section( $fields ) {

		// Validation of the section_one_text text input.
		// Show an error if it's empty.

		// to check the section that's being validated you can check the 'section_id'
		// that was added with a hidden field in the admin page form.
		//
		// example
		// if( 'first_section' === $fields['section_id'] ) { // do stuff }

		if ( empty( $fields['section_one_text'] ) ) {

			// Use add_settings_error() to show an error messages.
			add_settings_error(
				'section_one_text', // Form field id.
				'texterror', // Error id.
				__( 'Error: please enter some text.', 'plugin-text-domain' ), // Error message.
				'error' // Type of message. Use 'error' or 'updated'.
			);
		}

		// Don't forget to return the fields
		return $fields;
	}

} // end of class



?>
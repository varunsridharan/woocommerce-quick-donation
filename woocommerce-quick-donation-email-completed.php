<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class wc_quick_donation_completed_donation_email extends WC_Email {


	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {
        $this->project_name = '';
		$this->id = 'wc_quick_donation_completed_donation_email';
		$this->title = 'Donation completed';
		$this->description = 'Email Sent When New Donation Is Placed';

		$this->subject = 'Thank you for your donation {project_name}';
		$this->heading = 'Thank you for your donation  {project_name}';

		$this->template_base = wc_qd_p.'template/';
		$this->template_html  = 'donation_completed_html.php';
		$this->template_plain = 'donation_completed_plain.php';  
		
		// Trigger on new paid orders
		add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ) );

		parent::__construct(); 
	}


	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function trigger( $order_id ) {
 
		if ( ! $order_id )
			return;
		
        $this->project_name = get_post_meta($order_id, 'project_details', true );
        
		$order = new WC_Order( $order_id );
		$this->object = $order;
        $this->recipient	= $this->object->billing_email; 
		$products_in_order = array();

		foreach ( $order->get_items() as $item ) { 
			 foreach($item['item_meta']['_product_id'] as $id){ $products_in_order[] = $id; }
		 } 
		
		if(in_array(get_option('wc_quick_donation_product_id'),$products_in_order)){
			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{project_name}';
			$this->replace[] = get_post_meta($order_id, 'project_details', true );
			
			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();

			if ( ! $this->is_enabled())
				return;

			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );			
		}
	}

	/**
	 * Initialize Settings Form Fields
	 *
	 * @since 0.1
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled'    => array(
				'title'   => 'Enable/Disable',
				'type'    => 'checkbox',
				'label'   => 'Enable this email notification',
				'default' => 'yes'
			),
 
			'subject'    => array(
				'title'       => 'Subject',
				'type'        => 'text',
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
				'placeholder' => '',
				'default'     => ''
			),
			'heading'    => array(
				'title'       => 'Email Heading',
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
				'placeholder' => '',
				'default'     => ''
			),
			'email_type' => array(
				'title'       => 'Email type',
				'type'        => 'select',
				'description' => 'Choose which format of email to send.',
				'default'     => 'html',
				'class'       => 'email_type',
				'options'     => array(
					'plain'     => 'Plain text',
					'html'      => 'HTML',
					'multipart' => 'Multipart'
				)
			)
		);
	}
	
	/**
	 * get_content_html function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_html() {
		ob_start();
		woocommerce_get_template($this->template_html,array('project_name'=>$this->project_name,'order' => $this->object, 'email_heading' => $this->get_heading()),'',$this->template_base); 
		return ob_get_clean();
	}


	/**
	 * get_content_plain function.
	 *
	 * @since 0.1
	 * @return string
	 */
	public function get_content_plain() {
		ob_start();
		woocommerce_get_template($this->template_plain,array('project_name'=>$this->project_name,'order' => $this->object, 'email_heading' => $this->get_heading()),'',$this->template_base); 
		return ob_get_clean();
	}
 


}

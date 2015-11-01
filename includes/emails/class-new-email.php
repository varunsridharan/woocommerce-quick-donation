<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_QD_Donation_New_Email' ) ) :

/**
 * Customer Invoice
 *
 * An email sent to the customer via admin.
 *
 * @class       WC_QD_Donation_New_Email
 * @version     2.3.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_QD_Donation_New_Email extends WC_Email {

	public $find;
	public $replace;

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id             = WC_QD_DB.'donation_new_email';
		$this->title          = __( 'New Donation', 'woocommerce' );
		$this->description    = __( 'Customer invoice emails can be sent to customers containing their order information and payment links.', 'woocommerce' );

        $this->template_base = WC_QD_TEMPLATE;
		$this->template_html  = 'emails/donation-customer-invoice.php';
		$this->template_plain = 'emails/plain/donation-customer-invoice.php';

		$this->subject        = __( 'Reg : Your Recent Donation @ {site_title}', WC_QD_TXT);
		$this->heading        = __( 'Thank you. Your Donation has been received for {project_name}', 'woocommerce');

		$this->subject_paid   = __( 'Your {site_title} order from {order_date}', 'woocommerce');
		$this->heading_paid   = __( 'Order {order_number} details', 'woocommerce');

		// Call parent constructor
		parent::__construct();

		$this->heading_paid   = $this->get_option( 'heading_paid', $this->heading_paid );
		$this->subject_paid   = $this->get_option( 'subject_paid', $this->subject_paid );
        
        add_filter('woocommerce_template_directory',array($this,'change_dir'),2,1);
        add_action( 'woocommerce_donation_email_header', array( $this, 'email_header' ) );
        add_action( 'woocommerce_donation_email_footer', array( $this, 'email_footer' ) );
	}
    
    public function change_dir($dir){
        $dir = $dir.'/donation';
        return $dir;
    }
    
    

	/**
	 * Get the email header.
	 *
	 * @param mixed $email_heading heading for the email
	 */
	public function email_header( $email_heading ) {
		wc_get_template( 'emails/donation-email-header.php', array( 'order' => $this->object, 'email_heading' => $email_heading ) );
	}

	/**
	 * Get the email footer.
	 */
	public function email_footer() {
		wc_get_template( 'emails/donation-email-footer.php',array( 'order' => $this->object) );
	}    
	/**
	 * Trigger.
	 */
	function trigger( $order ) {
        
		if ( ! is_object( $order ) ) {
			$order = wc_get_order( absint( $order ) );
		}

		if ( $order ) {
			$this->object                  = $order;
			$this->recipient               = $this->object->billing_email;

			$this->find['order-date']      = '{order_date}';
			$this->find['order-number']    = '{order_number}';
            $this->find['donation-project-name'] = '{project_name}';
            
            $order_id = $this->object->get_order_number();
            $project_id = WC_QD()->db()->get_project_id($order_id);
            $project_name = get_the_title($project_id);
            
			$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );
			$this->replace['order-number'] = $this->object->get_order_number();
            $this->replace['donation-project-name'] = $project_name;
        
		}

		if ( ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		if ( $this->object->has_status( array( 'processing', 'completed' ) ) ) {
			return $this->format_string( $this->subject_paid );
		} else {
			return $this->format_string( $this->subject );
		}
	}
    
	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		if ( $this->object->has_status( array( 'completed', 'processing' ) ) ) {
			return $this->format_string( $this->heading_paid );
		} else {
			return $this->format_string( $this->heading );
		}
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false
		) ); 
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true
		) );
        
		return ob_get_clean();
        
	}

	/**
	 * Initialise settings form fields
	 */
	function init_form_fields() {
		$this->form_fields = array(
			'subject' => array(
				'title'         => __( 'Email Subject', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject ),
				'placeholder'   => '',
				'default'       => ''
			),
			'heading' => array(
				'title'         => __( 'Email Heading', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->heading ),
				'placeholder'   => '',
				'default'       => ''
			),
			'subject_paid' => array(
				'title'         => __( 'Email Subject (paid)', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject_paid ),
				'placeholder'   => '',
				'default'       => ''
			),
			'heading_paid' => array(
				'title'         => __( 'Email Heading (paid)', 'woocommerce' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->heading_paid ),
				'placeholder'   => '',
				'default'       => ''
			),
			'email_type' => array(
				'title'         => __( 'Email Type', 'woocommerce' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options()
			)
		);
	}
    
    
}

endif;

return new WC_QD_Donation_New_Email();

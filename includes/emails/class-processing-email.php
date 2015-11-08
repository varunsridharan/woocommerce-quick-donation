<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_QD_Processing_donation' ) ) :

/**
 * Customer Processing Order Email
 *
 * An email sent to the customer when a new order is received/paid for.
 *
 * @class       WC_QD_Processing_donation
 * @version     2.0.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_QD_Processing_donation extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id               = WC_QD_DB.'donation_processing_email';
		$this->title            = __( 'Processing Donation',WC_QD_TXT);
		$this->description      = __( 'This is an order notification sent to customers containing their donation details after payment.', WC_QD_TXT);

		$this->heading          = __( 'Reg : Your {site_title} donation receipt from {order_date}', WC_QD_TXT);
		$this->subject          = __( 'Thanks for donation', WC_QD_TXT );

		$this->template_html    = 'emails/donation-processing.php';
		$this->template_plain   = 'emails/plain/donation-processing.php';
        $this->template_base = WC_QD_TEMPLATE;

        // Call parent constructor
		parent::__construct();
	}

    
	/**
	 * Trigger.
	 */
	function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object       = wc_get_order( $order_id );
			$this->recipient    = $this->object->billing_email;

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

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
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
    
}

endif;

return new WC_QD_Processing_donation();

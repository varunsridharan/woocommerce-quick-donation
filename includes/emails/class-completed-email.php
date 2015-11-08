<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_QD_donation_completed_email' ) ) :

/**
 * Customer Completed Order Email
 *
 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
 *
 * @class       WC_QD_donation_completed_email
 * @version     2.0.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_QD_donation_completed_email extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id             =  WC_QD_DB.'donation_completed_email';
		$this->title          = __( 'Completed Donation', WC_QD_TXT);
		$this->description    = __( 'Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', WC_QD_TXT );

		$this->heading        = __( 'Your Donation for {project_name} is complete', WC_QD_TXT);
		$this->subject        = __( 'Your {site_title} donation from {order_date} is complete', WC_QD_TXT );

		$this->template_html  = 'emails/donation-completed.php';
		$this->template_plain = 'emails/plain/donation-completed.php';
        $this->template_base = WC_QD_TEMPLATE;
        
		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * Trigger.
	 */
	function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object                  = wc_get_order( $order_id );
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

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
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
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() ) {
			return $this->format_string( $this->subject_downloadable );
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
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() ) {
			return  $this->format_string( $this->heading_downloadable );
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
	 * Get content plain.
	 *
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
	 * Initialise settings form fields.
	 */
	function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'         => __( 'Enable/Disable', WC_QD_TXT ),
				'type'          => 'checkbox',
				'label'         => __( 'Enable this email notification', WC_QD_TXT ),
				'default'       => 'yes'
			),
			'subject' => array(
				'title'         => __( 'Subject', WC_QD_TXT ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', WC_QD_TXT ), $this->subject ),
				'placeholder'   => '',
				'default'       => ''
			),
			'heading' => array(
				'title'         => __( 'Email Heading', WC_QD_TXT ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', WC_QD_TXT ), $this->heading ),
				'placeholder'   => '',
				'default'       => ''
			),

            'email_type' => array(
				'title'         => __( 'Email type', WC_QD_TXT ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', WC_QD_TXT ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options()
			)
		);
	}
}

endif;

return new WC_QD_donation_completed_email();

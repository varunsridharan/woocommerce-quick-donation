<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_QD_Donation_New_Email' ) ) :

/**
 * New Order Email
 *
 * An email sent to the admin when a new order is received/paid for.
 *
 * @class       WC_QD_Donation_New_Email
 * @version     2.0.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_QD_Donation_New_Email extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id               = WC_QD_DB.'donation_new_email';
		$this->title            = __( 'New Donation ', WC_QD_TXT );
		$this->description      = __( 'New order emails are sent to the recipient list when an order is received. you can use <code> {project_name} </code> to get project name any where in the below fields', WC_QD_TXT );

		$this->heading          = __( 'New customer donation for {project_name}', WC_QD_TXT );
		$this->subject          = __( '[{site_title}] New customer donation ({order_number}) - {order_date}', WC_QD_TXT );

		$this->template_base = WC_QD_TEMPLATE;
		$this->template_html    = 'emails/donation-admin-new.php';
		$this->template_plain   = 'emails/plain/admin-new-order.php';

		add_filter('woocommerce_template_directory',array($this,'change_dir'),2,1);

		// Call parent constructor
		parent::__construct();

		// Other settings
		$this->recipient = $this->get_option( 'recipient' );

		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
	}

	public function change_dir($dir){
        $dir = $dir.'/donation';
        return $dir;
    }
	/**
	 * Trigger.
	 */
	function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object       = wc_get_order( $order_id );

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
			'sent_to_admin' => true,
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
			'sent_to_admin' => true,
			'plain_text'    => true
		) );
		return ob_get_clean();
	}

	/**
	 * Initialise settings form fields
	 */
	function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'         => __( 'Enable/Disable', WC_QD_TXT ),
				'type'          => 'checkbox',
				'label'         => __( 'Enable this email notification', WC_QD_TXT ),
				'default'       => 'yes'
			),
			'recipient' => array(
				'title'         => __( 'Recipient(s)', WC_QD_TXT ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', WC_QD_TXT ), esc_attr( get_option('admin_email') ) ),
				'placeholder'   => '',
				'default'       => ''
			),
			'subject' => array(
				'title'         => __( 'Subject', WC_QD_TXT ),
				'type'          => 'text',
				'description'   => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', WC_QD_TXT ), $this->subject ),
				'placeholder'   => '',
				'default'       => ''
			),
			'heading' => array(
				'title'         => __( 'Email Heading', WC_QD_TXT ),
				'type'          => 'text',
				'description'   => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', WC_QD_TXT ), $this->heading ),
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

return new WC_QD_Donation_New_Email();

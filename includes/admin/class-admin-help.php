<?php
/**
 * Add some content to the help tab.
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce_Quick_Donation_Admin_Help' ) ) :

/**
 * WooCommerce_Quick_Donation_Admin_Help Class
 */
class WooCommerce_Quick_Donation_Admin_Help {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( "current_screen", array( $this, 'add_tabs' ), 50 );
	}

	/**
	 * Add Contextual help tabs
	 */
	public function add_tabs() {
		$screen = get_current_screen();

		if ( ! in_array( $screen->id, wc_get_screen_ids() ) ) {
			return;
		}

		$video_map = array(
			'wc-settings' => array(
				'title' => __( 'General Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/mz2l10u5f6?videoFoam=true'
			),
			'wc-settings-general' => array(
				'title' => __( 'General Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/mz2l10u5f6?videoFoam=true'
			),
			'wc-settings-products' => array(
				'title' => __( 'Product Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/lolkan4fxf?videoFoam=true'
			),
			'wc-settings-tax' => array(
				'title' => __( 'Tax Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/qp1v19dwrh?videoFoam=true'
			),
			'wc-settings-checkout' => array(
				'title' => __( 'Checkout Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/65yjv96z51?videoFoam=true'
			),
			'wc-settings-shipping' => array(
				'title' => __( 'Shipping Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/9c9008dxnr?videoFoam=true'
			),
			'wc-settings-account' => array(
				'title' => __( 'Account Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/35mazq7il2?videoFoam=true'
			),
			'wc-settings-email' => array(
				'title' => __( 'Email Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/svcaftq4xv?videoFoam=true'
			),
			'wc-settings-api' => array(
				'title' => __( 'Webhook Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/1q0ny74vvq?videoFoam=true'
			),
			'wc-settings-checkout-wc_gateway_paypal' => array(
				'title' => __( 'PayPal Standard', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/rbl7e7l4k2?videoFoam=true'
			),
			'wc-settings-checkout-wc_gateway_simplify_commerce' => array(
				'title' => __( 'Simplify Commerce', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/jdfzjiiw61?videoFoam=true'
			),
			'wc-settings-shipping' => array(
				'title' => __( 'Shipping Settings', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/9c9008dxnr?videoFoam=true'
			),
			'wc-settings-shipping-wc_shipping_free_shipping' => array(
				'title' => __( 'Free Shipping', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/po191fmvy9?videoFoam=true'
			),
			'wc-settings-shipping-wc_shipping_local_delivery' => array(
				'title' => __( 'Local Delivery', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/5qjepx9ozj?videoFoam=true'
			),
			'wc-settings-shipping-wc_shipping_local_pickup' => array(
				'title' => __( 'Local Pickup', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/pe95ph0apb?videoFoam=true'
			),
			'edit-product_cat' => array(
				'title' => __( 'Product Categories, Tags, Shipping Classes, &amp; Attributes', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/f0j5gzqigg?videoFoam=true'
			),
			'edit-product_tag' => array(
				'title' => __( 'Product Categories, Tags, Shipping Classes, &amp; Attributes', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/f0j5gzqigg?videoFoam=true'
			),
			'edit-product_shipping_class' => array(
				'title' => __( 'Product Categories, Tags, Shipping Classes, &amp; Attributes', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/f0j5gzqigg?videoFoam=true'
			),
			'product_attributes' => array(
				'title' => __( 'Product Categories, Tags, Shipping Classes, &amp; Attributes', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/f0j5gzqigg?videoFoam=true'
			),
			'product' => array(
				'title' => __( 'Simple Products', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/ziyjmd4kut?videoFoam=true'
			),
			'wc-status' => array(
				'title' => __( 'System Status', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/xdn733nnhi?videoFoam=true'
			),
			'wc-reports' => array(
				'title' => __( 'Reports', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/6aasex0w99?videoFoam=true'
			),
			'edit-shop_coupon' => array(
				'title' => __( 'Coupons', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/gupd4h8sit?videoFoam=true'
			),
			'shop_coupon' => array(
				'title' => __( 'Coupons', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/gupd4h8sit?videoFoam=true'
			),
			'edit-shop_order' => array(
				'title' => __( 'Managing Orders', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/n8n0sa8hee?videoFoam=true'
			),
			'shop_order' => array(
				'title' => __( 'Managing Orders', WC_QD_TXT ),
				'url'   => '//fast.wistia.net/embed/iframe/n8n0sa8hee?videoFoam=true'
			)
		);

		$page      = empty( $_GET['page'] ) ? '' : sanitize_title( $_GET['page'] );
		$tab       = empty( $_GET['tab'] ) ? '' : sanitize_title( $_GET['tab'] );
		$section   = empty( $_REQUEST['section'] ) ? '' : sanitize_title( $_REQUEST['section'] );
		$video_key = $page ? implode( '-', array_filter( array( $page, $tab, $section ) ) ) : $screen->id;

		// Fallback for sections
		if ( ! isset( $video_map[ $video_key ] ) ) {
			$video_key = $page ? implode( '-', array_filter( array( $page, $tab ) ) ) : $screen->id;
		}

		// Fallback for tabs
		if ( ! isset( $video_map[ $video_key ] ) ) {
			$video_key = $page ? $page : $screen->id;
		}

		if ( isset( $video_map[ $video_key ] ) ) {
			$screen->add_help_tab( array(
				'id'        => 'woocommerce_101_tab',
				'title'     => __( 'WooCommerce 101', WC_QD_TXT ),
				'content'   =>
					'<h2><a href="http://docs.woothemes.com/document/woocommerce-101-video-series/?utm_source=WooCommerce&utm_medium=Wizard&utm_content=Videos&utm_campaign=Onboarding">' . __( 'WooCommerce 101', WC_QD_TXT ) . '</a> &ndash; ' . esc_html( $video_map[ $video_key ]['title'] ) . '</h2>' .
					'<iframe src="' . esc_url( $video_map[ $video_key ]['url'] ) . '" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="480" height="298"></iframe>'
			) );
		}

		$screen->add_help_tab( array(
			'id'        => 'woocommerce_docs_tab',
			'title'     => __( 'Documentation', WC_QD_TXT ),
			'content'   =>
				'<h2>' . __( 'Documentation', WC_QD_TXT ) . '</h2>' .
				'<p>' . __( 'Should you need help understanding, using, or extending WooCommerce, please read our documentation. You will find all kinds of resources including snippets, tutorials and much more.' , WC_QD_TXT ) . '</p>' .
				'<p><a href="' . 'http://docs.woothemes.com/documentation/plugins/woocommerce/' . '" class="button button-primary">' . __( 'WooCommerce Documentation', WC_QD_TXT ) . '</a> <a href="' . 'http://docs.woothemes.com/wc-apidocs/' . '" class="button">' . __( 'Developer API Docs', WC_QD_TXT ) . '</a></p>'

		) );

		$screen->add_help_tab( array(
			'id'        => 'woocommerce_support_tab',
			'title'     => __( 'Support', WC_QD_TXT ),
			'content'   =>
				'<h2>' . __( 'Support', WC_QD_TXT ) . '</h2>' .
				'<p>' . sprintf( __( 'After %sreading the documentation%s, for further assistance you can use the %scommunity forums%s on WordPress.org to talk with other users. If however you are a WooThemes customer, or need help with premium add-ons sold by WooThemes, please %suse our helpdesk%s.', WC_QD_TXT ), '<a href="http://docs.woothemes.com/documentation/plugins/woocommerce/">', '</a>', '<a href="https://wordpress.org/support/plugin/woocommerce">', '</a>', '<a href="http://support.woothemes.com">', '</a>' ) . '</p>' .
				'<p>' . __( 'Before asking for help we recommend checking the system status page to identify any problems with your configuration.', WC_QD_TXT ) . '</p>' .
				'<p><a href="' . admin_url( 'admin.php?page=wc-status' ) . '" class="button button-primary">' . __( 'System Status', WC_QD_TXT ) . '</a> <a href="' . 'https://wordpress.org/support/plugin/woocommerce' . '" class="button">' . __( 'WordPress.org Forums', WC_QD_TXT ) . '</a> <a href="' . 'http://support.woothemes.com' . '" class="button">' . __( 'WooThemes Customer Support', WC_QD_TXT ) . '</a></p>'
		) );

		$screen->add_help_tab( array(
			'id'        => 'woocommerce_education_tab',
			'title'     => __( 'Education', WC_QD_TXT ),
			'content'   =>
				'<h2>' . __( 'Education', WC_QD_TXT ) . '</h2>' .
				'<p>' . __( 'If you would like to learn about using WooCommerce from an expert, consider following a WooCommerce course ran by one of our educational partners.', WC_QD_TXT ) . '</p>' .
				'<p><a href="' . 'http://www.woothemes.com/educational-partners/?utm_source=WooCommerce&utm_medium=Wizard&utm_content=Partners&utm_campaign=Onboarding' . '" class="button button-primary">' . __( 'View Education Partners', WC_QD_TXT ) . '</a></p>'
		) );

		$screen->add_help_tab( array(
			'id'        => 'woocommerce_bugs_tab',
			'title'     => __( 'Found a bug?', WC_QD_TXT ),
			'content'   =>
				'<h2>' . __( 'Found a bug?', WC_QD_TXT ) . '</h2>' .
				'<p>' . sprintf( __( 'If you find a bug within WooCommerce core you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. To help us solve your issue, please be as descriptive as possible and include your <a href="%s">system status report</a>.', WC_QD_TXT ), 'https://github.com/woothemes/woocommerce/issues?state=open', 'https://github.com/woothemes/woocommerce/blob/master/CONTRIBUTING.md', admin_url( 'admin.php?page=wc-status' ) ) . '</p>' .
				'<p><a href="' . 'https://github.com/woothemes/woocommerce/issues?state=open' . '" class="button button-primary">' . __( 'Report a bug', WC_QD_TXT ) . '</a> <a href="' . admin_url('admin.php?page=wc-status') . '" class="button">' . __( 'System Status', WC_QD_TXT ) . '</a></p>'

		) );

		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', WC_QD_TXT ) . '</strong></p>' .
			'<p><a href="' . 'http://www.woothemes.com/woocommerce/' . '" target="_blank">' . __( 'About WooCommerce', WC_QD_TXT ) . '</a></p>' .
			'<p><a href="' . 'http://wordpress.org/extend/plugins/woocommerce/' . '" target="_blank">' . __( 'WordPress.org Project', WC_QD_TXT ) . '</a></p>' .
			'<p><a href="' . 'https://github.com/woothemes/woocommerce' . '" target="_blank">' . __( 'Github Project', WC_QD_TXT ) . '</a></p>' .
			'<p><a href="' . 'http://www.woothemes.com/product-category/themes/woocommerce/' . '" target="_blank">' . __( 'Official Themes', WC_QD_TXT ) . '</a></p>' .
			'<p><a href="' . 'http://www.woothemes.com/product-category/woocommerce-extensions/' . '" target="_blank">' . __( 'Official Extensions', WC_QD_TXT ) . '</a></p>'
		);
	}

}

endif;
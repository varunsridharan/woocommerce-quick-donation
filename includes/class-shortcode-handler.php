<?php
/**
 * functionality of the plugin.
 * @link       @TODO
 * @since      1.0
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Shortcode {

    public function __construct() {
        add_shortcode( 'wc_quick_donation', array($this, 'wc_quick_donation_handler' ));
		add_shortcode( 'wc_qd_single', array($this, 'wc_quick_donation_single_handler' ));
    }
    
    
    public function wc_quick_donation_handler($settings){
        global $donation_box,$donation_price,$currency;
		
        $settings = shortcode_atts( array(
			'type' => wcqd_get_option(WC_QD_DB.'default_render_type'),
			'grouped' => false,
			'show_errors' => wcqd_get_option(WC_QD_DB.'shortcode_show_errors'),
			'selected_value' => wcqd_get_option(WC_QD_DB.'pre_selected_project'),
			'defined_amount' => false
        ), $settings );
        
        $donation_box = WC_QD()->f()->generate_donation_selbox($settings['grouped'],$settings['type'],$settings['selected_value']);
        $donation_price =  WC_QD()->f()->generate_price_box($settings['defined_amount']);
		
        $currency = get_woocommerce_currency_symbol();
        $return_value = '';
        $messages = '';
		
        if($settings['show_errors']){
            ob_start();
            wc_print_notices();
            $return_value .= ob_get_clean(); 
            ob_flush();
        }


		do_action('wc_quick_donation_before_doantion_form',$return_value , $settings['type'],$settings['grouped']);
        $return_value .= WC_QD()->f()->load_template('donation-form.php',WC_QD_TEMPLATE,array('donation_box' => $donation_box,
                                                                             'donation_price' => $donation_price,
                                                                             'currency' => $currency));
        do_action('wc_quick_donation_after_doantion_form',$return_value , $settings['type'],$settings['grouped']);

		return $return_value;
    }

	
	public function wc_quick_donation_single_handler($settings){
		
		$settings = shortcode_atts( array( 
			'title' => 'true', 
			'content' => 'true',
			'defined_amount' => false,
			'id' => false,
		), $settings );
		
		$title = '';
		$content = '';
		
		$project = get_post($settings['id']);
		setup_postdata( $project ); 
		$donation_price =  WC_QD()->f()->generate_price_box($settings['defined_amount']);
		$donation_price .= WC_QD()->f()->generate_donation_selbox(false,'hidden',$settings['id']); 
		
		if($settings['title'] == 'true'){
			$title = get_the_title($settings['id']);
		}
		
		if($settings['content'] == 'true'){
			$content = get_the_excerpt();
		}
		
		$currency = get_woocommerce_currency_symbol();
        $return_value = '';
        $messages = '';
		$return_value .= WC_QD()->f()->load_template('single-donation-form.php',WC_QD_TEMPLATE,array(
			'project_id' => $settings['id'],
			'project' => $project,
			'donation_price' => $donation_price, 
			'currency' => $currency,
			'title' => $title,
			'content' => $content,
		));
		return $return_value;
	}
}
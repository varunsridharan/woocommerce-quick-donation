<?php
/*  Copyright 2014  Varun Sridharan  (email : varunsridharan23@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
    Plugin Name: Woocommerce Quick Donation
    Plugin URI: http://varunsridharan.in/
    Description: Woocommerce Quick Donation
    Version: 0.1
    Author: Varun Sridharan
    Author URI: http://varunsridharan.in/
    License: GPL2
*/
defined('ABSPATH') or die("No script kiddies please!"); 
define( 'wc_qd_u', plugin_dir_url( __FILE__ ) );
define( 'wc_qd_p', plugin_dir_path( __FILE__ ) );

class wc_quick_donation{
	
	private $donation_id;
	
	/**
	 * Setup The Plugin Class
	 */
	function __construct() {
		add_action( 'admin_menu', array($this,'add_menu'));
		 
		add_filter( 'woocommerce_settings_tabs_array',array($this,'add_menu'), 50 );
		add_action( 'woocommerce_settings_tabs_wc_quick_donation', array($this,'settings_page'));
		add_action( 'woocommerce_update_options_wc_quick_donation', array($this,'update_settings') );
		$this->donation_id = get_option('wc_quick_donation_product_id');
		add_shortcode( 'wc_quick_donation', array($this,'shortcode_handler' ));
		add_action('init',array($this,'process_donation'));
		add_filter('woocommerce_get_price', array($this,'get_price'),10,2);
		add_action('wc_qd_show_projects_list',array($this,'get_projects_list'));		 
		add_action('woocommerce_checkout_update_order_meta',  array($this,'add_order_meta'));
		add_action( 'woocommerce_admin_order_data_after_billing_address', array($this,'my_custom_checkout_field_display_admin_order_meta'), 10, 1 );
	}

				   
	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
	 */				   
	public function add_menu($settings_tabs){
		$settings_tabs['wc_quick_donation'] = 'WC Quick Donation';
		return $settings_tabs;		

	}
	
 
	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settings_page() {
		woocommerce_admin_fields( self::get_settings() );
	}  

	
	public function add_order_meta( $order_id ) { 
    	global $woocommerce;
	    update_post_meta( $order_id, 'project_details',$woocommerce->session->projects);
		update_post_meta( $order_id, 'is_donation','yes');
		$order = new WC_Order($order_id);
		$format = sprintf(get_option('wc_quick_donation_order_notes_title'), $woocommerce->session->projects);
		$order->add_order_note($format);
		unset($order);
		 
	} 

	
	public function my_custom_checkout_field_display_admin_order_meta($order){
		echo '<p><strong>'.get_option('wc_quick_donation_project_section_title').' :</strong>'.get_post_meta( $order->id, 'project_details', true ) . '</p>';
	} 	
	
	
	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function get_settings() { 
		
		$settings = array(
			'section_title' => array(
				'name' => 'Woocommerce Quick Donation Settings',
				'type' => 'title',
				'desc' => 'Just Call <code><strong>[wc_quick_donation]</strong></code> short code any where in your page,post,widgets or template <br/>
					To Customize the <strong>Donation Form</strong> copy the template file from <code>woocommerce-quick-donation/template/donation_form.php</code> to your <code>theme/woocommerce</code> folder.
				',
				'id' => 'wc_quick_donation_section_title'
			), 			
			'donate_redirect' => array(
				'name' => 'Donation Redirect',
				'desc' => 'Redirect User Checkout If Added To Cart.',
				'id' => 'wc_quick_donation_redirect',
				'type' => 'select', 
				'class' =>'chosen_select',
				'options' => array('false' => 'No Redirect','true'=>'Redirect To Checkout Page')
			),
			'donate_redirect' => array(
				'name' => 'Remove Cart Items',
				'desc' => 'Removes Other Cart Items If Donation Aded To Cart.',
				'id' => 'wc_quick_donation_cart_remove',
				'type' => 'select', 
				'class' =>'chosen_select',
				'options' => array('false' => 'Keep All Items','true'=>'Remove All Items')
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
		return apply_filters( 'wc_settings_tab_demo_settings', $settings );
	}
	
	
	
	
	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public static function update_settings() {
		woocommerce_update_options(self::get_settings());
	} 	
	
	
	
	/**
	 * Check's For Donation Product Exist In Cart
	 * @returns Boolean True|False
	 */
	public function donation_exsits(){
		global $woocommerce;
		if( sizeof($woocommerce->cart->get_cart()) > 0){
			foreach($woocommerce->cart->get_cart() as $cart_item_key => $values){
				$_product = $values['data'];
				if($_product->id == $this->donation_id){
					return true;	
				}				
			}
		}
		return false;
	}	
	
	
	/**
	 * Gets Donation Current Price
	 * @param  $price 
	 * @param  $product 
	 * @returns 0 | price
	 */
	public function get_price($price, $product){
		global $woocommerce;
		if($product->id == $this->donation_id){ 
			return isset($woocommerce->session->jc_donation) ? floatval($woocommerce->session->jc_donation) : 0; 
		}
		return $price;
	}	
	

	
	
	/**
	 * Process The Given Donation
	 */
	public function process_donation(){
		global $woocommerce;

		$donation = isset($_POST['jc-donation']) && !empty($_POST['jc-donation']) ? floatval($_POST['jc-donation']) : false;
		$projects = isset($_POST['projects']) && !empty($_POST['projects']) ? $_POST['projects'] : false;
		$_SESSION['wc_qd_projects'] = $projects; 
		
		if($donation && isset($_POST['donate-btn'])){
			$found = false;
			if($donation >= 0){
				$woocommerce->session->jc_donation = $donation;
				$woocommerce->session->projects = $projects; 
				if( sizeof($woocommerce->cart->get_cart()) > 0){
					foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
						$_product = $values['data'];
						if($_product->id == $this->donation_id)
							$found = true;
					}

					if(!$found)
						$this->remove_cart_items();
						$woocommerce->cart->add_to_cart($this->donation_id);
						$this->donation_redirect();
				}else{
					$this->remove_cart_items();
					$woocommerce->cart->add_to_cart($this->donation_id);
					$this->donation_redirect();
				}
			}
		}
	}	
	
	private function remove_cart_items(){
		$cart_remove = get_option('wc_quick_donation_cart_remove');
		if(isset($cart_remove) && $cart_remove == 'true'){
			global $woocommerce;
			$woocommerce->cart->empty_cart();
			return true;
		}
		return true;
	}
	
	
	public function donation_redirect(){
		global $woocommerce;
 		$redirect = get_option('wc_quick_donation_redirect');
		if(isset($redirect) && $redirect == 'true'){
			wp_safe_redirect($woocommerce->cart->get_checkout_url() );
			exit;
		}
		return '';		
	}	

	public function wc_qd_form(){

		global $woocommerce; 
		$donate = isset($woocommerce->session->jc_donation) ? floatval($woocommerce->session->jc_donation) : 0;
		if(!$this->donation_exsits()){  
			unset($woocommerce->session->jc_donation); 
			unset($woocommerce->session->projects); 
		}

		// uncomment the next line of code if you wish to round up the order total with the donation e.g. £53 = £7 donation
		// $donate = jc_round_donation($woocommerce->cart->total ); 
		if(!$this->donation_exsits()){
			$wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
			$wc_get_template( 'donation_form.php', array(), '', wc_qd_p . 'template/' ); 
		}
	}	
	 
	
	#$before,$after,$select_class,$select_id
	public function get_projects_list(){
			$projects_db = get_option('wc_quick_donation_projects');
		
			if($projects_db){
				$project = explode(',',$projects_db);
				$project_list = '';
				$project_list .= '<option value="" > Select A Project </option>';
				
				foreach($project as $proj){
					$project_list .= '<option value="'.$proj.'" > '.$proj.'</option>';
				}
				
				echo '<select name="projects">'.$project_list.'</select>';
			}
	}
	
	
	/**
	 * wc_quick_donation shortcode Handler
	 */
	public function shortcode_handler(){
		echo $this->wc_qd_form();
	}
	
	
	/**
	 * Install The Plugin
	 */
	public function install() {
		$exist = get_option('wc_quick_donation_product_id');
		if($exist){
			return true;
		} else {
			$userID = 1;
			if(get_current_user_id()){
				$userID = get_current_user_id();
			}
			$post = array(
				'post_author' => $userID,
				'post_content' => 'Used For Donation',
				'post_status' => 'publish',
				'post_title' => 'Donation',
				'post_type' => 'product',
			);

			$post_id = wp_insert_post( $post, $wp_error );  
			update_post_meta($post_id, '_stock_status', 'instock');
			update_post_meta($post_id, '_tax_status', 'none');
			update_post_meta($post_id, '_tax_class',  'zero-rate');
			update_post_meta($post_id, '_visibility', 'hidden');
			update_post_meta($post_id, '_stock', '');
			update_post_meta($post_id, '_virtual', 'yes');
			update_post_meta($post_id, '_featured', 'no');
			
			update_post_meta($post_id, '_manage_stock', "no" );
			update_post_meta($post_id, '_sold_individually', "yes" );
			update_post_meta($post_id, '_sku', 'checkout-donation');   			
			add_option('wc_quick_donation_product_id',$post_id); 	
		    add_site_option( 'wc_quick_donation_product_id', $post_id) ;
		}
	}	
}



/**
 * Check if WooCommerce is active 
 * if yes then call the class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	register_activation_hook( __FILE__, array( 'wc_quick_donation', 'install' ) );
	$wc_quick_buy = new wc_quick_donation;   
	
}
 

?>
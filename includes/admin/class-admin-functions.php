<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Admin_Function {
    
    public function __construct(){ 
        add_action( 'post_row_actions', array($this,'protect_donation_product'),99,2);
        add_action( 'parse_query', array( $this, 'hide_donation_order_woocommerce_order' ) );
        add_filter( 'wc_order_types',array($this,'add_wc_order_types'),99,2);
    }   
    
    

    
    public function hide_donation_order_woocommerce_order($query) {
        global $pagenow,$post_type;  
        $query = $query;
        
        if(!defined('WC_QD_QRY_OVERRIDE')){
            if( 'edit.php' == $pagenow || $query->is_admin && 'shop_order' == $post_type){ 
                $query->set('meta_query',array('relation' => 'AND', array('key' => '_is_donation','compare' => 'NOT EXISTS')));
            }
        }
        return $query;
    }    
    
     
    public function add_wc_order_types($order_types,$type){ 
        $order_type = $order_types;
        global $post_type; 
        if('' == $type){
            $order_type[] = WC_QD_PT; 
            $order_type[] = 'wcqd_project'; 
        } 
        return $order_type;
    }
    
	/**
	* Protects Donation Product By 
	* @filter_user post_row_actions
	* @param  Array $actions Refer WP.org
	* @param  Array $post    Refer WP.org
	* @return Array Refer WP.org
	* @since 1.0
	*/
	public function protect_donation_product($actions,$post) {
        
		if('product' == $post->post_type) {  
			if($post->ID == WC_QD_ID){
				unset($actions['inline hide-if-no-js']);
				unset($actions['trash']);
				unset($actions['duplicate']);
                $text = __('Remove '.WC_QD.' Plugin To Remove This Product',WC_QD_TXT);
				$actions['trash'] = '<a href="javascript:alert(\' '.$text.' \');"> Trash </a>';
				
			}
		}
        return $actions;
	}  
    
    
    
    public function get_OverRided(){
        $template_files = WC_QD_INSTALL::get_template_list();
        $overrided = array();
        if(is_array($template_files)){
            foreach($template_files as $file){
                $theme_file = false;
                if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
                    $theme_file = get_stylesheet_directory() . '/' . $file;
                } elseif ( file_exists( get_stylesheet_directory() . WC_QD_THEME_TEMPLATE . $file ) ) {
                    $theme_file = get_stylesheet_directory() . WC_QD_THEME_TEMPLATE . $file;
                } elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
                    $theme_file = get_template_directory() . '/' . $file;
                } elseif( file_exists( get_template_directory() . WC_QD_THEME_TEMPLATE . $file ) ) {
                    $theme_file = get_template_directory() . WC_QD_THEME_TEMPLATE . $file;
                }   
                
                if ( $theme_file !== false ) {
                    $core_version  = WC_Admin_Status::get_file_version(WC_QD_TEMPLATE.$file);
                    $theme_version = WC_Admin_Status::get_file_version( $theme_file );
                    if ( $core_version && $theme_version && version_compare( $theme_version, $core_version, '<' ) ) {
                        $overrided[] = array('file' => $file,'corev' => $core_version , 'themev' => $theme_version, 'is_old' => true);
                    } else {
                        $overrided[] = array('file' => $file,'corev' => $core_version , 'themev' => $theme_version, 'is_old' => false);
                    }
                    
                }
            }
        }
        return $overrided;
    }    
}
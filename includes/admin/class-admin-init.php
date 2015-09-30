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

class WooCommerce_Quick_Donation_Admin  {
    private $settings_page_hook;
    /**
	 * Initialize the class and set its properties.
	 * @since      0.1
	 */
	public function __construct() {
        $this->load_required_files(); 
        $this->init_hooks();
	}
    
    public function load_required_files(){
        WC_QD()->load_files(WC_QD_ADMIN.'metabox_framework/meta-box.php'); 
        
    } 
    
    public function init_hooks(){
        add_action( 'admin_menu', array( $this, 'sub_donation_order_menu' ) );
        add_action( 'admin_menu',  array($this,'add_donation_notification_bubble'),99);
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this, 'init_admin_class' ));
        add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
        add_filter( 'woocommerce_screen_ids',array($this,'set_wc_screen_ids'),99);
        add_filter( 'custom_menu_order', array($this,'reorder_donation_menu' ));
        
    }
    
    public function sub_donation_order_menu(){
        
        $this->order_menu_slug = add_submenu_page('edit.php?post_type=wcqd_project','Donation Orders','Donation\'s','administrator','wc_qd_orders',array($this,'donation_orders_page'));

    }
    
    
    public function reorder_donation_menu ($menu_ord ) {
        global $submenu;
        //echo '<pre>'.print_r($submenu,true).'</pre>'; exit;
        $name = 'edit.php?post_type='.WC_QD_PT;
        $arr = array();
        $arr[] = $submenu[$name][18];
        $arr[] = $submenu[$name][5];
        $arr[] = $submenu[$name][10];
        $arr[] = $submenu[$name][15];
        $arr[] = $submenu[$name][16];
        $arr[] = $submenu[$name][17];
        $submenu[$name] = $arr;
        return $menu_ord;
    }  
    
    
    public function add_donation_notification_bubble()  {
        global $submenu; 
        if(isset($submenu['edit.php?post_type='.WC_QD_PT])){
            foreach($submenu['edit.php?post_type='.WC_QD_PT] as $menuK => $menu){
                if($menu[2] === 'wc_qd_orders' ){
                    $submenu['edit.php?post_type='.WC_QD_PT][$menuK][0] .=  "<span class='update-plugins count-1'>
                                                                             <span class='update-count'>0</span></span>"; 
                }
            }
        }
    }    
    
    /**
     * Inits Admin Sttings
     */
    public function init_admin_class(){
        $this->functions =  new WooCommerce_Quick_Donation_Admin_Function;
    }
 
    
    public function donation_orders_page(){
        global $wpdb;

        define('WC_QD_QRY_OVERRIDE',true);
        $order_ids = WC_QD()->db()->get_donation_order_ids();
        $order_ids = WC_QD()->db()->extract_donation_id($order_ids);

        $args = array('post_type' => 'shop_order', 'post_status' =>  array_keys(wc_get_order_statuses()),'post__in' => $order_ids );
        $wp_query = new WP_Query($args);
        require('wp-donation-listing-table.php');
        tt_render_list_page($wp_query);
    }
    
    /**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() { 
        if('wcqd_project_page_WC_QD_settings' == $this->current_screen()){
            wp_enqueue_style(WC_QD_SLUG.'_core_style',WC_QD_CSS.'admin-settings-style.css' , array(), WC_QD()->version, 'all' );  
        }
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_style(WC_QD_SLUG.'_core_style',WC_QD_CSS.'admin-style.css' , array(), WC_QD()->version, 'all' );  
        }
	}
	
    
    /**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
        if(in_array($this->current_screen() , $this->get_screen_ids())) {
            wp_enqueue_script(WC_QD_SLUG.'_core_script', WC_QD_JS.'admin-script.js', array('jquery'), WC_QD()->version, false ); 
        }
	}
    
    public function set_wc_screen_ids($screens){
        $screen = $screens; 
        $screen[] = 'wcqd_project_page_WC_QD_settings';
        $screen[] = $this->order_menu_slug; 
        return $screen;
    }    
    
    /**
     * Gets Current Screen ID from wordpress
     * @return string [Current Screen ID]
     */
    public function current_screen(){
       $screen =  get_current_screen();
       return $screen->id;
    }
    
    /**
     * Returns Predefined Screen IDS
     * @return [Array] 
     */
    public function get_screen_ids(){
        $screen_ids = array();
        $screen_ids[] = 'edit-product';
        $screen_ids[] = 'product';
        $screen_ids[] = WC_QD_PT.'_page_wc_qd_settings';
        $screen_ids[] = 'wcqd_project_page_WC_QD_settings';
        $screen_ids[] = $this->order_menu_slug;
        return $screen_ids;
    }
    
    
    /**
	 * Adds Some Plugin Options
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( WC_QD_FILE == $plugin_file ) {
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('Settings',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('F.A.Q',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('View On Github',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', '#', __('Report Issue',WC_QD_TXT) );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', '#', __('Donate',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', __('Contact Author',WC_QD_TXT) );
		}
		return $plugin_meta;
	}	    
}

?>
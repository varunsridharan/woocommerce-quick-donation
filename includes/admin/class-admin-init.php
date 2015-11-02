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
    
    public function add_notice(){
        wc_qd_notice(sprintf('<p>%s</p> <p class="submit"><a id="WCQDShowTXT" class="button-primary debug-report" href="javascript:;">%s</a></p>',
                    __('Please copy and paste this information in your ticket when contacting support:',WC_QD_TXT),
                    __('Get System Report',WC_QD_TXT))
        ,'update',array('wraper' => false));
    }
    
    public function load_required_files(){
        WC_QD()->load_files(WC_QD_ADMIN.'metabox_framework/meta-box.php'); 
    } 
    
    public function init_hooks(){
        add_action( 'current_screen', array( $this, 'admin_screen' ));
        add_action( 'admin_menu', array( $this, 'sub_donation_order_menu' ) );
        add_action( 'admin_menu',  array($this,'add_donation_notification_bubble'),99);
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ),99);
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this, 'init_admin_class' ));
        add_filter( 'plugin_row_meta', array($this, 'plugin_row_links' ), 10, 2 );
        add_filter( 'woocommerce_screen_ids',array($this,'set_wc_screen_ids'),99);
        add_filter( 'custom_menu_order', array($this,'reorder_donation_menu' ));
        
    }
    
    public function admin_screen(){ 
        if($this->sys_info == $this->current_screen()){
            if(!WC_QD()->is_request('ajax')){
                $this->add_notice();
            }
        } 
    }
    
    public function sub_donation_order_menu(){
        
        $this->order_menu_slug = add_submenu_page('edit.php?post_type=wcqd_project',
                                                  __('Donation Orders',WC_QD_TXT),
                                                  __('Donation\'s',WC_QD_TXT),
                                                  'administrator',
                                                  'wc_qd_orders',
                                                  array($this,'donation_orders_page'));
        
        $this->donors_list = add_submenu_page('edit.php?post_type=wcqd_project',
                                                  __('Donors List',WC_QD_TXT),
                                                  __('Donors List',WC_QD_TXT),
                                                  'administrator',
                                                  'wc_qd_donors',
                                                  array($this,'donors_listing_page'));
        
        $this->sys_info = add_submenu_page('edit.php?post_type=wcqd_project',
                                                  __('System Tools',WC_QD_TXT),
                                                  __('System Tools',WC_QD_TXT),
                                                  'administrator',
                                                  'wc_qd_sys_info',
                                                  array($this,'system_tools'));
        $this->tools = add_submenu_page('edit.php?post_type=wcqd_project',
                                                  __('',WC_QD_TXT),
                                                  __('',WC_QD_TXT),
                                                  'administrator',
                                                  'wc_qd_tools',
                                                  array($this,'system_tools'));
    }
    
    
    public function reorder_donation_menu ($menu_ord ) {
        global $submenu;
        $name = 'edit.php?post_type='.WC_QD_PT;
        if(empty($submenu)){return $submenu;}
        $arr = array();
        $arr[] = $submenu[$name][18];
        $arr[] = $submenu[$name][19];
        $arr[] = $submenu[$name][5];
        $arr[] = $submenu[$name][10];
        $arr[] = $submenu[$name][15];
        $arr[] = $submenu[$name][16];
        $arr[] = $submenu[$name][17];
        $arr[] = $submenu[$name][20];
        $submenu[$name] = $arr;
        return $menu_ord;
    }  
    
    
    public function add_donation_notification_bubble()  {
        global $submenu; 
        $c = $this->get_status_count();
        if(isset($submenu['edit.php?post_type='.WC_QD_PT])){
            foreach($submenu['edit.php?post_type='.WC_QD_PT] as $menuK => $menu){
                if($menu[2] == 'wc_qd_orders' ){
                    $submenu['edit.php?post_type='.WC_QD_PT][$menuK][0] .=  "<span class='update-plugins count-1'>
                                                                             <span class='update-count'>$c</span></span>"; 
                }
            }
        } 
    }    
    
    private function get_status_count(){
        $order_ids = WC_QD()->db()->get_donation_order_ids(); 
        $count = 0;
        foreach($order_ids as $id){
            $order_status = get_post_status($id['donationid']); 
            if($order_status == 'wc-on-hold' || $order_status == 'wc-processing'){
                $count++; 
            }
        }
        
        return $count;
    }
    /**
     * Inits Admin Sttings
     */
    public function init_admin_class(){
        $this->functions =  new WooCommerce_Quick_Donation_Admin_Function;
        $this->admin_order_page = new WooCommerce_Quick_Donation_Admin_Order_Page_Functions;
        $this->admin_help = new WooCommerce_Quick_Donation_Admin_Help;
    }
 
    
    public function donors_listing_page(){
        $ids = WC_QD()->db()->get_doners_ids();
        $ids = WC_QD()->db()->extract_donation_id($ids);
        $args = array('include'  => $ids); 
        require('wp-donors-listing-table.php');
        donor_render_list_page($args); 
    }
        
    public function system_tools(){
        require(WC_QD_ADMIN.'/views/tools.php');
    }
    
    public function donation_orders_page(){
        global $wpdb;

        define('WC_QD_QRY_OVERRIDE',true);
        $order_ids = WC_QD()->db()->get_donation_order_ids();
        $order_ids = WC_QD()->db()->extract_donation_id($order_ids);

        $args = array('posts_per_page' => '0',
                      'post_type' => 'shop_order', 
                      'post_status' =>  array_keys(wc_get_order_statuses()),
                      'post__in' => $order_ids 
                     );
        
        if(isset($_GET['paged'])){$args['paged'] = $_GET['paged']; }
        if(isset($_GET['m'])){ $args['m'] = $_GET['m'];}        
        if(isset($_GET['_customer_user'])) { 
            $args['meta_query'][]['key'] = '_customer_user';
            $args['meta_query'][]['value'] = $_GET['_customer_user'];
            $args['meta_query'][]['compare'] = '=';
        }
        
        if(isset($_GET['dproj'])) { 
            $args['meta_query'][]['key'] = '_project_details';
            $args['meta_query'][]['value'] = $_GET['dproj'];
            $args['meta_query'][]['compare'] = '=';
        } 
        if(isset($_GET['post_status'])){ $args['post_status'] = $_GET['post_status'];}    
        $wp_query = new WP_Query($args);
        require('wp-donation-listing-table.php');
        tt_render_list_page($wp_query);
    }
    
    /**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() { 
        wp_enqueue_style(WC_QD_SLUG.'_quick_hacks',WC_QD_CSS.'admin-hack-style.css' , array(), WC_QD()->version, 'all' );  
        
        if('wcqd_project_page_WC_QD_settings' == $this->current_screen()){
            wp_enqueue_style(WC_QD_SLUG.'_settings_style',WC_QD_CSS.'admin-settings-style.css' , array(), WC_QD()->version, 'all' );  
        }
        
        if($this->sys_info == $this->current_screen()){
            wp_enqueue_style(WC_QD_SLUG.'_sysinfo_style',WC_QD_CSS.'sysinfo.css' , array(), WC_QD()->version, 'all' );  
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
        if($this->sys_info == $this->current_screen()){
            wp_register_script(WC_QD_SLUG.'_sysinfo_script', WC_QD_JS.'sysinfo.js', array( 'jquery' ), WC_QD()->version,false );
            wp_localize_script(WC_QD_SLUG.'_sysinfo_script', 'systemInfoAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
            wp_enqueue_script(WC_QD_SLUG.'_sysinfo_script');
            
        }
            
	}
    
    public function set_wc_screen_ids($screens){
        $screen = $screens; 
        $screen[] = 'wcqd_project_page_WC_QD_settings';
        $screen[] = $this->order_menu_slug;
        $screen[] = $this->donors_list;
        $screen[] = $this->sys_info; 
        $screen[] = $this->tools;
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
        $screen_ids[] = $this->order_menu_slug;
        $screen_ids[] = $this->donors_list;
        $screen_ids[] = $this->sys_info;   
        $screen_ids[] = $this->tools;
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
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', admin_url('edit.php?post_type=wcqd_project&page=WC_QD_settings'), __('Settings',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://wordpress.org/plugins/woocommerce-quick-donation/faq/', __('F.A.Q',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://github.com/technofreaky/woocomerce-quick-donation/', __('View On Github',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'https://github.com/technofreaky/woocomerce-quick-donation/issues/', __('Report Issue',WC_QD_TXT) );
            $plugin_meta[] = sprintf('&hearts; <a href="%s">%s</a>', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9MLKDVUVB7WBJ', __('Donate',WC_QD_TXT) );
            $plugin_meta[] = sprintf('<a href="%s">%s</a>', 'http://varunsridharan.in/plugin-support/', __('Contact Author',WC_QD_TXT) );
		}
		return $plugin_meta;
	}	    
}

?>
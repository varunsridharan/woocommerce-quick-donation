<?php
/**
 * Plugin Name:       WooCommerce Quick Donation
 * Plugin URI:        http://wordpress.org/plugins/woocommerce-quick-donation/
 * Description:       Turns WooCommerce Into Online Donation
 * Version:           1.3.5 BETA
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       woocommerce-quick-donation
 * Domain Path:       /languages/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: https://github.com/technofreaky/woocomerce-quick-donation
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
class WooCommerce_Quick_Donation {
	/**
	 * @var string
	 */
	public $version = '1.3.5';

	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;
    public static $is_donation_product_exist = true;
    protected static $f = null;
    public static $shortcode = null;
    public static $donation_id = null;
    public static $settings = null;
    public static $settings_values = null;
    public static $email = null;
    private static $db = null;
    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
        self::$donation_id = get_option(WC_QD_DB.'product_id');
        $this->define('WC_QD_ID',intval(get_option(WC_QD_DB.'product_id')));
        $this->load_required_files();
        register_activation_hook( __FILE__,array('WC_QD_INSTALL','INIT') );
        add_action( 'init', array( $this, 'init' ));
    }
    
    /**
     * Triggers When INIT Action Called
     */
    public function init(){
        $this->check_donation_product_exist();
        $this->init_class();
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
    
    /**
     * Checks If Donation Product Exist In WooCommerce Products
     */
    private function check_donation_product_exist($notice = true){
        $install = new WC_QD_INSTALL;
        if(! $install->check_donation_exists()){
            self::$is_donation_product_exist = false;
            if($notice){ wc_qd_notice('WooCommerce Donation Product Not Exist','error',true);}
        }
    }

        
    /**
     * Checks If Donation Product Exist In WooCommerce Products
     */
    public function donation_product_exist_public(){
        $this->check_donation_product_exist();
        return self::$is_donation_product_exist;
    }
    /**
     * Checks If Donation Product Exists In Cart
     */
    public function check_donation_exists_cart(){
        global $woocommerce;
        $found = false;
        if( sizeof($woocommerce->cart->get_cart()) > 0){
            foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
                $_product = $values['data'];
                if($_product->id == self::$donation_id)
                    $found = true;
            }

        }
        return $found;
    }
    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
        $this->load_files(WC_QD_INC.'wc-quick-donation-*.php'); 
        $this->load_files(WC_QD_ADMIN.'wps/*.php'); 
        //$this->load_files(WC_QD_INC.'class-admin-notice.php');
        $this->load_files(WC_QD_INC.'class-post-*.php');
        $this->load_files(WC_QD_INC.'class-quick-donation-db.php');
        $this->load_files(WC_QD_INC.'class-install.php');
        $this->load_files(WC_QD_INC.'class-quick-donation-functions.php');
        $this->load_files(WC_QD_INC.'class-quick-donation-email-functions.php');
        
        if($this->is_request('frontend')){
            $this->load_files(WC_QD_INC.'class-quick-donation-process.php');
            $this->load_files(WC_QD_INC.'class-wc-myaccount-functions.php');
            $this->load_files(WC_QD_INC.'class-shortcode-handler.php');
        }
        
        if($this->is_request('admin')){
           $this->load_files(WC_QD_ADMIN.'class-*.php');
        } 

    }
    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        self::$email = new WooCommerce_Quick_Donation_Emails_Functions;
        self::$f = new WooCommerce_Quick_Donation_Functions;
        self::$settings = new WooCommerce_Quick_Donation_Settings;
        self::$db = new WooCommerce_Quick_Donation_DB; 
        
        if($this->is_request('frontend')){
            self::$shortcode = new WooCommerce_Quick_Donation_Shortcode;
            $this->donation  =  new WooCommerce_Quick_Donation_Process;
            $this->my_account = new WooCommerce_Quick_Donation_MyAccount_Fuctions;
        }
        
        if($this->is_request('admin')){
            $this->admin = new WooCommerce_Quick_Donation_Admin;
        }
 
    }
    
    /**
     * Function Get Call Admin
     */
    public function admin(){
        return $this->admin;
    }
    
    /**
     * Returns Function Class 
     */
    public function f(){
        return self::$f;
    }
    
    /**
     * Retruns DB Class 
     */
    public function db(){
        return self::$db;
    }
    
    /**
     * Returns Settings Class 
     */
    public function settings(){
        return self::$settings;
    }
    
    /**
     * Gets Settings From DB 
     */
     public function get_option($key = ''){
        var_dump(self::$settings_values);
     }
    
    /**
     * Loads The Files From Given Path
     */
    public function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){

            if($type == 'require'){
                require_once( $files );
            } else if($type == 'include'){
                include_once( $files );
            }
            
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(WC_QD_TXT, false, WC_QD_LANG );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (WC_QD_TXT === $domain){ 
            return WC_QD_LANG.'/'.get_locale().'/'.get_locale().'.mo';
        }
        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        global $wpdb;
        $this->define('WC_QD','WooCommerce Quick Donation'); # Plugin Name
        $this->define('WC_QD_SLUG','wc-qd'); # Plugin Slug
        
        $this->define('WC_QD_DB_V','1.0');
        $this->define('WC_QD_V','1.0');
        
        $this->define('WC_QD_FILE',plugin_basename( __FILE__ ));
        
        $this->define('WC_QD_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
        $this->define('WC_QD_INC',WC_QD_PATH.'includes/');
        $this->define('WC_QD_ADMIN',WC_QD_INC.'admin/');
        
        $this->define('WC_QD_URL',plugins_url('', __FILE__ )); 
        $this->define('WC_QD_ADMIN_URL',WC_QD_URL.'/includes/admin/');
        $this->define('WC_QD_JS',WC_QD_URL.'/includes/js/');
        $this->define('WC_QD_CSS',WC_QD_URL.'/includes/css/');
        
        $this->define('WC_QD_TEMPLATE',WC_QD_PATH.'template/'); # Plugin Template DIR
        $this->define('WC_CORE_TEMPLATE','woocommerce/');
        $this->define('WC_QD_THEME_TEMPLATE','/'.WC_CORE_TEMPLATE.'donation/');
        $this->define('WC_QD_LANG',WC_QD_PATH.'languages');
        $this->define('WC_QD_TXT','woocommerce-quick-donation'); #plugin lang Domain

        $this->define('WC_QD_TB',$wpdb->prefix . 'wc_quick_donation');
        $this->define('WC_QD_DB','wc_qd_');
        $this->define('WC_QD_PT','wcqd_project');
        $this->define('WC_QD_CAT','wcqd_category');
        $this->define('WC_QD_TAG','wcqd_tags'); 
    }
    
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
    
    
    
	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
    
    
    
}

require_once(plugin_dir_path(__FILE__).'includes/class-wc-dependencies.php');
require_once(plugin_dir_path(__FILE__).'includes/class-admin-notice.php');

if(WC_QD_Dependencies()){
    function WC_QD(){
        return WooCommerce_Quick_Donation::get_instance();
    }
    $GLOBALS['woocommerce_quick_donation'] =  WC_QD();
    
} else {
   wc_qd_notice(__('WooCommerce Is Required. To Use This Plugin :)','woocommerce-quick-donation'),
                'error',
                array('times' => 1));
}


//Load language files for the theme
add_action( 'plugins_loaded', 'wcqd_load_textdomain' );
function wcqd_load_textdomain() {
    load_plugin_textdomain( 'wcqd', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

?>
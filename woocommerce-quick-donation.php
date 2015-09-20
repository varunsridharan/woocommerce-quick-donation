<?php
/**
 * Plugin Name:       WooCommerce Quick Donation
 * Plugin URI:        https://wordpress.org/plugins/woocommerce-plugin-boiler-plate/
 * Description:       Sample Plugin For WooCommerce
 * Version:           3.0
 * Author:            Varun Sridharan
 * Author URI:        http://varunsridharan.in
 * Text Domain:       woocommerce-quick-donation
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: @TODO
 */

if ( ! defined( 'WPINC' ) ) { die; }
 
class WooCommerce_Quick_Donation {
	/**
	 * @var string
	 */
	public $version = '0.1';

	/**
	 * @var WooCommerce The single instance of the class
	 * @since 2.1
	 */
	protected static $_instance = null;
    public static $is_donation_product_exist = true;
    protected static $f = null;
    public static $shortcode = null;
    public static $donation_id = null;

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
        $this->init_class();
        $this->check_donation_product_exist();
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
    
    private function check_donation_product_exist(){
        $install = new WC_QD_INSTALL;
        if(! $install->check_donation_exists()){
            self::$is_donation_product_exist = false;
            wc_qd_notice('WooCommerce Donation Product Not Exist','error');
        }
    }
    
    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
        $this->load_files(WC_QD_PATH.'includes/class-admin-notice.php');
        $this->load_files(WC_QD_PATH.'includes/class-post-*.php');

        $this->load_files(WC_QD_PATH.'includes/class-install.php');
        $this->load_files(WC_QD_PATH.'includes/class-quick-donation-functions.php');
        $this->load_files(WC_QD_PATH.'includes/class-quick-donation-process.php');
        $this->load_files(WC_QD_PATH.'includes/class-shortcode-handler.php');
        
        if($this->is_request('admin')){
           $this->load_files(WC_QD_PATH.'admin/class-*.php');
        } 

    }
    
    /**
     * Inits loaded Class
     */
    private function init_class(){
        self::$f = new WooCommerce_Quick_Donation_Functions;
        self::$shortcode = new WooCommerce_Quick_Donation_Shortcode;
        $this->donation = new WooCommerce_Quick_Donation_Process;
        
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
    
    public function f(){
        return self::$f;
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
        if (WC_QD_TXT === $domain)
            return WC_QD_LANG.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('WC_QD','WooCommerce Quick Donation'); # Plugin Name
        $this->define('WC_QD_SLUG','wc-qd'); # Plugin Slug
        $this->define('WC_QD_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
        $this->define('WC_QD_TEMPLATE',WC_QD_PATH.'template/'); # Plugin Template DIR
        
        $this->define('WC_QD_LANG',WC_QD_PATH.'languages');
        $this->define('WC_QD_TXT','woocommerce-quick-donation'); #plugin lang Domain
        $this->define('WC_QD_URL',plugins_url('', __FILE__ )); 
        $this->define('WC_QD_FILE',plugin_basename( __FILE__ ));
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

function WC_QD(){
    return WooCommerce_Quick_Donation::get_instance();
}

$GLOBALS['woocommerce_quick_donation'] =  WC_QD();

?>
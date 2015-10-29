<?php
/**
 * WC Dependency Checker
 *
 * Checks if WooCommerce is enabled
 */
if ( ! class_exists( 'WC_QD_Dependencies' ) ){
    class WC_QD_Dependencies {
        private static $active_plugins;

        public static function init() {
            self::$active_plugins = (array) get_option( 'active_plugins', array() );
            if ( is_multisite() )
                self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        public static function woocommerce_active_check() {
            if ( ! self::$active_plugins ) self::init();

            return in_array('woocommerce/woocommerce.php', self::$active_plugins) || array_key_exists('woocommerce/woocommerce.php', self::$active_plugins);
        }
    }
}


/**
 * WC Detection
 */
if(! function_exists('is_wc_qd_dependencies_active')){
    function WC_QD_Dependencies() {
        return WC_QD_Dependencies::woocommerce_active_check();
    }
}
?>
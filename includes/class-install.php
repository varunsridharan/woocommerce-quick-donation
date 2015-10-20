<?php

class WC_QD_INSTALL{

    /**
     * Inits Install Hook
     */
    public static function init(){
        $donation_exist = self::check_donation_exists();
        self::check_db_version();
        self::wc_qd_table_install();
        if($donation_exist){ return true; }
        
        $post_id = self::create_donation(); 
        update_option(WC_QD_DB.'product_id',$post_id); 
    }

    /**
     * Checks Upgrade Status
     */
    public static function check_db_version(){
        $current_version = get_option(WC_QD_DB.'db_version');
        if(! $current_version){
            add_option(WC_QD_DB.'db_version', WC_QD_DB_V);
        } 
    }
    
    /**
     * Checks Donation Product Exists
     */
    public static function check_donation_exists(){
        $exist = get_option(WC_QD_DB.'product_id');
        
        if($exist && get_post_status ($exist)){
            return true;
        }
        return false;
    }
    
    /**
     * Create Quick Donation Table
     */
    public static function wc_qd_table_install() {
        global $wpdb;
        global $jal_db_version;

        $table_name = WC_QD_TB;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(9) NOT NULL AUTO_INCREMENT,
            date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            userid bigint(20) NOT NULL,
            donationid bigint(20) NOT NULL,
            projectid bigint(20) NOT NULL, 
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql ); 
    }    
     
    /**
     * Create Donation Product In WooCommerce
     * @return int donation Post id
     */
    public static function create_donation(){
        $userID = 1;
        if(get_current_user_id()){ $userID = get_current_user_id(); }
        
        $post = array(
            'post_author' => $userID,
            'post_content' => 'Used For Donation',
            'post_status' => 'publish',
            'post_title' => 'Donation',
            'post_type' => 'product',
        );
        
        $post_id = wp_insert_post($post);  
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
        return $post_id;
    }
}

?>

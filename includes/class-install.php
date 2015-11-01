<?php

class WC_QD_INSTALL{

    /**
     * Inits Install Hook
     */
    public static function init(){
        $donation_exist = self::check_donation_exists();
        self::check_db_version();
        self::post_register();
        self::wc_qd_table_install();
        self::check_template_files();

        if(! $donation_exist){
            $post_id = self::create_donation(); 
            update_option(WC_QD_DB.'product_id',$post_id); 
        }
    }
    
    public static function post_register(){
        WC_QD_Post_Types::register_donation_posttype();
        WC_QD_Post_Types::register_donation_category();
        WC_QD_Post_Types::register_donation_tags();
        flush_rewrite_rules();
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
        if($exist && get_post_status ($exist)){ return true; }
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
    
    public static function get_template_list(){
        $core_tempalte_list = WC_Admin_Status::scan_template_files( WC_QD_TEMPLATE );
        return $core_tempalte_list;
    }
    
    
    
    public static function check_template_files(){
        $template_files = self::get_template_list();
        $outdated = false;
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
                        $outdated = true;
                        break;
                    }
                }
            }
            
                
            if ( $outdated ) {
                $theme = wp_get_theme(); 
                $message = sprintf(
                    __( '<p> <strong>Your theme (%s) contains outdated copies of some WooCommerce Quick Donation template files.</strong> These files may need updating to ensure they are compatible with the current version of WooCommerce Quick Donation. You can see which files are affected from the %ssystem status page%s. If in doubt, check with the author of the theme. </p> <p class="submit">%sLear More About Templates%s %s</p>',
                       'woocommerce' ), 
                    esc_html( $theme['Name'] ), 
                    '<a href="' . admin_url( 'admin.php?page=wc-status' ) . '">','</a>',
                    '<a  target="_blank" href="" class="button-primary" href="">','</a>', 
                    wc_qd_remove_link('class="button" ') 
                
                );
                wc_qd_notice($message,'error',array('times' => 0,'wraper' => false));
            }        
        }
    }    
}

?>
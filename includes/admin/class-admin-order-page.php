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

class WooCommerce_Quick_Donation_Admin_Order_Page_Functions {
    public function __construct(){
        WC_QD()->load_files(WC_QD_ADMIN.'metabox/*.php');
        
        add_filter( 'manage_shop_order_posts_columns', array( $this, 'shop_order_columns' ) );
        
        add_action( 'add_meta_boxes_shop_order',array($this,'remove_metabox'),99,2);
        add_action( 'manage_shop_order_posts_custom_column', array( $this, 'render_shop_order_columns' ), 10 );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
        
        add_action( 'woocommerce_quick_donation_process_shop_order_meta', 'WC_Quick_Donation_Meta_Box_Order_Data::save', 40, 2 );
    }
    
    /**
	 * Define custom columns for orders
	 * @param  array $existing_columns
	 * @return array
	 */
	public function shop_order_columns( $existing_columns ) {
        $existingc = $existing_columns;
        if(isset($_REQUEST['page']) && "wc_qd_orders" == $_REQUEST['page']){
            $existingc = '';
            $existingc['cb'] = $existing_columns['cb'];
            $existingc['order_status'] = $existing_columns['order_status'];
            $existingc['order_id'] = __('ID',WC_QD_TXT);
            $existingc['by_user']  = __('Donor',WC_QD_TXT);
            $existingc['donation_project'] = __("Project",WC_QD_TXT);
            $existingc['customer_message'] = $existing_columns['customer_message'];
            $existingc['order_notes'] = $existing_columns['order_notes'];
            $existingc['order_date'] = $existing_columns['order_date'];
            $existingc['order_total'] = $existing_columns['order_total'];
            $existingc['order_actions'] = $existing_columns['order_actions'];
        }
        
        return $existingc;
    }
     
    public function render_shop_order_columns( $column ){
		global $post, $woocommerce, $the_order;

		if ( empty( $the_order ) || $the_order->id != $post->ID ) {
			$the_order = wc_get_order( $post->ID );
		}

        if('donation_project' == $column){
            $project_ID = WC_QD()->db()->get_project_id($post->ID);
            $title = get_the_title($project_ID);
            $link = get_permalink($project_ID);
            printf('<a href="%s"> %s </a> ',$link,$title);
        } else if('order_id' == $column){
            printf( _x( '%s ', 'Order number by X', WC_QD_TXT ), '<a href="' . admin_url( 'post.php?post=' . absint( $post->ID ) . '&action=edit' ) . '" class="row-title"><strong>#' . esc_attr( $the_order->get_order_number() ) . '</strong></a>' );
        } else if ('by_user' == $column){
            if ( $the_order->user_id ) {
                $user_info = get_userdata( $the_order->user_id );
            }

            if ( ! empty( $user_info ) ) {

                $username = '<a href="user-edit.php?user_id=' . absint( $user_info->ID ) . '">';

                if ( $user_info->first_name || $user_info->last_name ) {
                    $username .= esc_html( ucfirst( $user_info->first_name ) . ' ' . ucfirst( $user_info->last_name ) );
                } else {
                    $username .= esc_html( ucfirst( $user_info->display_name ) );
                }

                $username .= '</a>';

            } else {
                if ( $the_order->billing_first_name || $the_order->billing_last_name ) {
                    $username = trim( $the_order->billing_first_name . ' ' . $the_order->billing_last_name );
                } else {
                    $username = __( 'Guest', WC_QD_TXT );
                }
            }

            echo $username;        
        }
    }
    
    public function remove_metabox($post){ 
        $is_donation = WC_QD()->db()->_is_donation($post->ID);
        if($is_donation){
            remove_meta_box('woocommerce-order-items','shop_order','normal');
            remove_meta_box('woocommerce-order-downloads','shop_order','normal');
            remove_meta_box('woocommerce-order-data','shop_order','normal');
            add_meta_box( 'woocommerce-quick-donation-order-data', sprintf( __( '%s Data', WC_QD_TXT ), 'Donation' ), 'WC_Quick_Donation_Meta_Box_Order_Data::output', 'shop_order', 'normal', 'high' );
            
            remove_action( 'woocommerce_process_shop_order_meta', 'WC_Meta_Box_Order_Data::save', 40);
        }
    }
    
    public function save_meta_boxes( $post_id, $post ) {
        if('shop_order' == $post->post_type)
            do_action( 'woocommerce_quick_donation_process_shop_order_meta', $post_id, $post );
    }
        
}
?>
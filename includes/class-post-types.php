<?php
if ( ! defined( 'WPINC' )  ) { die; } 

class WC_QD_Post_Types {
    
   /**
    * Inits Post Types Class
    */
   public static function init(){
        add_action( 'init', array(__CLASS__,'register_donation_posttype'), 5 );
        add_action( 'init', array(__CLASS__,'register_donation_category'), 5 );
        add_action( 'init', array(__CLASS__,'register_donation_tags'    ), 5 );
        add_action( 'init', array(__CLASS__,'register_post_status'      ), 0 );
   }
   
   /**
    * Registers Donation Post Type
    */
   public static function register_donation_posttype(){
        $args = array(
            'label'               => __( 'Quick Donation Project', WC_QD_TXT ),
            'description'         => __( 'WooCommerce Donation Projects', WC_QD_TXT ),
            'labels'              => self::get_post_types_labels(),
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', ),
            'taxonomies'          => array( 'donation_category', 'donation_tags' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            //'menu_position'       => 5,
            'menu_icon'           => 'dashicons-smiley',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,		
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => self::get_posttype_rewrite(),
            'capability_type'     => 'page',
        );
        register_post_type( WC_QD_PT, $args );
   }
    
   /**
    * Post Types Labels
    */
   public static function get_post_types_labels() {
       return array(
            'name'                => _x( 'Quick Donation Projects', 'Post Type General Name', WC_QD_TXT ),
            'singular_name'       => _x( 'Quick Donation Project', 'Post Type Singular Name', WC_QD_TXT ),
            'menu_name'           => __( 'Quick Donation', WC_QD_TXT ),
            'name_admin_bar'      => __( 'Donation', WC_QD_TXT ),
            'parent_item_colon'   => __( 'Parent Project :', WC_QD_TXT ),
            'all_items'           => __( 'All Project', WC_QD_TXT ),
            'add_new_item'        => __( 'Add New Project', WC_QD_TXT ),
            'add_new'             => __( 'Add Project', WC_QD_TXT ),
            'new_item'            => __( 'New Project', WC_QD_TXT ),
            'edit_item'           => __( 'Edit Project', WC_QD_TXT ),
            'update_item'         => __( 'Update ProjectNot found in Trash', WC_QD_TXT ),
            'view_item'           => __( 'View Project', WC_QD_TXT ),
            'search_items'        => __( 'Search Project', WC_QD_TXT ),
            'not_found'           => __( 'No Project Found', WC_QD_TXT ),
            'not_found_in_trash'  => __( 'No Project Found in Trash', WC_QD_TXT ),
        );
   }
                   
   /**
    * Returns Post Type Rewrites
    */
   public static function get_posttype_rewrite(){
       return array(
            'slug'                => 'donations',
            'with_front'          => true,
            'pages'               => true,
            'feeds'               => true,
        );
   }

    
    
   /**
    * Registers Post Type Category
    */
   public static function register_donation_category(){
   
        $labels = array(
                'name'                       => _x( 'Project Categories', 'Taxonomy General Name', WC_QD_TXT ),
                'singular_name'              => _x( 'Project Category', 'Taxonomy Singular Name', WC_QD_TXT ),
                'menu_name'                  => __( 'Categories', WC_QD_TXT ),
                'all_items'                  => __( 'All Categories', WC_QD_TXT ),
                'parent_item'                => __( 'Parent Category', WC_QD_TXT ),
                'parent_item_colon'          => __( 'Parent Category:', WC_QD_TXT ),
                'new_item_name'              => __( 'New Category Name', WC_QD_TXT ),
                'add_new_item'               => __( 'Add New Project Category', WC_QD_TXT ),
                'edit_item'                  => __( 'Edit Category', WC_QD_TXT ),
                'update_item'                => __( 'Update Category', WC_QD_TXT ),
                'view_item'                  => __( 'View Category', WC_QD_TXT ),
                'separate_items_with_commas' => __( 'Separate Categories with commas', WC_QD_TXT ),
                'add_or_remove_items'        => __( 'Add or remove Categories', WC_QD_TXT ),
                'choose_from_most_used'      => __( 'Choose from the most used', WC_QD_TXT ),
                'popular_items'              => __( 'Popular Categories', WC_QD_TXT ),
                'search_items'               => __( 'Search Categories', WC_QD_TXT ),
                'not_found'                  => __( 'Not Found', WC_QD_TXT ),
        );
        $rewrite = array(
            'slug'                       => 'donation/category',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
            //'update_count_callback'      => 'f',
        );
        
        register_taxonomy( WC_QD_CAT, array( WC_QD_PT ), $args );
       
   }
    
    
    
    /**
     * Registers Donation Tags
     */
    public static function register_donation_tags() {

        $labels = array(
            'name'                       => _x( 'Project Tags', 'Taxonomy General Name', WC_QD_TXT ),
            'singular_name'              => _x( 'Project Tag', 'Taxonomy Singular Name', WC_QD_TXT ),
            'menu_name'                  => __( 'Tags', WC_QD_TXT ),
            'all_items'                  => __( 'All Tags', WC_QD_TXT ),
            'parent_item'                => __( 'Parent Tag', WC_QD_TXT ),
            'parent_item_colon'          => __( 'Parent Tag :', WC_QD_TXT ),
            'new_item_name'              => __( 'New Tag Name', WC_QD_TXT ),
            'add_new_item'               => __( 'Add New Tag', WC_QD_TXT ),
            'edit_item'                  => __( 'Edit Tag ', WC_QD_TXT ),
            'update_item'                => __( 'Update Tags', WC_QD_TXT ),
            'view_item'                  => __( 'View Tag', WC_QD_TXT ),
            'separate_items_with_commas' => __( 'Separate Tags with commas', WC_QD_TXT ),
            'add_or_remove_items'        => __( 'Add or remove Tags', WC_QD_TXT ),
            'choose_from_most_used'      => __( 'Choose from the most used', WC_QD_TXT ),
            'popular_items'              => __( 'Popular Tags', WC_QD_TXT ),
            'search_items'               => __( 'Search Tags', WC_QD_TXT ),
            'not_found'                  => __( 'Not Found', WC_QD_TXT ),
        );
        $rewrite = array(
            'slug'                       => 'donation/tags',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
           // 'update_count_callback'      => 'f',
        );
        register_taxonomy( 'wcqd_tags', array(WC_QD_PT ), $args );

    }    

    // Register Custom Status
    public static function register_post_status() {
        register_post_status( 'donation-completed', array(
            'label'                     => _x( 'Completed', 'Order status',WC_QD_TXT),
            'label_count'               => _n_noop( 'Completed (%s)',  'Completed (%s)', WC_QD_TXT),
            'public'                    => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'exclude_from_search'       => true,
        ) );
        
        register_post_status( 'donation-on-hold', array(
            'label'                     => _x( 'Donation On Hold', 'Order status', WC_QD_TXT),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Donation On Hold <span class="count">(%s)</span>', 'DonationOn Hold <span class="count">(%s)</span>',WC_QD_TXT)
        ) );
        register_post_status( 'donation-refunded', array(
            'label'                     => _x( 'Donation Refunded', 'Order status', WC_QD_TXT),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', WC_QD_TXT)
        ) );        
    }
}


 
WC_QD_Post_Types::init();
?>
<?php

class WooCommerce_Quick_Donation_Project_Meta_Box {

    public function __construct() {
        add_filter( 'wcqd_metabox_meta_boxes', array($this,'register_metabox' ));
    }
    
    public function register_metabox($meta_boxes){
    
      
        // 1st meta box
        $meta_boxes[] = array(
            'id'       => WC_QD_SLUG.'-metabox',
            'title'    => WC_QD,
            'pages'    => array(WC_QD_PT ),
            'context'  => 'normal',
            'priority' => 'high',
            'fields' => array(
                
                array(
                    'name'  => __('Min Donation',WC_QD_TXT),
                    'desc'  => __('Min Required Donation',WC_QD_TXT),
                    'id'    => '_'.WC_QD_DB . 'min_req_donation',
                    'type'  => 'number',
                    'std'   => '10',
                    'class' => '',
                    'clone' => false,
                ),  
                array(
                   'name'  => __('Max Donation',WC_QD_TXT),
                    'desc'  => __('Max Required Donation',WC_QD_TXT),
                    'id'    => '_'.WC_QD_DB . 'max_req_donation',
                    'type'  => 'number',
                    'std'   => '10000',
                    'class' => '',
                    'clone' => false,
                )  ,
                array(
                   'name'  => __('Visibility',WC_QD_TXT),
                    'desc'  => __('Show Or Hide In Donation Form',WC_QD_TXT),
                    'id'    => '_'.WC_QD_DB . 'visibility_project',
                    'type'  => 'select_advanced',
                    'std'   => 'show',
                    'class' => '',
                    'options'=>array('hide'=>'Hide Listing', 'show'=>'Show Listing'),
                    'clone' => false,
                )

            )
        );

        return $meta_boxes;
    }
    
}

new WooCommerce_Quick_Donation_Project_Meta_Box;
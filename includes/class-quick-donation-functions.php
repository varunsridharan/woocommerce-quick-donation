<?php
/**
 * functionality of the plugin.
 * @author  Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Quick_Donation_Functions  {
    protected static $project_db_list = null;
    /**
     * Get Donation Project List From DB
     */
    public function get_donation_project_list(){
        if(self::$project_db_list != null || self::$project_db_list != ''){
            return self::$project_db_list;
        }
        $args = array(
            'posts_per_page'   => 0,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => WC_QD_PT,
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'post_status'      => 'publish',
            'suppress_filters' => true 
        );
        self::$project_db_list = get_posts($args);
        return self::$project_db_list;
    }
    
    public function get_porject_list($grouped = false){
        $list = $this->get_donation_project_list();
        $projects = array();
        foreach($list as $project){
            if($grouped){
                $term = get_the_terms( $project->ID, WC_QD_CAT );
                $projects[$term[0]->name][$project->ID] = $project->post_title;
            } else {
                $projects[$project->ID] = $project->post_title;
            } 
        }
        return $projects;
    }
    
     
    public function generate_donation_selbox($grouped = false,$type = 'select'){
        global $id, $name, $class, $field_output, $is_grouped, $project_list,$attributes;
        $field_output = '';
        $id = 'donation_project';
        $name = 'wc_qd_donate_project_name';
        $class = apply_filters('wcqd_project_name_'.$type.'_class',array(),$type);
        $custom_attributes = apply_filters('wcqd_project_name_'.$type.'_attribute',array(),$type);
        $is_grouped = $grouped;
        $project_list = $this->get_porject_list($grouped);
        
        $class = implode(' ',$class);
        $attributes = '';
        foreach($custom_attributes as $attr_key => $attr_val) {
            $attributes .= $attr_key.'="'.$attr_val.'" ';
        }
        
        $this->load_template('field-'.$type.'.php', WC_QD_TEMPLATE.'fields/' );
        
        return $field_output;
    }

    
    public function generate_price_box(){
        global $id, $name, $class, $field_output,$attributes,$value;
        $field_output = '';
        $id = 'donation_price';
        $name = 'wc_qd_donate_project_price';
        $class = apply_filters('wcqd_project_price_text_class',array(),'text');
        $custom_attributes = apply_filters('wcqd_project_price_text_attribute',array(),'text');
        $value = '';
        
        
        $class = implode(' ',$class);
        $attributes = '';
        foreach($custom_attributes as $attr_key => $attr_val) {
            $attributes .= $attr_key.'="'.$attr_val.'" ';
        }
        
        $this->load_template('field-text.php',WC_QD_TEMPLATE . 'fields/' );
        
        return $field_output;
    }
    
    public function load_template($file,$path){
        $wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
        $wc_get_template( $file,array(), '', $path); 
    }
}

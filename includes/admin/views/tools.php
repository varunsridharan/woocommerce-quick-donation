<div class="wrap woocommerce">
    <?php 
    $active = 'wc_qd_sys_info';
    
    if(isset($_GET['page'])){$active = $_GET['page'];}

    $pageToOpen = str_replace('wc_qd_','',$active);
    $pages= array();
    $pages['wc_qd_sys_info'] = __('System Status',WC_QD_TXT);
    $pages['wc_qd_tools'] = __('Tools',WC_QD_TXT);
    
    $links = '';
    foreach($pages as $pageid => $page){
        $class = 'nav-tab ';
        $url = menu_page_url($pageid, false ); 

        if($active == $pageid){$class .= ' nav-tab-active'; }
        $links .= '<a class="'.$class.'" href="'.$url.'">'.$page.'</a>'; 
    }
    ?>  
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper"><?php echo $links; ?></h2>  
    <?php require(WC_QD_ADMIN.'views/'.$pageToOpen.'_view.php'); ?>
</div>
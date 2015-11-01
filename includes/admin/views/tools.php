<div class="wrap woocommerce">
    <?php 
    $active = 'sysinfo';
    if(isset($_REQUEST['ntab'])){$active == $_GET['ntab'];}
    $pages= array();
    $pages['sysinfo'] = __('System Status',WC_QD_TXT);
    $pages['tools'] = __('Tools',WC_QD_TXT);
    $url = menu_page_url('wc_qd_tools', false );
    $links = '';
    foreach($pages as $pageid => $page){
        $class = 'nav-tab ';
        if($active == $pageid){$class .= ' nav-tab-active'; }
        $links .= '<a class="'.$class.'" href="'.$url.'&ntba='.$pageid.'">'.$page.'</a>'; 
    }
    ?>  
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper"><?php echo $links; ?></h2> 
    <?php require(WC_QD_ADMIN.'views/'.$active.'-view.php'); ?>
</div>
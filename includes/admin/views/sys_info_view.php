<?php
require(WC_QD_ADMIN.'/sysinfo/sysinfo.php');
$sysinfo = new WooCommerce_Quick_Donation_SysInfo;
$sysinfo->setup();
?>
<textarea style="display:none;" 
          readonly="readonly" 
          onclick="this.focus();this.select()" 
          id="wcqdssstextarea" 
          name="simple-system-status-textarea" 
          title="<?php _e( 'To copy the System Status, click below then press Ctrl + C (PC) or Cmd + C (Mac)',WC_QD_TXT);?>"><?php echo $sysinfo::display(); ?> </textarea>
<?php echo $sysinfo::display('table'); ?>
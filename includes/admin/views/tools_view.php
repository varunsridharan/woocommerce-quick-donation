<?php
$product_exist = __(' This will help you to create a new donation product. if not exist',WC_QD_TXT);
if(! WC_QD()->donation_product_exist_public()){
	$product_exist = sprintf(' <span class="wc_qd_error" >%s</span>',__('Donation Product Dose Not Exist In WooCommerce. Please Create It ',WC_QD_TXT));
}
?>
<form action="" method="post">
	<input type="hidden" value="wc_qd_wp_tools" name="action">
	<?php wp_nonce_field('wc_qd_wp_tools'); ?>

	<table cellspacing="0" class="wc_status_table widefat wc_qd_system_toold">
		<thead class="tools">
			<tr>
				<th colspan="2"><?php _e('System Tools',WC_QD_TXT); ?></th>
			</tr>
		</thead>
		<tbody class="tools">
			<tr class="clear_transients">
				<td><?php _e('Reinstall Donation Product',WC_QD_TXT); ?> </td>
				<td>
					<p>
						<button type="button" class="wcqdAjaxCall button clear_transients" 
								href="<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=CreateDonationProduct'),'CreateDonationProduct'); ?>"><?php _e('Create Donation Product',WC_QD_TXT); ?></button>
						<span class="description"><?php echo $product_exist; ?></span>
					</p>
				</td>
			</tr>
			
			<tr class="clear_transients">
				<td><?php _e('Clear Donation Log',WC_QD_TXT); ?> </td>
				<td>
					<p>
						<button type="button" class="ConfirmAction wcqdAjaxCall button" 
								data-alert-text="Are You Sure Want To Clear All Logs"
								href="<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=ClearDonationLog'),'ClearDonationLog'); ?>"><?php _e('Clear Log',WC_QD_TXT); ?></button>
						<span class="description"><?php _e('Clear Logs From WC Donation Table. which resets user donation count & other logs'); ?></span>
					</p>
				</td>
			</tr>

			
		</tbody>
	</table>
	
</form>


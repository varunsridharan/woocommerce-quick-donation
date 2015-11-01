<?php
require(WC_QD_ADMIN.'/sysinfo/sysinfo.php');
$sysinfo = new WooCommerce_Quick_Donation_SysInfo;
$sysinfo->setup();
echo $sysinfo::display('table');
?>

<table cellspacing="0" id="status" class="wc_status_table widefat">
	<thead>
		<tr>
			<th data-export-label="WordPress Environment" colspan="3">WordPress Environment</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Home URL">Home URL:</td>
			<td class="help"><a class="help_tip" href="#">[?]</a></td>
			<td>http://localhost/wpdev</td>
		</tr> 
		<tr>
			<td data-export-label="Language">Language:</td>
			<td class="help"><a class="help_tip" href="#">[?]</a></td>
			<td>en_US</td>
		</tr>
	</tbody>
</table>
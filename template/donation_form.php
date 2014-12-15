<?php
/**
 * Single Product Image
 *
 * @author 		Varun Sridharan
 * @package 	WC Quick Donation/Templates
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $donate;
?>
<form method="post">
	<table>
		<tr class="donation-block">
			<td colspan="6">
				<div class="donation">
					<p class="message"><strong>Add a donation to your order:</strong></p>
					
					<?php do_action('wc_qd_show_projects_list'); ?>
					
					<div class="input text">
						<label>Donation (&pound;):</label>
						<input type="text" name="donation_ammount" value="<?php echo $donate; ?>"/>
					</div>
					<div class="submit_btn">
						<input type="submit" name="donation_add" value="Add Donation"/>
					</div>
				</div>
			</td>
		</tr>
	</table>
</form> 
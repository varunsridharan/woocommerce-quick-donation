<?php
/**
 * Donation Form
 * 
 * @author  Varun Sridharan
 * @package WooCommerce Quick Donation/Templates
 * @version 0.1
 */
?>
<form method="post">

    <table>
        <tr>
            <td> <?php _e('Donation Project','wcqd'); ?> </td>
            <td> <?php echo $donation_box; ?></td>
        </tr>
        <tr>
            <td><?php _e('Donation Amount','wcqd'); ?> <?php echo $currency; ?></td>
            <td><?php echo $donation_price; ?></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="donation_add" value="<?php _e('Add Donation','wcqd'); ?>"/></td>
        </tr>
    </table>
    
</form>
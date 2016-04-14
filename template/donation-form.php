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
            <td> Donation Project </td>
            <td> <?php echo $donation_box; ?></td>
        </tr>
        <tr>
            <td>Donation Amount <?php echo $currency; ?></td>
            <td><?php echo $donation_price; ?></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="donation_add" value="<?php _e( 'Add Donation', WC_QD_TXT ) ?>"/></td>
        </tr>
    </table>
    
</form>
<?php
global $donation_box,$donation_price,$currency;
wc_print_notices();
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
            <td><input type="submit" name="donation_add" value="Add Donation"/></td>
        </tr>
    </table>
    
</form>
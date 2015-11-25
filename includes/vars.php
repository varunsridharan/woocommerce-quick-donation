<?php
global $wc_qd_template_list;
$wc_qd_template_list = array();
$wc_qd_template_list['general'] =  array(
            'donation-form.php' => 'donation-form.php',
			'single-donation-form.php' => 'single-donation-form.php',
            'field-radio.php' => 'fields/field-radio.php',
            'field-select.php' => 'fields/field-select.php',
            'field-text.php' => 'fields/field-text.php',
			'field-hidden.php' => 'fields/field-hidden.php',
            'myaccount/my-donations.php' => 'myaccount/my-donations.php',
			
			'projects/single.php' => 'projects/single.php',
        );
$wc_qd_template_list['is_donation'] = array( 
			'cart/mini-cart.php' => 'cart/donation-mini-cart.php',
            'cart/cart-item-data.php' => 'cart/donation-cart-item-data',
            'cart/cart-shipping.php' => 'cart/donation-cart-shipping.php',
            'cart/cart-totals.php' => 'cart/donation-cart-totals.php',
            'cart/cart.php' => 'cart/donation-cart.php',
            'cart/proceed-to-checkout-button.php' => 'cart/donation-proceed-to-checkout-button.php',
            'checkout/cart-errors.php' => 'checkout/donation-cart-errors.php',
            'checkout/form-billing.php' => 'checkout/donation-form-billing.php',
            'checkout/form-checkout.php' => 'checkout/donation-form-checkout.php',
            'checkout/form-coupon.php' => 'checkout/donation-form-coupon.php',
            'checkout/form-login.php' => 'checkout/donation-form-login.php',
            'checkout/form-pay.php' => 'checkout/donation-form-pay.php',
            'checkout/form-shipping.php' => 'checkout/donation-form-shipping.php',
            'checkout/payment-method.php' => 'checkout/donation-payment-method.php',
            'checkout/payment.php' => 'checkout/donation-payment.php',
            'checkout/review-order.php' => 'checkout/donation-review-order.php',
        );
  
$wc_qd_template_list['after_order'] = array(
            'checkout/thankyou.php' => 'checkout/donation-thankyou.php',
            'myaccount/view-order.php' => 'myaccount/view-donation.php',
            'order/order-details.php' => 'order/donation-order-details.php',
            'order/order-details-item.php' => 'order/donation-order-details-item.php',
            'order/order-details-customer.php' => 'order/donation-order-details-customer.php',
            'emails/email-styles.php' => 'emails/donation-email-styles.php',
            'emails/donation-admin-new.php' => 'emails/donation-admin-new.php',
            'emails/email-addresses.php' => 'emails/donation-email-addresses.php',
            'emails/donation-email-footer.php' => 'emails/donation-email-footer.php',
            'emails/donation-email-header.php' => 'emails/donation-email-header.php',
            'emails/email-order-items.php' => 'emails/donation-email-order-items.php',
            'emails/plain/email-addresses.php' => 'emails/plain/donation-email-addresses.php',
            'emails/plain/email-order-items.php' => 'emails/plain/donation-email-order-items.php',
            'emails/plain/donation-customer-invoice.php' => 'emails/plain/donation-customer-invoice.php',
            'emails/donation-processing.php' => 'emails/donation-processing.php',
            'emails/plain/donation-processing.php' => 'emails/plain/donation-processing.php',
);
?>
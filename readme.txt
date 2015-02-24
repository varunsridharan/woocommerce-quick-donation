=== WooCommerce Quick Donation ===
Contributors: varunms
Donate link: http://varunsridharan.in
Tags: Woocommerce,Quick Dontion,quick donation,online donation,wordpress donation,simple donation,donation form,WC donation,Online Payment,Payment,Online,Donate,Monthly Goal
Requires at least: 3.0 plus WooCommerce 2.x or higher
Tested up to: 4.1 + WooCommerce 2.x
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Trurns WooCommerce Into Online Donation.

== Description ==

<h3> <blink> Features </blink></h3>
* Redirect User After Donation Added To Cart [Cart Page / Checkout Page]
* Select Your Preferred Payment Gateway For Donation
* Custom Email Template For Donation Processing
* Custom Email Template For Donation Completed
* Some Minor Bug Fix

<h3> What We Will Give In Up Coming Releases </h3>
* Customized Report Page [Need Help]
* Better Email Template
* Target / Goal For The Project


WooCommerce Shopping Cart Donation which makes woocoomerce to use for online donation purpose.

This plugin will create a new product in the name of **donation**.

This Plugin Can called by using the below short code
`[wc_quick_donation]`


**Plugin Template List** *[You Can Modify The Template Buy Copying To Your Theme's Folder]*
1. Donation Form Template
`wc-quick-donation/template/donation_form.php`
2. Donation Processing Email Template
`wc-quick-donation/template/donation_processing_html.php
 wc-quick-donation/template/donation_processing_plain.php`
3. Donation Completed Email Template
`wc-quick-donation/template/donation_completed_html.php
 wc-quick-donation/template/donation_completed_plain.php`


Plugin Settings : ***Woocoomerce Settings => Quick Donation***

Email Template Settings : ***Woocommerce Settings => Emails => Donation Processing & Completed***

== Upgrade Notice ==
We have updated ***donation-form.php*** template. so please replace the template if you have modified

== Installation ==

1. Installing alternatives:
 * *via Admin Dashboard:* Go to 'Plugins > Add New', search for "WooCommerce Quick Donation", click "install"
 * *OR via direct ZIP upload:* Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * *OR via FTP upload:* Upload `woocommerce-quick-donation` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. For Settings Look at your `Woocommerce => Settings => WC Quick Donation`





== Frequently Asked Questions ==

**How To Call / Use This Plugin ?**  
This Plugin Can Be Called Using `[wc_quick_donation]`

**Can I Modify The Donation Form ?**  
Yes. Its Possible By Copying To Your Theme's Folder `wp-content/plugins/wc-quick-donation/template/donation_form.php`

**What Is The Use Of Project Field ?**  
Project is like [category / division]. for which you donation. multiple can be entered by `,` separated eg : `Project 1,Project 2`

**Donation Plugin Not Working After Upgrading To 0.2**
As we have updated ***donation-form.php*** template. it may not be working with the old one. so please replace the template if you have modified or contact us.

**How i can get project name in email template**
You can get the name by calling the variable `$project_name`

**I have an idea for your plugin!**  
That's great. We are always open to your input, and we would like to add anything we think will be useful to a lot of people. Please send your comment/idea to varunsridharan23@gmail.com

**I found a bug!**  
Oops. Please User github / WordPress to post bugs.  <a href="https://github.com/technofreaky/woocomerce-quick-donation/"> Open an Issue </a>

== Screenshots ==
1. Settings Panel
2. Email Template Settings For Donation Processing
3. Email Template Settings For Donation Completed.

== Changelog ==

= 0.4 =
* Internal Server Error / php error fixed while adding donation to cart  [WP : 4.1 | WC : 2.3.3]
* Minor Bug Fix

= 0.3 =
* Plugin Activation Issue Fixed.

= 0.2 =
* Redirect User After Donation Added To Cart [Cart Page / Checkout Page]
* Select Your Preferred Payment Gateway For Donation
* Custom Email Template For Donation Processing
* Custom Email Template For Donation Completed
* Some Minor Bug Fix

= 0.1 =
* Base Version

=== WooCommerce Quick Donation ===
Contributors: varunms
Donate link: http://varunsridharan.in
Tags: WooCommerce,Quick Dontion,quick donation,online donation,wordpress donation,simple donation,donation form,WC donation,Online Payment,Payment,Online,Donate,Monthly Goal,affiliate, cart, checkout, commerce, configurable, digital, download, downloadable, e-commerce, ecommerce, inventory, reports, sales, sell, shipping, shop, shopping, stock, store, tax, variable, widgets, woothemes, wordpress ecommerce
Requires at least: 3.0 or higher
Tested up to: 4.1.1
WC requires at least: 1.0
WC tested up to: 2.3.5
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turns WooCommerce Into Online Donation.

== Description ==

<div class="plugin-notice plugin-notice-requested"> <span>!</span><span class="plugin-notice-banner-msg">Dear User, We are still trying to make this plugin a stable and prefect.. if you find any bug / any feature is required please open an issue at <a href="https://github.com/technofreaky/woocomerce-quick-donation/issues">GitHub</a> or <a href="https://wordpress.org/support/plugin/woocommerce-quick-donation">WordPress Support</a> </span></div>

<h3> What's New In 1.2 </h3>
* Donation Widget
* Get Project's By Function
* Added 2 Actions
* Minor Bug Fixes 


<h3> Feature Will Be Implemented In Next Release </h3>
* Separate Donation Report Page


<h3> <blink> Features </blink></h3>
* Redirect User After Donation Added To Cart [Cart Page / Checkout Page]
* Select Your Preferred Payment Gateway For Donation
* Custom Email Template For Donation Processing
* Custom Email Template For Donation Completed
* Configurable Min & Max Donation Amount
* Donation From Widget
* Custom Error Messages


WooCommerce Shopping Cart Donation which makes WooComerce to use for online donation purpose.

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

**Plugin Filters, Actions & Functions** 
1. Before Donation Prints
`wc_quick_donation_before_form`
2. After Donation Prints
`wc_quick_donation_after_form`
3. Get Project's By Function
`global $wc_quick_donation; $wc_quick_donation->donation_projects();`

Plugin Settings : ***Woocoomerce Settings => Quick Donation***

Email Template Settings : ***WooCommerce Settings => Emails => Donation Processing & Completed***

== Upgrade Notice ==
We have updated ***donation-form.php*** template. so please replace the template if you have modified
please update the settings.

== Installation ==


= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Quick Donation, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Quick Donation" and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now"

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your Web Server via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).



1. Installing alternatives:
 * *via Admin Dashboard:* Go to 'Plugins > Add New', search for "WooCommerce Quick Donation", click "install"
 * *OR via direct ZIP upload:* Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * *OR via FTP upload:* Upload `WooCommerce-quick-donation` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. For Settings Look at your `WooCommerce => Settings => WC Quick Donation`





== Frequently Asked Questions ==

**How To Call / Use This Plugin ?**  
This Plugin Can Be Called Using `[wc_quick_donation]`

**Can I Modify The Donation Form ?**  
Yes. Its Possible By Copying To Your Theme's Folder `wp-content/plugins/wc-quick-donation/template/donation_form.php`

**What Is The Use Of Project Field ?**  
Project is like [category / division]. for which you donation. multiple can be entered by `,` separated eg : `Project 1,Project 2`

**Donation Plugin Not Working After Upgrading To 0.2**
As we have updated ***donation-form.php*** template. it may not be working with the old one. so please replace the template if you have modified or contact us.

**How I can get project name in email template**
You can get the name by calling the variable `$project_name`

**Where can I request new features**
Please open an issue at <a href="https://github.com/technofreaky/woocomerce-quick-donation/"> GitHub </a> and we will look into it

**I have an idea for your plugin!**  
That's great. We are always open to your input, and we would like to add anything we think will be useful to a lot of people. Please send your comment/idea to varunsridharan23@gmail.com

**I found a bug!**  
Oops. Please User github / WordPress to post bugs.  <a href="https://github.com/technofreaky/woocomerce-quick-donation/"> Open an Issue </a>

**WooCommerce Quick Donation is awesome! Can I contribute?**
Yes you can! Join in on our <a href="https://github.com/technofreaky/woocomerce-quick-donation/">GitHub repository :)</a>

== Screenshots ==
1. General Settings 
2. Donation Settings
3. Custom Error Message
4. Email Template Settings For Donation Processing
5. Email Template Settings For Donation Completed.

== Changelog ==
= 1.2 =
* Added Widget For Donation Form
* Added Seperate function to get Donation Projects `donation_projects()`
* Added 2 Actions `wc_quick_donation_before_form` & `wc_quick_donation_after_form` for donation form.
* Minor Bug Fix.

= 1.1 =
* Plugin Activation Issue Fixed.

= 1.0 =
* Configurable Min & Max Donation Amount
* Custom Error Messages
* Separate Menu with donation order listings
* Fixed Order Notes And Order Meta Added For All Products
* Fixed Saving Donation Order Id In DB [Before It Stored All Order IDS]
* Minor performance fixes
* Code Clean Up
* Removed Row Action [Quick Edit , Trash & Duplicate] Options For Donation Product In Product Listing

= 0.4 =
* Internal Server Error / php error fixed while adding donation to cart  [WP : 4.1 | WC : 2.3.3]
* Added Generator Meta Tag
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

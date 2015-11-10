=== WooCommerce Quick Donation ===
Contributors: varunms
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9MLKDVUVB7WBJ
Tags: WooCommerce,Quick Dontion,quick donation,online donation,wordpress donation,simple donation,donation form,WC donation,Online Payment,Payment,Online,Donate,Monthly Goal,affiliate, cart, checkout, commerce, configurable, digital, download, downloadable, e-commerce, ecommerce, inventory, reports, sales, sell, shipping, shop, shopping, stock, store, tax, variable, widgets, woothemes, wordpress ecommerce
Requires at least: 3.0 or higher
Tested up to: 4.4
WC requires at least: 1.0
WC tested up to: 2.4.10
Stable tag: 1.3.7.1 Beta
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turns WooCommerce Into Online Donation.

== Description ==
<h1> Dear User I am still trying to make things plugin more stable and prefect if you find any bug / any feature is required please open an issue at <a href="https://github.com/technofreaky/woocomerce-quick-donation/issues">GitHub</a> or <a href="https://wordpress.org/support/plugin/woocommerce-quick-donation">WordPress Support</a> </h1>


WooCommerce Shopping Cart Donation which makes WooComerce to use for online donation purpose.
This plugin will create a new product in the name of **donation**.

<h3> Plugin Support / Feature Request </h3>
Dear User, if you need any help regarding this plugin or require any new feature in this plugin kindly contact me via
Email : plugin@varunsridharan.in / kindly post it in github.

<h3> Features </h3>
* Redirect User After Donation Added To Cart [Cart Page / Checkout Page]
* Select Your Preferred Payment Gateway For Donation
* Custom Email Template For Donation Processing
* Custom Email Template For Donation Completed
* Configurable Min & Max Donation Amount Based On Project
* Custom Error Messages


**This Plugin Can called by using the below short code** `[wc_quick_donation]`

**Shortcode Variables**
<code>
1. type : select | radio
2. grouped : true | false
</code>
 

== Screenshots ==
1. Menu In WP-ADMIN View
2. Donation Project Listing View
3. New Donation Project View
4. General Settings View
5. Custom Error Message View
6. 2 Types Of Donation Form
7. Donation Cart View
8. Donation Checkout View
9. Donation Order Success View

== Upgrade Notice ==
Note this release is in beta and from now this plugin is totally rebuild. it may not work with or like older version's

== Installation ==


= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Quick Donation, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Quick Donation"Â and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now"

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


== Changelog ==
= 1.3.7.1 =
* **Fixed** - Fixed Empty Trash Issue
* **Fixed** - [`T_PAAMAYIM_NEKUDOTAYIM` Critical: Update to latest kills admin area] (https://github.com/technofreaky/woocomerce-quick-donation/issues/58) After Last UPDATE [1.3.7]
* **Added** - Option To Clear Donation Log In system tools menu


= 1.3.7 =

* **Added** - French translation
* **Added** - Processing & Completed Email Templates
* **Added** - System Tools (Create Donation Product)
* **Added** - Auto Config Default Settings When Installing
* **Added** - Pre Config Shortcode settings via admin
* **Fixed** - Email Template Call Issue (admin-new-donation)
* **Fixed** - Donation projects in front end limited to 5
* **Fixed** - Wrong Count In WC Orders (Counted With Donations)
* **Fixed** - Donor Stats Not Countend With Donation Order Is Deleted
* **Fixed** - Cart Mini Widget (Template Added)
* **Fixed** - Error When No Payment Gateway Configured
* **Fixed** - Trash & Restore Issue For Donation Orders

= 1.3.6 =
 
* **Fixes** - Fixed Count Notification Bubble Count
* **Fixes** - Metabox Error While Creating New Order Via Admin
* **Fixes** - Fixed Template Load Issue
* **Fixes** - Fixed issue when donation alredy exit in cart & redirect to checkout
* **Tweaks** - Remodified WP LIST TABLE For Donation Listing
* **Tweaks** - Renamed WC_TEMPLATE To WC_CORE_TEMPLATE
* **Tweaks** - Changed Order Text To Donation in Order Details Metabox
* **Tweaks** - Loaded Admin Notice Before
* **Tweaks** - Moved Template File Check From admin functions to plugin install class
* **Added** - Added Project & Doner Based Filter In Admin Listing
* **Added** - New function to get user based donation ids get_user_donations_ids
* **Added** - New template to list donations `myaccount/my-donations.php`
* **Added** - Seperate Page For Listing Donors
* **Added** - Function To Get Grouped Donation IDS By User & Project
* **Added** - 3 New Templates Added Used for my account in front end [ `myaccount/view-donation.php, order/order-details-item.php, order/order-details-customer.php`]
* **Added** - Plugin Template versioning #26
* **Added** - Checked If WooCommerce Is Active [#23]
* **Added** - FR Languages Translations
* **Added** - Included Sys Info plugin
* **Added** - Auto Flush Permalink
* **Added** - wcqd_project_limit  function to get limit of a project
* **Added** - wcqd_get_project_name from order id
* **Added** - wcqd_get_project to get project id from order id
* **Added** - Added default user configurable title for easy use
* **Added** - Added Custom HEADER & FOOTER For Emails

**Issues Link**
[#Shortcode position and page content ](https://wordpress.org/support/topic/shortcode-position-and-page-content?replies=7),
[#21](https://github.com/technofreaky/woocomerce-quick-donation/issues/21), 
[#23](https://github.com/technofreaky/woocomerce-quick-donation/issues/23), 
[#26](https://github.com/technofreaky/woocomerce-quick-donation/issues/26), 
[#27](https://github.com/technofreaky/woocomerce-quick-donation/issues/27), 
[#33](https://github.com/technofreaky/woocomerce-quick-donation/issues/33), 
[#34](https://github.com/technofreaky/woocomerce-quick-donation/issues/34), 
[#35](https://github.com/technofreaky/woocomerce-quick-donation/issues/35), 
[#38](https://github.com/technofreaky/woocomerce-quick-donation/issues/38),

= 1.3.5 BETA =
* **Fixes** - Metabox Error While Creating New Order Via Admin
* **Fixes** - Fixed Count Notification Bubble Count
* **Fixes** - Minor Bug Fix


= 1.3.4 BETA =
* **Fixes** - Menu Error In Front End When Logged In Using Custom ID
* **Tweaks** - Removed Unwated Metabox For Donation order Page
* **Tweaks** - Modifed Core WC Quick Donation Templates
* **Added** - Custom Cart Page Template Support
* **Added** - New Custom Metabox For Donation Order details
* **Added** - Few Functions In WC Quick Donation DB Class
* **Added** - Custom Order Thank You Page & Order Details Tables Support

= 1.3.3 BETA =
* **Fixes** - Moved Few DB functions from Functions class to db class
* **Fixes** - Fixed template override issue [https://github.com/technofreaky/woocomerce-quick-donation/issues/12]
* **Fixes** - Removed Unwated Metabox For Donation order Page

= 1.3.2 BETA =
* **New** - Added Quick Links At Plugin Listing Table
* **New** - Error Message When User Trying To Add Another Donation To Cart
* **New** - Already Exist Donation Error Message Option Added In Settings
* **Tweaks** - Standardized Coding 
* **Tweaks** - Settings Page Modified
* **Fixes** - Changed `Donation` Name To `Project Name` at Checkout in Review Order Section
* **Fixes** - Quick Donation Menu Not Listed In Some WP Settings.

= 1.3.1 BETA =
* **Fixes** - Error At WooCommerce Settings Page

= 1.3 Beta =
* **New** - Created Separate Custom Post Type For Donation
* **New** - Created Separate Custom Settings Page
* **New** - Total Plugin Redeveloped
* **New** - Min & Max Donation Amount Based On Project
* **New** - Separate Page For Donation Orders

= 1.2 =
* Added Widget For Donation Form
* Added Separate function to get Donation Projects `donation_projects()`
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
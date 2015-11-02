
<?php 

/**
 * Get User IP
 *
 * Returns the IP address of the current visitor
 *
 * @since 1.0.8.2
 * @return string $ip User's IP address
 */
function sss_get_ip() {

	$ip = '127.0.0.1';

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'edd_get_ip', $ip );
}


/**
 * Get user host
 *
 * Returns the webhost this site is using if possible
 *
 * @since 2.0
 * @return mixed string $host if detected, false otherwise
 */
function sss_get_host() {
	$host = false;

	if( defined( 'WPE_APIKEY' ) ) {
		$host = 'WP Engine';
	} elseif( defined( 'PAGELYBIN' ) ) {
		$host = 'Pagely';
	} elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
		$host = 'ICDSoft';
	} elseif( DB_HOST == 'mysqlv5' ) {
		$host = 'NetworkSolutions';
	} elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
		$host = 'iPage';
	} elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
		$host = 'IPower';
	} elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
		$host = 'MediaTemple Grid';
	} elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
		$host = 'pair Networks';
	} elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
		$host = 'Rackspace Cloud';
	} elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
		$host = 'SysFix.eu Power Hosting';
	} elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
		$host = 'Flywheel';
	} else {
		// Adding a general fallback for data gathering
		$host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
	}

	return $host;
} ?>
### Begin WooCommerce Quick Donation Status ###

-- WordPress Environment

Home URL:                 <?php echo home_url() . "\n"; ?>
Site URL:                 <?php echo site_url() . "\n"; ?>
WP Version:               <?php echo get_bloginfo( 'version' ) . "\n"; ?>
WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>
WP Language:              <?php echo ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n"; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>
WP Memory Limit:          <?php echo ( self::let_to_num( WP_MEMORY_LIMIT )/( 1024 ) )."MB"; ?><?php echo "\n"; ?>
WP Memory Limit Status:   <?php if (( WP_MEMORY_LIMIT )/( 1024 ) > 63) { echo 'OK'. "\n"; } else {echo 'Not OK - Recommended Memory Limit is 64MB'."\n";} ?>
WP Table Prefix:          <?php echo $wpdb->prefix. "\n"; ?>
WP Table Prefix Length:	  <?php echo strlen( $wpdb->prefix ). "\n"; ?>
WP Table Prefix Status:   <?php if ( strlen( $wpdb->prefix ) > 16 ) { echo 'ERROR: Too Long'; } else { echo 'Acceptable'; } echo "\n"; ?>
WP Timezone:              <?php echo get_option('timezone_string') . ', GMT: ' . get_option('gmt_offset') . "\n"; ?>
WP Remote Post:           <?php echo $WP_REMOTE_POST; ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Registered Post Stati:    <?php echo implode( ', ', get_post_stati() ) . "\n"; ?>
Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n" ?>
<?php if( get_option( 'show_on_front' ) == 'page' ) { 
	$front_page_id = get_option( 'page_on_front' );
	$blog_page_id = get_option( 'page_for_posts' ); ?>
Page On Front:            <?php ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n"; ?>
Page For Posts:           <?php ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n"; ?>
<?php } ?>

## Theme Information ##
<?php $active_theme = wp_get_theme(); ?>

Theme Name:               <?php echo $active_theme->Name . "\n"; ?>
Theme Version:            <?php echo $active_theme->Version . "\n"; ?>
Theme Author:             <?php echo $active_theme->get('Author') . "\n"; ?>
Theme Author URI:         <?php echo $active_theme->get('AuthorURI') . "\n"; ?>
Is Child Theme:           <?php echo is_child_theme() ? 'Yes' . "\n" : 'No' . "\n"; if( is_child_theme() ) { $parent_theme = wp_get_theme( $active_theme->Template ); ?>
Parent Theme:             <?php echo $parent_theme->Name ?>        
Parent Theme Version:     <?php echo $parent_theme->Version . "\n"; ?>
Parent Theme URI:         <?php echo $parent_theme->get('ThemeURI') . "\n"; ?>
Parent Theme Author URI:  <?php echo $parent_theme->{'Author URI'} . "\n"; ?>
<?php } ?>

## Plugins Information ##

<?php 
$muplugins = wp_get_mu_plugins();
    if( count( $muplugins > 0 ) ) {
        echo "\n" . '-- Must-Use Plugins' . "\n\n";

        foreach( $muplugins as $plugin => $plugin_data ) {
            echo $plugin['Name'] . ': {V : ' . $plugin['Version'] . ' || A : ' .$plugin['Author'] .' } ' .$plugin['PluginURI'] ."\n";
        }
    }

	// WordPress active plugins
	echo "\n" . '-- WordPress Active Plugins' . "\n\n";
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );

	foreach( $plugins as $plugin_path => $plugin ) {
		if( !in_array( $plugin_path, $active_plugins ) )
			continue;

		echo $plugin['Name'] . ' : {V : ' . $plugin['Version'] . ' ||  A : ' .$plugin['Author'] .' } ' .$plugin['PluginURI'] ."\n";
	}

	
	// WordPress inactive plugins
	echo "\n" . '-- WordPress Inactive Plugins' . "\n\n";

	foreach( $plugins as $plugin_path => $plugin ) {
		if( in_array( $plugin_path, $active_plugins ) )
			continue;

		echo $plugin['Name'] . ' : {V : ' . $plugin['Version'] . ' || A : ' .$plugin['Author'] .' } ' .$plugin['PluginURI'] ."\n";
	}

if( is_multisite() ) {
		// WordPress Multisite active plugins
		echo "\n" . '-- Network Active Plugins' . "\n\n";

		$plugins = wp_get_active_network_plugins();
		$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

		foreach( $plugins as $plugin_path ) {
			$plugin_base = plugin_basename( $plugin_path );

			if( !array_key_exists( $plugin_base, $active_plugins ) )
				continue;

			$plugin  = get_plugin_data( $plugin_path );
			echo $plugin['Name'] . ': ' . $plugin['Version'] . ' ' .$plugin['Author'] .' ' .$plugin['PluginURI'] ."\n";
		}
	}
?>

## Server Environment ##

Server Info:              <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>
Host:                     <?php echo sss_get_host() . "\n"; ?>
Default Timezone:         <?php echo date_default_timezone_get() . "\n"; ?>
<?php
if ( $wpdb->use_mysqli ) {
	$mysql_ver = @mysqli_get_server_info( $wpdb->dbh );
} else {
	$mysql_ver = @mysql_get_server_info();
}
?>
MySQL Version:            <?php echo $mysql_ver . "\n"; ?>

-- PHP Configuration

PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
PHP Time Limit:           <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
PHP Max Input Vars:       <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
PHP Safe Mode:            <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
PHP Upload Max Size:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Upload Max Filesize:  <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Arg Separator:        <?php echo ini_get( 'arg_separator.output' ) . "\n"; ?>
PHP Allow URL File Open:  <?php echo ini_get( 'allow_url_fopen' ) ? "Yes". "\n" : "No" . "\n"; ?>

-- PHP Extentions

DISPLAY ERRORS:           <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; ?><?php echo "\n"; ?>
SOAP Client:              <?php echo ( class_exists( 'SoapClient' ) ) ? 'Your server has the SOAP Client enabled.' : 'Your server does not have the SOAP Client enabled.'; ?><?php echo "\n"; ?>
SUHOSIN:                  <?php echo ( extension_loaded( 'suhosin' ) ) ? 'Your server has SUHOSIN installed.' : 'Your server does not have SUHOSIN installed.'; ?><?php echo "\n"; ?>

-- Session Configuration

Session:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; ?><?php echo "\n"; ?>
Session Name:             <?php echo esc_html( ini_get( 'session.name' ) ); ?><?php echo "\n"; ?>
Cookie Path:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); ?><?php echo "\n"; ?>
Save Path:                <?php echo esc_html( ini_get( 'session.save_path' ) ); ?><?php echo "\n"; ?>
Use Cookies:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>
Use Only Cookies:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>

-- Client Details:

<?php if ( isset( $_GET['simple_system_status'] ) ) {
	echo '// Browser of Current Viewer //';
	echo "\r\n\r\n";
} ?>
<?php echo $browser ; ?>
<?php if ( isset( $_GET['simple_system_status'] ) ) {
	echo "\r\n";
	echo '// End Browser of Current Viewer //';
	echo "\r\n\r\n";
} ?>

Client IP Address:        <?php echo sss_get_ip() . "\n"; ?>

## WC Qucik Donation Settings Information ##

Simple Donation Product Exist : <?php echo WC_QD()->donation_product_exist_public(); ?>

<?php

foreach(WC_QD()->settings()->settings_field as $setting){
    foreach($setting as $setK => $set){
        echo "\n ".'-- '.$setK.' Settings'. " \n"; 
        foreach($set as $s){
            $value = WC_QD()->settings()->get_option($s['id']);
            if(is_array($value)) {$value = json_encode($value);}
            if(empty($value)){$value = 'null';}
            echo $s['id'].' : '.$value." \n"; 
        }
    }
}
?>

## Template Override Information

<?php 
$override = WC_QD()->admin()->functions->get_OverRided();
foreach($override as $temp){
    $is_old = ''; 
    if($temp['is_old']) { $is_old = '[You are using an outdated version. kindly update it]'; }
    echo "".$temp['file']." || Core Version ".$temp['corev']." || Theme Version".$temp['themev']." $is_old". " \n"; 
}
?> 
### End WooCommerce Quick Donation Status ###
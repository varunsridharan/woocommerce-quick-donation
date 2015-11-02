<table cellspacing="0" class="wc_status_table widefat" id="status">
<thead><tr> <th colspan="2">WordPress Environment</th> </tr></thead>
<tbody>
<tr> <td > Home URL:   </td><td> <?php echo home_url(); ?></td> </tr> 
<tr> <td > Site URL:   </td><td> <?php echo site_url(); ?></td> </tr> 
<tr> <td > WP Version: </td><td> <?php echo get_bloginfo( 'version' ); ?></td> </tr> 
<tr> <td > WP_DEBUG:   </td><td> <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ?></td> </tr> 
<tr> <td > WP Language:           </td><td><?php echo ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ); ?><td></tr>
<tr> <td > Multisite:             </td><td><?php echo is_multisite() ? 'Yes' : 'No' ?><td></tr>
<tr> <td > WP Memory Limit:       </td><td><?php echo ( self::let_to_num( WP_MEMORY_LIMIT )/( 1024 ) )."MB"; ?><?php ; ?><td></tr>
<tr> <td > WP Memory Limit Status:</td><td><?php if ((WP_MEMORY_LIMIT)/(1024)>63){echo 'OK'; }else{echo 'Not OK - Recommended Memory Limit is 64MB'."\n";} ?><td></tr>
<tr> <td > WP Table Prefix:       </td><td><?php echo $wpdb->prefix ?><td></tr>
<tr> <td > WP Table Prefix Length:</td><td><?php echo strlen( $wpdb->prefix ) ?><td></tr>
<tr> <td > WP Table Prefix Status:</td><td><?php if ( strlen( $wpdb->prefix ) > 16 ) { echo 'ERROR: Too Long'; } else { echo 'Acceptable'; } ; ?><td></tr>
<tr> <td > WP Timezone:           </td><td><?php echo get_option('timezone_string') . ', GMT: ' . get_option('gmt_offset'); ?><td></tr>
<tr> <td > WP Remote Post:        </td><td><?php echo $WP_REMOTE_POST; ?><td></tr>
<tr> <td > Permalink Structure:   </td><td><?php echo get_option( 'permalink_structure' ); ?><td></tr>
<tr> <td > Registered Post Stati: </td><td><?php echo implode( ' <br/> ', get_post_stati() ); ?><td></tr>
<tr> <td > Show On Front:         </td><td><?php echo get_option( 'show_on_front' ) ?><td></tr>
<?php 
if( get_option( 'show_on_front' ) == 'page' ) { 
$front_page_id = get_option( 'page_on_front' );
$blog_page_id = get_option( 'page_for_posts' ); 
?>
<tr> <td > Page On Front:  </td><td><?php ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ); ?><td></tr>
<tr> <td > Page For Posts: </td><td><?php ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ); ?><td></tr>
<?php } ?>      
</tbody>
</table>

<?php $active_theme = wp_get_theme(); ?>
<table cellspacing="0" class="wc_status_table widefat" id="status">
<thead><tr> <th colspan="2">Theme Information</th> </tr></thead>
<tbody>
<tr> <td > Theme Name:             </td><td> <?php echo $active_theme->Name; ?></td> </tr> 
<tr> <td > Theme Version:          </td><td> <?php echo $active_theme->Version; ?></td> </tr> 
<tr> <td > Theme Author:           </td><td> <?php echo $active_theme->get('Author'); ?></td> </tr> 
<tr> <td > Theme Author URI:       </td><td> <?php echo $active_theme->get('AuthorURI'); ?></td> </tr> 
<tr> <td > Is Child Theme:         </td><td> <?php echo is_child_theme() ? 'Yes' : 'No'; ?> </td> </tr>
<?php if( is_child_theme() ) { $parent_theme = wp_get_theme( $active_theme->Template ); ?>
<tr> <td > Parent Theme:           </td><td> <?php echo $parent_theme->Name ?>        </td> </tr> 
<tr> <td > Parent Theme Version:   </td><td> <?php echo $parent_theme->Version; ?></td> </tr> 
<tr> <td > Parent Theme URI:       </td><td> <?php echo $parent_theme->get('ThemeURI'); ?></td> </tr> 
<tr> <td > Parent Theme Author URI:</td><td> <?php echo $parent_theme->{'Author URI'}; ?></td> </tr> 
<?php } ?>
</tbody>
</table> 

<?php 
    $plugin_output = '';
    $plugin_output .= '<tr> <th colspan="2">Must-Use Plugins</th></tr>';
    $muplugins = wp_get_mu_plugins();
    if( count( $muplugins > 0 ) ) {
        foreach( $muplugins as $plugin => $plugin_data ) {
            $plugin_output .=  '<tr> <td colspan="2"><a href="'.$plugin['PluginURI'].'" >'.$plugin['Name'].' </a>: || Version ' . $plugin['Version'] . ' || Author : ' .$plugin['Author']."</td> </tr>";
        }
    }

	if ( ! function_exists( 'get_plugins' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
    $plugin_output .= '<tr> <th colspan="2"> </th></tr>';
    $plugin_output .= '<tr> <th colspan="2">WordPress Active Plugins</th></tr>';
	$plugins = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );

	foreach( $plugins as $plugin_path => $plugin ) {
		if( !in_array( $plugin_path, $active_plugins ) )
			continue;

		$plugin_output .=  '<tr> <td colspan="2"><a href="'.$plugin['PluginURI'].'" >'.$plugin['Name'].' </a>: || Version ' . $plugin['Version'] . ' || Author : ' .$plugin['Author']."</td> </tr>";
	}
    $plugin_output .= '<tr> <th colspan="2"> </th></tr>';
    $plugin_output .= '<tr> <th colspan="2">WordPress Inactive Plugins</th></tr>';
	foreach( $plugins as $plugin_path => $plugin ) {
		if( in_array( $plugin_path, $active_plugins ) )
			continue;

		$plugin_output .=  '<tr> <td colspan="2"><a href="'.$plugin['PluginURI'].'" >'.$plugin['Name'].' </a>: || Version ' . $plugin['Version'] . ' || Author : ' .$plugin['Author']."</td> </tr>";
	}

if( is_multisite() ) {
        $plugin_output .= '<tr> <th colspan="2"> </th></tr>';
        $plugin_output .= '<tr> <th colspan="2">Network Active Plugins</th></tr>';
		$plugins = wp_get_active_network_plugins();
		$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
		foreach( $plugins as $plugin_path ) {
			$plugin_base = plugin_basename( $plugin_path );
			if( !array_key_exists( $plugin_base, $active_plugins ) )
				continue;

			$plugin  = get_plugin_data( $plugin_path );
			$plugin_output .=  '<tr> <td colspan="2"><a href="'.$plugin['PluginURI'].'" >'.$plugin['Name'].' </a>: || Version ' . $plugin['Version'] . ' || Author : ' .$plugin['Author']."</td> </tr>";
		}
	}
?>


<table cellspacing="0" class="wc_status_table widefat" id="status"> <thead><tr> <th colspan="2">Plugins Information</th> </tr></thead><tbody><?php echo $plugin_output; ?></tbody></table>

<?php if ( $wpdb->use_mysqli ) { $mysql_ver = @mysqli_get_server_info( $wpdb->dbh ); } else { $mysql_ver = @mysql_get_server_info(); } ?>
<table cellspacing="0" class="wc_status_table widefat" id="status"> 
<thead><tr> <th colspan="2">Server Environment</th> </tr></thead>
<tbody>
    
<tr> <td> Server Info:      </td><td> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
<tr> <td> Host:             </td><td> <?php echo sss_get_host(); ?></td></tr>
<tr> <td> Default Timezone: </td><td> <?php echo date_default_timezone_get(); ?></td></tr>
<tr> <td> MySQL Version: </td> <td> <?php echo $mysql_ver; ?></td></tr>

<tr> <td></td></tr>
<tr> <td colspan="2">PHP Configuration</td></tr> 
<tr> <td>PHP Version:             </td><td> <?php echo PHP_VERSION; ?></td></tr>
<tr> <td>PHP Post Max Size:       </td><td> <?php echo ini_get( 'post_max_size' ); ?></td></tr>
<tr> <td>PHP Time Limit:          </td><td> <?php echo ini_get( 'max_execution_time' ); ?></td></tr>
<tr> <td>PHP Max Input Vars:      </td><td> <?php echo ini_get( 'max_input_vars' ); ?></td></tr>
<tr> <td>PHP Safe Mode:           </td><td> <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?></td></tr>
<tr> <td>PHP Memory Limit:        </td><td> <?php echo ini_get( 'memory_limit' ); ?></td></tr>
<tr> <td>PHP Upload Max Size:     </td><td> <?php echo ini_get( 'upload_max_filesize' ); ?></td></tr>
<tr> <td>PHP Upload Max Filesize: </td><td> <?php echo ini_get( 'upload_max_filesize' ); ?></td></tr>
<tr> <td>PHP Arg Separator:       </td><td> <?php echo ini_get( 'arg_separator.output' ); ?></td></tr>
<tr> <td>PHP Allow URL File Open: </td><td> <?php echo ini_get( 'allow_url_fopen' ) ? "Yes". "\n" : "No"; ?></td></tr>

<tr> <td colspan="2">PHP Extentions</td></tr>  
<tr><td> DISPLAY ERRORS:</td><td><?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php ; ?></td></tr>
<tr><td> FSOCKOPEN: </td><td><?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php ; ?></td></tr>
<tr><td> cURL: </td><td><?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; ?><?php ; ?></td></tr>
<tr><td> SOAP Client: </td><td><?php echo ( class_exists( 'SoapClient' ) ) ? 'Your server has the SOAP Client enabled.' : 'Your server does not have the SOAP Client enabled.'; ?><?php ; ?></td></tr>
<tr> <td> SUHOSIN: </td><td><?php echo ( extension_loaded( 'suhosin' ) ) ? 'Your server has SUHOSIN installed.' : 'Your server does not have SUHOSIN installed.'; ?><?php ; ?></td></tr>
 
<tr> <td colspan="2">Session Configuration</td></tr>  
<tr><td>Session:          </td><td><?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; ?><?php ; ?></td></tr>
<tr><td>Session Name:     </td><td><?php echo esc_html( ini_get( 'session.name' ) ); ?><?php ; ?></td></tr>
<tr><td>Cookie Path:      </td><td><?php echo esc_html( ini_get( 'session.cookie_path' ) ); ?><?php ; ?></td></tr>
<tr><td>Save Path:        </td><td><?php echo esc_html( ini_get( 'session.save_path' ) ); ?><?php ; ?></td></tr>
<tr><td>Use Cookies:      </td><td><?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; ?><?php ; ?></td></tr>
<tr><td>Use Only Cookies: </td><td><?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; ?><?php ; ?></td></tr>

<tr> <td colspan="2">Client Details</td></tr>  
<?php echo '<tr><td>Browser</td><td>'; ?> 
<?php echo $browser ;  ?>
<?php echo "</td></tr>"; ?>
<tr><td> Client IP Address:  </td><td> <?php echo sss_get_ip(); ?></td></tr>
</tbody>
</table>

<table cellspacing="0" class="wc_status_table widefat" id="status"> 
<thead><tr> <th colspan="2">Plugin Settings Information</th> </tr></thead>
<tbody>
    <tr>
        <td>Simple Donation Product Exist</td>
        <td><?php echo WC_QD()->donation_product_exist_public(); ?></td>
    </tr>

<?php
//$settings = WC_QD()->settings()->get_settings();
//foreach($settings as $id => $setting){
//    $value = $setting;
//    if(is_array($setting)){$value = json_encode($setting);}
//    echo '<tr><td>'.$id .' </td><td> '.$value."</td></tr>";
//}

foreach(WC_QD()->settings()->settings_field as $setting){
    foreach($setting as $setK => $set){
        echo '<tr> <th colspan="2">'.$setK.' Settings</th> </tr>'; 
        foreach($set as $s){
            $value = WC_QD()->settings()->get_option($s['id']);
            if(is_array($value)) {$value = json_encode($value);}
            if(empty($value)){$value = 'null';}
            echo '<tr> <td colspan="1">'.$s['id'].'</td> <td>'.$value.'</td></tr>'; 
        }
    }
}
?>
    
<tr> <th colspan="2"> </th> </tr>
<tr> <th colspan="2">Template Override Information</th> </tr>
<tr> <th> Template Name</th> <th> Version Info</th></tr>
<?php 
$override = WC_QD()->admin()->functions->get_OverRided();
foreach($override as $temp){
    $is_old = '';
    $cls = '';
    if($temp['is_old']) {
        $is_old = '<small style="color:red; margin-left:10px; font-weight:bold;">You are using an outdated version. kindly update it</small>';
        $cls = 'style="color:red; font-weight:bold;"';
    }
    echo '<tr> <td '.$cls.' >'.$temp['file'].'</td> <td>   Core Version : <code>'.$temp['corev'].'</code> 
                                                 Theme Version : <code>'.$temp['themev'].' </code> '.$is_old.' </td> </tr>';
}
?>
</tbody>
</table> 
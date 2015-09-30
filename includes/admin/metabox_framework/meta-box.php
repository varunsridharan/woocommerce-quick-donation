<?php
/*
Plugin Name: Meta Box
Plugin URI: http://metabox.io
Description: Create meta box for editing pages in WordPress. Compatible with custom post types since WP 3.0
Version: 4.5.7
Author: Rilwis
Author URI: http://www.deluxeblogtips.com
License: GPL2+
Text Domain: meta-box
Domain Path: /lang/
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Script version, used to add version for scripts and styles
define( 'WCQD_METABOX_VER', '4.5.7' );

// Define plugin URLs, for fast enqueuing scripts and styles
if ( ! defined( 'WCQD_METABOX_URL' ) )
	define( 'WCQD_METABOX_URL', WC_QD_ADMIN_URL.'metabox_framework/');
define( 'WCQD_METABOX_JS_URL', trailingslashit( WCQD_METABOX_URL . 'js' ) );
define( 'WCQD_METABOX_CSS_URL', trailingslashit( WCQD_METABOX_URL . 'css' ) );

// Plugin paths, for including files
if ( ! defined( 'WCQD_METABOX_DIR' ) )
	define( 'WCQD_METABOX_DIR', WC_QD_ADMIN.'metabox_framework/' );
define( 'WCQD_METABOX_INC_DIR', trailingslashit( WCQD_METABOX_DIR . 'inc' ) );
define( 'WCQD_METABOX_FIELDS_DIR', trailingslashit( WCQD_METABOX_INC_DIR . 'fields' ) );


require_once WCQD_METABOX_INC_DIR . 'common.php';
require_once WCQD_METABOX_INC_DIR . 'field.php';
require_once WCQD_METABOX_INC_DIR . 'field-multiple-values.php';

// Field classes
foreach ( glob( WCQD_METABOX_FIELDS_DIR . '*.php' ) as $file )
{
	require_once $file;
}

// Meta box class
require_once WCQD_METABOX_INC_DIR . 'meta-box.php';

// Helper function to retrieve meta value
require_once WCQD_METABOX_INC_DIR . 'helpers.php';

// Main file
require_once WCQD_METABOX_INC_DIR . 'init.php';

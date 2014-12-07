<?php
/*  Copyright 2014  Varun Sridharan  (email : varunsridharan23@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/ 
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$options = array('wc_quick_donation_redirect','wc_quick_donation_cart_remove','wc_quick_donation_projects','wc_quick_donation_project_section_title','wc_quick_donation_order_notes_title');
foreach($options as $option_name){
	delete_option( $option_name );
	delete_site_option( $option_name );  
}
?>



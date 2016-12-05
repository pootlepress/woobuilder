<?php
/*
Plugin Name: Pootle Pagebuilder Product Builder
Plugin URI: http://pootlepress.com/
Description: Boilerplate for fast track Pootle Page Builder Addon Development
Author: Shramee
Version: 1.0.0
Author URI: http://shramee.com/
@developer shramee <shramee.srivastav@gmail.com>
*/
/** Plugin admin class */
require 'inc/class-admin.php';
/** Plugin public class */
require 'inc/class-public.php';
/** Including Main Plugin class */
require 'class-ppb-product-builder.php';
/** Intantiating main plugin class */
PPB_Product_Builder::instance( __FILE__ );

/** Addon update API */
add_action( 'plugins_loaded', 'PPB_Product_Builder_api_init' );

/**
 * Instantiates Pootle_Page_Builder_Addon_Manager with current add-on data
 * @action plugins_loaded
 */
function PPB_Product_Builder_api_init() {
	//Return if POOTLEPB_DIR not defined
	if ( ! defined( 'POOTLEPB_DIR' ) ) { return; }
	/** Including PootlePress_API_Manager class */
	require_once POOTLEPB_DIR . 'inc/addon-manager/class-manager.php';
	/** Instantiating PootlePress_API_Manager */
	new Pootle_Page_Builder_Addon_Manager(
		PPB_Product_Builder::$token,
		'Pootle Pagebuilder Product Builder',
		PPB_Product_Builder::$version,
		PPB_Product_Builder::$file,
		PPB_Product_Builder::$token
	);
}

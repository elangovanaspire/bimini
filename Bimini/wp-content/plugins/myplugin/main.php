<?php

/* 
 * Plugin Name: Myplugin
 * 
 */

function myplugin_api_init( $server ) {
	global $myplugin_api_mytype;

	require_once dirname( __FILE__ ) . '/class-myplugin-api-mytype.php';
	$myplugin_api_mytype = new MyPlugin_API_MyType( $server );
	$myplugin->register_filters();
}
add_action( 'wp_json_server_before_serve', 'myplugin_api_init' );
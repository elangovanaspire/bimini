<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MyPlugin_API_MyType extends WP_JSON_CustomPostType {
	protected $base = '/myplugin/mytypeitems';
	protected $type = 'myplugin-mytype';
        
	public function register_routes( $routes ) {      
            print "hi i'm here". $base; exit();
		$routes = parent::register_routes( $routes );
		// $routes = parent::register_revision_routes( $routes );
		// $routes = parent::register_comment_routes( $routes );

		// Add more custom routes here

		return $routes;
	}

	// ...
}
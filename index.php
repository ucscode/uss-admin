<?php 
/**
 * User Synthetics Admin Module
 *
 * This is a control panel module that utilizes the uss-dashboard module.
 * It enable adminstrators to manage website options and users without coding
 *
 * `ua` represents uss admin
 *
 * @version 1.2.0
 * @author ucscode
 * @copyright Copyright (c) 2023
 * @project uss.admin
 */
 
defined( 'ROOT_DIR' ) OR DIE;

define( 'UADMIN_DIR', __DIR__ );
define( 'UADMIN_ROUTE', 'admin' );


/**
 * Admin module will not work in absence of `uss-dashboard` module
 * Add a listener to the `udash:ready` event 
 */
Events::addListener('udash:ready', function() {
	
	/**
	 * Prepare The Admin Resource:
	 */
	$resources = array(
		"uadmin.php",
		"configure.php"
	);
	
	/**
	 * Include files
	 */
	foreach( $resources as $file ) require_once UADMIN_DIR . "/ua-resources/{$file}";
	
	/**
	 * Load Admin Programs
	 */
	Udash::load( UADMIN_ROUTE, Uadmin::RES_DIR . '/load-admin-pages.php' );
	
	/**
	 * Set hook for modules that wishes to load after the admin module;
	 */
	Events::exec('uadmin:ready');
	
}, EVENT_ID . 'uadmin' );

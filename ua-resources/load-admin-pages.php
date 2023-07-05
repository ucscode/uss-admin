<?php

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;


# Configure universal pages path

Udash::config( 'page:notification', UADMIN_ROUTE . '/notifications' );
Udash::config( 'page:signout', UADMIN_ROUTE . '/signout' );
Udash::config( 'signout:redirect', Core::url( ROOT_DIR . '/' . UADMIN_ROUTE ) );


# Customize The Admin Interface

require UADMIN_DIR . '/ua-style.php';


# Handle User Authentication

call_user_func(function() {
	
	$user = Uss::$global['user'];

	if( !$user || Udash::is_ajax_mode() ) {
		return;
	};

	# Check if user has permission to view the admin panel;
	
	$permit = Roles::user( $user['id'] )::hasPermission('view-cpanel');
	
	# Authorize User;

	if( $permit ) Udash::config( 'auth', true );

	else {
		
		Uss::view(function() {

			# Restrict further entry into the admin panel

			require Uadmin::VIEW_DIR . '/restricted.php';

		});
		
		die();
		
	};
	
});

# Hook into the admin ready event

Events::addListener('uadmin:ready', function() {
	
	# Require Admin Pages!
	
	require Uadmin::PAGES_DIR . '/index.php';
	require Uadmin::PAGES_DIR . '/users/index.php';
	require Uadmin::PAGES_DIR . '/tools.php';
	require Uadmin::PAGES_DIR . '/settings/index.php';

}, EVENT_ID );
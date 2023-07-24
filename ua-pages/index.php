<?php

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;


# Create The Overview Menu!

Uss::$global['menu']->add('_cpanel', array(
	"label" => "Overview",
	"icon" => "<i class='bi bi-columns-gap'></i>",
	'href' => Core::url( ROOT_DIR . '/' . UADMIN_ROUTE ),
	'active' => ( implode("/", Uss::query()) == UADMIN_ROUTE ),
	'order' => 0
));


# Stay Focused!

Uss::route( UADMIN_ROUTE, function() {
	
	Udash::view(function() {
		
		/**
		 * The index page is empty
		 * A module needs to fill it up by adding an event listener
		 */
		 
		Events::exec( 'uadmin:pages/index' );
		
	});
	
});


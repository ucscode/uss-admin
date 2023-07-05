<?php

# Secure Entry

defined( 'UADMIN_MOD_DIR' ) OR DIE;


# Create The Overview Menu!

Uss::$global['menu']->add('_cpanel', array(
	"label" => "Overview",
	"icon" => "<i class='bi bi-columns-gap'></i>",
	'href' => Core::url( ROOT_DIR . '/' . UADMIN_FOCUS_URI ),
	'active' => ( implode("/", Uss::query()) == UADMIN_FOCUS_URI )
));


# Stay Focused!

Uss::route( UADMIN_FOCUS_URI, function() {
	
	Udash::view(function() {
		
		/**
		 * The index page is empty
		 * A module needs to fill it up by adding an event listener
		 */
		 
		Events::exec( 'uadmin:page//index' );
		
	});
	
});


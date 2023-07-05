<?php 

defined( 'UADMIN_DIR' ) OR DIE;

call_user_func(function() {
	
	if( $_SERVER['REQUEST_METHOD'] !== 'POST' ) return;
	
	if( !Uss::nonce( 'email', $_POST['nonce'] ) ) return Uss::console( '@alert', "Security Check Failed!" );
	
	/**
	 * Update Email Configuration
	 */
	Events::addListener( 'uadmin:pages/settings/email.submit', function() {
			
		$progress = [];
		
		foreach( ['email', 'smtp'] as $key ) {
			foreach( $_POST[$key] as $name => $value ) {
				$progress[] = Uss::$global['options']->set( "{$key}:{$name}", $value );
			};
		};
		
		$status = !in_array(false, $progress);
		
		if( $status ) $message = "<i class='bi bi-check-circle text-success me-1'></i> Changes were saved";
		else {
			$message = "<i class='bi bi-x text-danger me-1'></i> Changes were not saved";
		};
		
		Uss::console( '@alert', $message );
		
	}, EVENT_ID . 'email' ); // [{ End Email Event }] 
	
	
	/**
	 * Event POST
	 */
	Events::exec( 'uadmin:pages/settings/email.submit' );

});
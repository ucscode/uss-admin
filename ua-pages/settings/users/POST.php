<?php 

defined( 'UADMIN_DIR' ) OR DIE;

call_user_func(function() {
	
	if( $_SERVER['REQUEST_METHOD'] != 'POST' ) return;
		
	if( !Uss::nonce( 'users', $_POST['nonce'] ) ) return Uss::console( '@alert', "Security Check Failed!" );

	/**
	 * Event Listener
	 */
	Events::addListener( 'uadmin:pages/settings/users.submit', function() {
		
		$progress = [];
		
		foreach( $_POST['user'] as $key => $value ) 
			$progress[] = Uss::$global['options']->set( "user:{$key}", $value );
		
		Uss::$global['status'] = $status = !in_array( false, $progress );
		
		if( $status ) {
			Uss::$global['message'] = "<i class='bi bi-check-circle text-success'></i> Changes were saved";
		} else {
			Uss::$global['message'] = "<i class='bi bi-x text-danger'></i> Changes were not saved!";
		};
		
		Uss::console( '@alert', Uss::$global['message'] );
	
	}, EVENT_ID . 'submit' ); // [{ End Post Event }];
		
		
	// -------- [{ Exec Event }] -------
	
	Events::exec( 'uadmin:pages/settings/users.submit' );

});
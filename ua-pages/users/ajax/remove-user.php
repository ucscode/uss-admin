<?php

defined( 'UADMIN_DIR' );

/**
 * Remove a user from database!
 */
Events::addListener('udash:ajax', function() {
	
	if( $_POST['route'] != 'ua-delete-user' ) return;
	
	try {
		
		/**
		 * Security Check
		 */
		if( !Uss::nonce( $_SESSION['uss_session_id'], $_POST['nonce'] ) ) throw new \Exception( "Security Check Failed" );
		
		/**
		 * Check Logged in user
		 */
		else if( empty(Uss::$global['user']) ) throw new \Exception( "Account is signed out" );
		
		/**
		 * Get undeleted user
		 */
		$prefix = DB_TABLE_PREFIX;

		$user = Udash::fetch_assoc( "{$prefix}_users", $_POST['userid'] ?? null );
		
		/** 
		 * Confirm if user exists
		 */
		if( !$user ) throw new \Exception( "The account was not found" );
		
		/**
		 * Confirm account ownership
		 */
		else if( $user['id'] == Uss::$global['user']['id'] ) throw new \Exception( "You cannot delete your own account!" );
			
		/** 
		 * Check if user has permission to delete
		 */
		$hasPermission = Roles::user( Uss::$global['user']['id'] )::hasPermission( 'manage-users');
		
		if( !$hasPermission ) throw new \Exception( "You do not have sufficient permission to take this action" );
				
		/**
		 * Compare user authorities
		 */
		$userAuth = Roles::user( $user['id'] )::authority();
		$selfAuth = Roles::user( Uss::$global['user']['id'] )::authority();
		
		if( $selfAuth <= $userAuth ) throw new \Exception( "You cannot delete an account with greater or same authority as you" );
		
		/** 
		 * Delete the user;
		 */
		$status = Udash::delete_user( $user['id'] );
			
		$msg = [
			"<i class='bi bi-person-dash fs-28px text-success'></i> <br> The account was successfully deleted",
			"<i class='bi bi-person-exclamation fs-28px text-warning'></i> <br> Something went wrong! <br> The account was not deleted"
		];
		
		/**
		 * End the script
		 */
		Uss::exit( empty($status) ? $msg[1] : $msg[0] , !empty($status));
		

	} catch( Exception $e ) {
		
		/**
		 * Display Error Message
		 */
		Uss::exit( "<i class='bi bi-x-octagon me-2 text-danger fs-28px'></i> <br>" . $e->getMessage() , false);
		
	}

}, EVENT_ID . 'uajax-remove' ); 



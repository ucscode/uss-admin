<?php

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * 
 */
call_user_func(function() {
	
	if( $_SERVER['REQUEST_METHOD'] == 'GET' ) return;
	
	# Security Error Message
	
	$securityErrorMsg = "
		<div class='d-flex'>
			<i class='bi bi-shield-fill-exclamation me-2'></i>
			<div>Security Check Failed!</div>
		</div>
	";
	
	# Handle Bulk Request
	
	Events::addListener('uadmin:pages/users.submit', function() use($securityErrorMsg) {
		
		# IF IT AIN'T A BULK REQUEST, IGNORE
		
		if( empty($_POST['ud-bulk']) ) return;
		
		# BULK VARIABLE
		
		$bulk = $_POST['ud-bulk'];
		
		# Validate Nonce
		
		if( !Uss::nonce( $bulk['table'], $bulk['nonce'] ) ) Uss::console( '@alert', $securityErrorMsg );
			
		else {
			
			switch( $bulk['action'] ) {
				
				/**
				 * ON DELETE REQUEST
				 */
				case 'delete':
						
						/**
						 * Check if user has permission to manage / delete users
						 * Send a negative response it permission is not granted
						 */ 
						$authorized = Roles::user( Uss::$global['user']['id'] )::hasPermission( 'manage-users' );
						
						if( !$authorized ) {

							Uss::console( '@alert', "
								<i class='bi bi-lock fs-20px me-1'></i> You do not have sufficient permission to execute the request
							" );

							break;

						};
						
						/**
						 * Get the highest authority / priority index of the current user
						 */
						$selfAuth = Roles::user( Uss::$global['user']['id'] )::authority();
						
						/**
						 * An array to count deleted users
						 */
						$deleted = array();
						
						foreach( $bulk['values'] as $userid ) {

							/**
							 * Get the highest authority index of the user that needs to be deleted
							 */
							$userAuth = Roles::user( $userid )::authority();
							
							/**
							 * If the authority of the undeleted user is less that the authority of the current user
							 * Then, delete the user
							 */
							if( $userAuth < $selfAuth ) $deleted[] = Udash::delete_user( $userid );

						};
						
						$deleted = array_filter($deleted);
						
						$message = "<i class='bi bi-trash3 me-2'></i> &mdash; ";
						$message .= count($deleted) . " out of " . count( $bulk['values'] ) . " accounts were deleted";
						
						Uss::console( '@alert', $message );
						
					break;
				
			}
			
		};
		
	}, EVENT_ID . 'bulk' );
	
	
	/**
	 * Handle a single action
	 */
	Events::addListener('uadmin:pages/users.submit', function() use($securityErrorMsg) {
		
		/**
		 * If it's a bulk request, ignore
		 */
		if( isset($_POST['ud-bulk']) ) return;
		
		/**
		 * 
		 */
		if( !Uss::nonce( $_POST['table'], $_POST['nonce'] ) ) Uss::console('@alert', $securityErrorMsg );
		
		else {
			
			switch( $_POST['action'] ):
				
				case 'delete':
				
					$userid = $_POST['userid'];
					
					/**
					 * Highest authority index of current user
					 */
					$selfAuth = Roles::user( Uss::$global['user']['id'] )::authority();
					
					/**
					 * Highest authority index of undeleted user
					 */ 
					$userAuth = Roles::user( $userid )::authority();
					
					/**
					 * If undeleted user authority is lower, delete the user
					 */
					if( $userAuth < $selfAuth ) {
						
						$status = Udash::delete_user( $userid );
						
						if( $status ) {
							$message = "<i class='bi bi-person-fill-x fs-20px me-1'></i> &mdash; Account deleted successfully";
						} else {
							$message = "<i class='bi bi-x-lg fs-20px me-1 text-danger'></i> &mdash; Account could not be deleted";
						};
						
						Uss::console( '@alert', $message );
						
					} else {
						Uss::console( '@alert', "
							<i class='bi bi-person-slash fs-20px me-1'></i> &mdash; You can neither delete your account nor a highly authorized account." );
					}
				
				break;
				
			endswitch;
		
		};
		
	}, EVENT_ID . 'single' );
	
	/**
	 * Execute Post Requests
	 */
	Events::exec('uadmin:pages/users.submit');
	
});

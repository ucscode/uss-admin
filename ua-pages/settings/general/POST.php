<?php 

defined( 'UADMIN_DIR' ) or DIE;

call_user_func(function() {
	
	if( $_SERVER['REQUEST_METHOD'] != 'POST' ) return;
		
	if( Uss::nonce( 'general', $_POST['nonce'] ) ) {
		
		$file = Udash::uploadFile( 'image/*', $_FILES['icon'], "images/general" );
		
		if( $file ) $_POST['site']['icon'] = $file;
		
		/**
		 * Create Post Event
		 */
		Events::addListener( 'uadmin:pages/settings/general.submit', function() {
			
			$progress = [];
			
			foreach( $_POST['site'] as $key => $value ) {
				$name = "site:{$key}";
				$progress[] = Uss::$global['options']->set( $name, $value );
			};
			
			if( isset($_POST['options']) ) {
				foreach( $_POST['options'] as $group => $data ) {
					if( !is_array($data) ) {
						continue;
					};
					foreach( $data as $key => $value ) {
						$name = "{$group}:{$key}";
						$progress[] = Uss::$global['options']->set( $name, $value );
					};
				};
			};

			Uss::$global['status'] = $status = !in_array(false, $progress);
			
			if( $status ) Uss::$global['message'] = "<i class='bi bi-check-circle text-success me-1'></i> Changes were saved";
			else Uss::$global['message'] = "<i class='bi bi-x text-danger me-1'></i> Changes were not saved";
			
			Uss::console( '@alert', Uss::$global['message'] );
		
		}, EVENT_ID ); // [{ End Post Event }]
		
		
		// ---------- [{ Exec Event }] ----------
		
		Events::exec( 'uadmin:pages/settings/general.submit' );
		
		
		Udash::refresh_site_vars();
		
		
	} else Uss::console( '@alert', "Security check failed!" );

});
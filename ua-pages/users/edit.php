<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Focus On The User List
 */
Uss::route( $userFocus . "/(\w+)", function($match) use($ulistMenu) {
	
	/**
	 * Get the usercode
	 */
	$usercode = $match[1];
	
	/** 
	 * Keep the list menu active
	 */
	$ulistMenu->setAttr('active', true);
	
	/**
	 * Define table prefix
	 */
	$prefix = DB_TABLE_PREFIX;
	
	/**
	 * Get user by the usercode
	 */
	$user = Udash::fetch_assoc( "{$prefix}_users", $usercode, 'usercode' );
	
	/**
	 *
	 */
	if( Uss::$global['user'] ) {
		
		/**
		 * The ajax execution file
		 */
		Uss::console( 'ua-ajax', Core::url( Udash::AJAX_DIR . "/@ajax.php" ) );
		
		/** 
		 * Include JavaScript File
		 * If user login session has not expired, add javascript codes;
		 * This is to prevent the javascript link from displaying in the login page if authentication has failed
		 */
		Events::addListener('@body:after', function() {
			$src = Core::url( __DIR__ . '/js/user-edit.js' );
			echo "\t<script src='{$src}'></script>\n";
		});
	
		/** 
		 * Handle POST Request
		 */
		require __DIR__ . '/POST/manage-edit.php';
		
	};
	
	
	/**
	 * Display the dashboard;
	 */
	Udash::view(function() use($user, $ulistMenu) {
		
		if( empty($user) ) {
			
			Events::addListener('uadmin:pages/users/edit', function() use($ulistMenu) {

				Udash::empty_state(function() use($ulistMenu) {
					$href = $ulistMenu->getAttr('href');
					$info = "
						<div class='mb-3'>It seems the user has been removed</div>
						<a href='{$href}' class='btn btn-primary'>
							<i class='bi bi-people me-1'></i> Back To List
						</a>
					";
					echo trim($info);
				});
			
			}, EVENT_ID );
			
		} else {
			
			/**
			 * Design Edit Page Template
			 */
			Events::addListener('uadmin:pages/users/edit', function($user) {
				
				$userRoles = Roles::user( $user['id'] )::get_user_roles();
				
				$self = ( Uss::$global['user']['id'] === $user['id'] );
				
				/**
				 * Load user detail into Template Tags
				 */
				array_walk($user, function($value, $key) {
					Uss::tag("user.{$key}", $value);
				});
				
				Uss::tag('col.left', 'col-sm-12 col-md-7 col-lg-8', false);
				Uss::tag('col.right', 'col-sm-12 col-md-5 col-lg-4', false);
				
				require __DIR__ . '/SECTIONS/template-user-edit.php';
				
			}, EVENT_ID );
			
		};

		/**
		 * Execute Edit Event
		 */
		Events::exec('uadmin:pages/users/edit', $user);
		
	});
	
}, null);
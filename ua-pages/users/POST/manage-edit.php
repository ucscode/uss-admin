<?php 

defined( 'UADMIN_DIR' ) OR DIE;


call_user_func(function() use(&$user) {
	
	if( $_SERVER['REQUEST_METHOD'] == 'GET' || empty($user) ) return;
	
	/**
	 * Handle _POST Request
	 */
	Events::addListener('uadmin:pages/users/edit.submit', function() use(&$user) {
		
		/**
		 * STEP 1
		 * Validate _POST Source
		 */
		if( !Uss::nonce($user['usercode'], $_POST['nonce']) ):
			
			return Uss::console( '@alert', "
				<span class='bi bi-shield-exclamation me-1 fs-20px'></span> 
				Security Check Failed!
			" );
		
		endif;
		
		/**
		 * STEP 2
		 * Confirm user permission
		 */
		$canManageUsers = Roles::user( Uss::$global['user']['id'] )::hasPermission('manage-users');
		
		if( !$canManageUsers ):
			
			return Uss::console( '@alert', "
				<i class='bi bi-lock-fill me-1 fs-20px'></i> 
				You do not have the permission to complete the request!
			" );
			
		endif; 
		
		
		/**
		 * STEP 3
		 * SET TRY / CATCH BLOCK
		 * Exception Handling
		 */
		try {
			
			/**
			 * STEP 4
			 * Create an Anonymous Class to specifically handle each index of the `$_POST` variable
			 */
			$AnonymousClass = new class($user) {
					
				/**
				 * ARRAY TO RECORD PROGRESS
				 */
				public $user;
				public $prefix = DB_TABLE_PREFIX;
				
				/**
				 *
				 */
				public function __construct(&$user) {
					
					$this->user = $user;
					$this->captureUploadedImage();
					
					/**
					 * Call Associated Method
					 */
					foreach( $_POST as $key => &$value ) {
						$methodName = $key . "Query";
						if( method_exists($this, $methodName) ) {
							if( $key != 'password' ) $value = $this->sanitize($value);
							$this->{$methodName}($value);
						}
					};
					
				}
				
				private function sanitize(&$data) {
					if( !is_array($data) ) $data = Uss::$global['mysqli']->real_escape_string(trim($data));
					else array_walk_recursive($data, function(&$value) {
						$value = Uss::$global['mysqli']->real_escape_string(trim($value));
					});
					return $data;
				}
				
				/**
				 *
				 */
				protected function captureUploadedImage() {
					
					if( !isset($_FILES['avatar']) ) return;
						
					/**
					 * HANDLE UPLOADED IMAGE
					 */
					$upload = Udash::uploadFile( 
					
						'image/jpg|png|gif|webp|jpeg',  // mime type
						
						$_FILES['avatar'], // uploaded file
						
						"images/profile", // upload directory
						
						$this->user['id'] . '-' // filename prefix
						
					);
					
					/**
					 * Save uploaded image path into $_POST['meta'] 
					 * Making it ready for update into database
					 */
					$_POST['meta'] = $_POST['meta'] ?? [];
					
					if( $upload ) $_POST['meta']['avatar'] = $upload;
					
				}
				
				/**
				 * HANDLE USER DATA
				 */
				protected function userQuery($data) {
					
					/**
					 * Regexp to validate Username & Email
					 */
					$regexp = array( 
						'username' => "/^\w+$/i", 
						'email' => Core::regex('email', true) 
					);
					
					/**
					 * Walk Through User Data & process the validation
					 */
					array_walk($data, function(&$value, $key) use($regexp, &$error) {
						
						if( !in_array($key, array_keys($regexp)) ) return;
						
						$value = strtolower($value);
						
						if( !preg_match($regexp[$key], $value) ) {
							if( $key == 'username' && empty( $value  ) ) $value = null;
							else {
								throw new \Exception( "The {$key} is invalid" );
							};
						}
						
					});
					
					/**
					 * Update User Table
					 */
					$SQL = SQuery::update( "{$this->prefix}_users", $data, "usercode = '{$this->user['usercode']}'" );
					
					$status = Uss::$global['mysqli']->query( $SQL );
					
					if( $status ) return;
					
					/**
					 * If error occured, check possible cause
					 */
					foreach( array_keys($regexp) as $key ):
						$SQL = SQuery::select( "{$this->prefix}_users", "{$key} = '{$data[$key]}' AND usercode <> '{$this->user['usercode']}'" );
						$exists = Uss::$global['mysqli']->query( $SQL )->num_rows;
						if( $exists ) throw new \Exception( "The {$key} already exists" );
					endforeach;
						
				}
				
				/**
				 * UPDATE META
				 */
				protected function metaQuery($data) {
					foreach( $data as $name => $value ) {
						$status = Uss::$global['usermeta']->set( $name, $value, $this->user['id'] );
					};
						
				}
				
				/**
				 * UPDATE USER ROLE
				 */
				protected function rolesQuery($data) {
					/** 
					 * Get user roles
					 */
					$roles = Roles::user( $this->user['id'] )::get_user_roles();
					
					/**
					 * Clear all user roles
					 */
					foreach( $roles as $role ) Roles::user( $this->user['id'] )::unassign( $role );
					
					/** 
					 * Assign new roles
					 */
					$status = Roles::user( $this->user['id'] )::assign( $data );
					
				}
				
				/**
				 * UPDATE PASSWORD
				 */
				protected function passwordQuery($value) {
					/**
					 * If password is not set, ignore
					 */
					if( empty($value) ) return;
					
					/**
					 * Create HASH Password
					 */
					$password = Udash::password( $value );
					
					/**
					 * Update SQL Query
					 */
					$SQL = SQuery::update( 
						"{$this->prefix}_users", 
						array( 'password' => $password ), 
						"usercode = '{$this->user['usercode']}'" 
					);
					$status = Uss::$global['mysqli']->query( $SQL );
					
					/**
					 * Password update failed
					 */
					if( !$status ) throw new \Exception( "Account password failed to update" );
					
					/**
					 * Update Access Token for current user
					 */
					if( $this->user['id'] == Uss::$global['user']['id'] ) Udash::setAccessToken( $this->user['id'] );
					
					/**
					 * Notify Admin Of The Change
					 */
					Events::addListener('uadmin:pages/users/edit.left', function() use($value) {
						
						$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
						
						$message = "Account password changed to &mdash; <span class='text-danger'>{$value}</span>";
						
						echo "<div class='alert alert-info'>
								<i class='bi bi-unlock me-2'></i> {$message}
							</div>";
						
					}, 'POST:Password' );
						
				}
				
				/**
				 * HANDLE ACTION
				 */
				protected function actionQuery($data) {
					foreach( $data as $key => $value ) {
						if( $key == 'confirm' ) Uss::$global['usermeta']->remove( 'v-code', $this->user['id'] );
					}
				}
				
			};
			
			/**
			 * Output Success Message
			 *
			 * Every error throws an exception
			 * If no exception is thrown up till this point
			 * The update was successful
			 */
			Events::addListener( 'uadmin:pages/users/edit.left', function() {
				echo "<div class='alert alert-success mb-5'>
						<i class='bi bi-check-circle me-2'></i> Account successfully updated
					</div>";
			}, 'POST:Success' );
			
			
		} catch( Exception $e ) {
			
			
			/**
			 * Disclose Exception Message
			 */
			Events::addListener('uadmin:pages/users/edit.left', function() use($e) {
				echo "<div class='alert alert-danger'>
						<i class='bi bi-exclamation-triangle me-2'></i> {$e->getMessage()}
					</div>";
			}, 'POST:Exception' );
			
		};
		
		
		/**
		 * Refresh User Variable
		 */
		$user = Udash::fetch_assoc( DB_TABLE_PREFIX . "_users", $user['id'] );
		
			
	}, EVENT_ID );
	
	/**
	 * Execute Submit Event
	 */
	Events::exec('uadmin:pages/users/edit.submit', $user);

});
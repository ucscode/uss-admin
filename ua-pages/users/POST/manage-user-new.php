<?php 

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * We wrap this into a self executing function
 * This is in order to terminate the request by a return statement if not valid
 */
call_user_func(function() {
		
	if( $_SERVER['REQUEST_METHOD'] == 'GET' || empty(Uss::$global['user']) ) return;

	Events::addListener('uadmin:pages/users/new.submit', function() {
	
		try {
			
			# Security Check
			
			if( !Uss::nonce( 'user-new', $_POST['nonce'] ) ) {
				throw new \Exception( "Security Check Failed" );
			};
			
			# Execute Request

			new class() {
				
				public $user;
				public $meta;
				
				public $imageMIME = 'image/jpg|jpeg|png|webp';
				public $defaultRole;
				public $prefix = DB_TABLE_PREFIX;
				
				public $message = [];
				
				public $regexp;
				
				/**
				 * Constructor
				 */
				public function __construct() {
					/**
					 * Process New User Data
					 */
					$this->init();
					$this->handleUser();
					$this->handleMeta();
					$this->sendEmail();
					$this->output();
				}
				
				protected function init() {
					
					# Get default Role
					
					$this->defaultRole = Uss::$global['options']->get('user:default-role');
					
					# Regular Expression
					
					$this->regexp = array(

						// validate \w+ or empty string
						"username" => [ "/^(?:\w+)?$/i", "Invalid Username" ],

						// validate email
						"email" => [ Core::regex('email', true), "Invalid Email Address" ]

					);
					
				}
				
				protected function handleUser() {
					
					# Get the user data
					
					$this->user = $_POST['user'];
					
					array_walk_recursive($this->user, function(&$value, $key) {

						if( $key == 'password' ) return;
						
						# Sanitize input

						$value = Uss::$global['mysqli']->real_escape_string( trim($value) );

						if( in_array($key, array_keys($this->regexp)) ) {

							if( !preg_match($this->regexp[$key][0], $value) ) {
								throw new \Exception( $this->regexp[$key][1] );
							};

							$value = strtolower($value);

						};

					});
					
					# Generate New User Code;
					
					$this->user['usercode'] = Core::keygen(mt_rand(5,7));
					
					# HASH Password
					
					$this->rawPassword = $this->user['password'];
					$this->user['password'] = Udash::password( $this->rawPassword );
					
					if( empty($this->user['username']) ) $this->user['username'] = null;

					$this->pushDBUser(); // Add user to database
						
				}
				
				/**
				 * Add user to database
				 */
				protected function pushDBUser() {
					
					$SQL = SQuery::insert( "{$this->prefix}_users", $this->user );
					
					# Insert into database

					$insert = Uss::$global['mysqli']->query( $SQL );
					
					if( !$insert ) {
						
						# Check Reason For Error
						
						foreach( array_keys($this->regexp) as $key ) {
							
							if( empty($this->user[$key]) ) continue;
							
							$client = Udash::fetch_assoc( "{$this->prefix}_users", $this->user[$key], $key );
							
							if( $client ) throw new \Exception( "The {$key} already exists" );
							
						};
						
						throw new \Exception( "The account was not added" );
					};
					
					# Save User New ID
					
					$this->user['id'] = Uss::$global['mysqli']->insert_id;
					
					# repare Output Message
					
					$href = Core::url( ROOT_DIR . '/' . UADMIN_ROUTE . "/users/{$this->user['usercode']}" );
					
					$this->message[] = "<div class='d-flex'>
						<i class='bi bi-person-check fs-22px me-2'></i> 
						<div> 
							The user account was successfully added. <br>
							<a href='{$href}'>Click here</a> to manage the user account
						</div>
					</div>";
					
				}
				
				/**
				 * Add user meta data
				 */
				protected function handleMeta() {
					
					# Meta would contain avatar and role
					
					$this->meta = $_POST['meta'] ?? [];

					if( !is_array($this->meta) ) $this->meta = array();
					
					# However, user account must have been created
					
					if( empty($this->user['id']) ) return;
					
					# Check if a photo was uploaded along

					if( isset($_FILES['avatar']) ):

						/**
						 * Load avatar
						 * NB: Relative paths are directed to the Udash::ASSETS_DIR directory
						 */
						$avatar = Udash::uploadFile( $this->imageMIME, $_FILES['avatar'], "images/profile", $this->user['id'] );
						
						if( $avatar ) $this->meta['avatar'] = $avatar;
						
					endif;
					
					# Assign User Role
					
					Roles::user( $this->user['id'] )::assign( $this->defaultRole );
					
					# Add user meta
					
					foreach( $this->meta as $key => $value ) {
						Uss::$global['usermeta']->set( $key, $value, $this->user['id'] );
					}
					
				}
				
				/**
				 * Send Email To User
				 */
				protected function sendEmail() {
					
					if( empty($this->user['id']) || empty($_POST['alert']) ) return;
					
					$sent = require_once __DIR__ . '/manage-user-new-email.php';
					
					if( $sent ) {
						$this->message[] = "<p>
							<i class='bi bi-check-circle me-1'></i> User was successfully notified by email
						</p>";
					} else {
						$this->message[] = "<p>
							<i class='bi bi-x-circle me-1'></i> User could not be notified by email
						</p>";
					}
					
				}
				
				protected function output() {
					
					if( empty($this->message) ) return;
					
					Events::addListener('uadmin:pages/users/new', function() {

						$message = implode("\n <div class='my-2'></div>", $this->message);
						
						echo "<div class='alert alert-success'>{$message}</div>";

					}, EVENT_ID . 'info');
					
				}
			
			}; // End AnonymousClass;
			
		
		} catch( Exception $e ) {
			
			/**
			 * Negative Response
			 */
			Uss::console('@alert', "<i class='bi bi-x-lg me-1'></i> " . $e->getMessage());
			
		}
	
	}, EVENT_ID );
	
	/**
	 *
	 */
	Events::exec('uadmin:pages/users/new.submit');

});
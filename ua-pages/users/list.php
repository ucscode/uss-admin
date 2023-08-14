<?php 

defined( 'UADMIN_DIR' ) OR DIE;

# Append child menu to the parent menu (user)

$ulistMenu = Uss::$global['menu']->get( $menuTitle )->add('list', array(
	'label' => 'All users',
	'href' => Core::url( ROOT_DIR . "/{$userFocus}" )
));

# Route;

Uss::route( $userFocus, function() use($ulistMenu) {
	
	# Activate this menu when it is in focus
	
	$ulistMenu->setAttr('active', true);
	

	# Handle $_POST request!
	
	if( Uss::$global['user'] ) require __DIR__ . '/POST/manage-list.php';
	
	
	# View Admin Panel!
	
	Udash::view(function() use($ulistMenu)  {
		
		/**
		 * Prepare MYSQLI Query!
		 * The query can be altered by a module based on search key!
		 */
		$prefix = DB_TABLE_PREFIX;

		require __DIR__ . '/SECTIONS/SQL-Query.php';

		/**
		 * Advance SQL Query for table display
		 */
		require __DIR__ . '/SECTIONS/table-config.php';
		
		/**
		 * Prepare Dropdown Items
		 */
		require __DIR__ . '/SECTIONS/dropdown-items.php';
		
		/**
		 * Create User List Table
		 */
		Events::addListener('uadmin:pages/users', function($data) {
			
			$table = $data['table'];
			
			require __DIR__ . '/SECTIONS/table-display.php';
			
		}, EVENT_ID . 'users');
		
		/**
		 * Execute the content for user list page
		 */
		Events::exec('uadmin:pages/users', array(
			"SQL" => &$SQL,
			"table" => &$table
		));
		
	});
	
}, null);

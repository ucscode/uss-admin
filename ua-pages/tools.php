<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Define Menu Title
 */
$menuTitle = '_tools';

/**
 * Create As Parent Menu (Tools)
 * Related items may be added to this
 */
$toolMenu = Uss::$global['menu']->add( $menuTitle, array(
	'label' => 'tools',
	'icon' => "<i class='bi bi-wrench-adjustable'></i>",
	"order" => 2
));


/**
 * Append A Child Menu
 * Containing Information about the project
 */
$infoMenu = $toolMenu->add('info', array(
	'label' => 'info',
	'href' => Core::url( ROOT_DIR . '/' . ( $infoFocus = UADMIN_ROUTE . '/info' ) )
));


// Declare a focus path;

Uss::route( $infoFocus, function() use($infoMenu) {
	
	# Activate Menu

	$infoMenu->setAttr('active', true);
	
	# Activate Parent Menu (Tools) 

	$infoMenu->parentMenu->setAttr('active', true);
	
	# Display CPanel Info

	Udash::view(function() {
		
		// Prepare Information;
		
		$info = array();
		
		$info['installation Directory'] = ROOT_DIR;
		$info['Domain Name'] = $_SERVER['SERVER_NAME'];
		$info['HTTPS'] = ( $_SERVER['SERVER_PORT'] == 80 ) ? 'Disabled' : 'Enabled';
		$info['IP Address'] = $_SERVER['REMOTE_ADDR'];
		$info['Website URL'] = Core::url( ROOT_DIR );
		$info['Admin Email'] = Uss::$global['options']->get('email:admin');
		
		$time = new DateTime();
		$timezone = $time->getTimezone();
		$location = $timezone->getLocation();
		
		$info['Date Time'] = $time->format('Y-m-d H:i:s');
		$info['TimeZone'] = $timezone->getName();
		$info['Country Code'] = $location['country_code'];
		$info['Country Name'] = Udash::countries( $location['country_code'] );
		$info['Latitude'] = $location['latitude'];
		$info['Longitude'] = $location['longitude'];
		
		$info['Server Software'] = $_SERVER['SERVER_SOFTWARE'];
		$info['PHP OS'] = PHP_OS;
		$info['PHP Version'] = PHP_VERSION;
		$info['MYSQLI Version'] = Uss::$global['mysqli']->server_info;
		
		$info['Database Host'] = DB_HOST;
		$info['Database Username'] = DB_USER;
		$info['Database Password'] = '****'; //DB_PASSWORD;
		$info['Database Name'] = DB_NAME;
		$info['Database Table Prefix'] = DB_TABLE_PREFIX;
		
		$info['Platform Name'] = PROJECT_NAME;
		$info['Platform Version'] = Uss::VERSION;
		$info['Platform Prefix'] = 'uss';
		$info['Platform Language'] = 'en-GB';
		$info['Platform Documentation'] = "<a class='text-nowrap' href='http://uss.ucscode.me/docs' target='_blank'>
			<i class='bi bi-link-45deg me-1'></i> View Documentation
		</a>";
		
		$info['Author Name'] = 'UCSCODE &lt;Uchenna Ajah&gt;';
		
		$info['Author Website'] = "<a href='http://ucscode.me' class='text-nowrap' target='_blank'>
			<i class='bi bi-globe-americas me-1'></i> ucscode.me
		</a>";
		
		$info['Author Github'] = "<a href='https://github.com/ucscode' target='_blank' class=' text-nowrap'>
			<i class='bi bi-github me-1'></i> github.com/ucscode
		</a>";
		
		
		// Prepare Table;
		
		$table = new DOMTablet( 'info' );
		$table->chunk(100);
		$table->columns(['key', 'value']);
		
		$data = array();
		
		foreach( $info as $key => $value ) {
			$data[] = array( 'key' => $key, 'value' => $value );
		};
		
		/** Set Table Data */
		
		$table->data( $data );
		
		/** Style Table */
		
		$table->wrap('container-fluid');
		
		$table->doc->getElementsByTagName('thead')->item(0)->setAttribute( 'class', 'd-none' );
		$table->table->setAttribute( 'class', $table->table->getAttribute('class') . " table-striped" );
		
		/**
		 * Display Table
		 */
		$table->prepare(null, true);
		
	});
	
});
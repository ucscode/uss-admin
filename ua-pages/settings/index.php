<?php 

$settingsFocus = UADMIN_ROUTE . '/settings';

/**
 * Create The settings Menu
 */
$settingsMenu = Uss::$global['menu']->get( $menuTitle )->add('settings', array(
	'label' => 'settings',
	'href' => Core::url( ROOT_DIR . "/{$settingsFocus}" ),
	'focus' => $settingsFocus
));


/**
 * The settings a page with an event listener that allows modules to add custom settings
 * The customly added settings will be grided
 * Therefore, the output of the module settings should be styled in bootstrap grid format
 */
 
/** 
 * Default uss admin settings 
 */
$simplePages = [ "general", "email", "users" ];

foreach( $simplePages as $pagedir ) require __DIR__ . "/{$pagedir}/index.php";


/**
 * The settings page
 */
Uss::route( $settingsFocus, function() use(&$settingsMenu) {
	
	/** Activate Menu */
	$settingsMenu->setAttr('active', true);
	
	/** Activate Parent Menu */
	$settingsMenu->parentMenu->setAttr('active', true);
	
	/** Display Menu Page */
	Udash::view(function() use(&$settingsMenu) {
	
?>
	<div class='container-fluid'>
		<div class='row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 text-center text-uppercase settings-log'>
			<?php 
				/**
				 * The setting menu is passed as a parameter to listener that expands the settings event
				 * With this menu, you can use `::get_attr()` or `::set_attr()` to handle you module events
				 *
				 * The classname of the settings container should be `col` 
				 * EG `<div class='col'>...</div>`
				 *
				 * This will enable the menu to align properly in the page
				 */
				$data = array( 'menu' => &$settingsMenu );
				
				/**
				 * Setting Page Event Execution
				 */
				Events::exec( 'uadmin:pages/settings', $data ); 
			?>
		</div>
	</div>
		
	<?php });
	
});
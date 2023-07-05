<?php 

defined( 'UADMIN_MOD_DIR' ) OR DIE;

/**
 * The user settings navigation board
 */
Events::addListener('uadmin:page//settings', function( $data ) {
	
	$settingsMenu = $data['menu'];
	
?>
		
	<div class='col'>
		<a href='<?php echo $settingsMenu->get_attr('href') . '/users'; ?>'>
			<div class='card'>
				<div class='card-body'>
					<h1 class='mb-2'> <span class='bi bi-people me-1'></span> </h1>
					<h3 class='mb-2 title'> Users </h3>
					<p class='text-sm'>Login, Permissions</p>
				</div>
			</div>
		</a>
	</div>

<?php }, EVENT_ID . 'users');

/**
 * The user settings main page
 */
require __DIR__ . '/main.php';


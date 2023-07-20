<?php 
	
defined( "UADMIN_DIR" ) or die;

/**
 * Email Setting Board
 */
Events::addListener('uadmin:pages/settings', function( $data ) {
	
	$settingsMenu = $data['menu'];
	
?>
	<div class='col'>
		<a href='<?php echo $settingsMenu->getAttr('href') . '/email'; ?>'>
			<div class='card'>
				<div class='card-body'>
					<h1 class='mb-2'> <span class='bi bi-envelope-at me-1'></span> </h1>
					<h3 class='mb-2 title'> Email </h3>
					<p class='text-sm text-truncate'>SMTP, Business Mail</p>
				</div>
			</div>
		</a>
	</div>

<?php }, EVENT_ID . 'email');

/**
 * Main Settings Page
 */
require __DIR__ . '/main.php';

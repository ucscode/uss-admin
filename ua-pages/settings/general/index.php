<?php 

/**
 * The Admin General Settings
 */
defined( 'UADMIN_DIR' ) OR DIE;

/**
 * General Settings Navigation Board
 */
Events::addListener('uadmin:pages/settings', function($data) {
	
	$settingsMenu = $data['menu'];
	
?>
	<div class='col'>
		<a href='<?php echo $settingsMenu->getAttr('href') . '/general'; ?>'>
			<div class='card'>
				<div class='card-body'>
					<h1 class='mb-2'> <span class='bi bi-gear me-1'></span> </h1>
					<h3 class='mb-2 title'> General </h3>
					<p class='text-sm text-truncate'>Logo, site name</p>
				</div>
			</div>
		</a>
	</div>

<?php }, EVENT_ID . 'general');

/**
 * General Settings Page
 */
require __DIR__ . '/main.php';


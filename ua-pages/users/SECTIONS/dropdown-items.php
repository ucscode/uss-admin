<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Create Dropdown Items
 */
Events::addListener('uadmin:pages/users@options.dropdown', function($data) use($ulistMenu) { 
	$href = $ulistMenu->getAttr('href') . "/{$data['usercode']}";
?>
<li>
	<a class='dropdown-item' href='<?php echo $href; ?>'>
		<i class='bi bi-person me-1'></i> Edit
	</a>
</li>
<?php }, EVENT_ID . "edit" );

Events::addListener('uadmin:pages/users@options.dropdown', function() {?>
<li>
	<button class='dropdown-item' name='action' value='delete' type='submit'>
		<i class='bi bi-x-lg me-1'></i> Delete
	</button>
</li>
<?php }, EVENT_ID . "trash"); ?>
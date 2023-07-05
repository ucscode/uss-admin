<?php 

defined( 'UADMIN_DIR' ) OR DIE;

Events::addListener('uadmin:pages/users/edit.left', function($user) {
	/**
	 *	Update User input
	 */
?>

	<div class='row mb-3'>
		<label class='form-label col-sm-3'>Username:</label>
		<div class='col-sm-9'>
			<input type='text' class='form-control' name='user[username]' value='%{user.username}'>
		</div>
	</div>
	
	<div class='row mb-4'>
		<label class='form-label col-sm-3'>Email:</label>
		<div class='col-sm-9'>
		
			<input type='email' required name='user[email]' value='%{user.email}' class='form-control'>
			
			<?php if( Uss::$global['usermeta']->get('v-code', $user['id']) ): ?>
			<div class='mt-1'>
				<p class='text-danger fs-12px'>
					<i class='mdi mdi-alert-outline me-1'></i> The email has not been confirmed
				</p>
				<div class='form-check text-muted'>
					<input type='checkbox' name='action[confirm]' class='form-check-input' id='email-check'>
					<label class='form-check-label fs-12px' for='email-check'>Confirm account email</label>
				</div>
			</div>
			<?php endif; ?>
			
		</div>
	</div>
	
<?php }, EVENT_ID . 'left' );


/**
 * Change User Avatar
 */
Events::addListener('uadmin:pages/users/edit.left', function($user) {
	
?>

	<div class='d-inline-block mb-2'>
		<div class='user-avatar mb-3'>
			<img src='<?php echo Udash::user_avatar($user['id']); ?>' class='img-fluid' id='avatar-img'>
		</div>
		<div class='position-relative'>
			<input type='file' accept='.jpg,.png,.gif,.jpeg,.webp' name='avatar' class='form-control d-none' id='avatar-input' data-uss-image-preview='#avatar-img'>
			<button type='button' class='btn btn-outline-primary btn-sm' data-uss-trigger-click='#avatar-input'>
				Change Avatar
			</button>
		</div>
	</div>
	
<?php }, EVENT_ID . 'left_100' );


/**
 * Execute events at left side
 */
Events::exec('uadmin:pages/users/edit.left', $user);
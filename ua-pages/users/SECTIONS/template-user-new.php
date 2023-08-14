<?php 

defined( 'UADMIN_DIR' ) OR DIE;

?>
<form method='post' enctype='multipart/form-data'>
	<fieldset>
		<div class='row'>
			
			<div class='%{col.left} mb-4'>
				
				<?php 
					Events::addListener('uadmin:pages/users/new.left@form', function() {
						
						$getPost = function($group, $key) {

							if( !empty(Uss::$global['status']) || empty($_POST[$group]) ) return;

							return $_POST[$group][$key] ?? null;

						};
					
				?>
				
				<div class='row mb-3'>
					<label class='col-lg-3 form-label'>Username</label>
					<div class='col-lg-9'>
						<input type='text' class='form-control' name='user[username]' placeholder='&mdash; unique' value='<?php echo $getPost('user', 'username'); ?>' pattern='^\w+$'>
					</div>
				</div>
			
				<div class='row mb-3'>
					<label class='col-lg-3 form-label --required'>Email</label>
					<div class='col-lg-9'>
						<input type='email' class='form-control' name='user[email]' placeholder='example &nbsp; &mdash; &nbsp; user@email.com' required value='<?php echo $getPost('user', 'email'); ?>'>
					</div>
				</div>
			
				<div class='row mb-4'>
					<label class='col-lg-3 form-label --required'>Password</label>
					<div class='col-lg-9'>
						<input type='text' class='form-control' name='user[password]' placeholder='****' required>
					</div>
				</div>
			
				<div class='row mb-3'>
					<div class='col-lg-9 ms-auto'>
						<div class='form-check form-switch toggle-switch'>
							<input type='checkbox' class='form-check-input' name='alert' value='1' checked>
							<label class='form-check-label'><big>Notify user by email</big></label>
						</div>
					</div>
				</div>
				
				<?php }, EVENT_ID . 'left' );
					
					/**
					 * New User Form Event Left
					 */
					Events::exec('uadmin:pages/users/new.left@form'); 
				?>
				
			</div>
			
			<div class='%{col.right} mb-4'>
				
				<?php 
					Events::addListener('uadmin:pages/users/new.right@form', function() { 
				?>
					<div class='text-center'>
						<div class='user-avatar mx-auto mb-2'>
							<img src='<?php echo Udash::user_avatar(); ?>' class='img-fluid' id='image'>
						</div>
						<input type='file' name='avatar' accept='.jpg, .gif, .png, .jpeg, .webp' class='d-none' id='file' data-uss-image-preview='#image'>
						<button class='btn btn-outline-primary' type='button' data-uss-trigger-click='#file'>Add Avatar</button>
					</div>
				<?php }, EVENT_ID . 'right' );
					
					/**
					 * New User Form Event Right
					 */
					Events::exec('uadmin:pages/users/new.right@form'); 
				?>
				
			</div>
			
			<input type='hidden' name='nonce' value='<?php echo Uss::nonce( 'user-new' ); ?>'>
			
			<div class='border-top py-3 mt-2'>
				<button class='btn btn-primary'>
					<i class='bi bi-person-plus me-1'></i> Add User
				</button>
			</div>
			
		</div>

	</fieldset>
</form>

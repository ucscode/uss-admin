<?php

defined( 'UADMIN_DIR' );

/**
 * 
 */
Uss::route( UADMIN_ROUTE . "/settings/users", function() use($settingsMenu) {
	
	$settingsMenu->setAttr('active', true);
	$settingsMenu->parentMenu->setAttr('active', true);

	require __DIR__ . '/POST.php';
	
	Udash::view(function() {
		
		$__checked = function( $key, $match = '1' ) {
			$value = Uss::$global['options']->get( $key );
			if( $value == $match ) echo 'checked';
		};
?>

	<div class='container'>
		<div class='row'>
			
			<div class='col-lg-12 mb-4 border-bottom'>
				<h2 class='mb-3'>User Settings</h2>
			</div>
			
			<div class='col-lg-8'>
				
				<form method='POST' enctype='multipart/form-data'>
					<fieldset>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row r'>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[disable-signup]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[disable-signup]' value='1' <?php $__checked('user:disable-signup'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big>Temporarily Disable Signup</big>
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Disallow registration until this option is turned off
									</span>
								</p>
							</div>
						
						<?php }, '0-disable-signup'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row '>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[collect-username]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[collect-username]' value='1' <?php $__checked('user:collect-username'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big>Collect username at signup</big>
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Self explanatory
									</span>
								</p>
							</div>
					
						<?php }, 'collect-username'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row '>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[confirm-email]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[confirm-email]' value='1' <?php $__checked('user:confirm-email'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big class=''>Send confirmation email to user after registration</big> 
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Users will need to confirm the email before they can sign in
									</span>
								</p>
						</div>
						
						<?php }, 'confirm-email'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row '>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[affiliation]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[affiliation]' value='1' <?php $__checked('user:affiliation'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big class=''>Enable Affiliate Program</big> 
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Users will have unique affiliation link to invite referrals
									</span>
								</p>
						</div>
						
						<?php }, 'enable-affiliation'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row '>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[lock-email]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[lock-email]' value='1' <?php $__checked('user:lock-email'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big class=''>Prevent User from updating their email.</big> 
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Discourage user from changing to fake email after logging in
									</span>
								</p>
							</div>
						
						<?php }, 'lock-email'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='mb-5 row '>
								<div class='col-4 col-sm-2'>
									<div class='form-check form-switch toggle-switch'>
										<input type='hidden' name='user[reconfirm-email]' value='0'>
										<input type='checkbox' class='form-check-input' name='user[reconfirm-email]' value='1' <?php $__checked('user:reconfirm-email'); ?>>
									</div>
								</div>
								<p class='col-8 col-sm-10'>
									<big class=''>Resend confirmation email on every email update.</big> 
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Force user to confirm the new email address
									</span>
								</p>
							</div>
						
						<?php }, 'reconfirm-email'); ?>
						
						<?php Events::addListener('uadmin:pages/settings/users@form', function() use($__checked) { ?>
						
							<div class='border-bottom mb-4'></div>
							
							<div class='mb-5 row '>
								<div class='col-md-10 ms-auto'>
									<label class=' mb-4 display-6'>Default Registation role</label> 
									<?php 
										foreach( array_keys(Roles::get()) as $key => $role ): 
											$__ = "_{$key}-role";
											if( in_array( $role, [ 'blocked', 'super-admin' ] ) ) continue;
									?>
										<div class='form-check form-switch toggle-switch mb-3 text-capitalize'>
											<input type='radio' class='form-check-input' name='user[default-role]' id='<?php echo $__; ?>' value='<?php echo $role; ?>' <?php $__checked('user:default-role', $role); ?>>
											<label class='form-check-label' for='<?php echo $__; ?>'>
												<big><?php echo $role; ?></big>
											</label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						
						<?php }, 'roles'); ?>
						
						<?php 
							Events::addListener('uadmin:pages/settings/users@form', function() {
						?>
							
							<div class='mb-5 row'>
								<div class='col-md-10 ms-auto'>
									<label class='form-label fw-normal'><big>Automatically delete unconfirmed account after:</big></label>
									<div class='input-group'>
										<input type='text' class='form-control form-control-lg' placeholder='days' value='<?php echo Uss::$global['options']->get('user:auto-trash-unverified-after-day'); ?>' name='user[auto-trash-unverified-after-day]'>
										<span class='input-group-text'>Days</span>
									</div>
									<span class='d-block mt-2 text-muted'>
										<i class='bi bi-exclamation-circle me-1'></i> Set to zero (0) to avoid deleting unconfirmed account
									</span>
								</div>
							</div>
							
						<?php }, 'trash-automatically'); ?>
						
						<?php Events::exec( 'uadmin:pages/settings/users@form', [], true ); ?>
						
						<input type='hidden' name='nonce' value='<?php echo Uss::nonce( 'users' ); ?>'>
						
						<div class=''>
							<button class='btn btn-primary'>
								<i class='mdi mdi-check'></i> Save Changes
							</button>
						</div>
						
					</fieldset>
				</form>
				
			</div>
			
			<div class='col-lg-4'>
				<?php Events::exec( 'uadmin:pages/settings/users.right' ); ?>
			</div>
			
		</div>
	</div>

<?php });
	
}, NULL);

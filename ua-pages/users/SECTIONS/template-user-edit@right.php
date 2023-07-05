<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Access Control Block
 */ 
Events::addListener('uadmin:pages/users/edit.right', function($user) use($self) {
	
?>
	
	<div class='border mb-3 fs-0_9rem rounded-2 overflow-auto card'>
		<div class='bg-light p-2 text-center'>
			<i class='bi bi-star text-warning'></i> - Access Control
		</div>
		<div class='p-3'>
				
			<?php 
			
				$roles = array_keys( Roles::get() );
				
				foreach( $roles as $role ):
					
					$attrs = array();
					$input = $disabled = null;
					
					$priority = Roles::get( $role, 'priority' );
					
					# -----------------------
					
					if( Roles::user( $user['id'] )::hasRole($role) ) $attrs['checked'] = 'checked';
					
					# ------------------------
					
					$authority = Roles::user( Uss::$global['user']['id'] )::maxPriority();
						
					if( $authority < $priority ) $disabled = 'hide';
					else if( $authority === $priority ) {
						$disabled = ($self || !empty($attrs['checked'])) ? 'disabled' : 'hide';
					}
					
					# ------------------------
					
					if( $disabled ) {
						$attrs['disabled'] = 'disabled';
						if( !empty($attrs['checked']) ) {
							$input = "<input type='hidden' name='roles[]' value='{$role}'>";
						};
					};
					
					$capid = "role-{$role}";
			
			?>
			
				<div class='form-check text-capitalize <?php if( $disabled == 'hide' ) echo 'd-none'; ?>'>
					<input type='checkbox' class='form-check-input' name='roles[]' value='<?php echo $role; ?>' id='<?php echo $capid; ?>' <?php echo Core::array_to_html_attrs($attrs); ?> data-priv required>
					<label class='form-check-label' for='<?php echo $capid; ?>'><?php echo $role; ?></label>
					<?php echo $input; ?>
				</div>
				
			<?php endforeach; ?>
	
		</div>
	</div>


<?php }, EVENT_ID . 'right' );


/**
 * Change Password Block
 */
Events::addListener('uadmin:pages/users/edit.right', function() { ?>
	
	<div class='border mb-3 fs-0_9rem rounded-2 overflow-auto'>
		<div class='bg-light p-2 text-center'>
			<i class='bi bi-lock'></i> - Change Password
		</div>
		<div class='p-3'>
			<input type='text' name='password' class='form-control form-control-sm mb-1' placeholder='New Password'>
		</div>
	</div>

<?php }, EVENT_ID . 'right_100' );


/**
 * Delete User Block
 */
Events::addListener('uadmin:pages/users/edit.right', function() use($user) {
	
	/**
	 * Determine users authority level
	 */
	$userpriv = Roles::user( $user['id'] )::authority();
	$selfpriv = Roles::user( Uss::$global['user']['id'] )::authority();
	
	if( $userpriv > $selfpriv ) return;
	
?>

	<div class='mb-3 text-center user-remove-btn border-top py-3'>
		<button type='button' class='btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center w-100' id='delete-account' data-user='%{user.id}'>
			<i class='bi bi-person-slash fs-22px me-2'></i> 
			<span>Delete Account</span>
		</button>
	</div>
	
<?php }, EVENT_ID . 'right_200' );
	

/**
 * Execute events at right
 */
Events::exec('uadmin:pages/users/edit.right', $user);
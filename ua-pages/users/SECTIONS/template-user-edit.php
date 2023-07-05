<?php 

defined( 'UADMIN_DIR' ) OR DIE;

?>
<div class='container-fluid'>

	<div class='d-flex justify-content-between flex-wrap user-info'>
		
		<div class='mb-3 me-4 mb-sm-0'>
		
			<h5 class='mb-1 fw-normal'>
				<i class='bi bi-person-<?php echo $self ? 'heart' : 'gear'; ?> fs-26px'></i> &mdash; 
				<?php if( !empty($user['username']) ) echo $user['username']; ?> ( %{user.usercode} )
			</h5>
			
			<div class='cursor-default text-capitalize'>
				<?php
					foreach( $userRoles as $role ):
						$color = Udash::get_color( $role );
				?>
					<span class='badge bg-<?php echo $color; ?> bg-opacity-50 rounded-4 fw-normal'> 
						<?php echo $role; ?> 
					</span>
				<?php endforeach; ?>
			</div>
			
		</div>
		
		<div class='fs-12px'>
			<div class='mb-1'> Registered: <span class='text-primary'> %{user.register_time} </span> </div>
			<div class='mb-1'> 
				Last Seen: <span class='text-secondary'> <?php echo Core::elapse( $user['last_seen'] ); ?> </span> 
			</div>
			<div class='mb-1'> User ID: <span class='text-secondary'> %{user.id} </span> </div>
		</div>
	
	</div>
	
	<hr>
	
	<form method='POST' enctype='multipart/form-data'>
		<fieldset>
			<div class='user-form-content'>
				<div class='row py-4'>
					
					
					<div class='%{col.left} mb-4'>
						<?php 
							/**
							 * Add left form inputs
							 */
							require __DIR__ . '/template-user-edit@left.php';
						?>
					</div>
					
					<div class='%{col.right} mb-3'>
						<?php
							/**
							 * Add right form inputs
							 */
							require __DIR__ . '/template-user-edit@right.php';
						?>
					</div>
						
					<div class='col-12 border-top pt-2'>
						<input type='hidden' name='nonce' value='<?php echo Uss::nonce( $user['usercode'] ); ?>'>
						<button class='btn btn-success w-100 d-inline-flex align-items-center justify-content-center'>
							<i class='bi bi-person-check fs-22px me-2'></i> 
							<span>Update Account</span>
						</button>
					</div>
					
				</div>
			</div>
		</fieldset>
	</form>

</div>

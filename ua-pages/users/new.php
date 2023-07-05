<?php 

defined( 'UADMIN_DIR' ) OR DIE;

# Declare focus path

$userFocusNew = $userFocus . "/@new";


# Create and activate menu

Uss::$global['menu']->get( $menuTitle )->add('new', array(
	'label' => 'Add new',
	'href' => Core::url( ROOT_DIR . "/{$userFocusNew}" ),
	'active' => implode("/", Uss::query()) === $userFocusNew
));


# Establish Focus & Contents

Uss::route( $userFocusNew, function() {
	
	# Handle POST Request
	
	require __DIR__ . '/POST/manage-user-new.php';
	
	Uss::tag('col.left', 'col-md-8', false);
	Uss::tag('col.right', 'col-md-4', false);
	
	Udash::view(function() {
	
	?>
	
		<div class='container-fluid'>
		
			<div class='border-bottom pb-2 mb-3'>
				<h3 class='fw-normal'> 
					<i class='bi bi-person-plus me-1'></i> Add New User
				</h3>
			</div>
				
			<?php
				Events::addListener('uadmin:pages/users/new', function() { 
			?>
				<div class='mb-4'>
					<div class='alert alert-dark text-sm fw-light'>
						<span class='text-capitalize'>Default registration role is &mdash; <strong class='text-primary'><?php echo Uss::$global['options']->get('user:default-role'); ?></strong></span>. You can change this in user settings
					</div>
				</div>
			<?php }, EVENT_ID . 'info' ); 
			
				# Template: User New 
				
				Events::addListener('uadmin:pages/users/new', function() {
					
					# Build Template
					
					require __DIR__ . '/SECTIONS/template-user-new.php';
					
				}, EVENT_ID . 'template' );
				
				/**
				 * Event for new user page
				 */
				Events::exec( 'uadmin:pages/users/new' );
				
			?>
			
		</div>
	
	<?php });
	
}, NULL);
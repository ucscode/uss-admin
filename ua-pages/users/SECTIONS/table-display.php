<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Prepare Table
 * Then display
 */
$table->prepare(function($data) use($table) {
	
	/**
	 * Check if user email has been confirmed
	 */
	$confirmed = !Uss::$global['usermeta']->get('v-code', $data['id']);
	
	/**
	 * Display an Icon respective to confirmed user
	 */
	$icon = !$confirmed ? 'bi bi-person-exclamation text-danger' : call_user_func(function() use($data) {
		if( Uss::$global['user']['id'] !== $data['id'] ) {
			return 'bi bi-person-check text-primary';
		} else return 'bi bi-person-circle text-success';
	});
	
	/** Add Icon To Data */
	$data['confirmed'] = "<i class='{$icon} fs-24px'></i>";
	
	/**
	 * Get the highest user authority
	 */
	$data['role'] = call_user_func(function($userid) {
		
		$value = Roles::user( $userid )::authority( true );
		
		/**
		 * If user has no role, return a question mark;
		 */
		if( empty($value) ) return '<i class="bi bi-question-circle"></i>';
		
		/**
		 * If user is blocked, display a warning exclamation
		 */
		if( Roles::user( $userid )::is( 'blocked' ) ) {
			$color = 'bg-warning';
			$prepend = "<span class='text-danger'>!</span>";
		} else {
			$color = 'bg-secondary';
			$prepend = null;
		};
		
		/**
		 * Create a badge
		 */
		$badge = "{$prepend} <span class='badge {$color} bg-opacity-50 rounded-3 fw-normal'>{$value}</span>";
		
		return $badge;
		
	}, $data['id']);
	
	/**
	 * Create Nonce For individual dropdown
	 */
	$nonce = Uss::nonce( $table->tablename );
	
	/**
	 * Create a dropdown action
	 */
	$data['action'] = "
		<form method='POST' data-uss-confirm='You are about making changes to the user data'>
			
			<input type='hidden' name='userid' value='{$data['id']}'>
			<input type='hidden' name='nonce' value='{$nonce}'>
			<input type='hidden' name='table' value='{$table->tablename}'>
			
			<div class='dropstart'>
				
				<a href='javascript:void(0)' class='inline-block p-2' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
					<i class='bi bi-three-dots'></i>
				</a>
				
				<ul class='dropdown-menu text-sm'>
	";
		
		/**
		 * Output Buffering
		 */
		ob_start();
		Events::exec( 'uadmin:pages/users@options.dropdown', $data );
	
	/**
	 * Append Output to dropdown list
	 */
	$data['action'] .= ob_get_clean(); // save buffer
		
	$data['action'] .= "</ul>
	
			</div>
			
		<form>
	";
	
	/**
	 * Finalize Dropdown Config
	 */
	if( array_key_exists('username', $data) && empty($data['username']) ) {
		$data['username'] = "<i class='bi bi-exclamation-circle text-muted'></i>";
	};
	
	return $data;
	
}, true); 


<?php 
/**
 * Add a new role ( administrator )
 * 
 * By default, `super-admin` priority number is 100
 * Thus, administrator should be in a lower priority of 99
*/

Roles::add( 'administrator', 99, [
	'view-cpanel',
	'manage-users'
]);

/**
 * Auto delete fake registrations 
 * As well as users who fail to activate their account
 */

call_user_func(function() {
	
	# Number of Days Before Unverified Users Should Be Deleted

	$trash_day = (int)Uss::$global['options']->get('user:auto-trash-unverified-after-day');
	
	if( empty($trash_day) ) return;
	
	# Prepare Statement
	
	$SQL = "
		SELECT %{prefix}_users.* 
		FROM %{prefix}_users
		INNER JOIN %{prefix}_usermeta
			ON %{prefix}_users.id = %{prefix}_usermeta._ref
		WHERE 
			%{prefix}_usermeta._key = 'v-code'
			AND ( UNIX_TIMESTAMP() - %{prefix}_usermeta.epoch ) > ( 60 * 60 * 24 * %{trash_day} )
	";
	
	$SQL = Core::replace_var($SQL, [
		'prefix' => DB_TABLE_PREFIX, 
		'trash_day' => $trash_day
	]);

	# Get Result

	$results = Uss::$global['mysqli']->query( $SQL );
	
	if( !$results->num_rows ) return;
	
	# Delete all associated users
	
	while( $user = $results->fetch_assoc() ) Udash::delete_user( $user['id'] );
	
});



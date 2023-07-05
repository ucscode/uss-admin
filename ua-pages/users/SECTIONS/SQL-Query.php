<?php

defined( 'UADMIN_DIR' ) OR DIE;

if( empty($_GET['search']) ):
	
	/**
	 * Regular Display
	 */
	$SQL = SQuery::select( "{$prefix}_users", "1 ORDER BY id DESC");
	
else:
	
	/**
	 * SQL Query For Search Input
	 */
	$searchWord = Uss::$global['mysqli']->real_escape_string( $_GET['search'] );
	
	/** The user table */
	
	$uss_users = $prefix . '_users';
	
	$SQL = "
		SELECT {$uss_users}.*
		
		FROM {$uss_users}
		
		LEFT JOIN (
			SELECT * FROM {$prefix}_usermeta
			WHERE _key = 'roles'
		) as usermeta_role
		
		ON {$uss_users}.id = usermeta_role._ref
		
		WHERE
			{$uss_users}.email LIKE '%{$searchWord}%' 
			OR {$uss_users}.username LIKE '%{$searchWord}%'
			OR {$uss_users}.usercode LIKE '%{$searchWord}%'
			OR usermeta_role._value LIKE '%{$searchWord}%'
			
		ORDER BY {$uss_users}.id DESC
	";

endif;
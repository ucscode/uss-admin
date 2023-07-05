<?php

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Prepare The DOMTablet
 * Which is an extension of the DOMTable class
 */
$table = new DOMTablet( 'users', 'container-fluid' );

/**
 * Establish DOMTable with the SQL Query
 */

$result = Uss::$global['mysqli']->query( $SQL );

$table->data( $result );

/**
 * Determin What column should display
 */
$columns = array(
	'email',
	'confirmed',
	'role' => "role",
	'usercode' => 'code',
	'action' => ''
);

/**
 * Add username to the column if applicable
 */
if( !empty(Uss::$global['options']->get('user:collect-username')) ) array_unshift($columns, 'username');

/**
 * Create The column
 */
$table->columns($columns);

/**
 * Wrap the table around a white card
 */
$table->wrap();

/**
 * Add a search widget
 */
$table->setWidget('search', true);

/**
 * Create Bulk Options
 * Each options will be identified by `id` (specified in 3rd parameter)
 */
$table->setWidget('bulk', array(
	'delete' => 'delete'
), 'id');


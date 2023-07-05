<?php 

defined( 'UADMIN_MOD_DIR' ) OR DIE;

# Create user Menu & URI

$userFocus = UADMIN_FOCUS_URI . "/users";

$menuTitle = '_users';


# Create the user menu holder

Uss::$global['menu']->add($menuTitle, array(
	'icon' => '<i class="bi bi-people"></i>',
	'label' => 'users',
	'active' => preg_match("~^{$userFocus}~", implode("/", Uss::query()))
));


# Initialte ajax requests

require __DIR__ . '/ajax/index.php';


/**
 * Require the child pages & menu
 */
require __DIR__ . '/users-list.php';
require __DIR__ . '/users-edit.php';
require __DIR__ . '/users-new.php';


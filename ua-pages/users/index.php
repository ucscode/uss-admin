<?php 

defined( 'UADMIN_DIR' ) OR DIE;

# Create user Menu & URI

$userFocus = UADMIN_ROUTE . "/users";

$menuTitle = '_users';


# Create the user menu holder

Uss::$global['menu']->add($menuTitle, array(
	'icon' => '<i class="bi bi-people"></i>',
	'label' => 'users',
	'active' => preg_match("~^{$userFocus}~", implode("/", Uss::query())),
	'order' => 1
));


# Initialte ajax requests

require __DIR__ . '/ajax/index.php';


/**
 * Require the child pages & menu
 */
require __DIR__ . '/list.php';
require __DIR__ . '/edit.php';
require __DIR__ . '/new.php';


<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Require Ajax Files
 * Only display on $_POST REQUEST
 */

/**
 * Condition To Run Ajax Using Uss Dashboard Ajax File:
 * ----------------------------------------------------
 * Every ajax code:
 *
 * 1. Must not be within a focus expression
 * 3. Must be globally wrapped inside `udash:ajax` event
 * 2. Must not print output outside the `udash:ajax` event
 * 4. Must be a `$_POST` REQUEST
 * 5. Must have a parameter named `route` on the `$_POST` variable
 *
 */
require_once __DIR__ . '/remove-user.php';



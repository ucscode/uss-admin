<?php 

# Secure Entry

defined( 'UADMIN_MOD_DIR' ) OR DIE;

/**
 * Remove Undesired Element
 * Dominate an event by using it's EVENT ID
 */
$templatePart = array(
	'auth:right' => [ 
		'signin-reset', 
		'signin-reverse'
	],
	'auth:form//signin' => [ 'reconfirm' ]
);

# Dominate events and hide some contents that would display in login page

foreach( $templatePart as $event => $IDs ) {
	foreach( $IDs as $eventID ) {
		# Override the event
		Events::addListener( $event, null, EVENT_ID . $eventID );
	};
};

# Add CSS script that will be used across the every page in the admin panel

Events::addListener('@head::after', function() {
	$href = Core::url( Uadmin::ASSETS_DIR . '/css/style.css' );
	echo "\t<link rel='stylesheet' href='{$href}' />\n";
});


# Customize the login page

Events::addListener('auth:form//signin', function() { ?>
	<h4 class='text-center fw-light mb-3'>
		<i class='bi bi-stars animate__animated animate__delay-1s animate__flash '></i>
		<span class='d-block mt-2'>Hi Admin</span>
	</h4>
<?php }, EVENT_ID . 'Welcome');


# Override and Customize the authentication fields in uss-dashboard login page

Events::addListener('auth:form//signin', function() { ?>
	<div class="mb-3">
		<div class='input-group'>
			<span class='input-group-text'>
				<i class='bi bi-person-gear'></i> 
			</span>
			<input type="email" placeholder="Email" class='form-control' name='login' required>
		</div>
	</div>
<?php }, EVENT_ID . 'login');


Events::addListener('auth:form//signin', function() { ?>
	<div class="mb-4">
		<div class='input-group'>
			<span class='input-group-text'>
				<i class='bi bi-lock'></i> 
			</span>
			<input type="password" placeholder="Password" class='form-control' name='password' required>
		</div>
	</div>
<?php }, EVENT_ID . 'password'); 


Events::addListener('auth:form//signin', function() { ?>
	<div class="">
		<button class="btn btn-outline-success w-100" type='submit'>
			<i class='bi bi-power'></i> - Login
		</button>
	</div>
<?php }, EVENT_ID . 'submit');


# Update the ~Title &~ Tagline

Uss::$global['tagline'] = "Professional Platform for <br> Professional Developers &amp; Entreprenuers";


# Add Ucscode Trademark;

require Uadmin::VIEW_DIR . '/footer.php';



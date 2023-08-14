<?php 

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;

# Add CSS script that will be used across the every page in the admin panel

Events::addListener('@head:after', function() {
	$href = Core::url( Uadmin::ASSETS_DIR . '/css/style.css' );
	echo "\t<link rel='stylesheet' href='{$href}' />\n";
}, EVENT_ID . 'style');


# Customize the login page

Events::addListener('udash:auth/signin@form', function() { ?>
	<h4 class='text-center fw-light mb-3'>
		<i class='bi bi-stars animate__animated animate__delay-1s animate__flash '></i>
		<span class='d-block mt-2'>Hi Admin</span>
	</h4>
<?php }, EVENT_ID . '_welcome');


# --------------------------------------------------------

# Override and Customize the authentication fields in uss-dashboard login page

Events::addListener('udash:auth/signin@form', function() { ?>
	<div class="mb-3">
		<div class='input-group'>
			<span class='input-group-text'>
				<i class='bi bi-person-gear'></i> 
			</span>
			<input type="email" placeholder="Email" class='form-control' name='login' required>
		</div>
	</div>
<?php }, EVENT_ID . 'field'); // login field


Events::addListener('udash:auth/signin@form', function() { ?>
	<div class="mb-4">
		<div class='input-group'>
			<span class='input-group-text'>
				<i class='bi bi-lock'></i> 
			</span>
			<input type="password" placeholder="Password" class='form-control' name='password' required>
		</div>
	</div>
<?php }, EVENT_ID . 'field_100'); // password field


Events::addListener('udash:auth/signin@form', function() { ?>
	<div class="">
		<button class="btn btn-outline-success w-100" type='submit'>
			<i class='bi bi-power'></i> - Login
		</button>
	</div>
<?php }, EVENT_ID . 'field_300'); // submit button

# ------------------------------------------------------

/**
 * Remove The rest Of the Unwanted Fields
 * Dominate an event by using it's EVENT ID
 */
$templatePart = array(
	'udash:auth/signin@form' => [ 
		'field_200'
	],
	'udash:auth.right' => [ 
		'signin_100',
		'signin_200'
	]
);

# Dominate events and hide some contents that would display in login page

foreach( $templatePart as $event => $IDs ) {
	foreach( $IDs as $eventID ) {
		# Override the event
		Events::addListener( $event, null, EVENT_ID . $eventID );
	};
};


# Update the ~Title &~ Tagline

if( empty(Uss::$global['tagline']) ) {
	Uss::$global['tagline'] = "Professional Platform for <br> Professional Developers &amp; Entreprenuers";
};

# Add Ucscode Trademark;

require Uadmin::VIEW_DIR . '/footer.php';



<?php 

( defined( 'UADMIN_DIR' ) && $_SERVER['REQUEST_METHOD'] != 'GET' ) OR DIE;

# Get Email Template

$template = require Udash::VIEW_DIR . "/MAIL/template.php";

# Email Title

$hostName = Uss::$global['title'];

# Has Admin Privilege?

$adminPermit = Roles::user( $this->user['id'] )::hasPermission( 'view-cpanel' );

# Location

$location = $adminPermit ? UADMIN_ROUTE : UDASH_ROUTE;

$href = Core::url( ROOT_DIR . "/{$location}" );


/**
 * Email Template Message
 */
$message = "
	<x2:html>
		<x2:head>
			<x2:style>
				.list { 
					margin: 12px; 
				}
				p { 
					padding: 5px; 
				}
				.mb {
					margin-bottom: 12px;
				}
				li { 
					margin: 5px 0 
				}
			</x2:style>
		</x2:head>
		<x2:body>
			<x2:h2>Your account has been created!</x2:h2>
			<x2:hr/>
			<x2:p>Hello</x2:p>
			<x2:p>
				A new account has been created for you on {$hostName}. <x2:br/>
				Use the credential below for your first login
			</x2:p>
			<x2:ul class='list'>
				<li>Login: {$this->user['email']}</li>
				<li>Password: {$this->rawPassword}</li>
			</x2:ul>
			<x2:p class='mb'>
				Use the link below to login and reset your password! <x2:br/>
				<x2:a href='{$href}'>{$href}</x2:a>
			</x2:p>
			<x2:h4>Regards</x2:h4>
		</x2:body>
	</x2:html>
";

/**
 * Imply Email Converter
 */
$X2Client = new X2Client( $message );
$message = $X2Client->render( "body > table");


$__body = Core::replace_var( $template, array(
	'content' => $message,
	'footer' => "Thank you for joining {$hostName}"
));


$PHPMailer = Udash::PHPMailer();
$PHPMailer->Subject = "New Account Created";
$PHPMailer->Body = $__body;
$PHPMailer->addAddress( $this->user['email'] );


return $PHPMailer->send();
	

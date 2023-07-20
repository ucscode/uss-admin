<?php

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * The Email Settings Main Page
 */
Uss::route( $settingsFocus . "/email", function() use($settingsMenu) {
	
	/**
	 * Activate Settings Menu
	 */
	$settingsMenu->setAttr('active', true);
	$settingsMenu->parentMenu->setAttr('active', true);
	
	/** Update POST Request */
	
	require __DIR__ . '/POST.php';
	
	/** Update Views */
	
	Udash::view(function() {
		
?>

	<div class='container'>
		<div class='row'>
			
			<div class='col-lg-12 border-bottom mb-4'>
				<h2 class='mb-3'>Email Settings</h2>
			</div>
			
			<div class='col-lg-8'>
			
				<form method='post' enctype='multipart/form-data'>
					
					<div class='row mb-4 mb-sm-5'>
						<label class='col-lg-3 form-label --required'>Admin Email</label>
						<div class='col-lg-9'>
							<input type='email' name='email[admin]' class='form-control' placeholder='example &nbsp; &mdash; &nbsp; admin@domain.com' value='<?php echo Uss::$global['options']->get('email:admin'); ?>' required>
							<p class='text-sm text-muted mt-1'>Used for sending email</p>
						</div>
					</div>
					
					<div class='row mb-4 mb-sm-5'>
						<label class='col-lg-3 form-label'>From</label>
						<div class='col-lg-9'>
							<input type='email' name='email[from]' class='form-control' placeholder='example &nbsp; &mdash; &nbsp; no-reply@domain.com' value='<?php echo Uss::$global['options']->get('email:from'); ?>'>
							<p class='text-sm text-muted mt-1'>Will be used over admin email if specified</p>
						</div>
					</div>
					
					<div class='row mb-4 mb-sm-5'>
						<label class='col-lg-3 form-label'>SMTP Option</label>
						<div class='col-lg-9 text-capitalize'>
							
							<div class='choice mb-4'>
								<?php
									foreach( ['default', 'custom'] as $value ):
										$checked = (Uss::$global['options']->get('smtp:state') == $value) ? "checked" : "";
								?>
								<div class='form-check'>
									<input class='form-check-input' type='radio' name='smtp[state]' id='smtp-<?php echo $value; ?>' value='<?php echo $value; ?>' required <?php echo $checked; ?>>
									<label class='form-check-label' for='smtp-<?php echo $value; ?>'>Use default <?php echo $value; ?> settings</label>
								</div>
								<?php endforeach; ?>
							</div>
							
							<div class='smtp-settings'>
								<fieldset class='border-top pt-4' disabled>
								
									<div class='mb-3'>
										<label class='fw-light mb-1'>SMTP Server</label>
										<input type='text' class='form-control' name='smtp[server]' placeholder='example &nbsp; &mdash; &nbsp; smtp.gmail.com' value='<?php echo Uss::$global['options']->get('smtp:server'); ?>' required>
									</div>
								
									<div class='mb-4'>
										<label class='fw-light mb-1'>SMTP Username</label>
										<input type='email' class='form-control' name='smtp[username]' placeholder='example &nbsp; &mdash; &nbsp; user@domain.com' value='<?php echo Uss::$global['options']->get('smtp:username'); ?>' required>
									</div>
								
									<div class='mb-4'>
										<label class='fw-light mb-1'>SMTP Password</label>
										<input type='text' class='form-control' name='smtp[password]' placeholder='****' value='<?php echo Uss::$global['options']->get('smtp:password'); ?>' required>
									</div>
								
									<div class='mb-4'>
										<label class='fw-light mb-1'>SMTP Port</label>
										<input type='text' class='form-control' name='smtp[port]' placeholder='example &nbsp; &mdash; &nbsp; 25' value='<?php echo Uss::$global['options']->get('smtp:port'); ?>' required>
									</div>
								
									<div class='mb-4'>
										<label class='fw-light mb-1'>SMTP Security</label>
										<select type='text' class='form-control form-select' name='smtp[secure]' value='<?php echo Uss::$global['options']->get('smtp:secure'); ?>' required>
											<option value='tls'>TLS</option>
											<option value='ssl'>SSL</option>
										</select>
									</div>
									
								</fieldset>
							</div>
							
						</div>
					</div>
					
					<div class='border-top mb-4'></div>
					
					<input type='hidden' name='nonce' value='<?php echo Uss::nonce( 'email' ); ?>'>
					
					<div class=''>
						<button class='btn btn-primary'>
							<i class='bi bi-save me-1'></i> Save Changes
						</button>
					</div>
					
				</form>
				
			</div>
			
		</div>
	</div>

	<script>
		window.addEventListener('load', function() {
			let state = $("[name='smtp[state]']");
			state.on('change', function() {
				$('.smtp-settings fieldset').prop( 'disabled', this.value != 'custom' );
			});
			$('.smtp-settings fieldset').prop( 'disabled', $("[name='smtp[state]']:checked").val() != 'custom' );
		});
	</script>
	
<?php });
	
}, NULL);

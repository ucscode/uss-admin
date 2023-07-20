<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * The Main General Setting Page
 */
Uss::route( $settingsFocus . "/general", function() use($settingsMenu) {
	
	/** Activate Menu */
	
	$settingsMenu->setAttr('active', true);
	$settingsMenu->parentMenu->setAttr('active', true);
	
	
	/** POST Request */
	
	require __DIR__ . '/POST.php';
	
	
	/** Template Attr */
	
	Uss::tag('col.main', 'col-lg-8 col-sm-10', false);
	
	
	/** Display Content  */
	
	Udash::view(function() {
		
?>
		<div class='container-fluid'>
				
			<div class='border-bottom mb-4'>
				<h2 class='mb-3'> General Settings </h2>
			</div>
			
			<div class='row'>
				
				<?php 
					Events::addListener('uadmin:pages/settings/general.main', function() {
				?>
				
				<div class='%{col.main}'>
					
					<form method='POST' enctype='multipart/form-data'>
						<fieldset>
							
							<?php 
								Events::addListener('uadmin:pages/settings/general@form', function() {
							?>
							<div class='mb-3 mb-md-5'>
								<div class='d-inline-flex'>
									<div class='text-center'>
										<div class='user-avatar site-icon mb-2'>
											<img src='<?php echo Uss::$global['icon']; ?>' class='img-fluid the-image'>
										</div>
										<button class='btn btn-outline-success btn-sm' type='button' data-uss-trigger-click='#iconic'>Change Icon</button>
									</div>
								</div>
								<input type='file' name='icon' class='form-control d-none'  accept='.jpg,.jpeg,.png,.gif' id='iconic' data-uss-image-preview='.the-image'>
							</div>
							<?php }, EVENT_ID . 'field'); ?>
							
							<?php 
								Events::addListener('uadmin:pages/settings/general@form', function() {
							?>
							<div class='mb-3 mb-lg-4 row'>
								<label class='form-label col-lg-3'>Site Name</label>
								<div class='col-lg-9'>
									<input type='text' name='site[title]' class='form-control' value='<?php echo Uss::$global['title']; ?>'>
								</div>
							</div>
							<?php }, EVENT_ID . 'field_100' ); ?>
								
							<?php 
								Events::addListener('uadmin:pages/settings/general@form', function() {
							?>
							<div class='mb-3 mb-lg-4 row'>
								<label class='form-label col-lg-3'>Headline</label>
								<div class='col-lg-9'>
									<input type='text' name='site[tagline]' class='form-control' value='<?php echo Uss::$global['tagline']; ?>'>
								</div>
							</div>
							<?php }, EVENT_ID . 'field_200'); ?>
								
							<?php 
								Events::addListener('uadmin:pages/settings/general@form', function() {
							?>
							<div class='mb-3 mb-lg-4 row'>
								<label class='form-label col-lg-3'>Description</label>
								<div class='col-lg-9'>
									<textarea type='text' name='site[description]' class='form-control' rows='8'><?php echo Uss::$global['description']; ?></textarea>
								</div>
							</div>
							<?php }, EVENT_ID . 'field_300' );
								
								/**
								 * Event Execution
								 */
								Events::exec( 'uadmin:pages/settings/general@form' ); 
							?>
							
							<div class='border-bottom mb-4'></div>
							
							<input type='hidden' name='nonce' value='<?php echo Uss::nonce( 'general' ); ?>'>
							
							<button class='btn btn-primary'>
								<i class='bi bi-save me-1'></i> Save Changes
							</button>
						
						</fieldset>
					</form>
					
				</div>
				
				<?php }, EVENT_ID . 'general' );
					
					/**
					 * Execute General Page Event
					 */
					Events::exec( 'uadmin:pages/settings/general.main' ); 
				?>

			</div>
		</div>

	<?php }); // Uss::view
	
}, NULL ); // Uss::route
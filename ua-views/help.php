<?php 

defined( 'UADMIN_DIR' ) OR DIE;

/**
 * Check For Dashboard Module
 */
Events::addListener('modules-loaded', function() {
	/**
	 * Check if user is accessing admin panel
	 * And then confirm if dashboard module is present
	 */
	if( Uss::query(0) == UADMIN_ROUTE && !class_exists('udash') ) {
	
		Uss::view(function() { 

?>
			<div class="container col-xxl-8 px-4 py-5">
				<div class="row flex-lgs-row-reverse align-items-center py-5 px-4 bg-light">
				
					<div class="col-lg-10 mx-auto mb-4">
						<img src="<?php echo Core::url( UADMIN_DIR . "/ua-assets/images/1677511496611.png" ); ?>" class="img-fluid" alt="User Synthetics Dashboard" loading="lazy">
					</div>
					
					<div class="col-lg-10 mx-auto text-center">
						<h1 class="display-5 fw-bold lh-2 mb-3">DASHBOARD MODULE REQUIRED</h1>
						<p class="lead fs-16px fs-md-20px mb-4">
							The admin module / cPanel depends on dashboard module to load. <br>
							Download the dashboard module from the  official <?php echo uss::$global['title']; ?> page
						</p>
						<div class="d-grid gap-2 d-md-flex justify-content-md-center">
							<a type="button" href='<?php echo uss::$global['website']; ?>' class="btn btn-outline-primary btn-lg px-4 me-md-2 w-100">
								Download
							</a>
						</div>
					</div>
					
				</div>
			</div>
<?php	});
		
		exit;
		
	};
	
});
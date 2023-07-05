<?php defined( 'UADMIN_DIR' ) OR DIE; ?>

<div class="d-flex vh-100 align-items-center text-center text-bg-dark">
	<div class="cover-container row w-100 p-3 mx-auto flex-column">

	  <div class="px-3 col-lg-8 mx-auto">
			
			<div class='mb-4'>
				<img src='<?php echo Core::url( Uadmin::ASSETS_DIR . "/images/ban.png" ); ?>' class='img-fluid' width='120px'>
			</div>
			
			<h1 class='text-uppercase display-5 fw-semibold'>Restricted!</h1>
			
			<h4 class='text-uppercase display-3 fw-semibold'>403 Error</h4>
			
			<p class="lead mb-3">You need special permission to access this page.</p>
			
			<p class="lead py-3">
				<a href="<?php echo Udash::config('403-redirect') ?? Core::url( ROOT_DIR . '/' . UDASH_ROUTE ); ?>" class="btn btn-lg btn-outline-secondary fw-bold border-white text-white rounded-5 px-4 px-sm-5">
					Exit this space
				</a>
			</p>
			
	  </div>
	  
	</div>
</div>
<?php 

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;

# Add Listener

Events::addListener('@body:beforeAfter', function() {

	# Check if user is logged in

	if( !Uss::$global['user'] ) return;
?>
	<footer class="footer">
		<div class="container-fluid">
			<p class="text-sm copyright text-center">
				Powered By &mdash;
				<a href="<?php echo Uss::$global['website']; ?>" rel="nofollow" target="_blank">
					<u><?php echo PROJECT_NAME; ?></u>
				</a>
			</p>
			<!-- end row -->
		</div>
	</footer>
<?php }, EVENT_ID . 'copyright'); ?>
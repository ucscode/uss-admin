<?php 

# Secure Entry

defined( 'UADMIN_MOD_DIR' ) OR DIE;

# Add Listener

Events::addListener('udash:footer', function() {

	# Check if user is logged in

	if( !Uss::$global['user'] ) return;
?>
	<footer class="footer">
		<div class="container-fluid">
			<p class="text-sm copyright text-center">
				Powered By &mdash;
				<a href="<?php echo Uss::$global['website']; ?>" rel="nofollow" target="_blank">
					<u>%{PROJECT_NAME}</u>
				</a>
			</p>
			<!-- end row -->
		</div>
	</footer>
<?php }, 'ucscode' . mt_rand() ); ?>
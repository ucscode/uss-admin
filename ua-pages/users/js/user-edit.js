"use strict";

$(function() {
	
	new class {
		
		constructor() {
			this.#checkRole();
			this.#deleteUser( this );
		}
		
		#checkRole() {
			
			/**
			 * Force at lease on `role` to be checked
			 */
			let checkbox = $(".user-form-content [data-priv]");
			
			checkbox.on('change', function() {
				checkbox.prop( 'required', !checkbox.is(':checked') );
			});
			
			checkbox.prop( 'required', !checkbox.is(':checked') );
		
		}
		
		#deleteUser( classObj ) {
			
			$("#delete-account").click(function() {
				
				//! The clicked button
				
				let self = this;
				
				bootbox.confirm({
					message: `
						<div class='fs-0_9rem'>
							<div class='mb-2'>You're about to take a serious action here! </div> 
							<div class='mb-2'>Deleting this account will remove everything pertaining to the user </div>
							<div> It is also important to note that the action can never be reversed!</div>
						</div>
					`,
					title: "<i class='bi bi-exclamation-circle me-1'></i> Wait a minute",
					callback: function(ans) {
						if( ans ) classObj.#processDelete( classObj, self );
					},
					buttons: {
						confirm: { 
							label: 'Delete',
							className: 'btn-danger'
						}
					}
				});
				
			});
		
		}
		
		#processDelete( classObj, btn ) {
			
			//! Loader
			Notiflix.Loading.hourglass( 'Deleting Account' );
			
			$.ajax({
				url: Uss['ua-ajax'],
				data: {
					userid: btn.dataset.user,
					nonce: Uss.Nonce,
					route: 'ua-delete-user'
				},
				method: 'post',
				success: function(response) {
					Notiflix.Loading.remove();
					classObj.#userDeleteResponse( response );
				}
			});
			
		}
		
		#userDeleteResponse( response ) {
			
			try {
				
				let result = JSON.parse(response);
				
				bootbox.alert({
					className: 'text-center',
					message: result.message,
					closeButton: false,
					centerVertical: true,
					size: 'small',
					callback: function() {
						if( result.status ) {
							Notiflix.Loading.circle();
							window.location.href = './';
						};
					}
				});
				
			} catch(e) {
				
				console.log( e );
				
				bootbox.alert({
					closeButton: false,
					size: 'small',
					message: "The server response could not be handled",
					className: 'text-center',
					centerVertical: true
				});
				
			}
			
		}
		
	};

});

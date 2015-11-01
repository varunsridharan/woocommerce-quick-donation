(function($) {
	$( document ).ready( function() {
		/**
		 * Generate new Remote View URL
		 * and display it on the admin page
		 */
		$( 'input[name="generate-new-url"]' ).on( 'click', function( event ) {
			event.preventDefault();
			$.ajax({
				type : 'post',
				dataType : 'json',
				url : systemInfoAjax.ajaxurl,
				data : { action : 'regenerate_url' },
				success : function( response ) {
					$( '.sss-url-text' ).val( response );
					$( '.sss-url-text-link' ).attr( 'href', response );
				},
				error : function( j, t, e ) {
					console.log( "Simple System Status Error: " + j.responseText );
				}
			});
		});
	});
})(jQuery);

jQuery( function ( $ )
{
	'use strict';

	/**
	 * Update date picker element
	 * Used for static & dynamic added elements (when clone)
	 */
	function wcqd_metabox_update_date_picker()
	{
		var $this = $( this ),
			options = $this.data( 'options' );

		$this.siblings( '.ui-datepicker-append' ).remove();         // Remove appended text
		$this.removeClass( 'hasDatepicker' ).attr( 'id', '' ).datepicker( options );
	}

	$( ':input.rwmb-date' ).each( wcqd_metabox_update_date_picker );
	$( '.rwmb-input' ).on( 'clone', ':input.rwmb-date', wcqd_metabox_update_date_picker );
} );

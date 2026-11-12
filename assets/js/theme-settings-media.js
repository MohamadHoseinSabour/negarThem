( function( $ ) {
	'use strict';

	$( document ).on( 'click', '.negar-media-control__select', function() {
		var control = $( this ).closest( '[data-media-control]' );
		var frame = wp.media( {
			title: 'انتخاب تصویر',
			button: { text: 'استفاده از این تصویر' },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function() {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			control.find( '.negar-media-control__id' ).val( attachment.id );
			control.find( '.negar-media-control__preview' ).attr( 'src', attachment.url );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.negar-media-control__remove', function() {
		$( this ).closest( '[data-media-control]' ).find( '.negar-media-control__id' ).val( '' );
		return false;
	} );
} )( jQuery );

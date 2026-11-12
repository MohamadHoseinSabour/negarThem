( function( $ ) {
	'use strict';

	function applyPreview( control, mediaId, imageUrl ) {
		var preview = control.find( '.negar-media-control__preview' );
		var fallbackUrl = preview.attr( 'data-fallback-url' ) || '';
		control.find( '.negar-media-control__id' ).val( mediaId );
		if ( imageUrl ) {
			preview.attr( 'src', imageUrl );
		} else if ( fallbackUrl ) {
			preview.attr( 'src', fallbackUrl );
		}
	}

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
			applyPreview( control, attachment.id, attachment.url );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.negar-media-control__remove', function() {
		var control = $( this ).closest( '[data-media-control]' );
		applyPreview( control, '', '' );
		return false;
	} );

	$( document ).on( 'change input', '.negar-media-control__id', function() {
		var control = $( this ).closest( '[data-media-control]' );
		var value = $.trim( String( $( this ).val() || '' ) );
		if ( '' === value || '0' === value ) {
			var preview = control.find( '.negar-media-control__preview' );
			var fallbackUrl = preview.attr( 'data-fallback-url' ) || '';
			if ( fallbackUrl ) {
				preview.attr( 'src', fallbackUrl );
			}
		}
	} );
} )( jQuery );

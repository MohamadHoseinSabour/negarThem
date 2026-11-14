( function( $ ) {
	'use strict';

	var config = window.NegarThemeStoriesConfig || {};
	var messages = config.messages || {};

	function updateEmptyState() {
		var list = $( '#negar-stories-list' );
		var cards = list.find( '.negar-story-card' );
		var emptyMsg = list.find( '.negar-stories-empty-message' );
		if ( cards.length === 0 ) {
			if ( emptyMsg.length === 0 ) {
				list.append( '<p class="negar-stories-empty-message description" style="padding: 16px; background: #fff; border: 1px dashed #c3c4c7; text-align: center;">هنوز استوری‌ای تعریف نشده است. برای ایجاد اولین استوری، دکمهٔ «افزودن استوری جدید» را بزنید.</p>' );
			} else {
				emptyMsg.show();
			}
		} else {
			emptyMsg.hide();
		}
	}

	function updateMediaTypeFields( card ) {
		var type = card.find( '.negar-story-media-type-select' ).val();
		var badge = card.find( '.negar-story-type-badge' );
		if ( 'video' === type ) {
			card.find( '.negar-story-image-fields' ).hide();
			card.find( '.negar-story-video-fields' ).show();
			badge.text( 'ویدیو' ).removeClass( 'negar-badge-image' ).addClass( 'negar-badge-video' );
		} else {
			card.find( '.negar-story-image-fields' ).show();
			card.find( '.negar-story-video-fields' ).hide();
			badge.text( 'تصویر' ).removeClass( 'negar-badge-video' ).addClass( 'negar-badge-image' );
		}
	}

	// Add new story
	$( document ).on( 'click', '#negar-add-story-btn', function( e ) {
		e.preventDefault();
		var templateHtml = $( '#negar-story-template' ).html();
		if ( ! templateHtml ) {
			return;
		}

		var uniqueIndex = 'story_' + Date.now() + '_' + Math.floor( Math.random() * 1000 );
		var newCardHtml = templateHtml.replace( /__INDEX__/g, uniqueIndex );
		var $newCard = $( newCardHtml );

		// Set initial stable id
		$newCard.find( '.negar-story-id' ).val( uniqueIndex );

		$( '#negar-stories-list' ).append( $newCard );
		updateEmptyState();
		updateMediaTypeFields( $newCard );

		// Initialize product search for the new card if available
		if ( window.NegarThemeInitProductSelectors ) {
			window.NegarThemeInitProductSelectors( $newCard[0] );
		}

		// Open details and focus title
		$newCard.prop( 'open', true );
		$newCard.find( '.negar-story-title-input' ).focus();
	} );

	// Remove story
	$( document ).on( 'click', '.negar-story-delete', function( e ) {
		e.preventDefault();
		e.stopPropagation();
		var card = $( this ).closest( '.negar-story-card' );
		card.remove();
		updateEmptyState();
	} );

	// Move Up (keyboard and click)
	$( document ).on( 'click', '.negar-story-move-up', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-story-card' );
		var prev = card.prev( '.negar-story-card' );
		if ( prev.length ) {
			card.insertBefore( prev );
			$( this ).focus();
		}
	} );

	// Move Down (keyboard and click)
	$( document ).on( 'click', '.negar-story-move-down', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-story-card' );
		var next = card.next( '.negar-story-card' );
		if ( next.length ) {
			card.insertAfter( next );
			$( this ).focus();
		}
	} );

	// Media type change
	$( document ).on( 'change', '.negar-story-media-type-select', function() {
		var card = $( this ).closest( '.negar-story-card' );
		updateMediaTypeFields( card );
	} );

	// Title preview update
	$( document ).on( 'input change', '.negar-story-title-input', function() {
		var card = $( this ).closest( '.negar-story-card' );
		var title = $( this ).val().trim() || 'استوری بدون عنوان';
		card.find( '.negar-story-title-preview' ).text( title );
	} );

	// Video Media Library selector
	$( document ).on( 'click', '.negar-video-control__select', function( e ) {
		e.preventDefault();
		var control = $( this ).closest( '[data-video-control]' );
		var frame = wp.media( {
			title: messages.selectVideo || 'انتخاب ویدیو',
			button: { text: messages.useVideo || 'استفاده از این ویدیو' },
			library: { type: 'video' },
			multiple: false
		} );

		frame.on( 'select', function() {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			control.find( '.negar-video-control__id' ).val( attachment.id );
			var label = attachment.filename || attachment.title || ( '#' + attachment.id );
			control.find( '.negar-video-control__filename' ).text( label );
		} );
		frame.open();
	} );

	// Video Media Library remove
	$( document ).on( 'click', '.negar-video-control__remove', function( e ) {
		e.preventDefault();
		var control = $( this ).closest( '[data-video-control]' );
		control.find( '.negar-video-control__id' ).val( '0' );
		control.find( '.negar-video-control__filename' ).text( '' );
	} );

	// Initial setup on ready
	$( function() {
		$( '.negar-story-card' ).each( function() {
			updateMediaTypeFields( $( this ) );
		} );
		updateEmptyState();
	} );

} )( jQuery );

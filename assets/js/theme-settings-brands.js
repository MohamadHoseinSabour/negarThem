( function( $ ) {
	'use strict';

	var config = window.NegarThemeBrandsConfig || {};
	var messages = config.messages || {};
	var maxRecords = 20;

	function updateEmptyNotice() {
		var list = $( '#negar-brands-list' );
		var cards = list.find( '.negar-brand-card' );
		var notice = $( '#negar-brands-empty-notice' );
		if ( cards.length === 0 ) {
			notice.show();
		} else {
			notice.hide();
		}
	}

	function reindexBrands() {
		var cards = $( '#negar-brands-list .negar-brand-card' );
		cards.each( function( index, el ) {
			var $card = $( el );
			$card.find( '.negar-brand-term-id' ).attr( 'name', 'negar_theme_brand_options[brands][' + index + '][term_id]' );
			$card.find( '.negar-brand-enabled-hidden' ).attr( 'name', 'negar_theme_brand_options[brands][' + index + '][enabled]' );
			$card.find( '.negar-brand-enabled-checkbox' ).attr( 'name', 'negar_theme_brand_options[brands][' + index + '][enabled]' );
			$card.find( '.negar-brand-logo-override-id' ).attr( 'name', 'negar_theme_brand_options[brands][' + index + '][logo_override_id]' );

			var $btnUp = $card.find( '.negar-brand-btn-up' );
			var $btnDown = $card.find( '.negar-brand-btn-down' );

			$btnUp.prop( 'disabled', index === 0 );
			$btnDown.prop( 'disabled', index === cards.length - 1 );
		} );
		updateEmptyNotice();
	}

	function addBrand( brand ) {
		var list = $( '#negar-brands-list' );
		var currentCards = list.find( '.negar-brand-card' );
		if ( currentCards.length >= maxRecords ) {
			alert( messages.maxReached || 'حداکثر ۲۰ برند می‌توانید اضافه کنید.' );
			return;
		}

		var existing = list.find( '.negar-brand-card[data-term-id="' + brand.term_id + '"]' );
		if ( existing.length > 0 ) {
			alert( messages.alreadyAdded || 'این برند قبلاً به لیست اضافه شده است.' );
			return;
		}

		var templateHtml = $( '#negar-brand-card-template' ).html();
		if ( ! templateHtml ) {
			return;
		}

		var index = currentCards.length;
		var cardHtml = templateHtml
			.replace( /__INDEX__/g, index )
			.replace( /__NAME__/g, $( '<div>' ).text( brand.name ).html() )
			.replace( /__SLUG__/g, $( '<div>' ).text( brand.slug ).html() )
			.replace( /__NATIVE_LOGO_URL__/g, brand.logo_url || '' )
			.replace( /__LOGO_URL__/g, brand.logo_url || '' );

		var $card = $( cardHtml );
		$card.attr( 'data-term-id', brand.term_id );
		$card.find( '.negar-brand-term-id' ).val( brand.term_id );
		$card.find( '.negar-brand-card__meta' ).text( '(#' + brand.term_id + ' • ' + brand.slug + ')' );

		list.append( $card );
		reindexBrands();
	}

	// Search brands
	var searchTimer = null;
	$( document ).on( 'input', '#negar-brand-search-input', function() {
		clearTimeout( searchTimer );
		var query = $.trim( $( this ).val() );
		var $results = $( '#negar-brand-search-results' );
		var $spinner = $( '#negar-brand-search-spinner' );
		var $status = $( '#negar-brand-search-status' );

		if ( ! query ) {
			$results.empty().hide();
			$spinner.removeClass( 'is-active' );
			$status.text( '' );
			return;
		}

		$spinner.addClass( 'is-active' );
		$status.text( messages.loading || 'در حال جست‌وجو…' );

		searchTimer = setTimeout( function() {
			$.ajax( {
				url: config.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'negar_theme_search_brands',
					nonce: config.nonce,
					query: query
				},
				success: function( response ) {
					$spinner.removeClass( 'is-active' );
					if ( response && response.success && response.data && response.data.brands ) {
						var brands = response.data.brands;
						$results.empty();
						if ( brands.length === 0 ) {
							$status.text( messages.empty || 'برندی پیدا نشد.' );
							$results.hide();
							return;
						}

						$status.text( '' );
						var currentIds = [];
						$( '#negar-brands-list .negar-brand-card' ).each( function() {
							currentIds.push( parseInt( $( this ).attr( 'data-term-id' ), 10 ) );
						} );

						brands.forEach( function( b ) {
							var isAdded = currentIds.indexOf( parseInt( b.term_id, 10 ) ) !== -1;
							var $item = $( '<div class="negar-brand-search-item" role="option"></div>' );

							var $info = $( '<div style="display:flex;align-items:center;gap:8px;"></div>' );
							if ( b.logo_url ) {
								$info.append( $( '<img>', {
									src: b.logo_url,
									width: 32,
									height: 24,
									style: 'object-fit:contain;background:#f0f0f1;border-radius:2px;'
								} ) );
							}
							$info.append( $( '<span></span>' ).text( b.name + ' (' + b.count + ' محصول)' ) );

							var $btn = $( '<button type="button" class="button button-small"></button>' );
							if ( isAdded ) {
								$btn.text( 'اضافه شده' ).prop( 'disabled', true );
							} else {
								$btn.text( '+ افزودن' ).addClass( 'button-primary' );
								$btn.on( 'click', function( e ) {
									e.stopPropagation();
									addBrand( b );
									$btn.text( 'اضافه شده' ).prop( 'disabled', true ).removeClass( 'button-primary' );
								} );
							}

							$item.append( $info ).append( $btn );
							$results.append( $item );
						} );
						$results.show();
					} else {
						$results.empty().hide();
						$status.text( ( response && response.data && response.data.message ) || messages.error || 'خطا در جست‌وجوی برندها.' );
					}
				},
				error: function() {
					$spinner.removeClass( 'is-active' );
					$results.empty().hide();
					$status.text( messages.error || 'خطا در ارتباط با سرور.' );
				}
			} );
		}, 300 );
	} );

	// Hide results when clicking outside
	$( document ).on( 'click', function( e ) {
		if ( ! $( e.target ).closest( '.negar-brand-search-box' ).length ) {
			$( '#negar-brand-search-results' ).hide();
		}
	} );

	// Move Up (keyboard & click)
	$( document ).on( 'click', '.negar-brand-btn-up', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-brand-card' );
		var prev = card.prev( '.negar-brand-card' );
		if ( prev.length ) {
			card.insertBefore( prev );
			reindexBrands();
			$( this ).focus();
		}
	} );

	// Move Down (keyboard & click)
	$( document ).on( 'click', '.negar-brand-btn-down', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-brand-card' );
		var next = card.next( '.negar-brand-card' );
		if ( next.length ) {
			card.insertAfter( next );
			reindexBrands();
			$( this ).focus();
		}
	} );

	// Remove brand
	$( document ).on( 'click', '.negar-brand-btn-remove', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-brand-card' );
		card.remove();
		reindexBrands();
	} );

	// Select custom logo from Media Library
	$( document ).on( 'click', '.negar-brand-btn-select-logo', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-brand-card' );
		var $logoId = card.find( '.negar-brand-logo-override-id' );
		var $preview = card.find( '.negar-brand-card__logo-preview' );
		var $badge = card.find( '.negar-brand-card__override-badge' );
		var $removeBtn = card.find( '.negar-brand-btn-remove-logo' );

		if ( typeof wp === 'undefined' || ! wp.media ) {
			return;
		}

		var frame = wp.media( {
			title: messages.selectLogo || 'انتخاب تصویر لوگوی اختصاصی',
			button: { text: messages.useLogo || 'استفاده از این تصویر' },
			multiple: false,
			library: { type: 'image' }
		} );

		frame.on( 'select', function() {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			if ( attachment && attachment.id ) {
				$logoId.val( attachment.id );
				var thumbUrl = ( attachment.sizes && attachment.sizes.thumbnail ) ? attachment.sizes.thumbnail.url : attachment.url;
				$preview.attr( 'src', thumbUrl );
				$badge.show();
				$removeBtn.show();
			}
		} );

		frame.open();
	} );

	// Remove custom logo override
	$( document ).on( 'click', '.negar-brand-btn-remove-logo', function( e ) {
		e.preventDefault();
		var card = $( this ).closest( '.negar-brand-card' );
		var $logoId = card.find( '.negar-brand-logo-override-id' );
		var $preview = card.find( '.negar-brand-card__logo-preview' );
		var $badge = card.find( '.negar-brand-card__override-badge' );
		var fallbackUrl = $preview.attr( 'data-fallback-url' ) || '';

		$logoId.val( '0' );
		$preview.attr( 'src', fallbackUrl );
		$badge.hide();
		$( this ).hide();
	} );

	// Setup on ready
	$( function() {
		reindexBrands();
	} );

} )( jQuery );

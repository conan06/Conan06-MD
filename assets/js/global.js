/* global ConanMDScreenReaderText */
(function( $ ) {

	// Variables and DOM Caching.
	var $body = $( 'body' ),
		$customHeader = $body.find( '.custom-header' ),
		$branding = $customHeader.find( '.site-branding-text' ),
		$navigation = $body.find( '.navigation-top' ),
		$navMenuItem = $navigation.find( '.menu-item' ),
		$navMenuBtn = $navigation.find( '.mdl-layout__drawer-button' ),
		$navMenuRow = $navigation.find( '.mdl-layout__header-row' ),
		$navSearchBtn = $navigation.find( '#navigation-search-btn' ),
		$navSearchBack = $navigation.find( '.nav-search-form-back' ),
		$navSearchForm = $navigation.find( '.nav-search-form-input' ),
		$navSearchClear = $navigation.find( '#nav-search-form-clear' ),
		$sidebar = $body.find( '#secondary' ),
		$entryTitle = $body.find( '.entry-title' ),
		$entryHeader = $body.find( '.single-info' ),
		$entryHeaderTitle = $entryHeader.find( '.single-info-header' ),
		$entryInfoTitle = $entryHeaderTitle.find( '.single-info-title' ),
		$entryContent = $body.find( '.entry-content' ),
		$formatQuote = $body.find( '.format-quote blockquote' ),
		$pageFAButton = $body.find( '.mdl-button--fab' ),
		isFrontPage = $body.hasClass( 'ConanMD-front-page' ) || $body.hasClass( 'home blog' ),
		navigationFixedClass = 'site-navigation-fixed',
		navigationShadowClass = 'mdl-shadow--4dp',
		navigationShowTitleClass = 'site-navigation-show-title',
		navigationSearhActiveClass = 'search-bar-active',
		navigationHeight,
		navigationOuterHeight,
		navMenuItemHeight,
		idealNavHeight,
		navIsNotTooTall,
		headerOffset,
		titleOffset,
		windowOffset,
		menuTop = 0,
		resizeTimer;

	// Ensure the sticky navigation doesn't cover current focused links.
	$( 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex], [contenteditable]', '.site-content-contain' ).filter( ':visible' ).focus( function() {
		if ( $navigation.hasClass( 'site-navigation-fixed' ) ) {
			var windowScrollTop = $( window ).scrollTop(),
				fixedNavHeight = $navigation.height(),
				itemScrollTop = $( this ).offset().top,
				offsetDiff = itemScrollTop - windowScrollTop;

			// Account for Admin bar.
			if ( $( '#wpadminbar' ).length ) {
				offsetDiff -= $( '#wpadminbar' ).height();
			}

			if ( offsetDiff < fixedNavHeight ) {
				$( window ).scrollTo( itemScrollTop - ( fixedNavHeight + 50 ), 0 );
			}
		}
	});

	// Set properties of navigation.
	function setNavProps() {
		navigationHeight      = $navigation.height();
		navigationOuterHeight = $navigation.outerHeight();
		navMenuItemHeight     = $navMenuItem.outerHeight() * 2;
		idealNavHeight        = navMenuItemHeight;
		navIsNotTooTall       = navigationHeight <= idealNavHeight;
	}

	// Make navigation 'stick'.
	function adjustScrollClass() {

		// When there's a custom header image or video, the header offset includes the height of the navigation.
		if ( ! $branding.length ) {
			if ( $entryHeader.hasClass( 'single-info-with-image' ) ) {
				headerOffset = $entryHeaderTitle.offset().top - $navigation.height();
			} else {
				headerOffset = $navigation.height();
			}
		} else {
			headerOffset = $branding.offset().top - $navigation.height();
		}
		
		// If the scroll is more than the custom header, set the fixed class.
		if ( $( window ).scrollTop() >= headerOffset ) {
			$navigation.addClass( navigationFixedClass );
			$navMenuRow.addClass( navigationShadowClass );
		} else {
			$navigation.removeClass( navigationFixedClass );
			$navMenuRow.removeClass( navigationShadowClass );
		}


		if ( $entryTitle.length ) {
			titleOffset = $entryTitle.offset().top - $entryTitle.height();
		} else if ( $entryInfoTitle.length ) {
			titleOffset = $entryInfoTitle.offset().top - 0.5 * $entryInfoTitle.height();
		} else {
			titleOffset = 0;
		}

		if ( titleOffset ) {
			if ( $( window ).scrollTop() >= titleOffset ) {
				$navigation.addClass( navigationShowTitleClass );
			} else {
				$navigation.removeClass( navigationShowTitleClass );
			}
		}
		
	}

	// Set icon for quotes.
	function setQuotesIcon() {
		$( ConanMDScreenReaderText.quote ).prependTo( $formatQuote );
	}

	// When click menu button in navigation, fixed the body
	function fixedBodyScroll() {

		if ( $navMenuBtn.length ) {

			if ( $body.hasClass( 'admin-bar' ) ) {
				menuTop -= $( '#wpadminbar' ).height();
			}
			if ( ! windowOffset ) {
				windowOffset = 0;
			}

			$navMenuBtn.click( function() {
				$body.css({
					"position":"fixed",
					"height":"100%",
					"overflow-y":"scroll",
					"left":"0",
					"top":-windowOffset + "px"
				});

				if ( menuTop ) {
					$( 'html' )[0].style.setProperty( 'margin-top', '0px', 'important' );
					$( 'body' )[0].style.setProperty( 'margin-top', -menuTop + 'px', 'important' );
				}

				// fix Prallax visibility
				//$( '.parallax-mirror' ).each(function () {
				//	this.style.setProperty( 'visibility', 'hidden', 'important' );
				//});

			});

		}
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass( param ) {
		var sidebarPos, sidebarPosBottom;

		if ( ! $body.hasClass( 'has-sidebar' ) || (
			$body.hasClass( 'search' ) ||
			$body.hasClass( 'single-attachment' ) ||
			$body.hasClass( 'error404' ) ||
			$body.hasClass( 'ConanMD-front-page' )
		) ) {
			return;
		}

		sidebarPos       = $sidebar.offset();
		sidebarPosBottom = sidebarPos.top + ( $sidebar.height() + 28 );

		$entryContent.find( param ).each( function() {
			var $element = $( this ),
				elementPos = $element.offset(),
				elementPosTop = elementPos.top;

			// Add 'below-entry-meta' to elements below the entry meta.
			if ( elementPosTop > sidebarPosBottom ) {
				$element.addClass( 'below-entry-meta' );
			} else {
				$element.removeClass( 'below-entry-meta' );
			}
		});
	}

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	function supportsInlineSVG() {
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( 'undefined' !== typeof SVGRect && div.firstChild && div.firstChild.namespaceURI );
	}

	/**
	 * Test if an iOS device.
	*/
	function checkiOS() {
		return /iPad|iPhone|iPod/.test(navigator.userAgent) && ! window.MSStream;
	}

	/*
	 * Test if background-attachment: fixed is supported.
	 * @link http://stackoverflow.com/questions/14115080/detect-support-for-background-attachment-fixed
	 */
	function supportsFixedBackground() {
		var el = document.createElement('div'),
			isSupported;

		try {
			if ( ! ( 'backgroundAttachment' in el.style ) || checkiOS() ) {
				return false;
			}
			el.style.backgroundAttachment = 'fixed';
			isSupported = ( 'fixed' === el.style.backgroundAttachment );
			return isSupported;
		}
		catch (e) {
			return false;
		}
	}

	// Fire on document ready.
	$( document ).ready( function() {

		// If navigation menu is present on page, setNavProps and adjustScrollClass.
		if ( $navigation.length ) {
			setNavProps();
			adjustScrollClass();
		}

		//fixedBodyScroll();

		setQuotesIcon();
		if ( true === supportsInlineSVG() ) {
			document.documentElement.className = document.documentElement.className.replace( /(\s*)no-svg(\s*)/, '$1svg$2' );
		}

		if ( true === supportsFixedBackground() ) {
			document.documentElement.className += ' background-fixed';
		}

		// Search button events
		$navSearchBtn.click( function() {
			if ( $navSearchForm.find( '#navigation-search' ).val() ) {
				$navSearchForm.find( '#navigation-search' ).val("");
				$navSearchForm.find( '.mdl-textfield' ).removeClass( 'is-dirty' );
			} 
			$navigation.addClass( navigationSearhActiveClass );
			$navSearchForm.find( '#navigation-search' ).focus();
		});

		$navSearchBack.click( function() {
			$navigation.removeClass( navigationSearhActiveClass );
			$navSearchForm.find( '#navigation-search' ).val("");
			$navSearchForm.find( '.mdl-textfield' ).removeClass( 'is-dirty' );
		});

		$navSearchForm.on('input', function() {
			if ( $( this ).find( '#navigation-search' ).val() ) {
				$navSearchClear.fadeIn(200);
			} else {
				$navSearchClear.fadeOut(200);
			}
		});

		$navSearchClear.click( function() {
			$navSearchForm.find( '#navigation-search' ).val("");
			$navSearchForm.find( '#navigation-search' ).focus();
			$navSearchForm.find( '.mdl-textfield' ).removeClass( 'is-dirty' );
			$( this ).fadeOut(200);
		});

		$pageFAButton.click( function() {
			$( this ).toggleClass( 'is-active' );
		});

	});

	// If navigation menu is present on page, adjust it on scroll and screen resize.
	if ( $navigation.length ) {

		// On scroll, we want to stick/unstick the navigation.
		$( window ).on( 'scroll', function() {
			adjustScrollClass();
			windowOffset = $( window ).scrollTop();
		});

		// Also want to make sure the navigation is where it should be on resize.
		$( window ).resize( function() {
			setNavProps();
			setTimeout( adjustScrollClass, 500 );
		});
	}

	$( window ).resize( function() {
		clearTimeout( resizeTimer );
		resizeTimer = setTimeout( function() {
			belowEntryMetaClass( 'blockquote.alignleft, blockquote.alignright' );
		}, 300 );
	});


	// Add header video class after the video is loaded.
	$( document ).on( 'wp-custom-header-video-loaded', function() {
		$body.addClass( 'has-header-video' );
	});


})( jQuery );

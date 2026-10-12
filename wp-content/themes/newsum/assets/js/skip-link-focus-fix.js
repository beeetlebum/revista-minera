/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard-only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}

	// Modern cross-browser skip-link focus handler
	document.addEventListener( 'DOMContentLoaded', function() {
		var skipLink = document.querySelector( '.skip-link' );
		if ( skipLink ) {
			skipLink.addEventListener( 'click', function( e ) {
				var targetId = this.getAttribute( 'href' ).replace( '#', '' );
				var target = document.getElementById( targetId );
				if ( target ) {
					target.setAttribute( 'tabindex', '-1' );
					target.focus();
				}
			} );
		}
	} );
}() );

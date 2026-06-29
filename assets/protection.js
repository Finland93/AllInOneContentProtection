/**
 * All in one Content Protection — front-end behaviour.
 * Reads the AIOCP config (localised from PHP) and wires up only the
 * protections that are enabled in the settings.
 */
( function () {
	'use strict';

	var c = window.AIOCP || {};

	function on( type, handler ) {
		document.addEventListener( type, handler, true );
	}
	function prevent( e ) {
		e.preventDefault();
	}

	if ( c.contextmenu ) {
		on( 'contextmenu', prevent );
	}

	if ( c.selection ) {
		on( 'selectstart', prevent );
		on( 'copy', prevent );
		on( 'cut', prevent );
	}

	if ( c.drag ) {
		on( 'dragstart', prevent );
	}

	if ( c.shortcuts ) {
		on( 'keydown', function ( e ) {
			var k = ( e.key || '' ).toLowerCase();

			// Don't fight the user while they're typing in a field.
			var tag = ( e.target && e.target.tagName ) ? e.target.tagName.toLowerCase() : '';
			var editable = 'input' === tag || 'textarea' === tag || ( e.target && e.target.isContentEditable );

			if ( ( e.ctrlKey || e.metaKey ) && ! editable ) {
				if ( [ 'c', 'a', 'u', 's', 'p', 'x' ].indexOf( k ) !== -1 ) {
					e.preventDefault();
				}
				// Dev tools (Ctrl/Cmd+Shift+I/J/C).
				if ( e.shiftKey && [ 'i', 'j', 'c' ].indexOf( k ) !== -1 ) {
					e.preventDefault();
				}
			}
			if ( 'f12' === k ) {
				e.preventDefault();
			}
		} );
	}

	if ( c.printProtect ) {
		window.addEventListener( 'beforeprint', function () {
			document.body.style.visibility = 'hidden';
		} );
		window.addEventListener( 'afterprint', function () {
			document.body.style.visibility = '';
		} );
	}

	if ( c.consoleMsg ) {
		try {
			console.log(
				'%c\u26A0\uFE0F ' + ( c.consoleTitle || 'STOP!' ),
				'color:#d63638;font-size:48px;font-weight:900;'
			);
			if ( c.consoleText ) {
				console.log( '%c' + c.consoleText, 'font-size:16px;font-weight:bold;' );
			}
		} catch ( err ) {}
	}
} )();

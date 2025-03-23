/* global  */

/**
 * Custom JS
 */

( function ( $ ) {

	"use strict";

	window.PLUGIN_CLASS_Script = {

		init: function () {
			console.log( 'Custom Script Loaded.' );
		}
	}

	$( document ).on( 'ready', function () {
		PLUGIN_CLASS_Script.init();
	});

} )( jQuery );

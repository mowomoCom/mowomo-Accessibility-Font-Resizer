
/*
 * JavaScript functions for mowomo Font Resizer.
 * Uses js.cookie.js
 */

var mwm_font_resizer_callback    = jQuery.Callbacks(); // Callback function to be fired after font resize.

jQuery.fn.mwm_font_resizer_manager = function () {
	var mwm_font_resizer_value       = jQuery('#mwm_font_resizer_value').val();
	var mwm_font_resizer_ownid       = jQuery('#mwm_font_resizer_ownid').val();
	var mwm_font_resizer_ownelement  = jQuery('#mwm_font_resizer_ownelement').val();
	var mwm_font_resizer_resizeMax   = parseFloat( jQuery('#mwm_font_resizer_resizeMax').val() );
	var mwm_font_resizer_resizeMin   = parseFloat( jQuery('#mwm_font_resizer_resizeMin').val() );
	var mwm_font_resizer_resizeSteps = parseFloat( jQuery('#mwm_font_resizer_resizeSteps').val() );
	var mwm_font_resizer_cookieTime  = parseFloat( jQuery('#mwm_font_resizer_cookieTime').val() );
	var mwm_font_resizer_element     = mwm_font_resizer_value;

	if (mwm_font_resizer_value == "innerbody") {
		mwm_font_resizer_element = "div#innerbody";
	} else if(mwm_font_resizer_value == "ownid") {
		mwm_font_resizer_element = "div#" + mwm_font_resizer_ownid;
	} else if(mwm_font_resizer_value == "ownelement") {
		mwm_font_resizer_element = mwm_font_resizer_ownelement;
	}

	var startFontSize = parseFloat( jQuery(mwm_font_resizer_element + "").css("font-size") );
	var savedSize = parseFloat( Cookies.get('fontSize') );

	if ( savedSize > mwm_font_resizer_resizeMin && savedSize < mwm_font_resizer_resizeMax ) {
		jQuery(mwm_font_resizer_element).css("font-size", savedSize + "px");
	}

	/* The Click events */
	jQuery('.mwm_font_resizer_add').click(function() {
		var newFontSize = parseFloat(jQuery(mwm_font_resizer_element + "").css("font-size"));
		newFontSize = newFontSize + parseFloat(mwm_font_resizer_resizeSteps);
		newFontSize = newFontSize.toFixed(2);
		var maxFontSize = startFontSize + ( mwm_font_resizer_resizeSteps * 5 );
		if (newFontSize > maxFontSize) { return false; }
		if (newFontSize > mwm_font_resizer_resizeMax) { return false; }
		jQuery(mwm_font_resizer_element + "").css("font-size", newFontSize + "px");
		Cookies.set('fontSize', newFontSize, {expires: parseInt(mwm_font_resizer_cookieTime), path: '/'});

		/*
		 * Callback function to be fired after font resize.
		 *
		 * @since 1.7.1
		 *
		 * Example code for using the callback:
		 *
		 * jQuery(document).ready(function($) {
		 *     mwm_font_resizer_callback.add( my_callback_function );
		 * });
		 *
		 * function my_callback_function( newFontSize ) {
		 *     console.log( 'This is the new fontsize: ' + newFontSize );
		 *     return false;
		 * }
		 *
		 */
		mwm_font_resizer_callback.fire( newFontSize );

		return false;
	});
	jQuery('.mwm_font_resizer_minus').click(function() {
		var newFontSize = parseFloat(jQuery(mwm_font_resizer_element + "").css("font-size"))
		newFontSize = newFontSize - mwm_font_resizer_resizeSteps;
		newFontSize = newFontSize.toFixed(2);
		var minFontSize = startFontSize - ( mwm_font_resizer_resizeSteps * 5 );
		if (newFontSize < minFontSize) { return false; }
		if (newFontSize < mwm_font_resizer_resizeMin) { return false; }
		jQuery("" + mwm_font_resizer_element + "").css("font-size", newFontSize + "px");
		Cookies.set('fontSize', newFontSize, {expires: parseInt(mwm_font_resizer_cookieTime), path: '/'});

		/*
		 * Callback function to be fired after font resize.
		 *
		 * @since 1.7.1
		 *
		 * Example code for using the callback:
		 *
		 * jQuery(document).ready(function($) {
		 *     mwm_font_resizer_callback.add( my_callback_function );
		 * });
		 *
		 * function my_callback_function( newFontSize ) {
		 *     console.log( 'This is the new fontsize: ' + newFontSize );
		 *     return false;
		 * }
		 *
		 */
		mwm_font_resizer_callback.fire( newFontSize );

		return false;
	});
	jQuery('.mwm_font_resizer_reset').click(function() {
		jQuery("" + mwm_font_resizer_element + "").css("font-size", startFontSize);
		Cookies.set('fontSize', startFontSize, {expires: parseInt(mwm_font_resizer_cookieTime), path: '/'});

		/*
		 * Callback function to be fired after font resize.
		 *
		 * @since 1.7.1
		 *
		 * Example code for using the callback:
		 *
		 * jQuery(document).ready(function($) {
		 *     mwm_font_resizer_callback.add( my_callback_function );
		 * });
		 *
		 * function my_callback_function( newFontSize ) {
		 *     console.log( 'This is the new fontsize: ' + newFontSize );
		 *     return false;
		 * }
		 *
		 */
		mwm_font_resizer_callback.fire( startFontSize );

		return false;
	});
}


jQuery(document).ready(function(){
	jQuery(".mwm_font_resizer").mwm_font_resizer_manager();
});

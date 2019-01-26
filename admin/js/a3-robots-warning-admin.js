(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(document).ready(function(){
		var $button = $('#a3rw_confirm_seo_new_ip');		

		$button.on('click', function(e){			
			$button.after('<div class="lds-css ng-scope" style="display: inline-block;vertical-align: text-top;"><div class="lds-gear" style="transform:scale(0.1)"><div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div>');

			$.get(a3_ajax_object.ajaxurl + '?action=a3rw_confirm_seo_new_ip&security=' + a3_ajax_object.ajax_nonce, {}, function(resp){
				if(resp.success) $button.closest('.notice').remove();
			});
		});
	 });

})( jQuery );

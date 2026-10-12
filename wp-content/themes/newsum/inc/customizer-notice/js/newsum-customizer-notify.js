(function (api) {

	// Register section constructor for plugin notification section
	api.sectionConstructor['newsum-customizer-plugin-notify-section'] = api.Section.extend(
		{

			// No events for this type of section.
			attachEvents: function () {
			},

			// Always make the section active.
			isContextuallyActive: function () {
				return true;
			}
		}
	);

})( wp.customize );

jQuery( document ).ready(
	function () {

		jQuery( '.newsum-customizer-notify-dismiss-recommended-action' ).click(
			function () {
				var id = jQuery( this ).attr( 'id' );
				jQuery( this ).parent().hide();
			}
		);

		jQuery( '.newsum-customizer-notify-dismiss-button-recommended-plugin' ).click(
			function () {
				var id = jQuery( this ).attr( 'id' );
				jQuery( this ).parent().parent().hide();
			}
		);

		// Handle activate/install button click
		jQuery(document).on('click', '.activate-now, .install-now', function(e) {
			e.preventDefault();
			var $btn = jQuery(this);
			
			if ( $btn.hasClass('updating-message') ) {
				return;
			}
			
			// Show loading state
			$btn.addClass('updating-message').text( newsum_customizer_ajax.installing_text );
			$btn.css({'opacity': '0.8', 'pointer-events': 'none'});
			
			// Fire AJAX request
			jQuery.ajax({
				url: newsum_customizer_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'newsum_install_activate_plugins',
					_ajax_nonce: newsum_customizer_ajax.nonce
				},
				success: function(response) {
					if (response.success) {
						// Success! Reload the customizer to load the new widgets
						window.location.reload();
					} else {
						alert( newsum_customizer_ajax.error_text );
						$btn.removeClass('updating-message').text( newsum_customizer_ajax.button_text );
						$btn.css({'opacity': '1', 'pointer-events': 'auto'});
					}
				},
				error: function() {
					alert( newsum_customizer_ajax.error_text );
					$btn.removeClass('updating-message').text( newsum_customizer_ajax.button_text );
					$btn.css({'opacity': '1', 'pointer-events': 'auto'});
				}
			});
		});
	}
);

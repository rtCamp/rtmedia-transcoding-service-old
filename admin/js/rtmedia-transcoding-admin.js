jQuery( document ).on( 'click', '.rtm-transcoding-try-now', function ( e ) {
	e.preventDefault();
	jQuery( '.rtm-transcoding-new-key-spinner' ).addClass( 'is-active' );
	// todo confirmation message
	var data = {
		action: 'rtmedia_free_encoding_subscribe',
		nonce: jQuery('#rtm_transcoding_settings_nonce' ).val()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.getJSON( ajaxurl, data, function ( response ) {
		jQuery( '.rtm-transcoding-new-key-spinner' ).removeClass( 'is-active' );
		if ( response.error === undefined && response.apikey ) {
			var tempUrl = window.location.href;
			var hash = window.location.hash;
			tempUrl = tempUrl.replace( hash, '' );
			document.location.href = tempUrl + '&apikey=' + response.apikey + hash;
		} else {
			jQuery( '.rtm-transcoding-settings-error' ).remove();
			jQuery( '.rtm-transcoding-settings' ).before( '<div class="error rtm-transcoding-settings-error"><p>' + response.error + '</p></div>' );
		}
	} );
} );

jQuery( document ).on( 'click', '.rtm-disable-transcoding', function ( e ) {
	e.preventDefault();
	// todo confirmation message
	jQuery( '.rtm-enable-disable-spinner-transcoding' ).addClass( 'is-active' );
	var data = {
		action: 'rtm_disable_transcoding',
		nonce: jQuery('#rtm_transcoding_settings_nonce' ).val()
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post( ajaxurl, data, function ( response ) {
		jQuery( '.rtm-enable-disable-spinner-transcoding' ).removeClass( 'is-active' );
		if ( response ) {
			jQuery( '.settings-error-encoding-disabled' ).remove();

			if ( jQuery( '#rtm-transcoding-settings-updated' ).length > 0 ) {
				jQuery( '#rtm-transcoding-settings-updated p' ).html( response );
			} else {
				jQuery( '.rtm-transcoding-settings' ).before( '<div class="updated" id="rtm-transcoding-settings-updated"><p>' + response + '</p></div>' );
			}
			jQuery( '.rtm-disable-transcoding' ).hide();
			jQuery( '.rtm-enable-transcoding' ).show();
		} else {
			jQuery( '.rtm-transcoding-settings-error' ).remove();
			jQuery( '.rtm-transcoding-settings' ).before( '<div class="error rtm-transcoding-settings-error"><p>Something went wrong. Please <a href onclick="location.reload();">refresh</a> page.</p></div>' );
		}
	} );
} );

jQuery( document ).on( 'click', '.rtm-enable-transcoding', function ( e ) {
	e.preventDefault();
	// todo confirmation message

	jQuery( '.rtm-enable-disable-spinner-transcoding' ).addClass( 'is-active' );
	var data = {
		action: 'rtm_enable_transcoding',
		nonce: jQuery('#rtm_transcoding_settings_nonce' ).val()
	};
	jQuery.post( ajaxurl, data, function ( response ) {
		jQuery( '.rtm-enable-disable-spinner-transcoding' ).removeClass( 'is-active' );
		if ( response ) {
			jQuery( '.settings-error-encoding-enabled' ).remove();

			if ( jQuery( '#rtm-transcoding-settings-updated' ).length > 0 ) {
				jQuery( '#rtm-transcoding-settings-updated p' ).html( response );
			} else {
				jQuery( '.rtm-transcoding-settings' ).before( '<div class="updated" id="rtm-transcoding-settings-updated"><p>' + response + '</p></div>' );
			}
			jQuery( '.rtm-enable-transcoding' ).hide();
			jQuery( '.rtm-disable-transcoding' ).show();
		} else {
			jQuery( '.rtm-transcoding-settings-error' ).remove();
			jQuery( '.rtm-transcoding-settings' ).before( '<div class="error rtm-transcoding-settings-error"><p>Something went wrong. Please <a href onclick="location.reload();">refresh</a> page.</p></div>' );
		}
	} );
} );
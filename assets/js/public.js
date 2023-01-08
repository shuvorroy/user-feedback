( function ( $ ) {
	'use strict';

	const UserFeedbackPublic = {

		init: function () {
			this.loadElement();
			this.loadEvents();
		},

		vars: user_feedback,

		loadElement: function () {
			//element object
			this.$body = $( 'body' );
			this.$document = $( document );
			this.$feedback_form = $( '.user-feedback' );
		},

		loadEvents: function() {
			let self = this;
			this.$feedback_form.on( 'submit', this.submitForm.bind( self ) );
			this.$feedback_form.on( 'change', 'input, textarea', this.validateField.bind( self ) );
		},

		submitForm: function ( e ) {
			e.preventDefault();
			let $el = $( e.target );
			let form_data = this.convertFormToJSON( $el );

			if ( !this.validateForm(form_data) ) return; //validate form data.

            form_data['security'] = this.vars.security;
			$el.find( '.loader-container' ).addClass( 'show' );
			$.ajax( {
				url: this.vars.ajaxurl,
				type: 'POST',
				data: form_data,
				success: function ( response ) {
					$el.html( `<p class="submit-message ${response.class}">${response.message}</p>` );
					$( 'html, body' ).stop().animate( {
						scrollTop: $el.offset().top - 100
					}, 600 );
				}
			} );
		},

        convertFormToJSON: function ( form ) {
            return $(form)
            .serializeArray()
            .reduce(function (json, { name, value }) {
                json[name] = value;
                return json;
            }, {});
        },

		validateForm: function (form_data) {
			var valid = true;
			for (let key in form_data) {
				let value = form_data[key];
				if ( value === '' ) {
					if ( ['firstname', 'lastname', 'subject', 'email'].includes( key ) ) {
						let $el = this.$feedback_form.find(`input[name="${key}"]`);
						$el.parent().find('span').remove();
						$el.addClass('input-error')
						.after(`<span class="input-error">${user_feedback.input_errors.text}</span>`);
						valid = false;
					}
					if ( ['message'].includes( key ) ) {
						let $el = this.$feedback_form.find(`textarea[name="${key}"]`);
						$el.parent().find('span').remove();
						$el.addClass('input-error')
						.after(`<span class="input-error">${user_feedback.input_errors.text}</span>`);
						valid = false;
					}
				} else {
					if ( ['email'].includes( key ) ) {
						if ( !this.validateEmail( value ) ) {
							let $el = this.$feedback_form.find(`input[name="${key}"]`);
							$el.parent().find('span').remove();
							$el.addClass('input-error')
							.after(`<span class="input-error">${user_feedback.input_errors.email}</span>`);
							valid = false
						}
					}
				}
			}
			return valid;
		},

		validateField: function (e) {
			let $el = $( e.target );
			let value = $el.val();
			if ( value !== '' ) {
				$el.removeClass('input-error');
				$el.parent().find('span').remove();
			}
		}, 

		validateEmail: function (email) {
			const res = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  			return res.test(String(email).toLowerCase());
		}
	}

	$( document ).ready( function () {
		UserFeedbackPublic.init();
	} );
} )( jQuery );
( function ( $ ) {
	'use strict';

	const UserFeedbackAdmin = {

		init: function () {
			this.loadElement();
			this.loadEvents();
			if ( this.$feedback_list_container.length ) {
				this.loadFeedback();
			}
		},

		vars: user_feedback,

		templates: {
			user_feedbacks: wp.template( 'user_feedbacks' ),
			user_feedback_details: wp.template( 'user_feedback_details' ),
			user_feedback_loader: wp.template( 'user_feedback_loader' )
		},

		loadElement: function () {
			//element object
			this.$body = $( 'body' );
			this.$document = $( document );
			this.$feedback_list_container = $( '.user-feedback-list-container' );
			this.$feedback_list = $( '.feedback-list' );
			this.$feedback_list_filter_form = $( '.feedback-list-filter-form' );
			this.$feedback_list_reset = $('.feedback-list-reset');

			//element html class name
			this.feedback_list_pagination_item = '.feedback-list-pagination .page-item';
			this.feedback_list_row = '.feedback-list-row';
			this.feedback_item_details = '.feedback-item-details';
			this.feedback_remove = '.feedback-remove';
		},

		loadEvents: function() {
			let self = this;
			this.$feedback_list_filter_form.on( 'submit', this.filterList.bind( self ) );
			this.$feedback_list_filter_form.find( 'select[name="order"]' ).on( 'change', this.filterListSort.bind( self ) );
			this.$feedback_list_filter_form.find( 'select[name="per_page"]' ).on( 'change', this.filterListNumber.bind( self ) );
			this.$feedback_list_reset.on( 'click', this.resetSearch.bind( self ) );

			this.$feedback_list_container.on( 'click', this.feedback_list_pagination_item, self.paginateList.bind( self ) );
			this.$feedback_list_container.on( 'click', this.feedback_list_row, self.loadDetails.bind( self ) );
			this.$feedback_list_container.on( 'click', this.feedback_remove, self.removeFeedback.bind( self ) );
		},

		loadFeedback: function( current_page = 1) {
			let self = this;
			var data = {
				action: 'get_user_feedbacks',
				security: this.vars.security,
				current_page: current_page,
				search: this.$feedback_list_filter_form.find( 'input[name="search"]' ).val(),
				per_page: this.$feedback_list_filter_form.find( 'select[name="per_page"]' ).val(),
				order: this.$feedback_list_filter_form.find( 'select[name="order"]' ).val()
			};

			this.vars.current_page = current_page;
			self.$feedback_list_container.find( '.loader-container' ).addClass( 'loading' );

			this.getFeeback( data ).then(
                function( feedbacks ) {
                    self.$feedback_list.html( self.templates.user_feedbacks( feedbacks ) );
					self.$feedback_list_container.find( '.loader-container' ).removeClass( 'loading' );
                },
                function( error ) {
                    console.log( error );
                }
            );
		}, 
		getFeeback: async function( data ) {
			return await $.ajax( {
				url: this.vars.ajaxurl,
				type: 'POST',
				data: data,
				async: true,
			} );
		},

		filterList: function( e ) {
			e.preventDefault();
			this.loadFeedback();
		},

		filterListNumber: function( e ) {
			e.preventDefault();
			this.loadFeedback();
		},

		resetSearch: function( e ) {
			e.preventDefault();
			this.$feedback_list_filter_form.find( 'input[name="search"]' ).val( '' )
			this.$feedback_list_filter_form.submit();
		},

		filterListSort: function( e ) {
			e.preventDefault();
			this.loadFeedback();
		},

		paginateList: function( e ) {
			e.preventDefault();
			let $el = $( e.target );
			this.loadFeedback( $el.data( 'page' ) );
		},

		loadDetails: function( e ) {
			e.preventDefault();
			let self = this;
			let $el = $( e.target );

			if ( ! $el.is( this.feedback_list_row ) ) return;

			let is_active = $el.hasClass( 'active' );
			let $all_rows = $( this.feedback_list_row );
			$all_rows.removeClass( 'active' );

			if ( is_active ) {
				$el.find( this.feedback_item_details ).html( '' );
				return;
			} else {
				$all_rows.find( this.feedback_item_details ).html( '' );
				$el.find( self.feedback_item_details ).html( self.templates.user_feedback_loader( {} ) );
				var data = {
					action: 'get_user_feedbacks',
					security: this.vars.security,
					id: $el.data( 'feedback_id' ),
					single: 1
				};
				this.getFeeback( data ).then(
					function( feedback ) {
						$el.addClass( 'active' );
						$el.find( self.feedback_item_details ).html( self.templates.user_feedback_details( feedback ) );
					},
					function( error ) {
						console.log( error );
					}
				);
			}
		},

		removeFeedback: function( e ) {
			let self = this;
			let $el = $( e.target );

			if ( confirm( "Are you sure?" ) ) {
				let data = {
					action: 'get_user_feedbacks',
					security: this.vars.security,
					id: $el.data( 'feedback_id' ),
					remove: 1,
					single: 1
				}
				this.getFeeback( data ).then(
					function( feedback ) {
						self.loadFeedback( self.vars.current_page );
					},
					function( error ) {
						console.log( error );
					}
				);
			}
		}
	}

	$( document ).ready( function () {
		UserFeedbackAdmin.init();
	} );
} )( jQuery );
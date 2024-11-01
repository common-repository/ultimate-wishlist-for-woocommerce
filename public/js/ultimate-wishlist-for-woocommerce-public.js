(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 */
 jQuery(document).ready(function() {

	/**==========================================================================================
	 *                              Object constants/Variables.
	=========================================================================================== */  
	
		// Datasets.
		let strings  = mwb_wfw_obj.strings;
		let settings = mwb_wfw_obj.settings;
		let user     = mwb_wfw_obj.user;
		// Settings Pickups.
		let permalink = mwb_wfw_obj.permalink;
		// Wishlist triggers/objects.
		let wishlistTrigger        = jQuery( '.add-to-wishlist' );
		let wishlistPopup          = jQuery( '.mwb-wfw-wishlist-dialog' );
		let addWishlistMetaTrigger = jQuery( '.wfw-action-comment' );
		let addWishlistMetaPopup   = jQuery( '.wfw_comment_wrapper' );

		// HTML Objects.
		const viewWishlistButton = '<a href="' + permalink + '" class="button mwb-wfw-action-button view">' + strings.view_text + '</a>';
		const processingIconHtml = '<span class="mwb-wfw-wishlist-processing"><i class="fa fa fa-spinner fa-spin"></i>' + strings.processing_text + '...</span>';

		/**==========================================================================================
		 *                               Library Functions
		=========================================================================================== */  

		// Initialise the wishlist action popup.
		const initWishlistPopup = () => {

			if( 'on' != settings.wfw_enable_popup ) return;
			 wishlistPopup.dialog({
				modal: true,
				title: strings.popup_title,
				width : 700,
				autoOpen : false,
				draggable: true,
				closeText: "Close",
				beforeClose: function() {
					wishlistPopupReset();
				},
				buttons: [{
					text: "Close",
					click: function() {
						jQuery( this ).dialog( "close" );
					}
				}]
			});
		}

		// Reset the popup on every close.
		const wishlistPopupReset = () => {
			jQuery( '.mwb-wfw-wishlist-item-img' ).attr( 'src', '' );
			jQuery( '.mwb-wfw-wishlist-item-img' ).trigger( 'change' );
			jQuery( '.mwb-wfw-wishlist-item-details' ).empty();
			jQuery( '.mwb-wfw-wishlist-action-buttons' ).empty();
			jQuery( '.mwb-wfw-wishlist-processing' ).remove();
		}

		// Wishlist process :: Product Add/Remove.
		const processWishtlist = ( obj ) => {
			var productId = obj.attr( 'data-product-id' );
			var wishlistId = obj.attr( 'data-wishlist-id' );
			// Add a flag.
			obj.addClass( 'current-trigger' );
	
			// If wishlist id is available remove from wishlist.
			if( null != wishlistId && '' != wishlistId ) {
				obj.removeClass('active-wishlist');
				obj.attr( 'data-wishlist-id', '' );
				removeFromWishlist( productId, wishlistId );
			}

			// If wishlist id is not available add to wishlist.
			else if ( null !=  productId ) {
				var product = obj.closest( 'li.product' );
				if( ( product.length == 0 ) && ( 'single' == location() ) ) {
					product = obj.parents('div');
				}
				obj.addClass('active-wishlist');
				triggerShowWishlist( productId, product );
			} else {
				triggerError();
			}
		}

		// Ajax Callbacks.
		async function doAjax(args) {
			try {
				return await jQuery.ajax({
					url: mwb_wfw_obj.ajaxurl,
					type: 'POST',
					data: args
				});

			} catch (error) {
				console.error(error);
			}
		}

		// Error Trigger process.
		const triggerError = ( msg='Something Went Wrong. Please Reload.' ) => {
			swal( 'Oopss...', msg, 'error' );
		}

		// Success Trigger process.
		const triggerSuccess = ( msg='' ) => {
			swal( 'Congratulations...', msg, 'success' );
		}

		// Prompt before operation.
		const promptIn = () => {
			swal( "Are you sure?", {
				buttons: {
				  cancel: "No, cancel it!",
				  accept: "Yes, I am sure!",
				},
			  })
			  .then((value) => {
				switch ( value ) {
				  case "accept":
					swal("Gotcha!", "Pikachu was caught!", "success");
					break;

				  default:
					break;
				}
			});
		}

		//  Pick product details from selected product and append to Wishlist.
		const cloneProductDetails = ( product ) => {

			if( product.length == 0 ) {
				console.error( 'Theme Issue:Unable to fetch product. Wishlist Popup wont be shown.' );
				return;
			}

			let images = product.find( '.mwb-wfw-product-image' );
			var src    = images.val();
			if('1' == product.find( '.product_title' ).length) {
				var title = product.find( '.product_title' ).clone();
			}
			else{
				var title = product.find( '.woocommerce-loop-product__title' ).clone();
			};
			if ('1' == product.find( 'p.price' ).length)
			{
				var priceHtml = product.find( 'p.price' ).clone();
			} else {
				var priceHtml = product.find( 'span.price' ).eq(0).clone();
			}
			if ('1' == product.find( 'button.single_add_to_cart_button').length) {
				var addToCartButton = product.find( 'button.single_add_to_cart_button' ).clone();
			} else {
				var addToCartButton = product.find( 'a.add_to_cart_button' ).clone();
			}

			jQuery( '.mwb-wfw-wishlist-item-img' ).attr( 'src', src );
			jQuery( '.mwb-wfw-wishlist-item-img' ).trigger( 'change' );

			title.appendTo( ".mwb-wfw-wishlist-item-details" );
			priceHtml.appendTo( ".mwb-wfw-wishlist-item-details" );
			addToCartButton.appendTo( ".mwb-wfw-wishlist-action-buttons" );
			addToCartButton.text( strings.add_to_cart );
			jQuery( ".mwb-wfw-wishlist-action-buttons" ).append( viewWishlistButton );
			jQuery( ".ui-dialog-title" ).append( processingIconHtml );
			jQuery( ".mwb-wfw-wishlist-action-buttons" ).find(addToCartButton).on('click', function() {
			// window.location.
			jQuery(this).hide();
			setTimeout(function(){ window.location.href = strings.uwfw_get_checkout_url; }, 50);

			});
		}

		// Async process : Add to wishlist.
		const addToWishlist = ( pId='' )  =>  {
			let data = {
				nonce     : mwb_wfw_obj.auth_nonce,
				action    : 'UpdateWishlist',
				productId : pId,
				task      : 'add',
			};

			let result = doAjax( data );
			result.then( ( result ) => processResponse( result ) );
		}
	
		// Async process : Remove From to wishlist.
		const removeFromWishlist = ( pId='', wId='' )  =>  {
			let data = {
				nonce      : mwb_wfw_obj.auth_nonce,
				action     : 'UpdateWishlist',
				productId  : pId,
				wishlistId : wId,
				task       : 'remove',
			};

			let result = doAjax( data );
			result.then( ( response ) => {
				response = JSON.parse( response );

				if( 200 == response.status ) {
					let trigger = jQuery('.current-trigger');
					if( trigger.hasClass( 'mwb-wfw-loop-text-button' ) ) {
						trigger.text( strings.add_to_wishlist );
						trigger.next( '.processing-button' ).css( 'display', 'none' );
						trigger.show();
					}
				}
				else {
					triggerError();
				}
			});
		}
	
		// Process to Show wishlist.
		const triggerShowWishlist = ( pId = '', product = {} ) => {
	
			// Show Popup for wishlist selection.
			if( 'on' == settings.wfw_enable_popup ) {
			// Prepare dialog box first only for shop page.54288826
				'shop' == location() && cloneProductDetails( product );
				'single' == location() && cloneProductDetails( product );
			}
			// Add product to current wishlist.
			addToWishlist( pId );

			if( product.length != 0 && wishlistPopup.length ) {
				wishlistPopup.dialog( 'open' );
			}
		}

		// Process the Async Output.
		const processResponse = ( response ) => {
			response = JSON.parse( response );
			if( 200 == response.status ) {
				let trigger = jQuery('.current-trigger');
				let processingIcon = jQuery('.mwb-wfw-wishlist-processing');
				trigger.attr( 'data-wishlist-id', response.id );
				if( trigger.hasClass( 'mwb-wfw-loop-text-button' ) ) {
					processingIcon.hide();
					trigger.text( strings.remove_from_wishlist );
					trigger.next( '.processing-button' ).css( 'display', 'none' );
					trigger.show();
				}
				processingIcon.hide();
			} else {
				triggerError();
			}
		}

		const location = () => {
			if( jQuery( 'body' ).hasClass( 'single-product' ) ) {
				return 'single';
			} else {
				return 'shop';
			}
		}

		/**
		 * Social icons appereance
		 * @param {*} object 
		 * @param {*} font_setting 
		 * @param {*} color_setting 
		 */
		const SocialIcons = ( object, font_setting, color_setting ) => {

			object.css( 'display', 'block' );
			let svg        = object.children();
			let svg_circle = svg.children( 'circle' );

			if ( font_setting.length > 0 ) {
				svg.attr( 'height', font_setting );
				svg.attr( 'width', font_setting );
			}

			if ( color_setting.length > 0 ) {
				svg_circle.attr( 'fill', color_setting );
			}
		}

		/**==========================================================================================
		 *                      Native Functional Callbacks
		=========================================================================================== */
		// Initialise dialog box for popup.
		initWishlistPopup();

		// Add/Remove to Wishlist.
		wishlistTrigger.on( 'click', function() {
			if( 0 == user ) {
				triggerError( strings.login_required );
			} else {
				wishlistTrigger.removeClass( 'current-trigger' );

					if( jQuery( this ).hasClass( 'mwb-wfw-loop-text-button' ) ) {
						jQuery( this ).hide();
						jQuery( this ).next( '.processing-button' ).css( 'display', 'flex' );
					}
				processWishtlist( jQuery( this ) );
			}
		});

		// Create New Wishlist.
		if( addWishlistMetaTrigger.length > 0 ) {

			addWishlistMetaTrigger.on( 'click', function() {
				var prod = jQuery(this).attr('data-prod');
				var wId = jQuery(this).attr('data-wId');
				jQuery('.comment_product').val( prod );
				jQuery('.comment_wid').val( wId );
				addWishlistMetaPopup.show();
			});
		}

		jQuery( '.wfw_comment_close, .wfw_comment_cancel' ).on( 'click', function(e) {
			e.preventDefault();
			addWishlistMetaPopup.hide()
		});

		jQuery( '.add-meta-to-wishlist' ).on( 'submit', function(e) {

			e.preventDefault();
			var data = jQuery(this).serializeArray();

			for (var i = data.length - 1; i >= 0; i--) {
				if (!data[i].value.replace(/\s/g, '').length) {
				  triggerError( 'Comment only contains whitespace (ie. spaces, tabs or line breaks)' );
				  addWishlistMetaPopup.hide();
				  return;
				}
			}

			let input = {
				nonce    : mwb_wfw_obj.auth_nonce,
				action   : 'UpdateWishlistMeta',
				formData : data,
			};

			let result = doAjax( input );
			result.then( ( response ) =>  {

				if ( true == response.status ) {
					addWishlistMetaPopup.hide();
					triggerSuccess( response.message );

				} else if( false == response.status ) {
					addWishlistMetaPopup.hide();
					triggerError( response.message );
				}
			});
		});

		// Send invitation email to collaborator.
		jQuery( document ).on( 'submit', '#wfw_email_invite_form', function(e) {

			e.preventDefault();

			let data = {
				nonce: mwb_wfw_obj.auth_nonce,
				action : 'InvitationEmail',
				id     : jQuery( 'input[name="wfw_toshow_id"]' ).val(),
				email  : jQuery( 'input[name="wfw_invite_email"]' ).val(),
			};

			let result = doAjax( data );

			result.then( ( response ) => {

				if( true == response.status ) {
					console.log(response);
					tb_remove();
					triggerSuccess( response.message );

				} else if ( false == response.message ) {
					tb_remove();
					triggerError( response.message );
				}
			});

		});

		// Display comments on click on details.
		jQuery( document ).on( 'click', '#wfw_get_details', function(e) {

			e.preventDefault();

			if( jQuery( '.wfw_show_details' ).length > 0 ) {
				jQuery( '.wfw_show_details' ).remove();
				return;
			}

			let wid = jQuery(this).attr( "data-wId" );
			let pro_id = jQuery(this).data("prod");

			let data = {
				nonce : mwb_wfw_obj.auth_nonce,
				action : 'wfw_get_item_details',
				wId : wid,
				pro_id : pro_id
			};

			let result = doAjax( data );

			result.then( ( response ) => {
				jQuery(this).after('<div class="wfw_show_details">' + response.message + '</div>');
			});
		});

		// Add to cart wishlist product
		jQuery( document ).on( 'click', '#wfw_add_to_cart', function(e) {
			e.preventDefault();
			var  pro_id = jQuery(this).data("prod");
			let data = {
				nonce : mwb_wfw_obj.auth_nonce,
				action : 'add_to_cart_wish_prod',
				wId : jQuery(this).attr( "data-wid" ),
				pro_id : jQuery(this).data("prod"),
			}

			let result = doAjax( data );

			result.then( (response) => {

				console.log(response);
				if( true == response.status ) {

					if ( true == response.variable ) {

						window.location.href = response.message;

					} else if( false == response.variable ) {

						jQuery( this ).css( 'display', 'none' );
						jQuery( '#wfw_go_to_checkout' + pro_id ).css( 'display', 'block' );
						jQuery( '#wfw_go_to_checkout' + pro_id ).attr( 'href', response.message );
						jQuery( '#wfw_go_to_checkout' + pro_id ).text( 'Go to checkout' );

						jQuery( '#wfw_go_to_checkout' + pro_id ).on( 'click', function(e) {
							e.preventDefault();
							let data = {
								nonce  : mwb_wfw_obj.auth_nonce,
								action : 'go_to_checkout_wish_prod',
								wId    : jQuery(this).attr( "data-wId" ),
								pro_id : jQuery(this).data("prod"),
							}

							let result = doAjax( data );
	
							result.then( (response) => {
								if ( true == response.status ) {
									window.location.href = response.message;
								}
								else {
									triggerError( response.message );
								}
							});
						});
					}
				} else if ( false == response.status ) {
					triggerError( response.message )
				} else if (response.length == 0) {
					triggerError();
				}
			});
		});
	
		// Delete prod from wishlist.
		jQuery( document ).on( 'click', '#wfw_del_prod_frm_wishlist', function(e) {

			e.preventDefault();

			let data = {
				nonce : mwb_wfw_obj.auth_nonce,
				action : 'delete_wish_prod',
				wId : jQuery(this).attr( "data-wId" ),
				pro_id : jQuery(this).data("prod"),
			}

			var pro_id = jQuery(this).data("prod");

			let result = doAjax( data );

			result.then( (response) => {

				if ( true == response.status ) {
				   jQuery( '.wfw_list_item_' + pro_id ).remove();
				} else {
					triggerError( response.message );
				}
			});
		});
		jQuery( document ).on( 'click', '#wfw_del_outofstock_prod_frm_wishlist', function(e) {

			let data = {
				nonce : mwb_wfw_obj.auth_nonce,
				action : 'delete_wish_prod',
				wId : jQuery(this).attr( "data-wId" ),
				pro_id : jQuery(this).data("prod"),
			}

			var pro_id = jQuery(this).data("prod");

			let result = doAjax( data );

			result.then( (response) => {

				if ( true == response.status ) {
				   jQuery( '.wfw_list_item_' + pro_id ).remove();
				} else {
					triggerError( response.message );
				}
			});
		});

		// Delete entire list.
		jQuery( document ).on( 'click', '.mwb-wfw-delete', function(e) {
			// e.preventDefault();
			let data = {
				nonce : mwb_wfw_obj.auth_nonce,
				action : 'delete_current_wishlist',
				wId : jQuery(this).attr( "data-wid" ),
			}

			let result = doAjax( data );

			result.then( ( response ) => {
				if ( true == response.status ) {
					triggerSuccess( response.message );
					jQuery( document ).on( 'click', '.swal-button--confirm', function() {

						window.location.href = response.reload;
					});
				} else {
					triggerError( response.message );
				}
			});
		});
	
		// Set as default.
		jQuery( document ).on( 'click', '.mwb-wfw-default', function(e) {
			// e.preventDefault();

			let data = {
				nonce  : mwb_wfw_obj.auth_nonce,
				action : 'wishlist_set_default',
				wId    : jQuery(this).attr( "data-wid" ),
			}

			let result = doAjax( data );

			result.then( (response) => {
				if ( true == response.status ) {
					triggerSuccess( response.message );
				} else {
					triggerError( 'Something went wrong' );
				}
			});
		});

		// Social share settings.
		let fb_settings = jQuery( '#mwb-wfw-share-fb' );
		let wa_settings = jQuery( '#mwb-wfw-share-wa' );
		let tt_settings = jQuery( '#mwb-wfw-share-tt' );
		let pt_settings = jQuery( '#mwb-wfw-share-pt' );

		if ( 'on' == settings.wfw_enable_fb_share ) {
			SocialIcons( fb_settings, settings.wfw_enable_icon_size, settings.wfw_enable_fb_color );
		}

		if ( 'on' == settings.wfw_enable_whatsapp_share ) {
			SocialIcons( wa_settings, settings.wfw_enable_icon_size, settings.wfw_enable_whatsapp_color );
		}

		if ( 'on' == settings.wfw_enable_twitter_share ) {
			SocialIcons( tt_settings, settings.wfw_enable_icon_size, settings.wfw_enable_twitter_color );
		}

		if ( 'on' == settings.wfw_enable_pinterest_share ) {
			SocialIcons( pt_settings, settings.wfw_enable_icon_size, settings.wfw_enable_pinterest_color );
		}

		// End of scripts.
	//-------------------------------------- quick view compatibilty script-------------------------------------------------

	$(document).on('click','.mwb_wqv_quickview_modal_button',function(e) {

		$('#mwb_wqv_add_to_cart').addClass( 'wishlist_del_pro_add_quick_view' );
		$('#mwb_wqv_add_to_cart').attr( "data-wishid", $(this).data( "wishid" ) );
		$('#mwb_wqv_add_to_cart').attr( "data-mwb_wqv_prod_id", $(this).data( "mwb_wqv_prod_id" ) );
	});// open quick view modal end.

	jQuery( document ).on( 'click', '.wishlist_del_pro_add_quick_view', function(e) {
		
		let data = {
			nonce : mwb_wfw_obj.auth_nonce,
			action : 'delete_wish_prod',
			wId : $(this).attr( "data-wishid" ),
			pro_id : $(this).attr( "data-mwb_wqv_prod_id" ),
		}

		var pro_id = jQuery(this).attr("data-mwb_wqv_prod_id");

		let result = doAjax( data );

		result.then( (response) => {
			if ( true == response.status ) {
			jQuery( '.wfw_list_item_' + pro_id ).remove();
			} else {
				triggerError( response.message );
			}
		});
	});
	});

})( jQuery );

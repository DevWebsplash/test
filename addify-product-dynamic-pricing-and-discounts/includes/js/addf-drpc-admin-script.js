jQuery( function($) {
	jQuery('#addf_disc_rpc_roles_select').select2();
	jQuery('#addf_disc_rpc_categories').select2();
	$(function () {
		// multi select
		$('.addf_disc_rpc_product_live_search').select2({
			ajax: {
				url: php_var.ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'addf_disc_rpc_product_live_search' ,// AJAX action for admin-ajax.php
						nonce: php_var.nonce
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});
					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose products',
			minimumInputLength: 3 // the minimum of symbols to input before perform a search
		});        
	});
});
jQuery(document).ready( function($){
	// checking check box in table
	$(document).on('change' , ".addf_disc_rpc_cb_dis_allow" , function(){
		if ( $(this).val() != 'fixed_price' ) {
			$(this).closest( "tr" ).find( "input[type='checkbox']" ).prop('checked' , false);
			$(this).closest( "tr" ).find( "input[type='checkbox']" ).prop('disabled' , true);
		} else {
			$(this).closest( "tr" ).find( "input[type='checkbox']" ).prop('disabled' , false);
		}
	});

	// addf_disc_rpc_customer_live_search
	addf_disc_rpc_customer_live_search_fn();

	function addf_disc_rpc_customer_live_search_fn(){
		jQuery(document).find('.addf_disc_rpc_customers').select2({
			ajax: {
					url: php_var.ajaxurl, // AJAX URL is predefined in WordPress admin
					dataType: 'json',
					type: 'POST',
					delay: 250, // delay in ms while typing when to perform a AJAX search
					data: function (params) {
						return {
							q: params.term, // search query
							action: 'addf_disc_rpc_customer_live_search', // AJAX action for admin-ajax.php
							nonce: php_var.nonce
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
								options.push( { id: text[0], text: text[1]  } );
							});    
						}
						return {
							results: options
						};
					},
					cache: true
				},
				placeholder: 'Choose Customer...',
				multiple: false,
				minimumInputLength: 3 // the minimum of symbols to input before perform a search
			});
		
	}

	addf_disc_rpc_search_gift_product();
	function addf_disc_rpc_search_gift_product(){
		$('.addf_disc_choose_new_gift_product').select2({
			ajax: {
				url: php_var.ajaxurl, // AJAX URL is predefined in WordPress admin
				dataType: 'json',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
					return {
						q: params.term, // search query
						action: 'addf_disc_choose_new_gift_product' ,// AJAX action for admin-ajax.php
						nonce: php_var.nonce
					};
				},
				processResults: function( data ) {
					var options = [];
					if ( data ) {
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});
					}
					return {
						results: options
					};
				},
				cache: true
			},
			placeholder: 'Choose products...',
			minimumInputLength: 3 // the minimum of symbols to input before perform a search
		});
	}

	$(document).on('click' , '.addf_disc_rpc_add_cust_row_tbl' , function(){
		var array_size = $(this).data('size');
		$(this).data('size' , ++array_size);
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_add_cust_rule_row'
				, table_id: "addf_disc_rpc_discount_adj_table"
				, array_size: array_size
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					
					$(".addf_disc_rpc_disc_for_customer_table").append( data["tr_data"] );
					addf_disc_rpc_customer_live_search_fn();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	// add new row in cart rule dynamic discount for customer rules
	$(document).on('click' , '.addf_disc_rpc_add_cust_row_cart_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_add_cust_row_cart_action'
				, table_id: "addf_disc_rpc_cart_cust_dynamic_tbl"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					$(".addf_disc_rpc_cart_cust_dynamic_tbl").append( data["tr_data"] );
					addf_disc_rpc_customer_live_search_fn();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	// add new row in cart rule dynamic discount for User role rules
	$(document).on('click' , '.addf_disc_rpc_add_user_role_row_cart_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_add_user_role_row_cart_action'
				, table_id: "addf_disc_rpc_cart_roles_dynamic_tbl"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					$(".addf_disc_rpc_cart_roles_dynamic_tbl").append( data["tr_data"] );
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	// add new row in cart rule conditional customer table
	$(document).on('click' , '.addf_disc_rpc_conditional_add_cust_row_cart_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_conditional_add_cust_row_cart_tbl'
				, table_id: "addf_disc_rpc_cart_cust_conditional_tbl"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					
					$(".addf_disc_rpc_cart_cust_conditional_tbl").append( data["tr_data"] );
					addf_disc_rpc_customer_live_search_fn();
					addf_disc_rpc_search_gift_product();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	// add new row in cart rule conditional user role table
	$(document).on('click' , '.addf_disc_rpc_conditional_add_user_role_row_cart_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_conditional_add_user_role_row_cart_tbl'
				, table_id: "addf_disc_rpc_cart_roles_conditional_tbl"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					$(".addf_disc_rpc_cart_roles_conditional_tbl").append( data["tr_data"] );
					addf_disc_rpc_search_gift_product();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	// add new row
	$(document).on('click' , '.addf_disc_rpc_add_gift_row_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl,
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_add_gift_row_tbl'
				, table_id: "addf_disc_rpc_discount_adj_table"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					
					$(".addf_disc_rpc_gift_table").append( data["tr_data"] );
					addf_disc_rpc_customer_live_search_fn();
					addf_disc_rpc_search_gift_product();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	$(document).on('click' , '.addf_disc_rpc_add_user_role_gift_row_tbl' , function(){
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_add_user_role_gift_row_tbl',
				table_id: "addf_disc_rpc_user_role_gift_table"
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					
					$(".addf_disc_rpc_user_role_gift_table").append( data["tr_data"] );
					addf_disc_rpc_customer_live_search_fn();
					addf_disc_rpc_search_gift_product();
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	$(document).on('click' , '.addf_disc_rpc_add_row_tbl' , function(){
		var array_size = $(this).data('size');
		$(this).data('size' , ++array_size);
		$(this).addClass("loading");
		$(this).prop("disabled" , true);
		jQuery.ajax({
			url: php_var.ajaxurl, 
			type: 'POST',
			data: {
				nonce: php_var.nonce, 
				action : 'addf_disc_rpc_discount_adj_table'
				, table_id: "addf_disc_rpc_discount_adj_table"
				, array_size: array_size
			},
			success: function( data ){
				if ( data['success'] = 'yes' ) {
					$(".addf_disc_rpc_discount_adj_table").append( data["tr_data"] );
				}
			}
		});
		$(this).removeClass("loading");
		$(this).prop("disabled" , false);
	});
	

	//  for other jquery
	// default values
	$(".addf_disc_rpc_days_radio").hide();
	$(".addf_drpc_discount_type_choice").hide();
	$(".addf_drpc_discount_type_choice_" + $("#addf_drpc_discount_type_choice").val() ).show();

	if ( $("#addf_disc_rpc_days_radio").val() == 'specific' ) {
		$(".addf_disc_rpc_days_radio").show();
	}
	if ( $("#addf_disc_rpc_roles_choice_cb").val() == 'specific' ) {
		$(".addf_disc_rpc_roles_choice_cb_specific").show();
	}
	if ( $("#addf_disc_rpc_product_selection_op").val() == 'specific' ) {
		$(".addf_disc_rpc_product_selection_op_specific").show();
	}
	if ( $("#addf_drpc_disc_min_spent_amount").val() == 'up_till_now' ) {
		$(".addf_drpc_disc_min_spent_amount_up_till_now").show();
	}
	if ( $("#addf_drpc_disc_min_spent_amount").val() == 'start_end_date' ) {
		$(".addf_drpc_disc_min_spent_amount_start_end_date").show();
	}

	// onchange
	$(document).on('change' , '.addf_disc_rpc_on_change' , function(){
		$("." + $(this).attr('id') ).hide();
		$("." + $(this).attr('id') + '_' + $(this).val() ).show();
	});

	$(document).on('click' , '.addf_disc_rpc_remove_row_tbl' , function(){
		$(this).closest('tr').remove();
	});

	$(document).on('click' , '.addf_disc_rpc_table_remove_row' , function(){
		$(this).closest('tr').remove();
	});
	
});



//template design update v-1.2.0


jQuery(document).ready(function($){
	
	$('#addf_drpc_reset_settings').hide();

	$('#addf_disc_rpc_show_prc_table').change(enable_tiered_pricing_table);
	$('#addf_drpc_discount_type_choice').change(enable_tiered_pricing_table);

	enable_tiered_pricing_table();

	function enable_tiered_pricing_table() {
		if ($('#addf_disc_rpc_show_prc_table').is(":checked") && 'dynamic_price_adj' == $('#addf_drpc_discount_type_choice').val()) { 
			$('.addf_drpc_pricing_template').show();

		} else {
			$('.addf_drpc_pricing_template').hide();

		}
	}

	//reset template settings to default
	jQuery(document).on('click', '.addf_drpc_reset_settings_button' , function(event) {
		event.preventDefault();

		$.ajax({
			url: php_var.ajaxurl,
			type: 'POST',
			data: {
				action: 'addf_drpc_reset_pricing_template_settings',
				nonce: php_var.nonce
			},
			success:function(){
				location.reload();
			}
		})
	})

	$('#addf_drpc_template_icon').hide();

	jQuery(document).on('click', '#addf-drpc-remove-image-btn' , function(event) {
		event.preventDefault();
		jQuery('#addf_drpc_template_icon').val('');
		jQuery('#addf_drpc_selected_image_display').attr('src', "");
	});

	$(document).on('click','#addf-drpc-upload-image-btn',function(event){ 
		event.preventDefault();
		var image = wp.media({ 
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(){
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			jQuery('#addf_drpc_template_icon').val(image_url);
			jQuery('#addf_drpc_selected_image_display').attr("src", image_url);
		});
	})

	$(document).on('change','#addf_drpc_template_type',pricing_design_select_change);


function pricing_design_select_change(){
		if($('#addf_drpc_template_type').val() == 'table'){
			$('.addf_drpc_table_img').show();
			$('.addf_drpc_card_img').hide();
			$('.addf_drpc_list_img').hide();
			$('.addf_drpc_table_field').closest('tr').show();
			$('.addf_drpc_list_field').closest('tr').hide();
			$('.addf_drpc_card_field').closest('tr').hide();



		}
		else if($('#addf_drpc_template_type').val() == 'list'){
			$('.addf_drpc_table_img').hide();
			$('.addf_drpc_card_img').hide();
			$('.addf_drpc_list_img').show();
			$('.addf_drpc_table_field').closest('tr').hide();
			$('.addf_drpc_list_field').closest('tr').show();
			$('.addf_drpc_card_field').closest('tr').hide();


		}
		else{
			$('.addf_drpc_table_img').hide();
			$('.addf_drpc_card_img').show();
			$('.addf_drpc_list_img').hide();
			$('.addf_drpc_table_field').closest('tr').hide();
			$('.addf_drpc_list_field').closest('tr').hide();
			$('.addf_drpc_card_field').closest('tr').show();



		}
	}

	$('#addf_drpc_enable_template_heading').change(enable_template_heading_change);
	function enable_template_heading_change() {
		if ($('#addf_drpc_enable_template_heading').is(":checked")) { 
			$('#addf_drpc_template_heading_text').closest('tr').show();
			$('#addf_drpc_template_heading_font_size').closest('tr').show();

		} else {
			$('#addf_drpc_template_heading_text').closest('tr').hide();
			$('#addf_drpc_template_heading_font_size').closest('tr').hide();

		}
	}

	$('#addf_drpc_enable_template_icon').change(enable_template_icon_change);
	function enable_template_icon_change() {
		if ($('#addf_drpc_enable_template_icon').is(":checked")) { 
			$('#addf_drpc_template_icon_container').closest('tr').show();
		} else {
			$('#addf_drpc_template_icon_container').closest('tr').hide();
		}
	}


	pricing_design_select_change();
	enable_template_heading_change();
	enable_template_icon_change();
	enable_card_sale_tag_change();
	enable_table_border_change();

	$('#addf_drpc_enable_sale_tag').change(enable_card_sale_tag_change);
	function enable_card_sale_tag_change() {
		if ($('#addf_drpc_enable_sale_tag').is(":checked") && $('#addf_drpc_template_type').val() == 'card') { 
			$('#addf_drpc_sale_tag_background_color').closest('tr').show();
			$('#addf_drpc_sale_tag_text_color').closest('tr').show();
	
		} else {
			$('#addf_drpc_sale_tag_background_color').closest('tr').hide();
			$('#addf_drpc_sale_tag_text_color').closest('tr').hide();
		}
	}


	$('#addf_drpc_enable_table_border').change(enable_table_border_change);
	function enable_table_border_change() {
		if ($('#addf_drpc_enable_table_border').is(":checked") && $('#addf_drpc_template_type').val() == 'table' ) { 
			$('#addf_drpc_table_border_color').closest('tr').show();
		} else {
			$('#addf_drpc_table_border_color').closest('tr').hide();
		}
	}
})


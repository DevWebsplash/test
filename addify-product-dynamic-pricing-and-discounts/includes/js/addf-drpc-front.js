jQuery(document).ready( function($){
	$(document).on('show_variation' , 'form.variations_form' , function(event, data){
		var variation_id = data.variation_id;
		if ( $('.af_drpc_variation_table').is(':visible') ) {
			$('.af_drpc_variable_table').hide();
		} else {
			$('.af_drpc_variable_table').show();
		}
	} );
});



jQuery(document).ready(function ($) {

    //for vairable product code is at end inside ajax
    
    //for grouped product
    if($('.woocommerce-grouped-product-list').length  && !$('.single_variation').length){
        var initialPrices = {};
        var classNames = {};
    
        $('.qty').each(function(index) {
            var productRow = $(this).closest('tr'); // Find the parent row

            var className = '';
        
            var initialPrice = productRow.find('.woocommerce-grouped-product-list-item__price ins .woocommerce-Price-amount bdi').text();
            
            if(initialPrice == ''){
                className = '.woocommerce-grouped-product-list-item__price .woocommerce-Price-amount bdi';
            }
            else{
                className = '.woocommerce-grouped-product-list-item__price ins .woocommerce-Price-amount bdi';
            }



            initialPrices[index] = productRow.find(className).text();
            
            classNames[index] = productRow.find(className);
        
            $(this).on('input change', function() {
                var newValue = parseInt($(this).val());
        
                $('.addf_drpc_table_for_dynamic_pricing tr').each(function() {
                    var minQuantity = parseInt($(this).find('td:nth-child(1)').text());
                    var maxQuantity = isNaN(parseInt($(this).find('td:nth-child(2)').text()))?0:parseInt($(this).find('td:nth-child(2)').text());
                    var priceText = $(this).find('td:nth-child(3)').text(); 

                    var replace_price = $(this).find('td:first').data('replace');
        
                    if ((newValue >= minQuantity && newValue <= maxQuantity) || (newValue >= minQuantity && 0 == maxQuantity)) {
                        var targetPriceElement = productRow.find(className);
                        targetPriceElement.html('<span class="woocommerce-Price-currencySymbol">' + priceText + '</span>');
                        if('yes' == replace_price){
                            $('.woocommerce-grouped-product-list-item__price del').hide();
                        }
                        else{
                            $('.woocommerce-grouped-product-list-item__price del').show();
                        }
                        return false;
                    }
                });
        
                if (!$('.addf_drpc_table_for_dynamic_pricing tr').is(function() {
                    var replace_price = $(this).find('td:first').data('replace');

                    return (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && newValue <= parseInt($(this).find('td:nth-child(2)').text()) || (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && (0 == parseInt($(this).find('td:nth-child(2)').text()) || isNaN(parseInt($(this).find('td:nth-child(2)').text())))));
                })) {
					$('.addf_drpc_list_box').each(function(){
						$(this).removeClass('addf_drpc_selected_list')
					})
					$('[name=offer]').each(function(){
						$(this).prop('checked', false);
					})
					$('.addf_drpc_inner_small_box').each(function(){
						$(this).removeClass('addf_drpc_selected_card');
					})
                    $('.woocommerce-grouped-product-list-item__price del').show();
                    classNames[index].text(initialPrices[index]);
                }
            });
        });
        
    
    }
    //for simple product
    else if(!$('.single_variation').length){
        var className = '';
    
        var initialPrice = $('.entry-summary .price ins .woocommerce-Price-amount bdi').text();
        
        if(initialPrice == ''){
            className = '.entry-summary .price .woocommerce-Price-amount bdi';
            initialPrice = $(className).text();
        }
        else{
            className = '.entry-summary .price ins .woocommerce-Price-amount bdi';
        }

        

        $('.qty').on('input change', function(){
            var newValue = parseInt($(this).val()); 

            $('.addf_drpc_table_for_dynamic_pricing tr').each(function() {
                var minQuantity = parseInt($(this).find('td:nth-child(1)').text());
                var maxQuantity = isNaN(parseInt($(this).find('td:nth-child(2)').text()))?0:parseInt($(this).find('td:nth-child(2)').text());
                var priceText = $(this).find('td:nth-child(3)').text(); 
                var replace_price = $(this).find('td:first').data('replace');

                
                if ((newValue >= minQuantity && newValue <= maxQuantity) || (newValue >= minQuantity && 0 == maxQuantity)) {
                    
                    $(className).html('<span class="woocommerce-Price-currencySymbol">' + priceText + '</span>' );
                    if('yes' == replace_price){
                        $('.entry-summary .price del').hide();
                    }
                    else{
                        $('.entry-summary .price del').show();
                    }
                    return false; 
                }
            });

            if (!$('.addf_drpc_table_for_dynamic_pricing tr').is(function() {
                var replace_price = $(this).find('td:first').data('replace');
                return (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && newValue <= parseInt($(this).find('td:nth-child(2)').text()) || (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && (0 == parseInt($(this).find('td:nth-child(2)').text()) || isNaN(parseInt($(this).find('td:nth-child(2)').text())))));
            })) {
				$('.addf_drpc_list_box').each(function(){
						$(this).removeClass('addf_drpc_selected_list')
					})
					$('[name=offer]').each(function(){
						$(this).prop('checked', false);
					})
					$('.addf_drpc_inner_small_box').each(function(){
						$(this).removeClass('addf_drpc_selected_card');
					})
                $('.entry-summary .price del').show();
                $(className).text(initialPrice);
            }
        });
        }

   
    



    $('.variations select').on('change', function() {

        
       
        
               
        setTimeout(function(){
            // $('.addf_drpc_radio_div').append('<input type="radio" name="offer" />');

            $('.addf_drpc_radio_div').each(function() {
                if ($(this).find('input[type="radio"][name="offer"]').length === 0) {
                    $('.addf_drpc_radio_div').append('<input type="radio" name="offer" />');
                }
            });

            var variation_id = $('.variation_id').val();
        
            if(variation_id != '' && variation_id != '0' ){

                $.ajax({
                    url: addf_drpc_php_vars.admin_url,
                    type: 'POST',
                    data: {
                        action: 'addf_drpc_get_variation_price',
                        nonce :  addf_drpc_php_vars.nonce,
                        variation_id:variation_id
                    },
                    success: function (response) {
                        var original_price = response.data.price;
                        var original_price_formatted = response.data.price;
                        
                        
                        var rows = document.querySelectorAll('.addf_drpc_table_for_dynamic_pricing tr');
        
                        rows.forEach(function(row) {
        
                            var priceStr = row.cells[2].innerText.trim();
                            var currencySymbol = priceStr.match(/[^\d.,]/g).join('').trim();
                            var priceValue = priceStr.replace(currencySymbol, '').trim();
                            var price = parseFloat(priceValue);
        
                            var decimalPlaces = priceValue.split('.')[1] ? priceValue.split('.')[1].length : 0;
        
                            var save = (original_price - price) > 0 ? (original_price - price) : 0;
        
                            var saveFormatted = save.toFixed(decimalPlaces);

                            original_price_formatted = parseFloat(original_price).toFixed(decimalPlaces)
                            
                            var currencyPosition = priceStr.indexOf(currencySymbol);
        
                            if (currencyPosition === 0) {
                                row.cells[3].innerText = currencySymbol + ' ' + saveFormatted;
                                original_price_formatted = currencySymbol + ' ' + original_price_formatted;
                            } 
                            else if (currencyPosition === priceStr.length - currencySymbol.length) {
                                row.cells[3].innerText = saveFormatted + ' ' + currencySymbol;
                                original_price_formatted = original_price_formatted + ' ' + currencySymbol;

                            } 
                            else {
                                if (priceStr.indexOf(currencySymbol + ' ') === 0) {
                                    row.cells[3].innerText = currencySymbol + ' ' + saveFormatted;
                                    original_price_formatted = currencySymbol + ' ' + original_price_formatted;

                                } else if (priceStr.indexOf(' ' + currencySymbol) === priceStr.length - currencySymbol.length - 1) {
                                    row.cells[3].innerText = saveFormatted + ' ' + currencySymbol;
                                    original_price_formatted = original_price_formatted + ' ' + currencySymbol;

                                } else {
                                    row.cells[3].innerText = currencySymbol + ' ' + saveFormatted;
                                    original_price_formatted = currencySymbol + ' ' + original_price_formatted;

                                    
                                }
                            }

                        });

                        //changing price on quantity change for variable price
                         if($('.single_variation').length){
                            var className = '';
                        
                            var initialPrice = $('.single_variation .price ins .woocommerce-Price-amount bdi').text();
                            
                            if(initialPrice == ''){
                                className = '.single_variation .price .woocommerce-Price-amount bdi';
                            }
                            else{
                                className = '.single_variation .price ins .woocommerce-Price-amount bdi';
                            }

                            initialPrice = original_price_formatted;
                             

                            function handleQuantityChange(newValue, className) {

                                $('.addf_drpc_table_for_dynamic_pricing tr').each(function() {
                                    var minQuantity = parseInt($(this).find('td:nth-child(1)').text());
                                    var maxQuantity = isNaN(parseInt($(this).find('td:nth-child(2)').text()))?0:parseInt($(this).find('td:nth-child(2)').text());
                                    var priceText = $(this).find('td:nth-child(3)').text(); 
                                    var replace_price = $(this).find('td:first').data('replace');

                                    
                                    if ((newValue >= minQuantity && newValue <= maxQuantity) || (newValue >= minQuantity && 0 == maxQuantity)) {
                                        $(className).html('<span class="woocommerce-Price-currencySymbol">' + priceText + '</span>');

                                        if('yes' == replace_price){
                                            $('.entry-summary .price del').hide();
                                        }
                                        else{
                                            $('.entry-summary .price del').show();
                                        }
                                        return false; 
                                    }
                                });

                                if (!$('.addf_drpc_table_for_dynamic_pricing tr').is(function() {
                                    return (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && newValue <= parseInt($(this).find('td:nth-child(2)').text()) || (newValue >= parseInt($(this).find('td:nth-child(1)').text()) && (0 == parseInt($(this).find('td:nth-child(2)').text()) || isNaN(parseInt($(this).find('td:nth-child(2)').text())))));
                                })) {
									$('.addf_drpc_list_box').each(function(){
										$(this).removeClass('addf_drpc_selected_list')
									})
									$('[name=offer]').each(function(){
										$(this).prop('checked', false);
									})
									$('.addf_drpc_inner_small_box').each(function(){
										$(this).removeClass('addf_drpc_selected_card');
									})
                                    $('.entry-summary .price del').show();
                                    $(className).text(initialPrice);
                                }
                            }

                            $(document).ready(function() {
                                var initialValue = parseInt($('.qty').val()); 
                                handleQuantityChange(initialValue, className); 
                            });

                            $(document).on('input change','.qty', function() { 
                                var newValue = parseInt($(this).val()); 
                                handleQuantityChange(newValue, className); 
                            });
                        }
                    }
                });
            }
        }, 10);
        
    });




    //card click logic
    $(document).on('click' ,'.addf_drpc_inner_small_box',function(){
        var min_qty = $(this).data('min-qty');
        if(min_qty>0){
            $('.qty').val(min_qty).trigger('change');
            $('.addf_drpc_inner_small_box').each(function(){
                $(this).removeClass('addf_drpc_selected_card');
            })
            $(this).addClass('addf_drpc_selected_card')
        }
    })



    $('.addf_drpc_radio_div').append('<input type="radio" name="offer" />');

   $(document).on('click','.addf_drpc_list_box',function(){
        var min_qty = $(this).data('min-qty');

         $(this).find('input[type="radio"]').prop('checked', true);
         $('.qty').val(min_qty).trigger('change');

         $('.addf_drpc_list_box').each(function(){
            $(this).removeClass('addf_drpc_selected_list');
        })
        $(this).addClass('addf_drpc_selected_list')

   })

   




   
});


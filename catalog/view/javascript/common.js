$(document).ready(function() {
	/* Search */
	$('.button-search').bind('click', function() {
		url = $('base').attr('href') + 'index.php?route=product/search';
				 
		var filter_name = $('input[name=\'filter_name\']').attr('value')
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		location = url;
	});
	
	$('#header input[name=\'filter_name\']').keydown(function(e) {
		if (e.keyCode == 13) {
			url = $('base').attr('href') + 'index.php?route=product/search';
			 
			var filter_name = $('input[name=\'filter_name\']').attr('value')
			
			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}
			
			location = url;
		}
	});
	
	/* Ajax Cart */

/*	$('#cart a').bind('click', function() {
		$('#cart').addClass('active');
		
		$.ajax({
			url: 'index.php?route=checkout/cart/update',
			dataType: 'json',
			success: function(json) {
				if (json['output']) {
					$('#cart .content').html(json['output']);
				}
			}
		});			
		
		$('#cart').bind('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
	
*/
	
	
	
	/* callmeback */
	$('#callmeback > .callmeback_heading a').bind('click', function() {
		$('#callmeback').addClass('active');
		
		$.ajax({
			dataType: 'json',
			success: function(json) {
				if (json['output']) {
					$('#callmeback .callmeback_content').html(json['output']);
				}
			}
		});			
		
		$('#callmeback').bind('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
	
	
	
	
	/* Mega Menu */
	$('#menu ul > li > a + div').each(function(index, element) {
		// IE6 & IE7 Fixes
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
			var category = $(element).find('a');
			var columns = $(element).find('ul').length;
			
			$(element).css('width', (columns * 143) + 'px');
			$(element).find('ul').css('float', 'left');
		}		
		
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();
		
		i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());
		
		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});

	// IE6 & IE7 Fixes
	if ($.browser.msie) {
		if ($.browser.version <= 6) {
			$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
			
			$('#column-right + #content').css('margin-right', '195px');
		
			$('.box-category ul li a.active + ul').css('display', 'block');	
		}
		
		if ($.browser.version <= 7) {
			$('#menu > ul > li').bind('mouseover', function() {
				$(this).addClass('active');
			});
				
			$('#menu > ul > li').bind('mouseout', function() {
				$(this).removeClass('active');
			});	
		}
	}
	
	$('.success img, .warning img, .attention img, .information img').live('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});	
});

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			}
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			}	 
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#cart_total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
				
				// setTimeout(function() { $(window.location).attr('href', 'index.php?route=checkout/quickcheckout'); }, 2000 );
			}	
		}
	});
}

function addQtyToCart(product_id) {
  var qty = $('#quant-' + product_id).val();
  if ((parseFloat(qty) != parseInt(qty)) || isNaN(qty)) {
                qty = 1;
  }
  addToCart(product_id, qty);
}

function QtyMinus(product_id, quantity_step, minimum) {
        var $input = $('#quant-' + product_id);
        var count = parseInt($input.val()) - quantity_step;
        count = count < minimum ? minimum : count;
             $input.val(count);
             $input.change();
             return FormSubmit('basket');
}

function QtyPlus(product_id, quantity_step, maximum) {
       var $input = $('#quant-' + product_id);
       var count = parseInt($input.val()) + quantity_step;
       count = count > maximum ? maximum : count;
             $input.val(count);
             $input.change();
             return FormSubmit('basket');
}

function removeCart(key) {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: 'remove=' + key,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
			
			if (json['output']) {
				$('#cart_total').html(json['total']);
				
				$('#cart .content').html(json['output']);
			}			
		}
	});
}

function removeCartOrder(product_id) {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: 'remove=' + product_id,
		dataType: 'json',
		success: function(msg){ setTimeout(function() { $(window.location.reload()); }, 500 ); }	
	});
}

function removeVoucher(key) {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: 'voucher=' + key,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
			
			if (json['output']) {
				$('#cart_total').html(json['total']);
				
				$('#cart .content').html(json['output']);
			}			
		}
	});
}

function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/update',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#wishlist_total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 				
			}	
		}
	});
}

function addToCompare(product_id) { 
	$.ajax({
		url: 'index.php?route=product/compare/update',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				//$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				//$('.success').fadeIn('slow');
				
				//$('#compare_total').html(json['total']);
				
				//$('html, body').animate({ scrollTop: 0 }, 'slow'); 

				$('a#compare_' + product_id).replaceWith(function () {
				return '<a class="compare_active" href="index.php?route=product/compare"></a>';
				}) ;

			}	
		}
	});
}

function saveCity(id) { 
	$.ajax({
		url: 'index.php?route=common/header/savecity',
		type: 'post',
		data: 'city=' + id,
		dataType: 'json',
		success: function(msg){ setTimeout(function() { $(window.location.reload()); }, 500 ); },
   		error: function(msg) { alert('fail') }		
	});
}

function FormSubmit(basket) {
	$.ajax({
		url: 'index.php?route=checkout/cart',
		type: 'post',
		dataType: 'html',
		data: jQuery("#"+basket).serialize(),
		success: function(){ setTimeout(function() { $(window.location.reload()); }, 1000 );
		 },
   		error: function(msg) { alert('fail') }		
	});
}

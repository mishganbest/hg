<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <div class="checkout">

    <div id="shipping-address" style="width:48%; float:left;">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_address; ?></div>
      <div class="checkout-content" style="display:block;">
<form action="" method="post" enctype="multipart/form-data" id="form_1">
		<table class="form">
		<tbody>
		<tr>
			<td><?php echo $entry_firstname; ?></td>
			<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field"/></td>
		</tr>
		<tr>
			<td><?php echo $entry_email; ?> <span class="required">*</span></td>
			<td><input type="text" name="email" value="<?php echo $email; ?>" class="large-field"/></td>
		</tr>
		<tr>
			<td><?php echo $entry_telephone; ?> <span class="required">*</span></td>
			<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field"/></td>
		</tr>
		<tr>
			<td><?php echo $entry_city; ?></td>
			<td><input type="text" name="city" value="<?php echo $city; ?>" class="large-field"/></td>
		</tr>
		<tr>
			<td><?php echo $entry_address_1; ?></td>
			<td><input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field"/></td>
		</tr>
		<tr style="display: none">
			<td><?php echo $entry_address_2; ?></td>
			<td><input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field"/></td>
		</tr>
		
		</tbody>
		</table>
</form>
      </div>
    </div>

    <div style="width:50%; float:right;">
    

      <div id="shipping-method">
        <div class="checkout-heading"><?php echo $text_checkout_shipping_method; ?></div>
        <div class="checkout-content" style="display:block;"></div>
      </div>
      

      <div id="payment-method">
        <div class="checkout-heading"><?php echo $text_checkout_payment_method; ?></div>
        <div class="checkout-content" style="display:block;"></div>
      </div>
    </div>

    <div id="confirm" style="clear:both;">
      <!-- <div class="checkout-heading"><?php echo $text_checkout_confirm; ?></div> -->
      <div class="checkout-content" style="display:block;"></div>
    </div>

  </div>
  <?php echo $content_bottom; ?></div>

<script type="text/javascript"><!--
function validate_generate() {
	$.ajax({
		url: 'index.php?route=checkout/quickcheckout_address/shipping',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'hidden\'], #shipping-address input[type=\'radio\']:checked, #shipping-method textarea, #confirm input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.error').remove();

			if (json['error']) {
				if (json['error']['firstname']) {
					$('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}

				if (json['error']['email']) {
					$('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}

				if (json['error']['telephone']) {
					$('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}

				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}

				if (json['error']['address_2']) {
					$('#shipping-address input[name=\'address_2\']').after('<span class="error">' + json['error']['address_2'] + '</span>');
				}

				if (json['error']['agree']) {
					$('#confirm input[name=\'agree\']').after('<span class="error">' + json['error']['agree'] + '</span>');
				}

				$('html, body').animate({ scrollTop: 250 }, 'fast'); 

			} else {
				generate_order();
			}
		}
	});
}

// Generate order
function generate_order() {
	$.ajax({
		url: 'index.php?route=checkout/quickcheckout_confirm/write',
		dataType: 'json',
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}

			// QIWI payment method uses own phone field here and it not re-initialized after validation.
			// ATTN: may affect other "heavy" payment methods. But should not (for most cases)
			/*
			if (json['output']) {
				$('#confirm .checkout-content').html(json['output']);
				$('#confirm .checkout-content').slideDown('fast');
			}
			*/

			// ROBOKASSA (Paypal too, IIRC) payment method need to be updated to change merchant URL in the payment_confirm()
			button_payment_then_confirm();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}
	});
}

function switch_shipping_method(force) {
	if( force == 'first' )
	{
		fdata = $('#shipping-method input[type=\'radio\']:first, #shipping-method textarea');
	} else {
		fdata = $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea');
	}

	$.ajax({
		url: 'index.php?route=checkout/quickcheckout_shipping',
		type: 'post',
		data: fdata,
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping').attr('disabled', true);
			$('#button-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

					$('.warning').fadeIn('fast');
				}
			} else if(force == 'first') {
				$.ajax({
					url: 'index.php?route=checkout/quickcheckout_confirm/write',
					dataType: 'json',
					success: function(json) {
						if (json['redirect']) {
							location = json['redirect'];
						}

						if (json['output']) {
							$('#confirm .checkout-content').html(json['output']);
							$('#confirm .checkout-content').slideDown('fast');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			} else {
				$.ajax({
					url: 'index.php?route=checkout/quickcheckout_confirm',
					dataType: 'json',
					success: function(json) {
						if (json['redirect']) {
							location = json['redirect'];
						}

						if (json['output']) {
							$('#confirm .checkout-content').html(json['output']);
							$('#confirm .checkout-content').slideDown('fast');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			}
		}
	});
}

function button_payment(force) {
	if( force == 'first' )
	{
		fdata = $('#payment-method input[type=\'radio\']:first, #payment-method input[type=\'checkbox\']:first');
	} else {
		fdata = $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked');
	}

	$.ajax({
		url: 'index.php?route=checkout/quickcheckout_payment',
		type: 'post',
		data: fdata,
		dataType: 'json',
		beforeSend: function() {
			// $('#confirm .checkout-content').slideUp('fast');
		},
		complete: function() {
			// $('#confirm .checkout-content').slideDown('fast');
		},
		success: function(json) {
			$('.warning').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
					$('.warning').fadeIn('fast');
				}
			} else if(force == 'first') {
				$.ajax({
					url: 'index.php?route=checkout/quickcheckout_confirm/write',
					dataType: 'json',
					success: function(json) {
						if (json['redirect']) {
							location = json['redirect'];
						}

						if (json['output']) {
							$('#confirm .checkout-content').html(json['output']);
							$('#confirm .checkout-content').slideDown('fast');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			} else {
				$.ajax({
					url: 'index.php?route=checkout/quickcheckout_confirm',
					dataType: 'json',
					success: function(json) {
						if (json['redirect']) {
							location = json['redirect'];
						}

						if (json['output']) {
							$('#confirm .checkout-content').html(json['output']);
							$('#confirm .checkout-content').slideDown('fast');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			}
		}
	});
}

function button_payment_then_confirm() {
	fdata = $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked');

	$.ajax({
		url: 'index.php?route=checkout/quickcheckout_payment',
		type: 'post',
		data: fdata,
		dataType: 'json',
		beforeSend: function() {
			// $('#confirm .checkout-content').slideUp('fast');
		},
		complete: function() {
			// $('#confirm .checkout-content').slideDown('fast');
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-method .checkout-content').prepend('<div class="s_server_msg s_msg_red" style="display: none;">' + json['error']['warning'] + '</div>');
					$('.s_server_msg').fadeIn('fast');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/quickcheckout_confirm',
					dataType: 'json',
					success: function(json) {
						if (json['redirect']) {
							location = json['redirect'];
						}

						if (json['output']) {
							$('#confirm .checkout-content').html(json['output']);
							$('#confirm .checkout-content').slideDown('fast');
						}

					// Call payment method confirmation
					payment_confirm();

					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			}
		}
	});
}


$.ajax({
	url: 'index.php?route=checkout/quickcheckout_shipping',
	dataType: 'json',
	success: function(json) {
		if (json['redirect']) {
			location = json['redirect'];
		}

		if (json['output']) {
			$('#shipping-method .checkout-content').html(json['output']);
			$('#shipping-method .checkout-content').slideDown('fast');
			$('#shipping-method .checkout-content input[name="shipping_method"]:first').click();
			switch_shipping_method('first');
		}
	}
});

$.ajax({
	url: 'index.php?route=checkout/quickcheckout_payment',
	dataType: 'json',
	success: function(json) {
		if (json['redirect']) {
			location = json['redirect'];
		}

		if (json['output']) {
			$('#payment-method .checkout-content').html(json['output']);
			$('#payment-method .checkout-content').slideDown('fast');
			$('#payment-method .checkout-content input[name="payment_method"]:first').click();
			button_payment('first');
		}
	}
});

$('#shipping-method .checkout-content input[name=\'shipping_method\']').live('change', switch_shipping_method);
$('#payment-method .checkout-content input[name=\'payment_method\']').live('change', button_payment);

$('#shipping-method .checkout-content').slideDown('fast');
$('#payment-method .checkout-content').slideDown('fast');
$('#confirm .checkout-content').slideDown('fast');

// Confirm order
$('#button-confirm').live('click', validate_generate);


//--></script>

<?php echo $footer; ?>
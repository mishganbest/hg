<?php echo $header; ?>
<h1><?php echo $heading_title; ?></h1>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div class="content-tovar">
			<div class="lists"></div>
			<div class="tovar-info left">
<?php if ($thumb || $images) { ?>

<?php if ($catprod == '59') { ?>
			<div class="medal" style="top: 218px;left: 235px;"></div>
<?php } ?>

				<div class="left" style="width:390px;">
<?php if ($thumb) { ?>
				<div class="gallery"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="fancybox" rel="fancybox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
<?php } ?>
				<div class="clear"></div>
<?php if ($quantity) { ?>
				<div class="okboss"><img src="catalog/view/theme/default/image/okboss.png" alt="" /> <?php echo $stock; ?></div>
<?php } else { ?>
<div class="okboss"><?php echo $stock; ?></div>
<?php } ?>

<?php } ?>
				</div>
<?php if (!$special) { ?>
<div class="tovarpage right">
					
					<br /><p><span style="color: #930700;">цена <span style="font-size:30px"><?php echo $price; ?></span></span>
					</p>
					<p style="font-style: italic;margin-top: 30px;font-size: 16px;">Количество: <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" /></p>
          <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
					<button id="button-cart" class="buysale radius" style="margin: 20px 0 0 30px;font-size:20px;width:260px;height:50px;">Купить</button>
					<button id="button-vkredit" class="buttonkred radius" style="margin: 10px 0 0 30px;width:205px;">Купи в Кредит</button>
					<a href="catalog/view/theme/default/image/promo-kvk.jpg" class="fancybox" rel="vkredit1" title="О кредите"><button class="buttonkred radius" style="margin: 10px 0 0 10px;width:40px;">?</button></a>

		<?php if ($discounts) { ?>
		        <br />
		        <div class="discount">
		          <?php foreach ($discounts as $discount) { ?>
		          <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
		          <?php } ?>
		        </div>
	        <?php } ?>

		<?php if ($podarok) { ?>
		<div class="podarok"><img src="catalog/view/theme/default/image/prize.png" class="left" /> <span><?php echo $podarok; ?></span></div>
		<?php } ?>

				</div>
<?php } else { ?>
				<div class="action-tovar right">
					<h2>АКЦИЯ</h2>
					<h3>Купите со скидкой!</h3>
					<p>старая цена <span style="font-size:24px;text-decoration:line-through"><?php echo $price; ?></span><br />
					<span style="color: #930700;">новая цена <span style="font-size:30px"><?php echo $special; ?></span></span>
					</p>
					<p style="font-style: italic;margin-top: 30px;font-size: 16px;">ДО КОНЦА АКЦИИ ОСТАЛОСЬ:</p>

<?php if ($special_date_end): ?>

<script type="text/javascript">
        $(document).ready(function () {
            $('#black').county({ endDateTime: new Date('<?php echo $special_date_end; ?>'), reflection: false, animation: 'scroll', theme: 'black' });            
        });
    </script>
                    <div id="black"></div>

					<div class="nameretime">
						<span>Дней</span>
						<span>Часов</span>
						<span>Минут</span>
						<span>Секунд</span>
					</div>

			<?php endif; ?>

					<input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
					<button id="button-cart" class="buysale radius" style="margin: 20px 0 0 30px;font-size:20px;width:260px;height:50px;">Купите со скидкой</button>
<input type="hidden" id="vkredit_input" name="vkredit" size="2" value="0" />
					<button id="button-vkredit" class="buttonkred radius" style="margin: 10px 0 0 30px;width:205px;">Купи в Кредит</button>
					<a href="catalog/view/theme/default/image/promo-kvk.jpg" class="fancybox" rel="vkredit1" title="О кредите"><button class="buttonkred radius" style="margin: 10px 0 0 10px;width:40px;">?</button></a>

		 <?php if ($discounts) { ?>
		        <br />
		        <div class="discount">
		          <?php foreach ($discounts as $discount) { ?>
		          <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
		          <?php } ?>
		        </div>
	        <?php } ?>

				</div>
<?php } ?>
				<div class="clear"></div>

<?php if ($images) { ?>
				<div class="gallery-prew">
					<?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="fancybox" rel="fancybox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
				</div>
<?php } ?>

				<div class="clear"></div>

				<div class="colls">

			<?php if ($catprod == '65' || $catprod == '81' || $catprod == '103') { ?>
					<div class="coll"><img src="catalog/view/theme/default/image/coll.png" alt="" /><p>Привезем товарный и кассовый чеки</p></div>
			<?php } else { ?>
					<div class="coll"><img src="catalog/view/theme/default/image/coll.png" alt="" /><p>Привезем фирменный гарантийный талон,  товарный и кассовый чеки</p></div>
			<?php } ?>


					<div class="coll"><img src="catalog/view/theme/default/image/coll2.png" alt="" />

<?php $price_for_shipping = preg_replace("/\D/", '', $price); ?>

<?php if ($price_for_shipping > 3000 && $city_to == 'Новосибирску') { ?>
<p>Бесплатная доставка по <?php echo $city_to; ?> в удобное для Вас время</p>
<?php } else { ?>
<p>Доставка по <?php echo $city_to; ?> в удобное для Вас время</p>
<?php } ?>
</div>
					<div class="coll"><img src="catalog/view/theme/default/image/coll3.png" alt="" />

<p>Заберите товар после подтверждения заказа в наших <a class="delivery_point" href="javascript:void(0);">пунктах выдачи</a></p>
<div class="delivery_point_content">
	
	<?php if ($delivery_points) { ?>
	<ul>
	<?php foreach ($delivery_points as $delivery_point) { ?>
	<li><?php if ($delivery_point['address']) { ?>
	<?php echo $delivery_point['address']; ?>
	<?php } ?>
          <?php if ($delivery_point['time']) { ?>
	<br />Время работы: <?php echo $delivery_point['time']; ?>
	<?php } ?>
	<?php if ($delivery_point['phone']) { ?>
	<br />Телефон: <?php echo $delivery_point['phone']; ?>
	<?php } ?>
          </li>
          <?php } ?>
          </ul><?php } ?>
          </div>
</div>



<?php if ($catprod == '65' || $catprod == '81' || $catprod == '103') { ?>
<?php } else { ?>
					<div class="coll"><img src="catalog/view/theme/default/image/coll4.png" alt="" />
<?php if ($service_centers) { ?>
<p>Подлежит гарантийному обслуживанию в авторизованных <a class="service_center" href="javascript:void(0);">сервисных центрах <?php echo $city_a; ?></a></p>
<?php } else { ?>
<p>Подлежит гарантийному обслуживанию в авторизованных сервисных центрах</p>
<?php } ?>
<div class="service_center_content">
	<?php if ($service_centers) { ?>
	<ul>
	<?php foreach ($service_centers as $service_center) { ?>
	<li><?php if ($service_center['href']) { ?>
	<a href="<?php echo $service_center['href']; ?>" target="_blank"><strong><?php echo $service_center['name']; ?></strong></a>
	<?php } else { ?>
          <strong><?php echo $service_center['name']; ?></strong><br />
          <?php echo $service_center['address']; ?>
          <?php if ($service_center['phone']) { ?>
          <br />Тел.: <?php echo $service_center['phone']; ?>
          <?php } ?> 
          <?php if ($service_center['time']) { ?>
          <br />Часы работы: <?php echo $service_center['time']; ?>
          <?php } ?>    
          <?php } ?></li>
          <?php } ?>
          </ul><?php } ?>
          </div>
</div>
<?php } ?>



				</div>

<div class="clear"></div>
				<div class="good-tovars">
					
					
				</div>
				<div class="clear"></div><br />
				  <div id="tabs">
					  <ul>
						<li><a href="#tab-description"><?php echo $tab_description; ?></a></li>
						<?php if ($attribute_groups) { ?>
						<li><a href="#tab-attribute"><?php echo $tab_attribute; ?></a></li>
<?php } ?>
					<?php if ($products) { ?>
						<li><a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a></li>
<?php } ?>
					  </ul>
					  <div class="clear"></div>
					  <div id="tab-description">
						<?php echo $description; ?>
					  </div>
<?php if ($attribute_groups) { ?>
					  <div id="tab-attribute">
						<table class="attribute">
	<thead>
        	<tr>
	          <td colspan="2"><?php echo $text_weight_and_dim; ?></td>
	        </tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo $text_weight; ?></td>
		<td><?php echo $weight; ?></td>
	</tr>
	<tr>
		<td><?php echo $text_dimension; ?></td>
		<td><?php echo $length; if(!empty($length)) echo " x "; echo $width; if(!empty($width)) echo " x "; echo $height; ?></td>
	</tr>
	</tbody>
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
					  </div>
<?php } ?>
<?php if ($products) { ?>
					  <div id="tab-related">
<?php foreach ($products as $product) { ?>
											<div class="good-tovar">
<?php if ($catprod == '68' || $catprod == '59') { ?>
											<div class="medalmin"></div>
<?php } ?>
<?php if ($product['thumb']) { ?>
											<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
<?php } ?>
											<div class="price">
<?php if (!$product['special']) { ?>
<p class="new">Цена <span style="font-size: 12px;font-weight: bold;"><?php echo $product['price']; ?></span></p>
<?php } else { ?>
												<p class="old">старая цена <span style="font-size:13px;text-decoration:line-through"><?php echo $product['price']; ?></span></p>
												<p class="new">новая цена <span style="font-size: 12px;font-weight: bold;"><?php echo $product['special']; ?></span></p>
<?php } ?>
											</div>
											<button onclick="addToCart('<?php echo $product['product_id']; ?>');" class="buysale radius">ЗАКАЖИТЕ СЕЙЧАС</button>
											<a href="<?php echo $product['href']; ?>" class="ok radius">Подробнее <div class="arrowmin2"></div></a>

										</div><?php } ?>
										
					  </div>
<?php } ?>
					</div>
			</div>
		
  <?php echo $content_bottom; ?></div>

<script type="text/javascript">
	$(document).ready(function(){
	$('body').click(function(){
	$('.service_center_content, .delivery_point_content').slideUp("fast");
	});	
	$('a.service_center').click(function(e){
	e.stopPropagation();
	$('.service_center_content').slideDown("fast");
	});    	
	$('a.delivery_point').click(function(e){
	e.stopPropagation();
	$('.delivery_point_content').slideDown("fast");
	});
    });
</script>

<script type="text/javascript">
var google_conversion_id = 962445188;
var google_conversion_label = "ZZKxCMzh01QQhP_2ygM";
</script>
<script type="text/javascript" src="//autocontext.begun.ru/conversion.js"></script>
  
<script type="text/javascript"><!--
<!--- vkredit module--->
$('#button-vkredit').bind('click', function() {
$('#vkredit_input').val(1);
$('#button-cart').click();
});
<!--- end vkredit module--->
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: $('.tovar-info input[type=\'text\'], .tovar-info input[type=\'hidden\'], .tovar-info input[type=\'radio\']:checked, .tovar-info input[type=\'checkbox\']:checked, .tovar-info select, .tovar-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
					$('.warning').fadeIn('slow');
				}
				
				for (i in json['error']) {
					$('#option-' + i).after('<span class="error">' + json['error'][i] + '</span>');
				}
			}	 
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
				$('.success').fadeIn('slow');
					
				$('#cart_total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 

				$('.tovarpage #button-cart, .action-tovar #button-cart ').replaceWith(function () {
				return '<a href="index.php?route=checkout/simplecheckout"><button id="button-cart" class="buysale radius" style="margin: 20px 0 0 30px;font-size:20px;width:260px;height:50px;">Оформить</button></a>';
				}) ;


				// begun start
				(function(w, p) {
				var a, s;
				(w[p] = w[p] || []).push({
				counter_id: 385502828,
				tag: 'ec28ef987079139860e75cd2f8f24011'
				});
				a = document.createElement('script'); a.type = 'text/javascript'; a.async = true;
				a.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'autocontext.begun.ru/analytics.js';
				s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(a, s);
				})(window, 'begun_analytics_params');
				// begun end

				
				// setTimeout(function() { $(window.location).attr('href', 'index.php?route=checkout/simplecheckout'); }, 2000 );
			}	
		}
	});
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			alert(json.success);
			
			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
		}
		
		$('.loading').remove();	
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review-title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review-title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script> 

<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}

$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 

<?php echo $footer; ?>
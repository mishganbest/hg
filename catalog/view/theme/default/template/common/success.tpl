<?php echo $header; ?><h1><?php echo $heading_title; ?></h1><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

  <?php echo $text_message; ?>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
  <?php if(isset($order_id) && $order_id) { ?>
    <script type="text/javascript">
	    var yaParams = {
			order_id: "<?php echo $order_id; ?>",
			order_price: "<?php echo  round($order_info['total']); ?>",
			currency: "<?php echo $order_info['currency_code']; ?>",
			exchange_rate: 1,
			goods: []
			};
													  
<?php foreach ($order_products as $i=>$row) { ?>												yaParams.goods[<?php echo $i; ?>] = {												id: "<?php echo $row['order_product_id']; ?>",
		name: "<?php echo htmlentities($row['name'],ENT_COMPAT,'UTF-8'); ?>",
		price: "<?php echo  round($row['price']); ?>",
		quantity: "<?php echo $row['quantity']; ?>"
		}
<?php } ?>					
	</script>

<script type="text/javascript">		
	// Оплата
	_gaq.push(['_addTrans',
		'<?php echo $order_id; ?>',         // номер заказа
		'<?php echo $store_name; ?>',   // название партнера или магазина
		'<?php echo  round($order_info["total"]); ?>',    // итоговая суммарная стоимость заказа
		'',           // налоги
		'',           // стоимость доставки
		'', 	    // город доставки
		'', 	    // регион доставки
		''          // страна доставки
	]);
		
	// Товар (выводить для каждого товара из корзины)
	<?php foreach ($order_products as $i=>$row) { ?>
	_gaq.push(['_addItem',
		'<?php echo $order_id; ?>',    // номер заказа
		'<?php echo htmlentities($row["model"],ENT_COMPAT,"UTF-8"); ?>',   // код товара (или SKU)
		'<?php echo htmlentities($row["name"],ENT_COMPAT,"UTF-8"); ?>',  // название товара
		'',     // категория или версия
		'<?php echo  round($row["price"]); ?>',          // цена за единицу
		'<?php echo $row["quantity"]; ?>'               // количество единиц товара
	]);
	<?php } ?>	
	// Отправка данных
	_gaq.push(['_trackTrans']);
</script>

	<!-- begun -->
	<script type="text/javascript">
	var google_conversion_id = 962445188;
	var google_conversion_label = "ZZKxCMzh01QQhP_2ygM";
	</script>
	<script type="text/javascript" src="//autocontext.begun.ru/conversion.js"></script>
	<!-- Conversion tracking code: purchases. Step 3: Order complete -->
	<script type="text/javascript">
	(function(w, p) {
	var a, s;
	(w[p] = w[p] || []).push({
	counter_id: 385502828,
	tag: '0479133bbafb55cb54288a36d72e7637'
	});
	a = document.createElement('script'); a.type = 'text/javascript'; a.async = true;
	a.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'autocontext.begun.ru/analytics.js';
	s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(a, s);
	})(window, 'begun_analytics_params');
	</script>
	<!-- begun -->
																		<?php } ?>
<?php echo $footer; ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<script type="text/javascript" src="view/javascript/jquery/jquery-1.6.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/blank.css" />
<style media='print' type='text/css'>
.noprint {display: none}
body {background:#FFF; color:#000}
}
</style>
</head>
<body>
<span class="noprint">
<a onClick="window.print()" href="javascript:void(0)"><img src="view/image/print.png" title="Печать товарного чека" /></a>
</span>
<?php foreach ($orders as $order) { ?>
<div class="block">
  <div class="header"><?php echo $text_order_id_invoice; ?> <?php echo $order['order_id']; ?></div>
  <div class="date"> <input type="text" maxlength="15" value="от <?php echo $order['date_added']; ?>" /></div>
  
 
  <table class="store">
    <tr>    
      <td>
	<span>ИП Лунькова Татьяна Владимировна</span><br />
        <span>ОГРНИП: 312547613200171</span><br />
        <span>ИНН 540862795901</span>
	</td>
    </tr>
  </table>

  <table class="product">
    <tr class="heading">
      <td><?php echo $column_product_invoice; ?></td>
      <td style="width: 48px" align="right"><?php echo $column_quantity_invoice; ?></td>
      <td style="width: 80px" align="right"><?php echo $column_price_invoice; ?></td>
      <td style="width: 80px" align="right"><?php echo $column_total_invoice; ?></td>
    </tr>
    <?php $counter = 0; ?>
    <?php foreach ($order['product'] as $product) { ?>
    <?php $counter++; ?>
    <tr>
      <td><input type="text" maxlength="48" value="<?php echo $product['name']; ?>" />
        <?php foreach ($product['option'] as $option) { ?>
        <br />
       <input type="text" value="- <?php echo $option['name']; ?>: <?php echo $option['value']; ?>" />
        <?php } ?></td>
      <td align="right" class="number"><input type="text" maxlength="4" value="<?php echo $product['quantity']; ?>" /></td>
      <td align="right" class="number"><input type="text" maxlength="10" value="<?php echo $product['price']; ?>" /></td>
      <td align="right" class="number"><input type="text" maxlength="10" value="<?php echo $product['total']; ?>" /></td>
    </tr>
    <?php } ?>
<?php if ($counter <= 6) { ?>
<?php $line = '<tr>
      <td><input type="text" maxlength="48" value="" /></td>
      <td><input type="text" maxlength="4" value="" /></td>
      <td><input type="text" maxlength="10" value="" /></td>
      <td><input type="text" maxlength="10" value="" /></td>
      </tr>';
  ?>
<?php $empty = 6 - $counter; ?>
<?php $lines = str_repeat($line, $empty); ?>
<?php echo $lines; ?>
<?php } ?>
</table>
    
<table class="bottom">
     <tr>
      <td align="left"><input type="text" name="discount" maxlength="45" value="" /></td>
      <td align="right"></td>
    </tr>
   <?php foreach ($order['shipping'] as $shipping) { ?>
   <?php $shipping_price = preg_replace("/\D/", '', $shipping['text']); ?>
   <?php if ($shipping_price > 0) { ?>
    <tr>
      <td align="left"><input type="text" name="shipping" maxlength="50" value="<?php echo $shipping['title']; ?>: <?php echo $shipping['text']; ?>" /></td>
    </tr>
    <?php } ?>
    <?php } ?>
   <?php foreach ($order['total'] as $total) { ?>
    <tr>
      <td align="left"><input type="text" name="total" maxlength="45" value="Итого: <?php echo $total['text']; ?>" /></td>
      <td align="left" style="padding-left: 35px">Подпись ______________</td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } ?>

<script type="text/javascript"><!--
$("input[name='discount']").change(function(){
	var name = $("input[name='discount']").val();
	if (name != '') {		
			var discount = $("input[name='discount']").val();
			var snum = parseInt(discount.replace(/\D+/g,""));
			var tnum = parseInt('<?php echo $total['text']; ?>'.replace(/\D+/g,""));
			var sum1 = tnum / 100 * snum;
			var sum2 = tnum - sum1;
			var sum3 = sum2.toString().replace(/(\d{3})$/, " $1");
			$("input[name='total']").val('Итого с учётом скидки: ' +sum3+ ' руб');
	}else{
			$("input[name='total']").val('Итого: <?php echo $total['text']; ?>');
	}
});
//--></script> 

</body>
</html>
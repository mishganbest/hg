<form action="" method="post" id="basket">
<div class="checkout-product">
  <table>
    <thead>
      <tr>
        <td class="name"><?php echo $column_name; ?></td>
        <td class="model"><?php echo $column_model; ?></td>
        <td style="padding-right: 20px" class="quantity"><?php echo $column_quantity; ?></td>
        <td style="padding-right: 18px" class="price"><?php echo $column_price; ?></td>
        <td class="total"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td class="model"><?php echo $product['model']; ?></td>
        
       
        <td class="quantity">
        
        <input type="button" value="" class="minus" onclick="QtyMinus(<?php echo $product['product_id']; ?>, 1, <?php echo $product['minimum']; ?>);" />
            
            <input type="text" name="quantity[<?php echo $product['product_id']; ?>]" id="quant-<?php echo $product['product_id']; ?>" class="qty" value="<?php echo $product['quantity']; ?>" readonly />
            
            <input type="button" value="" class="plus" onclick="QtyPlus(<?php echo $product['product_id']; ?>, 1, 100);" />

        </td>
        
      
        <td class="price"><?php echo $product['price']; ?></td>
        <td class="total"><?php echo $product['total']; ?>
        
        &nbsp;<input type="image" style="vertical-align: middle; cursor: pointer" src="catalog/view/theme/default/image/remove.png" onclick="removeCartOrder('<?php echo $product['product_id']; ?>'); return false;" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" />
        
        </td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
        <td class="total"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
</form>

<?php if ($text_agree) { ?>
  <div class="buttons">
	<?php echo $text_agree; ?>
	<?php if ($agree) { ?>
	<input type="checkbox" name="agree" value="1" checked="checked" />
	<?php } else { ?>
	<input type="checkbox" name="agree" value="1" />
	<?php } ?>
  </div>
<?php } ?>

<div class="payment"><?php echo $payment; ?></div>

<script type="text/javascript"><!--
$('.fancybox').fancybox({
	width: 560,
	height: 560,
	autoDimensions: false
});
//--></script>

<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb["separator"]; ?><a href="<?php echo $breadcrumb["href"]; ?>"><?php echo $breadcrumb["text"]; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?> 
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
	  
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="yabuy_status">
              <?php if ($yabuy_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
		</tr>

        <tr>
          <td><?php echo $entry_yacompany; ?></td>
          <td><input type="text" name="yabuy_yacompany" value="<?php echo $yabuy_yacompany; ?>" size="10" />
          </td>
		</tr>

        <tr>
          <td><?php echo $entry_yalogin; ?></td>
          <td><input type="text" name="yabuy_login" value="<?php echo $yabuy_login; ?>" size="18" />@yandex.ru
          </td>
		</tr>
		
        <tr>
          <td><?php echo $entry_token; ?></td>
          <td><input type="text" name="yabuy_token" value="<?php echo $yabuy_token; ?>" size="18" />
          </td>
		</tr>
		
        <tr>
          <td><?php echo $entry_payments; ?></td>
          <td><select name="yabuy_payments[]" size="4" multiple>
              <option value="YANDEX"<?php echo (in_array('YANDEX', $yabuy_payments) ? ' selected="selected"' : ''); ?>>предоплата через Яндекс</option>
              <option value="SHOP_PREPAID"<?php echo (in_array('SHOP_PREPAID', $yabuy_payments) ? ' selected="selected"' : ''); ?>>предоплата магазину</option>
              <option value="CASH_ON_DELIVERY"<?php echo (in_array('CASH_ON_DELIVERY', $yabuy_payments) ? ' selected="selected"' : ''); ?>>оплата наличностью при получении заказа</option>
              <option value="CARD_ON_DELIVERY"<?php echo (in_array('CARD_ON_DELIVERY', $yabuy_payments) ? ' selected="selected"' : ''); ?>>оплата карточкой при получении заказа</option>
            </select></td>
		</tr>
		
      </table>

	  <h3><?php echo $entry_deliveries; ?></h3>
	  Берется из модуля <a href="index.php?route=shipping/geocost&token=<?php echo $token; ?>">Доставка в зависимости от города</a>
	  
<?php /*
      <table id="deliveries" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $text_delivery_id; ?></td>
            <td class="left"><?php echo $text_delivery_name; ?></td>
            <td class="left"><?php echo $text_delivery_price; ?><sup><a href="#*">*</a></sup></td>
            <td class="left"><?php echo $text_delivery_from; ?></td>
            <td class="left"><?php echo $text_delivery_to; ?></td>
            <td class="left"><?php echo $text_delivery_region; ?><sup><a href="#*">**</a></sup></td>
            <td></td>
          </tr>
        </thead>
        <?php $delivery_row = 0; ?>
        <?php foreach ($yabuy_deliveries as $delivery) { ?>
        <tbody id="delivery-row<?php echo $delivery_row; ?>">
          <tr>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][id]" value="<?php echo $delivery['id']; ?>" size="10" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][name]" value="<?php echo $delivery['name']; ?>" size="35" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][price]" value="<?php echo $delivery['price']; ?>" size="15" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][from]" value="<?php echo $delivery['from']; ?>" size="6" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][to]" value="<?php echo $delivery['to']; ?>" size="6" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_deliveries[<?php echo $delivery_row; ?>][region]" value="<?php echo $delivery['region']; ?>" size="6" />
            </td>
            <td class="right"><a onclick="$('#delivery-row<?php echo $delivery_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $delivery_row++; ?>
        <?php } ?>
		<tfoot>
          <tr>
            <td colspan="6"></td>
            <td class="right"><a onclick="addDelivery();" class="button"><span><?php echo $button_add_outlet; ?></span></a></td>
		  </tr>
		</tfoot>
		</table>
*/ ?>
	  <h3><?php echo $entry_outlets; ?></h3>
	  Берется из модуля <a href="index.php?route=shipping/geocost_pickup&token=<?php echo $token; ?>">Самовывоз в зависимости от города</a>
<?php /*	  
      <table id="outlets" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $text_outlet_id; ?></td>
            <td class="left"><?php echo $text_outlet_zone; ?></td>
            <td class="left"><?php echo $text_outlet_city; ?></td>
            <td class="left"><?php echo $text_outlet_postcode; ?></td>
            <td class="left"><?php echo $text_outlet_address_1; ?></td>
            <td class="left"><?php echo $text_outlet_address_2; ?></td>
            <td class="left"><?php echo $text_outlet_price; ?><sup><a href="#*">*</a></sup></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($yabuy_outlets as $outlet) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][id]" value="<?php echo $outlet['id']; ?>" size="7" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][zone]" value="<?php echo $outlet['zone']; ?>" size="35" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][city]" value="<?php echo $outlet['city']; ?>" size="20" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][postcode]" value="<?php echo $outlet['postcode']; ?>" size="6" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][address_1]" value="<?php echo $outlet['address_1']; ?>" size="35" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][address_2]" value="<?php echo $outlet['address_2']; ?>" size="35" />
            </td>
            <td class="left">
				<input type="text" name="yabuy_outlets[<?php echo $module_row; ?>][price]" value="<?php echo (isset($outlet['price']) ? $outlet['price'] : 0); ?>" size="15" />
            </td>
            <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot id="csvoutlets">
          <tr>
            <td colspan="7"></td>
            <td class="right"><a onclick="addOutlet();" class="button"><span><?php echo $button_add_outlet; ?></span></a></td>
          </tr>
		<?php if ($csvoutlets) { ?>
          <tr>
		  <td colspan="8">
		  <a name="outlets"></a><b><?php echo $text_csvoutlets; ?></b> <a id="load_outlets" href="<?php $action; ?>#outlets" onClick="return loadOutlets();"><?php echo $text_show; ?></a>
		  </td>
		  </tr>
		<?php } ?>
          <tr>
		  <td colspan="8">
		  <a name="*"></a>
		  * Цену можно задавать в виде: <b>0:500|3000:200</b> (доставка 500 при сумме заказа от 0, и 200 при сумме заказа от 3000)<br/>
		  ** Код региона/города доставки можно узнать на странице управления Яндекс-маркетом (регионы доставки)
		  </td>
		  </tr>
        </tfoot>
      </table>
*/ ?>
    </form>
  </div>
</div>
<?php /*
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
var delivery_row = <?php echo $delivery_row; ?>;

function loadOutlets() {
	$('#load_outlets').fadeOut();
	$.ajax({
		url: '<?php echo $url_csvoutlets; ?>&token=<?php echo $token; ?>',
		dataType: 'json',
		success: function(json) {
			for (i = 0; i < json.length; i++) {
				var outlet = json[i];
				html  = '  <tr>';
				html += '    <td class="left">' +outlet.id+ '</td>';
				html += '    <td class="left">' +outlet.zone+ '</td>';
				html += '    <td class="left">' +outlet.city+ '</td>';
				html += '    <td class="left">' +outlet.postcode+ '</td>';
				html += '    <td class="left">' +outlet.address_1+ '</td>';
				html += '    <td class="left">' +outlet.address_2+ '</td>';
				html += '    <td class="left">' + (outlet.price ? outlet.price : 0) + '</td>';
				html += '    <td class="right">&nbsp;</td>';
				html += '  </tr>';
				$('#csvoutlets').append(html);
			}
		}
	})
	return false;
}

function addDelivery() {
	html  = '<tbody id="delivery-row' + delivery_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][id]" value="" size="10" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][name]" value="" size="35" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][price]" value="0" size="15" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][from]" value="" size="6" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][to]" value="" size="6" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_deliveries[' + delivery_row + '][region]" value="" size="6" /></td>';
	html += '    <td class="right"><a onclick="$(\'#delivery-row' + delivery_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	$('#deliveries tfoot').before(html);
	
	delivery_row++;
}

function addOutlet() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][id]" value="" size="7" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][zone]" value="" size="35" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][city]" value="" size="20" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][postcode]" value="" size="6" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][address_1]" value="" size="35" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][address_2]" value="" size="35" /></td>';
	html += '    <td class="left"><input type="text" name="yabuy_outlets[' + module_row + '][price]" value="0" size="15" /></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#outlets tfoot').before(html);
	
	module_row++;
}
//--></script>
*/ ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-42296537-2']);
  _gaq.push(['_setDomainName', 'sourcedistillery.com']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php echo $footer; ?>

<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/country.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><select name="city_id">
            	<option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($cities as $city) { ?>
                <?php if ($city['city_id'] == $city_id) { ?>
                <option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_city) { ?>
              <span class="error"><?php echo $error_city; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address; ?></td>
            <td><textarea name="address" cols="70" rows="5"><?php echo $address; ?></textarea>
              <?php if ($error_address) { ?>
              <span class="error"><?php echo $error_address; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_time; ?></td>
            <td><input type="text" name="time" size="50" value="<?php echo $time; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_phone; ?></td>
            <td><input type="text" name="phone" size="50" value="<?php echo $phone; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_delivery_period_to_warehouse; ?></td>
            <td><input type="text" name="delivery_period_to_warehouse" size="10" value="<?php echo $delivery_period_to_warehouse; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_price_to_warehouse; ?></td>
            <td><input type="text" name="price_to_warehouse" size="10" value="<?php echo $price_to_warehouse; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_delivery_period_to_door; ?></td>
            <td><input type="text" name="delivery_period_to_door" size="10" value="<?php echo $delivery_period_to_door; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_price_to_door; ?></td>
            <td><input type="text" name="price_to_door" size="10" value="<?php echo $price_to_door; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_photo_url; ?></td>
            <td><input type="text" name="photo_url" size="50" value="<?php echo $photo_url; ?>" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
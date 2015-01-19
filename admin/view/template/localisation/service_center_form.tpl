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
      <h1><img src="view/image/stock-status.png" alt="" /> <?php echo $heading_title; ?></h1>
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
            <td><span class="required">*</span> <?php echo $entry_manufacturer; ?></td>
            <td><select name="manufacturer_id">
            	<option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($manufacturers as $manufacturer) { ?>
                <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_manufacturer) { ?>
              <span class="error"><?php echo $error_manufacturer; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input type="text" name="name" size="40" value='<?php echo $name; ?>' />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
            </tr>
          <tr>
              <td><?php echo $entry_address; ?></td>
              <td><input type="text" name="address" size="40" value='<?php echo $address; ?>' /></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_time; ?></td>
              <td><input type="text" name="time" size="40" value="<?php echo $time; ?>" /></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_phone; ?></td>
              <td><input type="text" name="phone" size="40" value="<?php echo $phone; ?>" /></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_urlserviceinfo; ?></td>
              <td><input type="text" name="urlserviceinfo" size="40" value="<?php echo $urlserviceinfo; ?>" /></td>
            </tr>
          
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
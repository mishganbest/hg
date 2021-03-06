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
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merch_z; ?></td>
          <td><input type="text" name="kupivkredit_merch_z" value="<?php echo $kupivkredit_merch_z; ?>" />
            <?php if ($error_merch_z) { ?>
            <span class="error"><?php echo $error_merch_z; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_secret_key; ?></td>
          <td><input type="text" name="kupivkredit_secret_key" value="<?php echo $kupivkredit_secret_key; ?>" />
            <?php if ($error_secret_key) { ?>
            <span class="error"><?php echo $error_secret_key; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_server; ?></td>
          <td><select name="kupivkredit_server">
              <?php if ($kupivkredit_server) { ?>
              <option value="1" selected="selected"><?php echo $entry_work; ?></option>
              <option value="0"><?php echo $entry_test; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $entry_work; ?></option>
              <option value="0" selected="selected"><?php echo $entry_test; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_result_url; ?></td>
          <td><?php echo $kupivkredit_result_url; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_success_url; ?></td>
          <td><?php echo $kupivkredit_success_url; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_fail_url; ?></td>
          <td><?php echo $kupivkredit_fail_url; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="kupivkredit_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $kupivkredit_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="kupivkredit_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $kupivkredit_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="kupivkredit_status">
              <?php if ($kupivkredit_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="kupivkredit_sort_order" value="<?php echo $kupivkredit_sort_order; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_donate_me; ?></td>
          <td><strong>Z252886677544<br>R420875722159<br>U916758560909<br>E975226598305<strong></td>
        </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
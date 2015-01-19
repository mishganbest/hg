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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div style="width: 100%;">
        
<div style="width: 500px; height: 160px; vertical-align: top; display: inline-block; padding: 5px">
            <?php echo $entry_rate; ?>
        </div>

          <table style="display: inline" class="form">
            <tr>
              <td><?php echo $entry_tax; ?></td>
              <td><select name="geocost_pickup_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $geocost_pickup_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="geocost_pickup_status">
                  <?php if ($geocost_pickup_status) { ?>
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
              <td><input type="text" name="geocost_pickup_sort_order" value="<?php echo $geocost_pickup_sort_order; ?>" size="1" /></td>
            </tr>
          </table>

<table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_city; ?></td>
              <td class="center"><?php echo $column_rate; ?></td>
              <td class="center">Яндекс-код пункта самовывоза</td>
              <td class="center"><?php echo $column_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($geo_zones) { ?>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <tr>
              <td class="left"><b><?php echo $geo_zone['city']; ?></b><br /><?php echo $geo_zone['address']; ?></td>
              <td class="center"><input type="text" name="geocost_pickup_<?php echo $geo_zone['delivery_point_id']; ?>_rate" value="<?php echo ${'geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate'}; ?>" size="35" /></td>
              <td class="center"><input type="text" name="geocost_pickup_<?php echo $geo_zone['delivery_point_id']; ?>_yacode" value="<?php echo ${'geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode'}; ?>" size="8" /></td>
              <td class="center"><select name="geocost_pickup_<?php echo $geo_zone['delivery_point_id']; ?>_status">
                  <?php if (${'geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status'}) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

        </div>
      </div>
    </form>
  </div>
</div>
</div>
<?php echo $footer; ?>
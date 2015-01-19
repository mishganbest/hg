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
  
  <?php if (isset($text_success)) { ?>
  <div class="success"><?php echo $text_success; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
        <table id="module" class="form">
            <tr>
                <td><?php echo $entry_showshippingtocustomer; ?>:</td>
                <td><input type="checkbox" name="categoryshipping[showshippingtocustomer]" value="1" <?php if (isset($modules['showshippingtocustomer'])&&($modules['showshippingtocustomer'])) echo "checked"; ?> ></td>
                <td><span class="help"><?php echo $entry_showshippingtocustomer_help; ?></span></td>
            </tr>
            <tr>
                <td><?php echo $entry_replacement_shipping; ?>:</td>
                <td>
                  <select name="categoryshipping[replacement_shipping]">
                    <option value="0" <?php if (!isset($modules['replacement_shipping']) || (int)$modules['replacement_shipping'] == "0") echo "selected"; ?>><?php echo $replacement_shipping_not_use; ?></option>
                    <?php
                      foreach ($shippings as $shipping) {
                        echo "<option value=\"".$shipping['extension_id']."\" ".((isset($modules['replacement_shipping']) && $modules['replacement_shipping']==$shipping['extension_id'])?("selected"):("")).">".$shipping['name']."</option>";
                      }
                      
                    ?>
                  </select>
                </td>
                <td><span class="help"><?php echo $entry_replacement_shipping_help; ?></span></td>
            </tr>
            <tr>
                <td><?php echo $entry_noshipinng_error; ?>:</td>
                <td><textarea rows="5" cols="50" name="categoryshipping[noshipinng_error]"><?php if (isset($modules['noshipinng_error'])) echo $modules['noshipinng_error']; ?></textarea></td>
                <td><span class="help"><?php echo $entry_noshipinng_error_help; ?></span></td>
            </tr>
        </table>
      
      </form>
    </div>
  </div>
  
  <div> <?php echo $heading_title; ?> 1.3 <br> <?php echo $categoryshipping_development; ?><br> <?php echo $categoryshipping_contact; ?></div>
  
  
</div>



<?php echo $footer; ?>
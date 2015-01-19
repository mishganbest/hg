<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<!-- <p><?php echo $text_shipping_method; ?></p> -->
<table class="form">
  <?php foreach ($shipping_methods as $shipping_method) { ?>
  <tr style="display:none;">
    <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
  </tr>
  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <tr>
    <td style="width: 1px;">
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
    </td>
    <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
    <td style="width: 150px; text-align:right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr>
    <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<?php } ?>
<p><?php // echo $text_shipping_detailed; ?></p>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="4" style="width: 98%;"><?php echo $comment; ?></textarea>
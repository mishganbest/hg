<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  	
  	<div class="content"><?php echo $text_conditions ?></div>
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="testimonial">
	<div class="content">
        <table width="100%">
         <tr>
            <td><?php echo $entry_name ?><span class="required">*</span><br />
              <input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?>
		</td>
          </tr>
          <tr>
             <td><?php echo $entry_city ?><br />
			<input type="text" name="city" value="<?php echo $city; ?>" />
		</td>
          </tr>
          <tr>
             <td><?php echo $entry_phone ?><br />
			<input type="text" name="phone" value="<?php echo $phone; ?>" />
		</td>
          </tr>
          
          <tr>
              <td><?php echo $entry_email ?><br />
              <input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?>
              </td>
          </tr>

          <tr>
            <td><?php echo $entry_enquiry ?><span class="required">*</span><br />
              <textarea name="description" rows="10"><?php echo $description; ?></textarea><br />

              <?php if ($error_enquiry) { ?>
              <span class="error"><?php echo $error_enquiry; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
          <td id="revimg">        
              <?php echo $entry_file ?>&emsp;<input type="file" id="button-revimg" style="width:165px" />
              <input type="hidden" name="image" value="" />
              </td>
          </tr>
          
          <tr>         
               <td id="atach"></td>
          </tr>
          
          <tr>
             <td><?php echo $entry_vk ?><br />
		<input type="text" name="vk" value="<?php echo $vk; ?>" />
		<?php if ($error_vk) { ?>
                <span class="error"><?php echo $error_vk; ?></span>
                <?php } ?>
		</td>
	  </tr>
          <tr>
             <td><?php echo $entry_odnoklass ?><br />
		 <input type="text" name="odnoklass" value="<?php echo $odnoklass; ?>" />
		 <?php if ($error_odnoklass) { ?>
                <span class="error"><?php echo $error_odnoklass; ?></span>
                <?php } ?>
		</td>
          </tr>

          
          
          <tr style="display: none">
            <td><br><?php echo $entry_rating; ?> &nbsp;&nbsp;&nbsp; <span><?php echo $entry_bad; ?></span>&nbsp;
        		<input type="radio" name="rating" value="1" style="margin: 0;" <?php if ( $rating == 1 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="2" style="margin: 0;" <?php if ( $rating == 2 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="3" style="margin: 0;" <?php if ( $rating == 3 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="4" style="margin: 0;" <?php if ( $rating == 4 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="5" style="margin: 0;" <?php if ( $rating == 5 ) echo 'checked="checked"';?> />
        		&nbsp; <span><?php echo $entry_good; ?></span><br /><br>

          	</td>
          </tr>
          <tr style="display: none">
            <td>
              <?php if ($error_captcha) { ?>
              <span class="error"><?php echo $error_captcha; ?></span>
              <?php } ?>
              
              <img src="index.php?route=information/contact/captcha" /> <br>
		<?php echo $entry_captcha; ?><span class="required">*</span> <br>

              <input type="text" name="captcha" value="<?php echo $captcha; ?>" /><br>
		</td>
          </tr>
        </table>
        <br /><div class="left"><a onclick="$('#testimonial').submit();" class="button"><span><?php echo $button_send; ?></span></a></div>
	  </div>
    </form>
  
<?php echo $content_bottom; ?></div>

<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<script type="text/javascript"><!--
new AjaxUpload('#button-revimg', {
	action: 'index.php?route=product/isitestimonial/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-revimg').after('<img src="catalog/view/theme/default/image/loading.gif" id="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			$('#atach').html('<img src="' + json.image + '" alt="" />');
			
			$('input[name=\'image\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#button-revimg').after('<span class="error">' + json.error + '</span>');
		}
		
		$('#loading').remove();	
	}
});
//--></script>

<?php echo $footer; ?> 
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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">

            <table class="form">

	          <tr>
	            <td> <?php echo $entry_description; ?><span class="required">*</span></td>
	            <td>

			  <textarea rows="10" cols="95" name="testimonial_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($testimonial_description[$language['language_id']]) ? $testimonial_description[$language['language_id']]['description'] : ''; ?></textarea>
	              
				<?php if (isset($error_description[$language['language_id']])) { ?>
	              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
	              <?php } ?>
			</td>
	          </tr>
      
            </table>

          </div>
          <?php } ?>
        </div>

      <table class="form">
            <tr>
              <td> <?php echo $entry_name; ?><span class="required">*</span></td>
    		 <td><input type="text" name="name" value="<?php echo $name; ?>">
    
    	              <?php if (isset($error_name)) { ?>
    	              <span class="error"><?php echo $error_name; ?></span>
    	              <?php } ?>
    
    		</td>
            </tr>   

    	     <tr>
              	<td><?php echo $entry_city; ?></td>
    	    	<td><input type="text" name="city" value="<?php echo $city; ?>"></td>
            </tr>
		        
		         <tr>
             <td><?php echo $entry_phone ?></td>
			<td><input type="text" name="phone" value="<?php echo $phone; ?>" />
		</td>
          </tr>
          
          <tr>
              <td><?php echo $entry_email ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
              </td>
          </tr>
          
          <tr>
             <td><?php echo $entry_vk ?></td>
			<td><input type="text" name="vk" value="<?php echo $vk; ?>" />
		</td>
	  </tr>
          <tr>
             <td><?php echo $entry_odnoklass ?></td>
	     <td>
	     	<input type="text" name="odnoklass" value="<?php echo $odnoklass; ?>" />
	     </td>
          </tr>
          
          <tr>
             <td><?php echo $entry_image ?></td>
		 <td>
			<?php if ($image) { ?>
			<img style="display: block" id="atach" src="<?php echo $image; ?>" />
			<a id="button-image"><?php echo $text_change; ?></a>
			&nbsp;|&nbsp; <a onclick="$('#atach').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
			<?php } else { ?>
			<img id="button-image" src="<?php echo $no_image; ?>" />			
			<?php } ?>
			<input type="hidden" id="image" name="image" value="<?php echo $s_image; ?>" />
		</td>
          </tr>
          
          <tr>
             <td><?php echo $entry_audio ?></td>
			<td><input type="file" id="button-audio" /> <input type="hidden" name="audio" id="audio" value="<?php echo $audio; ?>" />&emsp;<span id="atach_audio">
			<?php if ($audio) { ?>
			<?php echo $text_audiofile; ?> <?php echo $audio; ?> &nbsp; <a onclick="$('#atach_audio').html('<?php echo $text_file_clear; ?>'); $('#audio').attr('value', '');"><?php echo $text_clear; ?></a>
			<?php } ?></span>
		</td>
          </tr>
          
          <tr>
             <td><?php echo $entry_video ?></td>
	     <td>http://www.youtube.com/watch?v=<input type="text" id="video" name="video" value="<?php echo $video; ?>" onchange="video_preview()" /><br />
	     <span id="video_atach">
	     <?php if ($video) { ?>
	     <img id="thumb_v" style="width: 100px" src="http://img.youtube.com/vi/<?php echo $video; ?>/default.jpg">
	     <?php } ?>
	     <?php if (isset($error_video)) { ?>
    	              <span class="error"><?php echo $error_video; ?></span>
    	     <?php } ?>
	     </span>
	     </td>
          </tr>
     
        <tr>
          <td><?php echo $entry_date_added; ?></td>
     	    <td><input type="text" name="date_added" value="<?php echo $date_added; ?>"></td> 	
        </tr>
        
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        
          <tr style="display: none">
            <td><?php echo $entry_rating; ?></td> 
		<td><span><?php echo $entry_bad; ?></span>&nbsp;
        		<input type="radio" name="rating" value="1" style="margin: 0;" <?php if ( $rating == 1 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="2" style="margin: 0;" <?php if ( $rating == 2 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="3" style="margin: 0;" <?php if ( $rating == 3 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="4" style="margin: 0;" <?php if ( $rating == 4 ) echo 'checked="checked"';?> />
        		&nbsp;
        		<input type="radio" name="rating" value="5" style="margin: 0;" <?php if ( $rating == 5 ) echo 'checked="checked"';?> />
        		&nbsp; <span><?php echo $entry_good; ?></span>

          	</td>
            </tr>
      </table>
      
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
//--></script> 

<script type="text/javascript"><!--
function video_preview() {
   var v_thumb = $('#video').val(); 
   if (v_thumb) {
   $('#video_atach').show().html('<img style="width: 100px" src="http://img.youtube.com/vi/' + v_thumb + '/default.jpg">');
   } else {
   $('#video_atach').hide();
   }
}
//--></script> 

<script type="text/javascript" src="view/javascript/jquery/ajaxupload.js"></script>
<script type="text/javascript"><!--
new AjaxUpload('button-image', {
	action: 'index.php?route=catalog/testimonial/upload_image&token=<?php echo $token; ?>',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-image').after('<img src="view/image/loading.gif" id="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			$('#atach, #button-image').attr('src', json.image);
			
			$('input[name=\'image\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#button-image').after('<span class="error">' + json.error + '</span>');
		}
		
		$('#loading').remove();	
	}
});


new AjaxUpload('button-audio', {
	action: 'index.php?route=catalog/testimonial/upload_audio&token=<?php echo $token; ?>',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-audio').after('<img src="view/image/loading.gif" id="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();
		
		if (json.success) {
			$('#atach_audio').html('<span><?php echo $text_audiofile; ?> ' + json.prev + '</span>');
			
			$('input[name=\'audio\']').attr('value', json.file);
		}
		
		if (json.error) {
			$('#button-audio').after('<span class="error">' + json.error + '</span>');
		}
		
		$('#loading').remove();	
	}
});
//--></script>

<?php echo $footer; ?>
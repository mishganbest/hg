<?php echo $header; ?>
<div id="content">
<style>
	.positioning-table  td {
		padding: 8px;
	}
	#watermark-color-handler {
		position: relative;
		width: 36px;
		height: 36px;
		background: url(/admin/view/image/colorpicker/select.png);
		cursor: pointer;
	}
	#watermark-color-handler div {
		position: absolute;
		top: 3px;
		left: 3px;
		width: 30px;
		height: 30px;
		background: url(/admin/view/image/colorpicker/select.png) center;
	}
	.no-mark {
		list-style: none;
	}
	.scaling label {
		width: 35px;
		display: inline-block;
	}
</style>
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($errors) { ?>
<ul class="warning no-mark">
	<?php foreach ($errors as $error) { ?>
	<li><?php echo $error; ?></li>
	<?php } ?>
</ul>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $clear; ?>';" class="button"><?php echo $button_clear; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<tbody>
				<tr>
					<td><?php echo $text_status; ?></td>
					<td>
						<select name="watermark[status]">
							<?php foreach ($statuses as $key => $status) { ?>
							<option <?php if ($watermark_module_status == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="watermark-type"><?php echo $text_type; ?></label>
					</td>
					<td>
						<select id="watermark-type" name="watermark[type]">
							<?php foreach ($types as $key => $type) { ?>
							<option <?php if ($watermark_module_type == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $type; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</tbody>
			<tbody id="row-img" class="rows">
				<tr>
					<td>
						<label for="field-image"><span class="required">*</span> <?php echo $text_image; ?></label>
					</td>
					<td>
						<div class="image">
							<img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                  			<input type="hidden" name="watermark[image]" value="<?php echo $image; ?>" id="image" />
                  			<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody  id="row-text" class="rows">
				<tr>
					<td><label for="watermark-text"><span class="required">*</span> <?php echo $text_text; ?></label></td>
					<td><input type="text" name="watermark[watermark_text]" value="<?php echo $watermark_module_watermark_text; ?>" id="watermark-text"/></td>
				</tr>
				<tr>
					<td><label for="watermark-font"><?php echo $text_font; ?></label></td>
					<td>
						<select id="watermark-font" name="watermark[watermark_font]">
							<?php foreach ($fonts as $key => $font) { ?>
							<option <?php if ($watermark_module_watermark_font == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $font; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="watermark-font-size"><span class="required">*</span> <?php echo $text_fontsize; ?></label></td>
					<td><input type="text" name="watermark[watermark_font_size]" value="<?php echo $watermark_module_watermark_font_size; ?>" id="watermark-font-size"/></td>
				</tr>
				<tr>
					<td><label for="watermark-color"><?php echo $text_color; ?></label></td>
					<td><div id="watermark-color-handler"><div style="background-color: rgb(<?php echo $watermark_module_color; ?>);"></div></div><input type="hidden" name="watermark[color]" value="<?php echo $watermark_module_color; ?>" id="watermark-color"/></td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<td><label for="watermark-angle"><?php echo $text_angle; ?></label> <?php echo $help_angle; ?></td>
					<td><input type="text" name="watermark[angle]" value="<?php echo $watermark_module_angle; ?>" id="watermark-angle"/></td>
				</tr>
				<tr>
					<td><label for="watermark-positionings"><?php echo $text_positionings; ?></label></td>
					<td>
						<table border="1" class="positioning-table">
							<tbody>
								<tr>
									<?php foreach($positionings as $key => $position) { ?>
									<?php if ($key%3 === 0 && $key != 0) { ?>
									</tr><tr>
									<?php } ?>
									<td><input <?php if ($watermark_module_position == $position) echo 'checked="checked"'; ?> type="radio" name="watermark[position]" value="<?php echo $position; ?>"></td>
									<?php } ?>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td><?php echo $text_offset; ?></td>
					<td>
						<p>x: <input type="text" name="watermark[offset][x]" value="<?php echo $watermark_module_offset['x']; ?>" /> <?php echo $text_px; ?></p>
						<p>y: <input type="text" name="watermark[offset][y]" value="<?php echo $watermark_module_offset['y']; ?>" /> <?php echo $text_px; ?></p>
					</td>
				</tr>
				
				<tr>
					<td>Скалинг:</td>
					<td>
						<p class="scaling">
							<label for="watermark-scale-width">width:</label>
								<select id="watermark-scale-width" name="watermark[scaling][width]">
									<?php foreach ($sizes['width'] as $key => $width) { ?>
									<option <?php if ($watermark_module_scaling['width'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $width; ?></option>
									<?php } ?>
								</select>
							
						</p>
						<p class="scaling">
							<label for="watermark-scale-height">height:</label>
								<select id="watermark-scale-height" name="watermark[scaling][height]">
									<?php foreach ($sizes['height'] as $key => $height) { ?>
									<option <?php if ($watermark_module_scaling['height'] == $key) echo 'selected="selected"'; ?> value="<?php echo $key; ?>"><?php echo $height; ?></option>
									<?php } ?>
								</select>
							
						</p>
					</td>
				</tr>
				
				<tr>
					<td><label for=""><?php echo $text_opacity; ?></label> <?php echo $help_opacity; ?></td>
					<td><input type="text" name="watermark[opacity]" value="<?php echo $watermark_module_opacity; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo $text_min; ?> <?php echo $help_min; ?></td>
					<td>
						<p>width: <input type="text" name="watermark[min][width]" value="<?php echo $watermark_module_min['width']; ?>" /> <?php echo $text_px; ?></p>
						<p>height: <input type="text" name="watermark[min][height]" value="<?php echo $watermark_module_min['height']; ?>" /> <?php echo $text_px; ?></p>
					</td>
				</tr>
				<tr>
					<td><?php echo $text_folders; ?> <?php echo $help_folders; ?></td>
					<td>
						<div class="scrollbox">
							<?php $class = 'odd'; ?>
							<?php foreach ($folders as $key => $folder) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
							<?php if (in_array($key, $watermark_module_folders)) { ?>
							<input type="checkbox" name="watermark[folders][]" value="<?php echo $key; ?>" checked="checked" />
							<?php } else { ?>
							<input type="checkbox" name="watermark[folders][]" value="<?php echo $key; ?>" />
							<?php } ?>
							<?php echo $folder; ?>
							</div>
							<?php } ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--

	$('#watermark-color-handler').ColorPicker({
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#watermark-color-handler div').css('backgroundColor', '#' + hex);
			$('#watermark-color').val(rgb.r + ',' + rgb.g + ',' + rgb.b);
			console.log(rgb);
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor($('#watermark-color').val());
		}
	});

	function image_upload(field, thumb) {
		$('#dialog').remove();
		
		$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
		
		$('#dialog').dialog({
			title: '<?php echo $text_image_manager; ?>',
			close: function (event, ui) {
				if ($('#' + field).attr('value')) {
					$.ajax({
						url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
						dataType: 'text',
						success: function(text) {
							$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
						}
					});
				}
			},	
			bgiframe: false,
			width: 800,
			height: 400,
			resizable: false,
			modal: false
		});
	};
	$('tbody.rows').each(function(i, el){
		if ($(el).attr('id') != 'row-' + $('#watermark-type').val()) {
			$(el).hide();
		}
		
	});
	$('#watermark-type').change(function(){
		$('tbody.rows').hide();
		$('tbody#row-' + $(this).val()).toggle();
	});

//--></script>
<?php echo $footer; ?>
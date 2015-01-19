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
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
<style>
.w_legend {
	padding: 0px 5px 0px 5px;
	//font-family: DeJavu;
	font-family: Verdana;
	font-weight: bold;
}

.w_default_settings {
	font-size: 13px;
	color: #7A7A7A;
}
</style>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
          <tr>
            <td><?php echo $entry_category; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($categories as $category) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($category['category_id'], $webme_categories_at_homepage_category)) { ?>
                  <input type="checkbox" name="webme_categories_at_homepage_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                  <?php echo $category['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="webme_categories_at_homepage_category[]" value="<?php echo $category['category_id']; ?>" />
                  <?php echo $category['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div></td>
          </tr>
        <tr>
          <td><?php echo $entry_limit; ?><br /><small><?php echo $entry_no_limit; ?></small></td>
          <td><input type="text" name="webme_categories_at_homepage_limit" value="<?php echo $webme_categories_at_homepage_limit; ?>" size="2" />
          <div class="w_default_settings"><br /><?php echo $entry_limit_default; ?></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_random; ?></td>
          <td><input type="radio" name="webme_categories_at_homepage_random" value="1" <?php echo $webme_categories_at_homepage_random_on; ?> /><?php echo $text_yes; ?><input type="radio" name="webme_categories_at_homepage_random" value="0" <?php echo $webme_categories_at_homepage_random_off; ?> /><?php echo $text_no; ?>
          <div class="w_default_settings"><br /><?php echo $entry_random_default; ?></div></td>
        </tr>
        <tr id="tr_webme_categories_at_homepage_sort_by">
          <td id="td1_webme_categories_at_homepage_sort_by"><?php echo $entry_sort_by; ?><br /><small><?php echo $entry_sort_by_hint; ?></small></td>
          <td id="td2_webme_categories_at_homepage_sort_by"><select name="webme_categories_at_homepage_sort_by" id="webme_categories_at_homepage_sort_by">
          <?php foreach ($sorts as $sort) { ?>
          <?php if ($sort['value'] == $webme_categories_at_homepage_sort_by) { ?>
          <option value="<?php echo $sort['value']; ?>" selected="selected"><?php echo $sort['text']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $sort['value']; ?>"><?php echo $sort['text']; ?></option>
          <?php } ?>
          <?php } ?>
          </select>
          <div class="w_default_settings"><br /><?php echo $entry_sort_by_default; ?></div></td>
        </tr>
      </table>
      <!-- //-->
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="webme_categories_at_homepage_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="webme_categories_at_homepage_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="webme_categories_at_homepage_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="webme_categories_at_homepage_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      <!-- //-->
    </form>
	<script><!--
		/* check when page loaded */
		var random_is_used = $('input:radio[name="webme_categories_at_homepage[0][random"]:checked').val();
		(random_is_used == 1) ? $('#tr_webme_categories_at_homepage_sort_by').hide() : $('#tr_webme_categories_at_homepage_sort_by').show();
		
		/* check when value changed */
		$('input:radio[name="webme_categories_at_homepage_random"]').change(function(){
			(this.value == 1) ? $('#tr_webme_categories_at_homepage_sort_by').hide() : $('#tr_webme_categories_at_homepage_sort_by').show();
		});
	//--></script>
  </div>
</div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	//html += '    <td class="left"><input type="text" name="webme_categories_at_homepage_module[' + module_row + '][limit]" value="5" size="1" /></td>';
	//html += '    <td class="left"><input type="text" name="webme_categories_at_homepage_module[' + module_row + '][image_width]" value="80" size="3" /> <input type="text" name="webme_categories_at_homepage_module[' + module_row + '][image_height]" value="80" size="3" /></td>';	
	html += '    <td class="left"><select name="webme_categories_at_homepage_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="webme_categories_at_homepage_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="webme_categories_at_homepage_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="webme_categories_at_homepage_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
<?php echo $footer; ?>
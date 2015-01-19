<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/
?>
<script type="text/javascript"><!--
<?php if (isset($advanced_settings)): ?>
function initSettings(){
    $('.advanced_settings:not(.isinitialised)').each(function(index){
        $.ajax({
            url: 'index.php?route=module/<?php echo $module_id; ?>/settings&setting_id=' + $(this).attr('setting_id') + '&token=<?php echo $token; ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#settings_' + data.setting_id).html(data.html);
                $('#load_settings_' + data.setting_id).hide();
                $('#settings_' + data.setting_id).trigger("settings", [data.setting_id]);
            }
        });
        $(this).addClass("isinitialised");
    });
}

$(document).ready(function(){
    initSettings();
});
<?php endif; ?>

function in_array(what, where) {
    for (var i = 0; i < where.length; i++)
        if (what == where[i]['value'])
            return true;
    return false;
}

var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="center"><select class="layout_id" name="<?php echo $module_id; ?>_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="center"><select name="<?php echo $module_id; ?>_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="center"><select name="<?php echo $module_id; ?>_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="center"><input type="text" name="<?php echo $module_id; ?>_module[' + module_row + '][sort_order]" value="" size="5" /></td>';
<?php if (isset($advanced_settings)): ?>
    html += '    <td class="center">';
    html += '       <span class="advanced_settings" setting_id="' + module_row + '" id="settings_' + module_row + '">';
    html += '       </span>';
    html += '       <img src="view/image/loading.gif" style="vertical-align: middle;" id="load_settings_' + module_row + '"/>'
    html += '    </td>';
<?php endif; ?>
	html += '    <td class="center"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

    $('#module-row' + module_row + ' select[name=\'<?php echo $module_id; ?>_module[' + module_row + '][position]\']').val(
            $('#default_position').val()
    );

    $('#module-row' + module_row + ' select[name=\'<?php echo $module_id; ?>_module[' + module_row + '][status]\']').val(
            $('#default_status').val()
    );

    $('#module-row' + module_row + ' input[name=\'<?php echo $module_id; ?>_module[' + module_row + '][sort_order]\']').val(
            $('#default_sort_order').val()
    );
<?php
    $layout_ids = array();
    foreach ($layouts as $layout){
        $layout_ids[] = $layout['layout_id'];
    }
    $layout_ids = implode(', ', $layout_ids);
?>

    var layout_ids = [<?php echo $layout_ids?>];
    var known_layout_ids = $('.layout_id').serializeArray();
    for (var layout_id in layout_ids){
        if (!in_array(layout_ids[layout_id], known_layout_ids)){
            $('#module-row' + module_row + ' select[name=\'<?php echo $module_id; ?>_module[' + module_row + '][layout_id]\']').val(layout_ids[layout_id]);
            break;
        }
    }
	module_row++;
    <?php if (isset($advanced_settings)): ?>
    initSettings();
    <?php endif; ?>
}
//--></script>
    
<?php if (isset($advanced_settings['script'])): ?>
    <?php echo $advanced_settings['script']; ?>
<?php endif; ?>


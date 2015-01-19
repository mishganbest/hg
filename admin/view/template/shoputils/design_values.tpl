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
<div id="tab-design">
    <table id="module" class="list">
        <thead>
        <tr>
            <td class="center" style="width: 145px;"><?php echo $entry_layout; ?></td>
            <td class="center" style="width: 145px;"><?php echo $entry_position; ?></td>
            <td class="center" style="width: 95px;"><?php echo $entry_status; ?></td>
            <td class="center" style="width: 95px;"><?php echo $entry_sort_order; ?></td>
            <?php if (isset($advanced_settings) && $advanced_settings): ?>
            <td class="center"><?php echo $entry_settings; ?></td>
            <?php endif; ?>
            <td style="width: 130px;"></td>
        </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
        <tr>
            <td class="center"><select class="layout_id" name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>"
                        selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select></td>
            <td class="center"><select name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][position]">
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
            <td class="center"><select name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select></td>
            <td class="center"><input type="text"
                                     name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][sort_order]"
                                     value="<?php echo $module['sort_order']; ?>" size="5"/></td>
            <?php if (isset($advanced_settings) && $advanced_settings): ?>
            <td class="center">
                <span class="advanced_settings" setting_id="<?php echo $module_row; ?>" id="settings_<?php echo $module_row; ?>">
                <?php if (isset($module['settings'])): ?>
                <?php foreach ($module['settings'] as $key=>$value): ?>
                <?php if (is_array($value)): ?>
                        <?php foreach ($value as $key1=>$value1): ?>
                        <input type="hidden"
                               name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][settings][<?php echo $key; ?>][<?php echo $key1; ?>]"
                               value="<?php echo $value1; ?>"/>
                        <?php endforeach; ?>
                <?php else: ?>
                        <input type="hidden"
                               name="<?php echo $module_id; ?>_module[<?php echo $module_row; ?>][settings][<?php echo $key; ?>]"
                               value="<?php echo $value; ?>"/>
                <?php endif ?>
                <?php endforeach; ?>
                <?php endif ?>
                </span>
                <img src="view/image/loading.gif" style="vertical-align: middle;" id="load_settings_<?php echo $module_row; ?>"/>
            </td>
            <?php endif; ?>
            <td class="center"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();"
                                class="button"><span><?php echo $button_remove; ?></span></a></td>
        </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
        <tr>
            <td style="padding: 5px;">
                <?php echo $text_default_parameters; ?>
                <div class="help"><?php echo $text_default_parameters_help; ?></div>
            </td>
            <td class="center">
                <select id="default_position">
                    <option value="content_top"><?php echo $text_content_top; ?></option>
                    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                    <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                    <option value="column_right"><?php echo $text_column_right; ?></option>
                </select>
            </td>
            <td class="center">
                <select id="default_status">
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                </select>
            </td>
            <td class="center">
                <input type="text" id="default_sort_order" value="0" size="5"/>
            </td>
            <?php if (isset($advanced_settings) && $advanced_settings): ?>
            <td></td>
            <?php endif; ?>
            <td class="center"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a>
            </td>
        </tr>
        </tfoot>
    </table>
</div>

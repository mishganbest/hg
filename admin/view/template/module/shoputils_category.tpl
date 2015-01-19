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
<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
            href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>

            <div class="buttons"><a onclick="$('#form').submit();"
                                    class="button"><span><?php echo $button_save; ?></span></a><a
                    onclick="location = '<?php echo $cancel; ?>';"
                    class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs"><a
                    href="#tab-general"><?php echo $tab_general; ?></a><?php echo $design_tab; ?></div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-general">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_expand_by_button; ?><span
                                    class="help"><?php echo $entry_expand_by_button_help; ?></span></td>
                            <td>
                                <select name="shoputils_category_expand_by_button">
                                    <?php if ($shoputils_category_expand_by_button) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_expand_by_text; ?><span
                                    class="help"><?php echo $entry_expand_by_text_help; ?></span></td>
                            <td>
                                <select name="shoputils_category_expand_by_text">
                                    <?php if ($shoputils_category_expand_by_text) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_path_current; ?><span
                                    class="help"><?php echo $entry_path_current_help; ?></span>
                            </td>
                            <td>
                                <select name="shoputils_category_path_current">
                                    <?php if ($shoputils_category_path_current) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_category; ?><span
                                    class="help"><?php echo $entry_category_help; ?></span></td>
                            <td>
                                <select name="shoputils_category_expand_id">
                                    <option value=""><?php echo $text_none; ?></option>
                                    <?php foreach ($categories as $category) { ?>
                                    <?php if ($category['category_id'] == $shoputils_category_expand_id) { ?>
                                    <option value="<?php echo $category['category_id']; ?>"
                                            selected="selected"><?php echo $category['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_products; ?><span
                                    class="help"><?php echo $entry_products_help; ?></span></td>
                            <td>
                                <select name="shoputils_category_products">
                                    <?php if ($shoputils_category_products) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php echo $design_values; ?>
            </form>
        </div>
    </div>
    <?php echo $design_script; ?>
    <script type="text/javascript"><!--
    $('#tabs a').tabs();
    //--></script>
        <?php echo $footer; ?>
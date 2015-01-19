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
  <div class="buttons">
   <a onclick="location = '<?php echo $cancel;  ?>';" class="button"><?php echo $button_cancel;  ?></a>
   <a onclick="location = '<?php echo $modules; ?>';" class="button"><?php echo $button_modules; ?></a>
  </div>
 </div>
 <div class="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
   <?php if ($page == 'MODULES_EXTRACT') { ?>
   <!-- Start Modules Extract Page -->
   <?php if ($module_total) { ?>
   <em><?php echo $text_total; ?> <?php echo $module_total; ?></em>
   <?php } ?>
   <table class="list">
    <?php if ($module_list) { ?>
    <thead>
     <tr>
      <td class="left"><input type="checkbox" onclick="$('input[name*=\'module_list\']').attr('checked', this.checked);" /></td>
      <td class="left"><?php echo $text_module_name; ?></td>
      <td class="left"><?php echo $text_module_size; ?></td>
      <td class="center"><?php echo $text_module_date; ?></td>
      <td class="center"><?php echo $text_module_time; ?></td>
     </tr>
    </thead>
    <?php foreach ($module_list as $module) { ?>
    <tbody>
     <tr>
      <td class="center" width="1%"><input name="module_list[]" type="checkbox" value="<?php echo $module['file']; ?>" /></td>
      <td class="left"><a href="<?php echo $module['link']; ?>"><?php echo $module['name']; ?></a></td>
      <td class="left"><?php echo $module['size' ]; ?></td>
      <td class="center"><?php echo $module['date']; ?></td>
      <td class="center"><?php echo $module['time']; ?></td>
     </tr>
    </tbody>
    <?php } ?>
    <tfoot>
     <tr>
      <td class="center" colspan="5"><a class="button" onclick="formSubmit('delete');" ><?php echo $button_delete; ?></a></td>
     </tr>
    </tfoot>
    <?php } else { ?>
    <tr>
     <td class="center"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
   </table>
   <!-- End Modules Extract Page -->
   <?php } else { ?>
   <!-- Start Main Page -->
   <table id="module" class="list">
    <thead>
     <tr>
      <td class="left" width="50%"><?php echo $text_module_name; ?><?php echo $example_module_name; ?></td>
      <td class="left" width="50%"></td>
     </tr>
    </thead>
    <tbody>
     <tr>
      <td class="left"><input type="text" name="module_name" value="<?php echo $module_name; ?>" style="width:99%;" /></td>
      <td class="left"><a class="button" onclick="formSubmit('search');"><?php echo $text_search; ?></a></td>
     </tr>
    </tbody>
   </table>
   <?php if (isset ($module_total)) { ?>
   <em><?php echo $text_total_search; ?> <?php echo $module_total; ?></em>
   <?php } ?>
   <?php if ($module_search) { ?>
   <table class="list">
    <thead>
     <tr>
      <td class="center" width="1%"><input type="checkbox" onclick="$('input[name*=\'module_search\']').attr('checked', this.checked);" /></td>
      <td class="left" width="44%"><?php echo $text_path_name; ?></td>
      <td class="left" width="45%"><?php echo $text_file_name; ?></td>
     </tr>
    </thead>
    <?php foreach ($module_search as $modules) { ?>
    <tr class="filter">
     <td class="left" colspan="3"><b style="text-transform:uppercase;"><?php echo $modules['module']; ?>&nbsp;&nbsp;&nbsp;(<?php echo count ($modules['files']); ?>)</b></td>
    </tr>
    <?php foreach ($modules['files'] as $module) { ?>
    <tr>
     <td class="center"><input type="checkbox" name="module_search[]" value="<?php echo $module['name']; ?>" /></td>
     <td class="left"><?php echo $module['path']; ?></td>
     <td class="left"><?php echo $module['file']; ?></td>
    </tr>
    <?php } ?>
    <?php } ?>
    <tfoot>
     <tr>
      <td class="center" colspan="3">
       <?php if (isset ($module_total) && $module_total) { ?>
       <a class="button" onclick="formSubmit('extract');"><?php echo $button_extract; ?></a>
       <?php } ?>
      </td>
     </tr>
    </tfoot>
   </table>
   <?php } ?>
   <!-- End Main Page -->
   <?php } ?>
  </form>
 </div>
</div>
<script type="text/javascript"><!--
function formSubmit(action) {
	if (action == 'search') {
		$('#form input[name*=\'module_search\']').attr('checked', false);
		$('#form').submit();
	}
	if (action == 'extract') {
		var inputs = $('#form input[name*=\'module_search\']:checked');
		if (inputs.length) {
			$('#form').submit();
		} else {
			alert('<?php echo $error_select_extract; ?>');
		}
	}
	if (action == 'delete') {
		var inputs = $('#form input[name*=\'module_list\']:checked');
		if (inputs.length) {
			$('#form').submit();
		} else {
			alert('<?php echo $error_select_delete; ?>');
		}
	}
}
//--></script>
<?php echo $footer; ?>
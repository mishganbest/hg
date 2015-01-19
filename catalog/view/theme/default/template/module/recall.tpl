<style type="text/css">

/* #recall_form {
    background: none repeat scroll 0 0 #FFFFFF;
    -webkit-box-shadow: 0px 0px 20px rgba(50, 50, 50, 1);
    -moz-box-shadow:    0px 0px 20px rgba(50, 50, 50, 1);
    box-shadow:         0px 0px 20px rgba(50, 50, 50, 1);
    display: none;
    left: 50%;
    padding: 0;
    position: fixed;
    top: 40%;
    width: 380px;
    z-index: 99999;
} */
.green_big_title {
    color: #FFFFFF;
    font-family: tahoma;
    font-size: 16px;
    font-style: normal;
    line-height: 25px;
    padding-left: 30px;
    display: none;
}
.recall_header {
    background: none repeat scroll 0 0 #585858;
    margin: 7px;
    width: 366px;
    display: none;
}

.recall_header {
    cursor: move;
}

.td_recall_caption {
	width: 115px;
	font-size: 12px;
}

.recall_input {
    width: 190px;
}
.success_block {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #FFFFFF;
    border-radius: 4px 4px 4px 4px;
    margin: 20px 10px;
}
.form_table_recall {
	padding-left: 0px;
}
.form_table_recall .input_error{
	border-color: #000000;
}

#submit_recall {
	margin-left: 12px;
	font-family: Arial;
}

.error_message {
    color: #7A0404;
    /*display: block;*/
    font-family: tahoma;
    font-size: 11px;
    font-weight: normal;
    line-height: 11px;
    padding: 10px 10px 10px 0;
	display: none;
}
</style>

<div id="recall_form"  style="border-radius: 10px 10px 10px 10px;">

	<table cellpadding="0" border="0" class="recall_header " colspan="0" style="border-radius: 5px 5px 5px 5px;">
		<tbody><tr>
			<td valign="center" align="center">
				<div class="green_big_title"><?php echo $text_recall?></div>
			</td>
			<td valign="center" align="right">
				<a onclick="recall_close();" class="recall_close" href="javascript:void(0);"><img title="<?php echo $close_window?>" src="catalog/view/theme/default/image/cancel.png"></a>
			</td>
		</tr>
	</tbody></table>

	<div id="recall_message2" style="display:none;" class="error_block">
		<span id="recall_message" class="error_message" style="text-align:center"></span>
	</div>

	<div style="display:none;" id="recall_success" class="success_block">
		<span class="success_message" style="text-align:center">
			<?php echo $text_success?>
		</span>
	</div>

	<form id="recall_ajax_form" onsubmit="return recall_ajax();" method="POST">
		<input type="hidden" value="yes" name="recall">
		<table cellspacing="5" class="form_table_recall">
			<tbody>
				<?php if ($show_name) { ?>
			<tr>
				<td class="td_recall_caption"><?php if( $required_name ) {?><span class="required">*</span>&nbsp;<?php } ?><?php echo $text_name?></td>
				<td colspan="2"><input type="text" class="recall_input" value="" id="user_name" name="user_name"><div id="user_name_error" class="error_message"></div></td>
			</tr>
				<?php } ?>
			<tr>
				<td class="td_recall_caption"><?php if( $required_phone ) {?><span class="required">*</span>&nbsp;<?php } ?><?php echo $text_phone?></td>
				<td colspan="2"><input type="text" class="recall_input" value="" id="user_phone" name="user_phone"><div id="user_phone_error" class="error_message"></div></td>
			</tr>
			<?php if ($show_email) { ?>
			<tr>
				<td class="td_recall_caption"><?php if( $required_email ) {?><span class="required">*</span>&nbsp;<?php } ?><?php echo $text_email?></td>
				<td colspan="2"><input type="text" class="recall_input" value="" id="user_email" name="user_email"><div id="user_email_error" class="error_message"></div></td>
			</tr>
				<?php } ?>
			<?php if ($show_time) { ?>
			<tr>
				<td class="td_recall_caption"><?php if( $required_time ) {?><span class="required">*</span>&nbsp;<?php } ?><?php echo $text_time?></td>
				<td colspan="2"><input type="text" class="recall_input" value="" id="recommend_to_call" name="recommend_to_call"><div id="recommend_to_call_error" class="error_message"></div></td>
			</tr>
				<?php } ?>
			<?php if ($show_comment) { ?>
			<tr>
				<td class="td_recall_caption"><?php if( $required_comment ) {?><span class="required">*</span>&nbsp;<?php } ?><?php echo $text_comment?></td>
				<td colspan="2"><textarea class="recall_input" rows="5" cols="20" id="user_comment" name="user_comment"></textarea><div id="user_comment_error" class="error_message"></div></td>
			</tr>
				<?php } ?>
			<tr>
			<td align="right" colspan="3">
				<img style="display:none;" id="load_recall" src="catalog/view/theme/default/image/loading.gif">
				<button class="buttonkred radius" style="margin: 10px 0 0 30px; width:200px;" id="submit_recall" type="submit"><?php echo $text_request?></button>
			</td>
		</tr>
		</tbody></table>
	</form>
</div>


<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	validate_generate();
});

function payment_confirm()
{
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/qc_cod/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
}
//--></script>

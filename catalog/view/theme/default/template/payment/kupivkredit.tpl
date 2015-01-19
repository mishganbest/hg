<div id="simple_auto_off">
<div id="mydiv_id"><?php //print_r ($order); ?></div>

<script type="text/javascript">
$.getScript("<?php echo $text_server; ?>", function() {
  vkredit = new VkreditWidget(1, <?php echo $total; ?>,  {
			order: "<?php echo $base64; ?>",
			sig: "<?php echo $sig; ?>"
	});
	vkredit.openWidget();
});
	

</script>

<div class="buttons">
	<div class="right">
	<!-- <a onclick="vkredit.openWidget();" class="button"><span>Оформить кредит</span></a> -->
	<a href="<?php echo $action; ?>" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
</div>




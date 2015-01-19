<div class="vitrin">

<div class="green"></div>
<div class="green2"></div>
<div class="green3"></div>
  
<?php foreach ($categoryhome as $categoryhome) { ?>

<?php if ($categoryhome['category_id'] == '68') {
$name = 'Мойки воздуха';
} elseif ($categoryhome['category_id'] == '80') {
$name = 'Увлажнители';
} elseif ($categoryhome['category_id'] == '102') {
$name = 'Очистители';
} elseif ($categoryhome['category_id'] == '92') {
$name = 'Осушители';
} else {
$name = $categoryhome['name'];
} ?>

	<div class="tovar">
		<h3><?php echo $name; ?></h3>
			<a href="<?php echo $categoryhome['href']; ?>"><img src="<?php echo $categoryhome['thumb']; ?>" alt="<?php echo $name; ?>" /></a>
			<a style="margin-left: 20px" class="readmore" href="<?php echo $categoryhome['href']; ?>"></a>
	</div>
<?php } ?>				
			</div>
			<div class="clear"></div>
		

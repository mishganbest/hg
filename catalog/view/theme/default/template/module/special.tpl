<?php foreach ($products as $product) { ?>
<div class="action">
<?php if ($product['thumb']) { ?>
			<div class="actimg"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
<?php } ?>
<?php $name = iconv_strlen($product['name'], 'UTF-8'); ?>
<?php if ($name > 25) { ?>
				<h5 style="color:#930700;padding: 17px 0 5px 0;margin: 0 0 0 250px;"><?php echo $product['name']; ?></h5>
<?php } else { ?>
				<h4 style="color:#930700;padding-top: 10px;margin: 0 0 0 250px;"><?php echo $product['name']; ?></h4>
<?php } ?>
				<h4 style="color:#001A22;margin-left: 250px;">Привезем и покажем</h4>
				<input type="text" placeholder="Телефон" style="margin: 20px 0 0 300px;"/><br />
				<button class="red radius10" style="margin:20px 0 0 250px;">Заказать демонстрацию</button>
			</div>
<?php } ?>
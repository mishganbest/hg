<?php echo $header; ?>
<h1>Осушители и мойки, увлажнители и очистители воздуха для дома</h1>
<div id="content_home"><div class="right" style="width:320px;margin:20px 37px 0 0">
				<h2>ЗВОНИТЕ! <br /><span style="color:#2b80d7">8 (800) 250-07-33</span><br />ИЛИ ЗАКАЖИТЕ<br /> бесплатную<br /> консультацию</h2>
					<form class="forma2" method="post" id="form_submit_1" action="">
						<input type="text" id="form_1" name="phone" class="deftext required" value="Телефон*" onFocus="if(this.value=='Телефон*') this.value='';" onBlur="if(!this.value) this.value='Телефон*';" /><br />
						<button type="submit" class="bigbut radius" style="margin: 20px 0 0 32px;" onclick="validate_form(1); return false;">Заказать консультацию</button>
				<input type="text" name="bot" style="display:none;">
                		<input type="hidden" name="what" value="form1">
					</form>
			</div>
			<div class="medal" style="top: 358px;left: 520px;"></div>
			<div class="left" style="margin: 230px 0 0 70px;">
				<div class="prem" style="height: 65px;">
					<img src="catalog/view/theme/default/image/box.png" alt="Бесплатная доставка" class="left" />
					<div style="margin-left: 30px; padding-left: 50px">
					<?php if ($price_to_door == 0) { ?>	
						<h2>Бесплатная доставка: </h2>
					<?php } else { ?>
						<h2>Доставка: </h2>
					<?php } ?>	
						<p>по <?php echo $city_to; ?> <?php echo $delivery_period_to_door; ?></p>
					</div>
				</div>
				<div class="prem" style="height: 80px;">
					<img src="catalog/view/theme/default/image/prize.png" alt="Подарок" class="left" />
					<div style="margin-left: 30px; padding-left: 50px">	
						<h2>Подарок: </h2>
						<p>В подарок к мойкам воздуха Venta пробник <br />аромамасла</p>
					</div>
				</div>
				<div class="prem">
					<img src="catalog/view/theme/default/image/doc.png" alt="Официальный дилер" class="left" />
					<div style="margin-left: 30px; padding-left: 50px">	
						<h2>Официальный дилер: </h2>
						<p>Привезем фирменный гарантийный талон, <br />товарный и кассовый чеки</p>
					</div>
				</div>
			</div>

</div><div class="left center-content">
			<h2>ВЫБЕРИТЕ ЧТО ВАС ИНТЕРЕСУЕТ <div class="arrow"></div></h2>
			<div class="vitrin">
			
				<div class="green"></div>
				<div class="green2"></div>
				<div class="green3"></div>
			<?php echo $content_bottom; ?>
			</div>
			</div>
			<div class="sidebar"><?php echo $column_right; ?></div>
<?php echo $footer; ?>
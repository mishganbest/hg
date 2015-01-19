<?php echo $header; ?>
<h1><?php echo $heading_title; ?></h1>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<div id="content_category"><div class="right" style="width:320px;margin:20px 37px 0 0">
				<h2>ЗВОНИТЕ! <br /><span style="color:#2b80d7">8 (800) 250-07-33</span><br />ИЛИ ЗАКАЖИТЕ<br /> бесплатную<br /> консультацию</h2>
					<form class="forma2" method="post" id="form_submit_1" action="">
						<input type="text" id="form_1" name="phone" class="deftext required" value="Телефон*" onFocus="if(this.value=='Телефон*') this.value='';" onBlur="if(!this.value) this.value='Телефон*';" /><br />
						<button type="submit" class="bigbut radius" style="margin: 20px 0 0 32px;" onclick="validate_form(1); return false;">Заказать консультацию</button>
				<input type="text" name="bot" style="display:none;">
                		<input type="hidden" name="what" value="form1">
					</form>
			</div>
			<div class="left" style="margin: 160px 0 0 70px;">
				<div class="prem" style="height: 65px;">
					<img src="catalog/view/theme/default/image/ok.png" class="left" />
					<div style="margin-left: 30px; padding-left: 50px">	
						<?php if ($price_to_door == 0) { ?>	
						<h2>Бесплатная доставка: </h2>
					<?php } else { ?>
						<h2>Доставка: </h2>
					<?php } ?>	
						<p>по <?php echo $city_to; ?> <?php echo $delivery_period_to_door; ?></p>
					</div>
				</div>
				<div class="prem" style="height: 70px;">
					<img src="catalog/view/theme/default/image/ok.png" class="left" />
					<div style="width: 400px; margin: 0px 0 0 30px; padding-left: 50px">
					<?php if ($podarok) { ?>
					<h2>Подарок</h2>
					<p><?php echo $podarok; ?></p>
						<?php } else { ?>	
						<h2>Подарок</h2>
						<?php } ?>
					</div>
				</div>
				<div class="prem">
					<img src="catalog/view/theme/default/image/ok.png"class="left" />
					<div style="margin-left: 30px; padding-left: 50px">	
						<h2>Поставщики и отзывы</h2>
						<p>Привезем фирменный гарантийный талон, <br />товарный и кассовый чеки</p>
					</div>
				</div>
			</div>
</div>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
 
<?php if ($description) { ?>
    <?php echo $description; ?><br />
    <?php } ?>

<?php if ($categories) { ?>
  
    <?php foreach ( $categories as $k=>$category ) { 

                if( $k > 0 ) echo '<br /><br />';

		if (!$category['info']) {
                echo '<div class="product-filter">';
                echo '<h2 class="subcat">' . $category['name'] . '</h2>';
                echo '</div>';
		}

                echo '<div class="product-grid">';

                foreach ( $products_all[ $category['category_id'] ] as $product )
                {
                    echo '<div>';
                    if ( $product['thumb'] ) {
 if ($category['category_id'] == '59') {
                        echo '<div class="medalmin-cat"></div><div class="image"><a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" title="' . $product['name'] . '" alt="' . $product['name'] . '" /></a></div>';
}else{
echo '<div class="image"><a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" title="' . $product['name'] . '" alt="' . $product['name'] . '" /></a></div>';
}
                    }

		if ( $product['price'] ) {
                        
                        if ( !$product['special'] ) { echo '<div class="price"><span class="price-text">Цена</span> ' .$product['price'];} 
                        else { echo '<div class="price1"><span class="price-old-text">старая цена</span> <span class="price-old">' . $product['price'] . '</span> <br /><span class="price-new-text">новая цена</span> <span class="price-new">' . $product['special'] . '</span>'; }
                        if ( $product['tax'] ) { echo '<br /><span class="price-tax">' . $text_tax . ' ' . $product['tax'] . '</span>'; }
                        echo '</div>';
                    }

		echo '<div class="cart">';
                    echo '<a onclick="addToCart(\'' . $product['product_id'] . '\');" class="buy-button"></a>';
                    echo '</div>';

                    echo '<div class="name"><a href="' . $product['href'] . '">' . $product['name'] . '</a></div>';
                    echo '<div class="description">' . $product['description'] . '</div>';

		echo '<div><a class="readmore" href="' . $product['href'] . '"></a></div>';

		echo '<div style="height: 28px"><a class="compare" id="compare_'.$product['product_id'].'" onclick="addToCompare(' . $product['product_id'] . ');"></a></div>';
 
                    echo '</div>';
              }
              echo '</div>';

 }  
?>
<?php } else {  ?>

<?php                            
                echo '<div class="product-grid">';

                foreach ($products as $product) 
                {
                    echo '<div>';
                    if ( $product['thumb'] ) {
if ($medal == '59') {
                        echo '<div class="medalmin-cat"></div><div class="image"><a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" title="' . $product['name'] . '" alt="' . $product['name'] . '" /></a></div>';
}else{
echo '<div class="image"><a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" title="' . $product['name'] . '" alt="' . $product['name'] . '" /></a></div>';
}
                    }

		if ( $product['price'] ) {
                        
                        if ( !$product['special'] ) { echo '<div class="price"><span class="price-text">Цена</span> ' .$product['price'];} 
                        else { echo '<div class="price1"><span class="price-old-text">старая цена</span> <span class="price-old">' . $product['price'] . '</span> <br /><span class="price-new-text">новая цена</span> <span class="price-new">' . $product['special'] . '</span>'; }
                        if ( $product['tax'] ) { echo '<br /><span class="price-tax">' . $text_tax . ' ' . $product['tax'] . '</span>'; }
                        echo '</div>';
                    }

		echo '<div class="cart">';
                    echo '<a onclick="addToCart(\'' . $product['product_id'] . '\');" class="buy-button"></a>';
                    echo '</div>';

                    echo '<div class="name"><a href="' . $product['href'] . '">' . $product['name'] . '</a></div>';
                    echo '<div class="description">' . $product['description'] . '</div>';

		echo '<div><a class="readmore" href="' . $product['href'] . '"></a></div>';

		echo '<div style="height: 28px"><a class="compare" id="compare_'.$product['product_id'].'" onclick="addToCompare(' . $product['product_id'] . ');"></a></div>';
 
                    echo '</div>';
              }
              echo '</div>';

   
?>
<?php }  ?>

  
  <?php if (!$categories && !$products) { ?>
  <?php if (!$info) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
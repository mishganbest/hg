<?php
if (isset($w_categories)) {
	foreach ($w_categories as $w_category) {
?>
<?php if (isset($w_category["products"])) { ?>
<div class="box">
  <div class="home-heading"><a href="<?php echo $w_category["href"]; ?>"><?php echo $w_category["heading_title"]; ?></a></div>
  
   
<?php if ($w_category['heading_title'] == 'Мойки воздуха Venta' || $w_category['heading_title'] == 'Бытовые мойки воздуха VENTA') { 

	$tip = false;
	$proizvod = false;
	$ploschad = true;
	$ploschad_2 = false;
	$ploschad_3 = false;
	$ploschad_4 = false;
	$voda = true;
	$filtri = false;
	$garant = true;
	
} elseif ($w_category['heading_title'] == 'Промышленные мойки воздуха VENTA') { 

	$tip = false;
	$proizvod = false;
	$ploschad = false;
	$ploschad_2 = false;
	$ploschad_3 = false;
	$ploschad_4 = true;
	$voda = true;
	$filtri = false;
	$garant = true;

} elseif ($w_category['heading_title'] == 'Увлажнители Boneco Air-O-Swiss') { 

	$tip = true;
	$proizvod = false;
	$ploschad = false;
	$ploschad_2 = true;
	$ploschad_3 = false;
	$ploschad_4 = false;
	$voda = true;
	$filtri = false;
	$garant = true;

 } elseif ($w_category['heading_title'] == 'Осушители воздуха Ballu') { 

	$tip = false;
	$proizvod = true;
	$ploschad = false;
	$ploschad_2 = false;
	$ploschad_3 = true;
	$ploschad_4 = false;
	$voda = false;
	$filtri = false;
	$garant = true;

} elseif ($w_category['heading_title'] == 'Очистители воздуха Ballu') { 

	$tip = false;
	$proizvod = false;
	$ploschad = false;
	$ploschad_2 = false;
	$ploschad_3 = true;
	$ploschad_4 = false;
	$voda = false;
	$filtri = true;
	$garant = false;
	
} ?>
  
<div class="pricelist"> 
  <table>
        <!-- noindex --><thead>
          <tr>
          <?php if ($tip) { ?>
            <th class="tip"><?php echo $column_tip; ?></th>
          <?php } ?>
            <th class="name"><?php echo $column_name; ?></th>
            <?php if ($proizvod) { ?>
            <th class="proizvod"><?php echo $column_proizvod; ?></th>
            <?php } ?>
            <?php if ($ploschad) { ?>
            <th class="ploschad"><?php echo $column_ploschad; ?></th>
            <?php } ?>
            <?php if ($ploschad_2) { ?>
            <th class="ploschad"><?php echo $column_ploschad_2; ?></th>
            <?php } ?>
            <?php if ($ploschad_3) { ?>
            <th class="ploschad"><?php echo $column_ploschad_3; ?></th>
            <?php } ?>
            <?php if ($ploschad_4) { ?>
            <th class="ploschad"><?php echo $column_ploschad_4; ?></th>
            <?php } ?>
            <?php if ($voda) { ?>
            <th class="voda"><?php echo $column_voda; ?></th>
            <?php } ?>
            <?php if ($filtri) { ?>
            <th class="filtri"><?php echo $column_filtri; ?></th>
            <?php } ?>
            <?php if ($garant) { ?>
            <th class="garant"><?php echo $column_garant; ?></th>
            <?php } ?>
            <th class="image"><?php echo $column_image; ?></th>
            <th class="price"><?php echo $column_price; ?></th>
            <th class="action"><?php echo $column_action; ?></th>
          </tr>
        </thead><!-- /noindex -->
        
      <?php foreach ($w_category["products"] as $product) { ?>

      <tbody>
      
      <tr>
      		<?php if ($tip) { ?>
           <td class="center">
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Тип') { ?>
                <?php echo $attribute['text']; ?>	        
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>
          </td>
          <?php } ?>
                
      	    <td class="name">
      	    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
      	    </td>
      	          	    
      	    <?php if ($proizvod) { ?>
           <td class="center">
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Производительность по осушению, л/сут') { ?>
                <?php echo $attribute['text']; ?>	        
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>
          </td>
          <?php } ?>
      	          	                    
                <?php if ($ploschad) { ?>
           <td class="center">         
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Общая площадь комнаты, м2 (увлажнение/очистка)') { ?>
	        <?php echo $attribute['text']; ?>
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>          
          </td>
          <?php } ?>
          
          <?php if ($ploschad_2) { ?>
           <td class="center">         
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Площадь / Объем помещения, , м²/м³') { ?>
	        <?php echo $attribute['text']; ?>
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>          
          </td>
          <?php } ?>
          
          <?php if ($ploschad_3) { ?>
           <td class="center">         
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Рекомендуемая площадь, м2') { ?>
	        <?php echo $attribute['text']; ?>
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>          
          </td>
          <?php } ?>
          
          <?php if ($ploschad_4) { ?>
           <td class="center">         
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Подходит для помещений, м3 (увлажнение и очистка)') { ?>
	        <?php echo $attribute['text']; ?>
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>          
          </td>
          <?php } ?>
                    
          
          <?php if ($voda) { ?>
           <td class="center">
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Объем заливаемой воды, л') { ?>
                <?php echo $attribute['text']; ?>	        
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>
          </td>
          <?php } ?>
                    
          
          <?php if ($filtri) { ?>
           <td class="center">
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Число фильтров') { ?>
                <?php echo $attribute['text']; ?>        
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>
          </td>
          <?php } ?>
                    
                    
          <?php if ($garant) { ?>
           <td class="center">
                <?php if ($product['attribute_groups']) { ?>
      		<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <?php if ($attribute['name'] == 'Гарантия') { ?>
                <?php echo $attribute['text']; ?>	        
	        <?php } ?><?php } ?><?php } ?>
	        <?php } ?>
          </td>
          <?php } ?>
          
                
          <td class="center image">  
          	<?php if ($product['thumb']) { ?>
        	<div class="image"><a href="<?php echo $product['popup']; ?>" class="fancybox" title="<?php echo $product['name']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>   
        </td>
                
        <td class="center">
	        <?php if ($product['price']) { ?>
	        <div class="price">
	          <?php if (!$product['special']) { ?>
	          <?php echo $product['price']; ?>
	          <?php } else { ?>
	          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
	          <?php } ?>
	        </div>
	        <?php } ?>
        </td>
                               
        <td class="center">
        	<a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a>
        </td>
        
     </tr>       
 </tbody>
      
      <?php } ?>
      </table>
    </div>
  
</div>
<?php } ?>

<?php
	} 
} 
?>
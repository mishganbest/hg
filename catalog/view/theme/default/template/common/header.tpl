<!DOCTYPE HTML>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta charset="UTF-8">
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css?ver=3" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/reset.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/county.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/county.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(function() {
	$.fn.__tabs = $.fn.tabs; 
	$.fn.tabs = function (a, b, c, d, e, f) { var base = location.href.replace(/#.*$/, ''); 				$('ul>li>a[href^="#"]', this).each(function () { var href = $(this).attr('href'); 
	$(this).attr('href', base + href); }); 
	$(this).__tabs(a, b, c, d, e, f); }; 
    	$( "#tabs" ).tabs();
  });
  </script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!--[if IE]>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4-iefix.js"></script>
<![endif]--> 

<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->

<?php echo $google_analytics; ?>
</head>
<body>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>

  <div id="logo"><a href="<?php echo $home; ?>">Интернет магазин</a>
<br /> 

  <div class="slogan-noindex"><a href="/delivery-info/"><span class="delivery_day_head">Доставка по <?php echo $city_to; ?> <?php echo $delivery_period_to_door; ?></span></a></div>

 </div>

  <?php } ?>
  <?php if (count($languages) > 1) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div id="language"><?php echo $text_language; ?><br />
      <?php foreach ($languages as $language) { ?>
      &nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().parent().submit();" />
      <?php } ?>
      <input type="hidden" name="language_code" value="" />
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>	
  </form>
  <?php } ?>
  <?php if (count($currencies) > 1) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div id="currency"><?php echo $text_currency; ?><br />
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['code'] == $currency_code) { ?>
      <?php if ($currency['symbol_left']) { ?>
      <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_left']; ?></b></a>
      <?php } else { ?>
      <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_right']; ?></b></a>
      <?php } ?>
      <?php } else { ?>
      <?php if ($currency['symbol_left']) { ?>
      <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>').submit(); $(this).parent().parent().submit();"><?php echo $currency['symbol_left']; ?></a>
      <?php } else { ?>
      <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>').submit(); $(this).parent().parent().submit();"><?php echo $currency['symbol_right']; ?></a>
      <?php } ?>
      <?php } ?>
      <?php } ?>
      <input type="hidden" name="currency_code" value="" />
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>
  </form>
  <?php } ?>


<div id="user_welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
<br />
<span class="cursiv"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a> &emsp;&nbsp;&nbsp; <a href="<?php echo $delivery; ?>"><?php echo $text_delivery; ?></a></span>
</div>

<div id="search">
    <div class="button-search"></div>
    <?php if ($filter_name) { ?>
    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
    <?php } else { ?>
    <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
    <?php } ?>
  </div>


  <div id="welcome">

<?php $city_length = iconv_strlen($h_city, 'UTF-8'); ?>
<?php if ($city_length > 15) { ?>
<p class="city_small">
<?php } else { ?>
<p class="city">
<?php } ?>
  <a class="select" href="javascript:void(0);">г. <?php echo $h_city; ?> <img src="catalog/view/theme/default/image/strl.png" alt="" /></a>
</p>
  

<?php if ($cities) { ?>
<div id="city">
<div class="city_content">
   <?php for ($i = 0; $i < count($cities);) { ?>
    <ul>
      <?php $j = $i + ceil(count($cities) / 5); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($cities[$i])) { ?>
      <li><a onclick="saveCity(this.id);" id="<?php echo $cities[$i]['name']; ?>" href="javascript:void(0);"><?php echo $cities[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
</div>
  </div>
<script type="text/javascript">
	$(document).ready(function(){
	$('body').click(function(){
	$('.city_content').fadeOut("fast");
	});
	$('.city_content').css({'display':'none'});
	$('.select').click(function(e){
	e.stopPropagation();
	$('.city_content').fadeIn("fast");
	});
    });
</script>
   <?php } ?>


   
    <div class="phone_block">
    <?php if (isset($this->session->data['url_detect'])) { ?>
	<span class="phone"><?php echo $this->session->data['url_detect']; ?></span>
    <?php } elseif ($h_city == 'Новосибирск') { ?>
        <span class="phone"><?php echo $telephone; ?></span>
    <?php } else { ?>
       <span class="phone"><?php echo $telephone_2; ?></span>
  <?php } ?>
  </div>
  
  <?php foreach ($modules as $module) { ?> 
  <div id="callmeback">
  <div class="callmeback_heading">
  <a class="callmeback_button radius10" href="javascript:void(0);">Заказать звонок</a>
  </div>
<script type="text/javascript" src="catalog/view/javascript/jquery/recall.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	$('body').click(function(){
	$('.callmeback_content').slideUp("fast");
	});
	$('.callmeback_content').css({'display':'none'});
	$('.callmeback_button, #recall_form').click(function(e){
	e.stopPropagation();
	$('.callmeback_content').slideDown("fast");
	});
    });
</script>
<div class="callmeback_content"><?php echo $module; ?></div>
  </div>
  <?php } ?>

  <div id="cart">    
    <img src="catalog/view/theme/default/image/cart.png"/><span><a href="index.php?route=checkout/simplecheckout"><?php echo $text_cart; ?></a></span><a><span id="cart_total" class="bold"><?php echo $text_items; ?></span></a>
		<div class="content"></div>
  </div>
	
	</div>

</div>
<?php if ($categories) { ?>
<div id="menu">
  <ul>
    <?php foreach ($categories as $category) { ?>
    <li><?php if ($category['active']) {  ?>
    
	<a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
	<?php } else { ?>
	<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	<?php } ?>

      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
                          
          <?php } ?>
          <?php } ?>

        </ul>
        <?php } ?>
        
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>

<div id="notification"></div>

<?php 
class ControllerInformationDelivery extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('information/delivery');

    	$this->document->setTitle($this->language->get('heading_title'));  
    	
    	$this->load->model('setting/store');
			
		$this->load->model('module/geo');
		$city = $this->model_module_geo->smarty_function_get_city();
		
	$this->data['delivery_description'] = '';
		
		$this->load->model('catalog/product');	
			
		$delivery_points = $this->model_catalog_product->getDeliveryPoints($city);
			
			$this->data['delivery_points'] = array(); 
			
			foreach ($delivery_points as $delivery_point) {
			
			$address = array();
			$address['address'] = $delivery_point['address'];
			$address['time'] = $delivery_point['time'];
			$address['phone'] = $delivery_point['phone'];
			$address['photo_url'] = $delivery_point['photo_url'];
			
			$addresses[] = $address;
			
			if (count($addresses)) { 
		          $i_addresses = array();
		          foreach ($addresses as $addre) {
		          if ($addre['address']) { 
		          	$e_address = $addre['address'];
		          	}else{
		          	$e_address = '';
		          }
		          if ($addre['time']) { 
		          	$e_time = 'Время работы: '.$addre['time'];
		          	}else{
		          	$e_time = '';
		          }
		          if ($addre['phone']) { 
		          	$e_phone = 'Тел: '.$addre['phone'];
		          	}else{
		          	$e_phone = '';
		          }
		          if ($addre['photo_url']) { 
		          	$e_photo_url = '<a class="fancybox" href="'.$addre['photo_url'].'">посмотреть на карте</a>';
		          	}else{
		          	$e_photo_url = '';
		          }
		            
		          $i_addresses[] = '<li>'.$e_address.'&nbsp;&nbsp;'.$e_photo_url.'<br />'.$e_time.'<br />'.$e_phone.'</li>';
		          }
		         
		     	  $i_delivery = implode($i_addresses);
		     	  $delivery = '<ul>'.$i_delivery.'</ul>';
		     	           
			  }
			  
			  if ($city == 'Новосибирск') {
       				$phone = $this->config->get('config_telephone');
   				 } else {
       				$phone = $this->config->get('config_telephone_2');
  				}
						
			if (!$this->config->get('config_store_id')) {
			$delivery_description = html_entity_decode($this->config->get('config_delivery_description_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
						
					
		$rate = $this->config->get('geocost_' . $delivery_point['city_id'] . '_rate');

			$data = explode(':', $rate);

			$terms = count($data);

			if ($terms == 2) {
						
			$shipping_price = 'При заказе от '.$data[0].' руб доставка осуществляется бесплатно.<br /> При заказе до '.$data[0].' руб, стоимость доставки '.$data[1].' руб.';

			} elseif ($terms == 3) {

			$shipping_price = 'При заказе от '.$data[0].' руб стоимость доставки '.$data[2].' руб.<br /> При заказе до '.$data[0].' руб, стоимость доставки '.$data[1].' руб.';

			} elseif ($terms == 4) {

			$shipping_price = 'При заказе от '.$data[2].' руб доставка осуществляется бесплатно.<br /> При заказе от '.$data[0].' руб до '.$data[2].' руб, стоимость доставки '.$data[3].' руб.<br /> При заказе до '.$data[0].' руб, стоимость доставки '.$data[1].' руб.';

			} elseif ($terms == 5) {

			$shipping_price = 'При заказе до '.$data[0].' руб, стоимость доставки '.$data[1].' руб.<br /> При заказе от '.$data[0].' руб до '.$data[2].' руб, стоимость доставки '.$data[3].' руб.<br /> При заказе от '.$data[2].' руб, стоимость доставки '.$data[4].' руб.';

			} else {

			$shipping_price = 'Стоимость доставки по запросу.';

			}


		// pickup

		$rate_pickup = $this->config->get('geocost_pickup_' . $delivery_point['delivery_point_id'] . '_rate');

			$data_pickup = explode(':', $rate_pickup);

			$terms_pickup = count($data_pickup);

			if ($terms_pickup == 2) {
						
			$pickup_price = 'При заказе от '.$data_pickup[0].' руб самовывоз осуществляется бесплатно.<br /> При заказе до '.$data_pickup[0].' руб, стоимость доставки до пункта выдачи '.$data_pickup[1].' руб.';

			} elseif ($terms_pickup == 3) {

			$pickup_price = 'При заказе от '.$data_pickup[0].' руб стоимость доставки до пункта выдачи '.$data_pickup[2].' руб.<br /> При заказе до '.$data_pickup[0].' руб, стоимость доставки до пункта выдачи '.$data_pickup[1].' руб.';

			} elseif ($terms_pickup == 4) {

			$pickup_price = 'При заказе от '.$data_pickup[2].' руб самовывоз осуществляется бесплатно.<br /> При заказе от '.$data_pickup[0].' руб до '.$data_pickup[2].' руб, стоимость доставки до пункта выдачи '.$data_pickup[3].' руб.<br /> При заказе до '.$data_pickup[0].' руб, стоимость доставки до пункта выдачи '.$data_pickup[1].' руб.';

			} elseif ($terms_pickup == 5) {

			$pickup_price = 'При заказе до '.$data_pickup[0].' руб, стоимость доставки до пункта выдачи '.$data_pickup[1].' руб.<br /> При заказе от '.$data_pickup[0].' руб до '.$data_pickup[2].' руб, стоимость доставки до пункта выдачи '.$data_pickup[3].' руб.<br /> При заказе от '.$data_pickup[2].' руб, стоимость доставки до пункта выдачи '.$data_pickup[4].' руб.';

			} else {

			$pickup_price = 'Стоимость доставки до пункта выдачи по запросу.';

			}

					
		$find = array(
				'{city}',
				'{city_in}',
				'{city_to}',
				'{city_a}',
				'{phone}',
				'{delivery_points}',
				'{shipping_price}',
				'{pickup_price}',
				'{delivery_period_to_warehouse}',
				'{price_to_warehouse}',
				'{delivery_period_to_door}'
			);
			

			$replace = array(
				'city' => $delivery_point['name'],
				'city_in' => $delivery_point['name_in'],
				'city_to' => $delivery_point['name_to'],
				'city_a' => $delivery_point['name_a'],
				'phone' => $phone,
				'delivery_points' => html_entity_decode($delivery, ENT_QUOTES, 'UTF-8'),
				'shipping_price' => $shipping_price,
				'pickup_price' => $pickup_price,
				'delivery_period_to_warehouse' => $delivery_point['delivery_period_to_warehouse'],
				'price_to_warehouse' => $delivery_point['price_to_warehouse'],
				'delivery_period_to_door' => $delivery_point['delivery_period_to_door']			  
			);
		
		
			$this->data['delivery_description'] = str_replace($find, $replace, $delivery_description);

			} 
	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/delivery'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	
    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/delivery.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/delivery.tpl';
		} else {
			$this->template = 'default/template/information/delivery.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render());		
  	}

}
?>

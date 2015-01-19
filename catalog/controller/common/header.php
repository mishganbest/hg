<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		
		$this->language->load('common/header');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = $this->config->get('config_name');
				
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		
		// Calculate Totals
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {						 
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_cart'] = $this->language->get('text_cart');
		$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_language'] = $this->language->get('text_language');
    	$this->data['text_currency'] = $this->language->get('text_currency');
    	$this->data['text_delivery'] = $this->language->get('text_delivery');
    	$this->data['text_contact'] = $this->language->get('text_contact');
    	
    	$this->data['email'] = $this->config->get('config_email');
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['telephone_2'] = $this->config->get('config_telephone_2');
				
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/quickcheckout', '', 'SSL');
		$this->data['delivery'] = $this->url->link('information/delivery');
		$this->data['contact'] = $this->url->link('information/contact');
		
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		$this->data['action'] = $this->url->link('common/home');

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}			
			
			$this->data['redirect'] = $this->url->link($route, $url);
		}
		
		
		
		$module_data = array();
		
		$this->load->model('setting/extension');
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($extension['code'] == 'recall' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module
						);				
					}
				}
			}
		}
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}


		if (isset($this->session->data['simple']['shipping_address']['city'])) {
			$city = $this->session->data['simple']['shipping_address']['city'];
		}else{
			$this->load->model('module/geo');
			$city = $this->model_module_geo->smarty_function_get_city();
		}
		
		$this->data['h_city'] = $city;


		if ($city == 'Новосибирск') {
		

	// URL Detect
		$full_url = $this->request->server['REQUEST_URI'];
 
		$url_detect_1 = 'utm_source=marketplace&amp;utm_medium=pulscen';
 
		$detect1 = strpos($full_url, $url_detect_1);
		if ($detect1 == true) {
			$this->session->data['url_detect'] = '8 (383) 207-55-34';
		}

		$url_detect_2 = 'utm_source=marketplace&amp;utm_medium=blizko&amp;utm_source=blizkoru_id12694548';
 
		$detect2 = strpos($full_url, $url_detect_2);
		if ($detect2 == true) {
			$this->session->data['url_detect'] = '8 (383) 207-56-39';
		}

		$url_detect_3 = '_openstat=dGVzdDsxOzE7';
 
		$detect3 = strpos($full_url, $url_detect_3);
		if ($detect3 == true) {
			$this->session->data['url_detect'] = '8 (383) 207-56-73';
		}

		$url_detect_4 = 'utm_source=marketplace&amp;utm_medium=begun';
 
		$detect4 = strpos($full_url, $url_detect_4);
		if ($detect4 == true) {
			$this->session->data['url_detect'] = '8 (383) 207-56-15';
		}

		$url_detect_5 = 'yclid';
 
		$detect5 = strpos($full_url, $url_detect_5);
		if ($detect5 == true) {
			$this->session->data['url_detect'] = '8 (383) 207-56-73';
		}


		if (!isset($this->session->data['url_detect']) || $this->session->data['url_detect'] != '8 (383) 207-55-34' && $this->session->data['url_detect'] != '8 (383) 207-56-39' && $this->session->data['url_detect'] != '8 (383) 207-56-73' && $this->session->data['url_detect'] != '8 (383) 207-56-15') {
			$this->session->data['url_detect'] = '8 (383) 310-33-39'; 
		}

	// URL Detect

		} elseif ($city == 'Москва') {
			$this->session->data['url_detect'] = '8 (495) 215-51-67';
		} else {
			$this->session->data['url_detect'] = '8 (800) 250-07-33';
		}

		
		$this->load->model('catalog/product');
		
		$delivery_points = $this->model_catalog_product->getDeliveryPoints($city);

			if ($delivery_points) {
			
			$this->data['delivery_points'] = array(); 
			
			foreach ($delivery_points as $delivery_point) {
				$this->data['price_to_door'] = $delivery_point['price_to_door'];
				$this->data['city_to'] = $delivery_point['name_to'];
			}
			}else{
				$this->data['price_to_door'] = '';
				$this->data['city_to'] = $city;
			}

	/*	if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != 0) {
			$d = $delivery_point['delivery_period_to_door'];
		} else {
			$d = 0;
		}
		
	    $date=explode(".", date("l.d.m.Y", strtotime((+(int)$d)." days")));
		    switch ($date[0]){
		    case 'Monday': $l='в понедельник'; break;
		    case 'Tuesday': $l='во вторник'; break;
		    case 'Wednesday': $l='в среду'; break;
		    case 'Thursday': $l='в четверг'; break;
		    case 'Friday': $l='в пятницу'; break;
		    case 'Saturday': $l='в субботу'; break;
		    case 'Sunday': $l='в воскресенье'; break;
		    }
		    switch ($date[2]){
		    case 1: $m='января'; break;
		    case 2: $m='февраля'; break;
		    case 3: $m='марта'; break;
		    case 4: $m='апреля'; break;
		    case 5: $m='мая'; break;
		    case 6: $m='июня'; break;
		    case 7: $m='июля'; break;
		    case 8: $m='августа'; break;
		    case 9: $m='сентября'; break;
		    case 10: $m='октября'; break;
		    case 11: $m='ноября'; break;
		    case 12: $m='декабря'; break;
	    }
    
		if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != '0') {
			$this->data['delivery_period_to_door'] = $l.'&nbsp;'.(int)$date[1].'&nbsp;'.$m;
		} elseif (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		} */



	 if (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		} 

	// $this->data['delivery_period_to_door'] = '';


		
		$this->load->model('localisation/city');
		
		$this->data['cities'] = $this->model_localisation_city->getCities();
		
		$cities_h = $this->db->query("SELECT * FROM " . DB_PREFIX . "cities WHERE name = '" . $city . "'");
		if ($cities_h->num_rows) {
			$rows = $cities_h->rows;
			foreach ($rows as $row) {
			$this->data['slogan_city'] = $row['name_to'];
			}
		} else {
			$this->data['slogan_city'] = '';
		}

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
    	}		
						
		$this->data['language_code'] = $this->session->data['language'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
				
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
   		}
						
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
		
		// Menu
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($category['top']) {
				$children_data = array();
				
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				
				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true	
					);		
						
					$product_total = $this->model_catalog_product->getTotalProducts($data);
					
						if ($child['info'] == 0) {	
						$child['name'] .= ' (' . $product_total . ')';
						} else {
						$child['name'] .= '';
						}
									
					$children_data[] = array(
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
					);					
				}
				
				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
					'active'   => in_array($category['category_id'], $parts)
				);
			}
		}
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 
	
	public function savecity() {
		$city = $this->request->post['city'];
		setcookie('city', $city, time()+3600*24*7, '/');
		$this->session->data['simple']['shipping_address']['city'] = $city;
		//unset($this->session->data['shipping_methods']);
	}	
}
?>
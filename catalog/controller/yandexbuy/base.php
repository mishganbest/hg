<?php
/**
* Yandex CPA "Покупка на Маркете" для OpenCart (ocStore) 1.5.5.x
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

class ControllerYandexbuyBase extends Controller {
	protected $logfile;
	protected $paymentMethods;
	protected $OUTLET_MAPPING;
	protected $CONFIG;
	
	protected $PAYMENT_TYPES;
	protected $PAYMENT_METHODS;

	protected function setConfig() {
		//Соответсвие точек продаж Яндекс-Маркета адресам, в созданном заказе
		//Устанавливается в системе администрирования, но может быть установлено и здесь
		$this->OUTLET_MAPPING = array();
		foreach($this->getOutlets() as $outlet) {
			$this->OUTLET_MAPPING[$outlet['id']] = array(
				'zone'=>$outlet['zone'],
				'city'=>$outlet['city'],
				'postcode'=>$outlet['postcode'],
				'address_1'=>$outlet['address_1'],
				'address_2'=>$outlet['address_2']
			);
		}

		$this->CONFIG = array(
			//Цифры - статусы заказа в OpenCart
			//Если они у вас особенные - проставьте свои ID статусов заказа
			'STATUS_MAPPING' => array(
				'RESERVED'=>1,		//Товары зарезервированы
				'UNPAID'=>1,		//Ожидание оплаты
				'PROCESSING'=>1,	//Ожидание (По умолчанию)
				'DELIVERY'=>2,		//В обработке
				'PICKUP'=>3,		//Доставлено
				'DELIVERED'=>3,		//Доставлено
				'CANCELLED'=>7,		//Отменено
			)
		);
	}

	//Курьерская доставка из модуля "Доставка в зависимости от города"
	protected function getDeliveries() {
		$query = $this->db->query("SELECT city_id, name FROM " . DB_PREFIX . "cities");
		$deliveries = array();
      					
		foreach ($query->rows as $result) {
   			if ($this->config->get('geocost_' . $result['city_id'] . '_status') && $this->config->get('geocost_' . $result['city_id'] . '_yacode')) {
				$deliveries[] = array(
					'id'=>'city',
					'name'=>'Доставка по г.'.$result['name'], 
					'price'=>$this->config->get('geocost_' . $result['city_id'] . '_rate'),
					'region'=>$this->config->get('geocost_' . $result['city_id'] . '_yacode'),
					'from'=>24,
					'to'=>48
				);
			}
		}

		if (!is_array($deliveries)) {
			$deliveries = array();
		}
		return $deliveries;
	}
	
	protected function getOutlets() {
		$query = $this->db->query("SELECT *, dp.address, c.name AS city FROM " . DB_PREFIX . "delivery_point dp LEFT JOIN " . DB_PREFIX . "cities c ON (dp.city_id = c.city_id)");
		$outlets = array();
		foreach ($query->rows as $result) {
   			if ($this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_status') && $this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_yacode')) {
				$outlets[] = array(
					'id'=>$this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_yacode'),
					'zone' => '',
					'city' => $result['city'],
					'postcode' => '',
					'address_1' => strip_tags($result['address']),
					'address_2' => '',
					'price'=>$this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_rate'),
					'from'=>24,
					'to'=>48
				);
			}
		}
		return $outlets;
	}

	protected function getDeliveryPrice($price, $total=0) {
		$price = str_replace('|', ':', $price);
		$cost = 0.00;
		if (strpos($price, ':') === false)
			return $price;
			
		$data = explode(':', $price);
		$terms = count($data);
		if ($terms == 2) {
			if ($data[0] >= $total) {							
					$cost = $data[1];						
			}else{
					$cost = 0;							
			}
		} elseif ($terms == 3) {
			if ($data[0] >= $total) {							
					$cost = $data[1];						
			}elseif ($data[0] < $total) {
					$cost = $data[2];							
			}else{
					$cost = 0;							
			}
		} elseif ($terms == 4) {
			if ($data[0] >= $total) {							
				$cost = $data[1];						
			}elseif ($data[0] < $total && $data[2] > $total) {
				$cost = $data[3];							
			}else{
				$cost = 0;							
			}
		} elseif ($terms == 5) {
			if ($data[0] >= $total) {							
				$cost = $data[1];						
			}elseif ($data[0] < $total && $data[2] > $total) {
				$cost = $data[3];							
			}else{
				$cost = $data[4];							
			}
		} else {
			$cost = false;
		}		
		return $cost;
	}

	/**
	* Метод возвращает возможные способы доставки
	* @param float $total стоимость товаров в корзине
	* @param array $region регион доставки в формате Яндекса
	*/
	protected function getShipping($total=0, $regions=array()) {
		//В данном примере 2 способа доставки: 
		//1) Курьеркая доставка: бесплатно при заказе от 1500руб., 200руб. до 1500руб.; доставка в течении 24-48 часов.
		//2) Cамовывоз: бесплатно, срок доставки 24-48 часов, ID пунктов самовывоза берутся из админки.
		$ret = array(
		);
		
		$deliveries = $this->getDeliveries();
		if ($deliveries && is_array($deliveries)) {
			foreach ($deliveries as $delivery) {
				$price = isset($delivery['price']) ? $this->getDeliveryPrice($delivery['price'], $total) : 0;
				if ($price === false)
					continue;
				if ($delivery['region'] && !in_array($delivery['region'], $regions))
					continue;
				$ret[] = array(
					'id'=>$delivery['id'],
					'type'=>'DELIVERY',
					'serviceName'=>$delivery['name'],
					'price'=>intval($price),
					'dates'=>array('fromDate'=> date('d-m-Y', time() + intval($delivery['from'])*3600), 'toDate'=> date('d-m-Y', time() + intval($delivery['to'])*3600)),
				);
			}
		}
		
		$outlets = $this->getOutlets();
		if ($outlets && is_array($outlets)) {
			$outlet_ids = array();
			$prev_price = 'unset';
			foreach ($outlets as $outlet) {
				$price = isset($outlet['price']) ? $this->getDeliveryPrice($outlet['price'], $total) : 0;
				if ($price!==$prev_price) {
					if ($price === false)
						continue;
					$prev_price = $price;
					$ret[] = array(
						'id'=>'pickup',
						'type'=>'PICKUP',
						'serviceName'=>'Самовывоз',
						'price'=>intval($price),
						'dates'=>array('fromDate'=> date('d-m-Y', time() + intval($outlet['from'])*3600), 'toDate'=> date('d-m-Y', time() + intval($outlet['to'])*3600)),
						'outlets'=>array(array('id' => intval($outlet['id'])))
					);
				}
				else {
					$ret[count($ret)-1]['outlets'][] = array('id' => intval($outlet['id']));
				}
			}
		}
		return $ret;
	}
	
	public function __construct($registry) {
		parent::__construct($registry);
	
		if (!$this->config->get('yabuy_status')) {
			echo '<h1>Yandex CPA integration is off</h1>';
			exit;
		}
		
		$this->logfile = DIR_LOGS . 'yandexbuy.log';
		
		$this->token = $this->config->get('yabuy_token');
		
		//Доступные способы оплаты
		//Могут быть:
		//'SHOP_PREPAID' - предоплата
		//'CASH_ON_DELIVERY' - оплата наличностью при получении заказа
		//'CARD_ON_DELIVERY' - оплата карточкой при получении заказа
		$this->paymentMethods = explode(',', $this->config->get('yabuy_payments'));

		$this->PAYMENT_TYPES = array('PREPAID'=>'предоплата', 'POSTPAID'=>'постоплата');
		$this->PAYMENT_METHODS = array('SHOP_PREPAID'=>'предоплата напрямую магазину',
			'YANDEX'=>'предоплата через Яндекс',
			'CASH_ON_DELIVERY'=>'оплата наличностью при получении заказа',
			'CARD_ON_DELIVERY'=>'оплата карточкой при получении заказа');
		
		$this->OUTLET_MAPPING = array();
		$outlets = $this->config->get('yabuy_outlets');
		if (is_array($outlets))
		foreach ($outlets as $outlet) {
			if (!isset($outlet['id']) || !$outlet['id']) {
				continue;
			}
			$this->OUTLET_MAPPING[$outlet['id']] = $outlet;
		}
		
		$this->setConfig();
		
		if(!function_exists('apache_request_headers')) {
			$http_headers = array('Authorization'=>$this->request->get['auth-token']);
		}
		else {
			$http_headers = apache_request_headers();
		}
		
		if (!isset($http_headers['Authorization']) || $http_headers['Authorization'] != $this->token) {
			header('HTTP/1.0 403 Forbidden');
			echo '<h1>Wrong or empty Yandex Authorization token</h1>';
			exit;
		}
	}
	
	protected function getProductOptionData($product_id, $option_value_id) {
		$ret = array();

		if ($option_value_id > 0) {
			$query = $this->db->query("SELECT pov.*, o.type, od.name, ovd.name AS value FROM `" . DB_PREFIX . "product_option_value` pov
				LEFT JOIN `" . DB_PREFIX . "option` o ON (pov.option_id = o.option_id)
				LEFT JOIN `" . DB_PREFIX . "option_description` od ON (pov.option_id = od.option_id AND od.language_id = '" . (int)$this->config->get('config_language_id') . "')
				LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ovd.option_value_id = pov.option_value_id AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "')
				WHERE pov.option_value_id = '". (int)$option_value_id ."' AND pov.product_id = '" . (int)$product_id . "'");
			$ret = $query->row;
		}

		return $ret;
	}

	protected function log($text) {
		$flog = fopen($this->logfile, 'a');
		fwrite($flog, date('d.m.Y H:i:s').' '.$text."\n");
		fclose($flog);
	}
}

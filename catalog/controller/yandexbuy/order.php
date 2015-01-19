<?php
/**
* Yandex CPA "Покупка на Маркете" для OpenCart (ocStore) 1.5.5.x
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
require_once(dirname(__FILE__).'/base.php');

class ControllerYandexbuyOrder extends ControllerYandexbuyBase {

	public function accept() {
		$postdata = file_get_contents("php://input");
		if (!$postdata) {
			header('HTTP/1.0 404 Not Found');
			echo '<h1>No data posted</h1>';
			exit;
		}
		
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$data = json_decode($postdata, true);

		$subtotal = 0;
		$total = 0;
		$totals = array();
		$products = array();
		foreach ($data['order']['items'] as $item) {
			$subtotal+= $item['count']*$item['price'];
			$offer_id = $item['offerId'];
			$option_value_id = 0;
			$option2_value_id = 0;
			if ($offer_id > 100000000000000) {
				$offer_id = (int)floor($offer_id/100000000000000);
				$option_value_id = (int)floor(($item['offerId'] % 100000000000000) / 10000000);
				$option2_value_id = $item['offerId'] % 10000000;
			}
			elseif ($offer_id > 10000000) {
				$offer_id = (int)floor($offer_id/10000000);
				$option_value_id = $item['offerId'] % 10000000;
			}
			$product_info = $this->model_catalog_product->getProduct($offer_id);
			$option = $this->getProductOptionData($offer_id, $option_value_id);
			$product_data = array(
				'product_id'=>$offer_id,
				'name'=>$item['offerName'],
				'model'=>$product_info['model'],
				'quantity'=>$item['count'],
				'price'=>$item['price'],
				'total'=>$item['count']*$item['price'],
				'tax'=>0,
				'reward'=>0,
				'option'=>(count($option) > 0 ? array($option) : array()),
				'download'=>array()
			);
			if ($option2_value_id) {
				$option2 = $this->getProductOptionData($offer_id, $option2_value_id);
				$product_data['option'][] = $option2;
			}
			$products[] = $product_data;
		}
		$total = $subtotal;
		$totals[] = array('code'=>'sub_total', 'title'=>'Сумма', 'text'=>$this->currency->format($subtotal), 'value'=>$subtotal, 'sort_order'=>1);
		
		$currency = $data['order']['currency']; //Assume RUR
		$country_id = 176;
		$country = 'Российская федерация';
		$zone_id = '';
		$zone = '';
		$city = '';
		$postcode = '';
		$address_1 = '';
		$address_2 = '';

		if ($data['order']['delivery']['type'] != 'PICKUP') {
			$zone = (isset($data['order']['delivery']['region']) && isset($data['order']['delivery']['region']['parent']) ? $data['order']['delivery']['region']['parent']['name'] : '');
			$city = (isset($data['order']['delivery']['address']['city']) ?  $data['order']['delivery']['address']['city'] : '');
			$postcode = (isset($data['order']['delivery']['address']['postcode']) ?  $data['order']['delivery']['address']['postcode'] : '');
			$address_1 = (isset($data['order']['delivery']['address']['street']) ?  'ул. '.$data['order']['delivery']['address']['street'] : '')
				.(isset($data['order']['delivery']['address']['house']) ?  ', д. '.$data['order']['delivery']['address']['house'] : '')
				.(isset($data['order']['delivery']['address']['block']) ?  ', корп. '.$data['order']['delivery']['address']['block'] : '');
			$address_2 = (isset($data['order']['delivery']['address']['floor']) ?  'этаж '.$data['order']['delivery']['address']['floor'] : '');
		}
		elseif ($data['order']['delivery']['type'] == 'PICKUP') {
			$outlet_id = $data['order']['delivery']['outlet']['id'];
			if (!isset($this->OUTLET_MAPPING[$outlet_id])) {
				$this->OUTLET_MAPPING[$outlet_id] = array(
					'zone'=>'Зона точки продаж #'.$outlet_id,
					'city'=>'Город т.п. #'.$outlet_id,
					'postcode'=>'000000',
					'address_1'=>'Адрес точки продаж #'.$outlet_id,
					'address_2'=>'Адрес точки продаж #'.$outlet_id
				);
			}
			$zone = $this->OUTLET_MAPPING[$outlet_id]['zone'];
			$city = $this->OUTLET_MAPPING[$outlet_id]['city'];
			$postcode = $this->OUTLET_MAPPING[$outlet_id]['postcode'];
			$address_1 = $this->OUTLET_MAPPING[$outlet_id]['address_1'];
			$address_2 = $this->OUTLET_MAPPING[$outlet_id]['address_2'];
		}
		$totals[] = array('code'=>'shipping', 'title'=>'Доставка', 'text'=>$this->currency->format($data['order']['delivery']['price']), 'value'=>$data['order']['delivery']['price'], 'sort_order'=>2);		
		$total+= $data['order']['delivery']['price'];
		
		$totals[] = array('code'=>'total', 'title'=>'Итого', 'text'=>$this->currency->format($total), 'value'=>$this->currency->format($total), 'sort_order'=>3);

		if (isset($data['order']['paymentType']) && isset($data['order']['paymentMethod'])) {
			$payment_method_text = isset($this->PAYMENT_TYPES[$data['order']['paymentType']]) ? $this->PAYMENT_TYPES[$data['order']['paymentType']] : $data['order']['paymentType'];
			$payment_method_text.= $data['order']['paymentMethod'] && isset($this->PAYMENT_METHODS[$data['order']['paymentMethod']]) ? ' ('.$this->PAYMENT_METHODS[$data['order']['paymentMethod']].')' : '';
		}
		else {
			$payment_method_text = 'Способ оплаты не выбран';
		}

		$order = array(
			'invoice_prefix'=>$this->config->get('config_invoice_prefix'),
			'store_id'=>$this->config->get('config_store_id'),
			'store_name'=>$this->config->get('config_name'),
			'store_url'=>($this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER),
			'customer_id'=>0,
			'customer_group_id'=>0, //!!!
			'firstname'=>'Яндекс',
			'lastname'=>'Маркет',
			'email'=>'trash@yandex.ru',
			'telephone'=>'',
			'fax'=>'',
			'payment_firstname'=>'Яндекс',
			'payment_lastname'=>'Маркет',
			'payment_company'=>'Yandex',
			'payment_company_id'=>'',
			'payment_tax_id'=>'',
			'payment_address_1'=>$address_1,
			'payment_address_2'=>$address_2,
			'payment_city'=>$city,
			'payment_postcode'=>$postcode,
			'payment_country'=>$country,
			'payment_country_id'=>$country_id,
			'payment_zone'=>$zone,
			'payment_zone_id'=>$zone_id,
			'payment_address_format'=>'',
			'payment_method'=>$payment_method_text,
			'payment_code'=>(isset($data['order']['paymentType']) && isset($data['order']['paymentMethod'])) ? $data['order']['paymentType'].'.'.$data['order']['paymentMethod'] : '',
			
			'shipping_firstname'=>'Яндекс',
			'shipping_lastname'=>'Маркет',
			'shipping_company'=>'Yandex',
			'shipping_address_1'=>$address_1,
			'shipping_address_2'=>$address_2,
			'shipping_city'=>$city,
			'shipping_postcode'=>$postcode,
			'shipping_country'=>$country,
			'shipping_country_id'=>$country_id,
			'shipping_zone'=>$zone,
			'shipping_zone_id'=>$zone_id,
			'shipping_address_format'=>'',
			'shipping_method'=>$data['order']['delivery']['serviceName'],
			'shipping_code'=>$data['order']['delivery']['type'],
			'comment'=>(isset($data['order']['delivery']['dates']) ? 'Доставка ' 
					.(isset($data['order']['delivery']['dates']['fromDate']) ? ' c '.$data['order']['delivery']['dates']['fromDate'] : '')
					.(isset($data['order']['delivery']['dates']['toDate']) ? ' до '.$data['order']['delivery']['dates']['toDate'] : '')
				: ''),
			'reward'=>0,
			'total'=>$total,
			'affiliate_id'=>0,
			'commission'=>0,
			'language_id'=>$this->config->get('config_language_id'), //!!!
			'currency_id'=>$this->currency->getId(), //!!!
			'currency_code'=>$this->currency->getCode(), //!!!
			'currency_value'=>$this->currency->getValue($this->currency->getCode()), //!!!
			'ip'=>$this->request->server['REMOTE_ADDR'],
			'forwarded_ip'=>(isset($this->request->server['HTTP_X_FORWARDED_FOR']) ? $this->request->server['HTTP_X_FORWARDED_FOR'] : $this->request->server['REMOTE_ADDR']),
			'user_agent'=>'Yandex Robot',
			'accept_language'=>(isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) ? $this->request->server['HTTP_ACCEPT_LANGUAGE'] : ''),
			
			'products'=>$products,
			'totals'=>$totals,
			
			'vouchers'=>array()
		);
		$order_id = $this->model_checkout_order->create($order);
		
		if ($order_id) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET yaorder_id='".intval($data['order']['id'])."' WHERE order_id='".$order_id."'");
			$ret =  array('order'=>array('id'=>$order_id.'', 'accepted'=>true));
		}
		else {
			$ret =  array('order'=>array('accepted'=>false));
		}
		
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($ret);
	}
	
	public function status() {
		$postdata = file_get_contents("php://input");
		if (!$postdata) {
			header('HTTP/1.0 404 Not Found');
			echo '<h1>No data posted</h1>';
			exit;
		}
		
		$this->load->model('checkout/order');
		$data = json_decode($postdata, true);
		
		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE yaorder_id='".intval($data['order']['id'])."'");
		$order_id = (isset($query->row['order_id']) ? $query->row['order_id'] : 0);
		
		if ($order_id) {
			if ($data['order']['status'] == 'PROCESSING') {
				$upd_sql = '';
				if ($data['order']['delivery']['type'] != 'PICKUP') {
					$address_1 = (isset($data['order']['delivery']['address']['street']) ?  'ул. '.$data['order']['delivery']['address']['street'] : '')
						.(isset($data['order']['delivery']['address']['house']) ?  ', д. '.$data['order']['delivery']['address']['house'] : '')
						.(isset($data['order']['delivery']['address']['block']) ?  ' корп. '.$data['order']['delivery']['address']['block'] : '')
						.(isset($data['order']['delivery']['address']['apartment']) ?  ', кв. '.$data['order']['delivery']['address']['apartment'] : '');
						
					$address_2 = (isset($data['order']['delivery']['address']['floor']) ?  'этаж '.$data['order']['delivery']['address']['floor'] : '')
						.(isset($data['order']['delivery']['address']['entrance']) ?  ' подъезд '.$data['order']['delivery']['address']['entrance'] : '')
						.(isset($data['order']['delivery']['address']['entryphone']) ?  ' код домофона: '.$data['order']['delivery']['address']['entryphone'] : '');
						
					$upd_sql = ", shipping_address_1='$address_1', shipping_address_2='$address_2'";
				}
				if (isset($data['order']['delivery']['address']['recipient'])) {
					$arr = explode(' ', $data['order']['delivery']['address']['recipient']);
					$shipping_firstname = isset($arr[1]) ? $arr[1] : '';
					$shipping_lastname = isset($arr[0]) ? $arr[0] : '';
				}
				else {
					$shipping_firstname = $data['order']['buyer']['firstName'];
					$shipping_lastname = isset($data['order']['buyer']['lastName']) ? $data['order']['buyer']['lastName'] : '';
				}
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET
					firstname='".$this->db->escape($data['order']['buyer']['firstName'])."',
					lastname='".(isset($data['order']['buyer']['lastName']) ? $this->db->escape($data['order']['buyer']['lastName']) : '')."',
					email='".$this->db->escape($data['order']['buyer']['email'])."',
					telephone='".$this->db->escape($data['order']['buyer']['phone'])."',
					payment_firstname='".$this->db->escape($data['order']['buyer']['firstName'])."',
					payment_lastname='".$this->db->escape($data['order']['buyer']['lastName'])."',
					payment_company='',
					shipping_firstname='".$this->db->escape($shipping_firstname)."',
					shipping_lastname='".($shipping_lastname)."'
					$upd_sql
					WHERE order_id=".(int)$order_id);
				$this->model_checkout_order->confirm($order_id, $this->CONFIG['STATUS_MAPPING'][$data['order']['status']]);
			}
			else {
				$this->model_checkout_order->update($order_id, $this->CONFIG['STATUS_MAPPING'][$data['order']['status']],
					isset($data['order']['substatus']) ? $data['order']['substatus'] : '');
			}
		}
		
		header('Content-Type: application/json;charset=utf-8');
	}
	
}

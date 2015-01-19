<?php
class ControllerPaymentKupivkredit extends Controller {
	protected function index() {
	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		
		
		$this->data['action'] =  $this->url->link('payment/kupivkredit/success');
		
		$this->load->language('payment/kupivkredit');
		if($this->config->get('kupivkredit_server'))	$this->data['text_server'] = $this->language->get('text_work');
		else	$this->data['text_server'] = $this->language->get('text_test');
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		
		$items = array();
		//print_r($order_info);exit;
			foreach ($this->cart->getProducts() as $product) {
				$option_data = '';
				
				
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$option_data .= " ".$option['name'].": ".$option['option_value'];
					}
				}
				
				if($option_data) $option_data = " (".$option_data." )";
	 
				$items[] = array(
					'title' => $product['name'].$option_data,
					'category' => $this->getCategoryByProductId($product['product_id']),
					'qty' => $product['quantity'],
					'price' => round($this->currency->convert($product['price'], $this->currency->getCode(), 'RUB'), 2)
				/*
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'tax'        => $this->tax->getTax($product['total'], $product['tax_class_id']),
					'price'      => $this->currency->format($product['price']),
					'total'      => $this->currency->format($product['total']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				*/
				); 
			}
		
		
		if ($this->customer->isLogged()) {	
			$firstname=str_replace(",", "", $this->customer->getFirstName());
			$middlename='';
			$names=explode(" ", $firstname);
			if(isset($names[0]))$firstname=$names[0];
			if(isset($names[1]))$middlename=$names[1];
			$details =   array (
				'firstname' => $firstname,
				'lastname' => $this->customer->getLastName(),
				'middlename' => $middlename,
				'email' => $this->customer->getEmail(),
				'cellphone' => $this->customer->getTelephone(),
			);
		}else{
			$firstname=str_replace(",", "", $this->session->data['guest']['firstname']);
			$middlename='';
			$names=explode(" ", $firstname);
			if(isset($names[0]))$firstname=$names[0];
			if(isset($names[1]))$middlename=$names[1];
			$details =   array (
				'firstname' => $firstname,
				'lastname' => $this->session->data['guest']['lastname'],
				'middlename' => $middlename,
				'email' => $this->session->data['guest']['email'],
				'cellphone' => $this->session->data['guest']['telephone'],
			);
		}
		
		$order = array (
		  'items' => $items,
		  'details' => $details,
		  'partnerId' => $this->config->get('kupivkredit_merch_z'),
		  'partnerName' => $this->config->get('config_name'),
		  'partnerOrderId' => $this->session->data['order_id'],
		  'deliveryType' => ''
		);
		$this->data['order'] = $order;
		$this->data['total'] = (int)$order_info['total'];
		$this->data['base64'] = base64_encode(json_encode($order));
		$this->data['sig'] = $this->signMessage($this->data['base64'], $this->config->get('kupivkredit_secret_key'));
		
		//$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		/*
		$rub_code = "RUB";
		$rub_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $rub_code);
		$this->data['amount'] = $this->currency->format($rub_order_total, $rub_code, $order_info['currency_value'], FALSE);
		
		$this->data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		*/
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/kupivkredit.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/kupivkredit.tpl';
		} else {
			$this->template = 'default/template/payment/kupivkredit.tpl';
		}
		
		$this->render();
	}
	
	public function fail() {
	
		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
		
		return TRUE;
	}
	
	public function success() {
		
		//$LMI_PAYMENT_NO = (int)$this->request->post['LMI_PAYMENT_NO'];
		
		$this->load->model('checkout/order');
		
		//$this->model_checkout_order->confirm($LMI_PAYMENT_NO, $this->config->get('kupivkredit_order_status_id'), 'Купивкредит');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('kupivkredit_order_status_id'), 'Купивкредит');
		
		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/success');
		
		return TRUE;
	}
	
	private function getCategoryByProductId($product_id) {
		$query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "product_to_category p LEFT JOIN " . DB_PREFIX . "category c ON (p.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE p.product_id = '".(int)$product_id."' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		return $query->row['name'];
	}
	
	private function signMessage($message, $salt, $iterationCount = 1102) {
	  $message = $message.$salt;
	  $result = md5($message).sha1($message);
	  for($i = 0; $i < $iterationCount; $i++)
		$result = md5($result);
		return $result;
	}


}
?>
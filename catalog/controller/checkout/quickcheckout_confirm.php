<?php
class ControllerCheckoutQuickcheckoutConfirm extends Controller {
	public function write() {
		$this->index(TRUE);
	}

	public function index( $enable_write_db = FALSE ) {

		// $this->session->data['payment_method']['code'] = 'quickcheckout_cod';	// cash on delivery

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$json['redirect'] = $this->url->link('checkout/cart');
    	}

		//$this->load->model('account/address');

		//if ($this->customer->isLogged()) {
		//	$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		//} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['shipping'];
		//}

		if (!isset($payment_address)) {
			$json['redirect'] = $this->url->link('checkout/quickcheckout', '', 'SSL');
		}

		if (!isset($this->session->data['payment_method'])) {
	  		//$json['redirect'] = $this->url->link('checkout/quickcheckout', '', 'SSL');
			$this->session->data['payment_method']['code'] = 'quickcheckout_cod';	// cash on delivery
    	}

    	if ($this->cart->hasShipping()) {
			//$this->load->model('account/address');

			//if ($this->customer->isLogged()) {
			//	$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			//} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			//}

			if (!isset($this->session->data['shipping_method'])) {
				$this->session->data['shipping_method']['code'] = 'flat.flat';
				$this->session->data['shipping_method']['title'] = '';
				$this->session->data['shipping_method']['cost'] = 0;
				$this->session->data['shipping_method']['tax_class_id'] = 0;
    		}
		} elseif($enable_write_db) {
			//unset($this->session->data['guest']['shipping']);
			//unset($this->session->data['shipping_address_id']);
			//unset($this->session->data['shipping_method']);
			//unset($this->session->data['shipping_methods']);
		}

		$json = array();

		if (!$json) {
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

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

			$sort_order = array();

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);

			$this->language->load('checkout/quickcheckout');

			$data = array();

			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');

			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');
			} else {
				$data['store_url'] = HTTP_SERVER;
			}

			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();

				$this->load->model('account/address');

				//$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$this->load->model('account/customer');

				$customer_id_email = $this->model_account_customer->getCustomerIdByEmail($this->session->data['guest']['email']);			
				if ($customer_id_email) {				
					$data['customer_id'] = $customer_id_email['customer_id'];
				}else{
				$customer_id_next = $this->model_account_customer->getCustomerNextId();
					$data['customer_id'] = $customer_id_next['max'];
				}
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];
				$data['fax'] = $this->session->data['guest']['fax'];
			} else {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
				$data['firstname'] = '~~~';
				$data['lastname'] = '~~~';
				$data['email'] = '~~~';
				$data['telephone'] = '~~~';
				$data['fax'] = '~~~';
			}
			$payment_address = $this->session->data['guest']['shipping'];

			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];
			$data['payment_company'] = $payment_address['company'];
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];

			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$data['payment_method'] = '';
			}

			if ($this->cart->hasShipping()) {
				//if ($this->customer->isLogged()) {
				//	$this->load->model('account/address');

				//	$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				//} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				//}

				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];
				$data['shipping_company'] = $shipping_address['company'];
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];

				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$data['shipping_method'] = '';
				}
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';
				$data['shipping_company'] = '';
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
			}

			//if ($this->cart->hasShipping()) {
			//	$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
			//} else {
			//	$this->tax->setZone($payment_address['country_id'], $payment_address['zone_id']);
			//}

			$product_data = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['option_value'],
							'type'                    => $option['type']
						);
					} else {
						$this->load->library('encryption');

						$encryption = new Encryption($this->config->get('config_encryption'));

						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $encryption->decrypt($option['option_value']),
							'type'                    => $option['type']
						);
					}
				}

				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => 0 //$this->tax->getRate($product['tax_class_id'])
				);
			}

			// Gift Voucher
			if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$product_data[] = array(
						'product_id' => 0,
						'name'       => $voucher['description'],
						'model'      => '',
						'option'     => array(),
						'download'   => array(),
						'quantity'   => 1,
						'subtract'   => false,
						'price'      => $voucher['amount'],
						'total'      => $voucher['amount'],
						'tax'        => 0
					);
				}
			}

			$data['products'] = $product_data;
			$data['totals'] = $total_data;
			$data['comment'] = isset($this->session->data['comment']) ? $this->session->data['comment'] : '';
			$data['total'] = $total;
			$data['reward'] = $this->cart->getTotalRewardPoints();

			if ($this->config->get('config_checkout_id')) {
				$this->load->model('catalog/information');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
				if ($information_info) {
					$this->data['text_agree'] = sprintf(
						$this->language->get('text_agree'),
						$this->url->link('information/information/info', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'),
						$information_info['title'],
						$information_info['title']);
				} else {
					$this->data['text_agree'] = '';
				}
			} else {
				$this->data['text_agree'] = '';
			}

			if (isset($this->session->data['agree'])) {
				$this->data['agree'] = $this->session->data['agree'];
			} else {
				$this->data['agree'] = '';
			}


			if (isset($this->request->cookie['tracking'])) {
				$this->load->model('affiliate/affiliate');

				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$data['commission'] = ($total / 100) * $affiliate_info['commission'];
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}

			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			if( $enable_write_db )
			{
				$this->load->model('checkout/order');
				$this->session->data['order_id'] = $this->model_checkout_order->create($data);
			}

			// Gift Voucher
			if (isset($this->session->data['vouchers']) && is_array($this->session->data['vouchers'])) {
				$this->load->model('checkout/voucher');
				$this->load->model('checkout/order');
				$this->session->data['order_id'] = $this->model_checkout_order->create($data);

				foreach ($this->session->data['vouchers'] as $voucher) {
					$this->model_checkout_voucher->addVoucher($this->session->data['order_id'], $voucher);
				}
			}

			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			
			$this->data['button_update'] = $this->language->get('button_update');
			$this->data['button_remove'] = $this->language->get('button_remove');

			$this->data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($option['option_value']) > 150 ? utf8_substr($option['option_value'], 0, 150) . '...' : $option['option_value'])
						);
					} else {
						$this->load->library('encryption');

						$encryption = new Encryption($this->config->get('config_encryption'));

						$file = utf8_substr($encryption->decrypt($option['option_value']), 0, utf8_strrpos($encryption->decrypt($option['option_value']), '.'));

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($file) > 150 ? utf8_substr($file, 0, 150) . '...' : $file)
						);
					}
				}

				$this->data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'minimum'  => $product['minimum'],
					'quantity_step'  => 1,
					'maximum'  => 100,
					'subtract'   => $product['subtract'],
					'tax'        => 0, // $this->tax->getRate($product['tax_class_id']),
					'price'      => $this->currency->format($product['price']),
					'total'      => $this->currency->format($product['total']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'remove'   => $this->url->link('checkout/cart', 'remove=' . $product['product_id'])
				);
			}

			// Gift Voucher
			$this->data['vouchers'] = array();

			if (isset($this->session->data['vouchers']) && $this->session->data['vouchers']) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $product_id) 
					);
				}
			}

			$this->data['totals'] = $total_data;

			$this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/quickcheckout_confirm.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/quickcheckout_confirm.tpl';
			} else {
				$this->template = 'default/template/checkout/quickcheckout_confirm.tpl';
			}

			$json['output'] = $this->render();
		}

		$this->response->setOutput(json_encode($json));
  	}
}
?>

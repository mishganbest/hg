<?php
class ControllerCheckoutQuickcheckoutAddress extends Controller {

	public function shipping() {
		$this->language->load('checkout/quickcheckout');
		$this->load->model('account/address');
		$json = array();

		/*
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/quickcheckout', '', 'SSL');
		}

		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/quickcheckout', '', 'SSL');
		}
		*/

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				//$l = utf8_strlen($this->request->post['firstname']);
				//if (($l < 1) || ($l > 32)) {
				//	$json['error']['firstname'] = $this->language->get('error_firstname');
				//}

				$l = utf8_strlen($this->request->post['email']);
				if (($l < 1) || ($l > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
					$json['error']['email'] = $this->language->get('error_email');
				}

				$l = utf8_strlen($this->request->post['telephone']);
				if (($l < 1) || ($l > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}

				if ((utf8_strlen($this->request->post['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}

				if ( (utf8_strlen($this->request->post['address_2']) > 128)) {
					$json['error']['address_2'] = $this->language->get('error_address_2');
				}

				if ($this->config->get('config_checkout_id')) {
					$this->load->model('catalog/information');
					$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
					if ($information_info && !isset($this->request->post['agree'])) {
						$json['error']['agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
					}
				}

			}
			if (!$json) {
				$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['email']     = $this->request->post['email'];
				$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
				$this->session->data['guest']['city'] = $this->request->post['city'];
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);

				$this->session->data['guest']['shipping']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['shipping']['email']     = $this->request->post['email'];
				$this->session->data['guest']['shipping']['telephone'] = $this->request->post['telephone'];
				$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
				$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
			}
		} else {
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_email']     = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');

			if (isset($this->session->data['guest']['firstname']))
				$this->data['firstname'] = $this->session->data['guest']['firstname'];
			else
				$this->data['firstname'] = '';

			if (isset($this->session->data['guest']['email']))
				$this->data['email'] = $this->session->data['guest']['email'];
			else
				$this->data['email'] = '';

			if (isset($this->session->data['guest']['telephone']))
				$this->data['telephone'] = $this->session->data['guest']['telephone'];
			else
				$this->data['telephone'] = '';
				
			if (isset($this->session->data['guest']['city']))
				$this->data['city'] = $this->session->data['guest']['city'];
			else
				$this->data['city'] = '';

			if (isset($this->session->data['guest']['shipping']['address_1']))
				$this->data['address_1'] = $this->session->data['guest']['shipping']['address_1'];
			else
				$this->data['address_1'] = '';

			if (isset($this->session->data['guest']['shipping']['address_2']))
				$this->data['address_2'] = $this->session->data['guest']['shipping']['address_2'];
			else
				$this->data['address_2'] = '';


			$this->data['type'] = 'shipping';

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/quickcheckout_address.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/quickcheckout_address.tpl';
			} else {
				$this->template = 'default/template/checkout/quickcheckout_address.tpl';
			}

			$json['output'] = $this->render();
		}

		$this->response->setOutput(json_encode($json));
  	}

}
?>

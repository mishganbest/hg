<?php
class ControllerCheckoutQuickCheckout extends Controller {
	public function index() {
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->url->link('checkout/cart'));
    	}
    	
    		$this->load->model('module/geo');
		
		$city = $this->model_module_geo->smarty_function_get_city();
		
		

		$this->language->load('checkout/quickcheckout');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_email']     = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		//$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['confirm_continue'] = $this->url->link('checkout/success');


		// Set defaults
		if ($this->customer->isLogged()) {
			$this->session->data['guest'] = array();
			$this->session->data['guest']['firstname'] = $this->customer->getFirstName();
			$this->session->data['guest']['lastname']  = $this->customer->getLastName();
			$this->session->data['guest']['email']     = $this->customer->getEmail();
			$this->session->data['guest']['telephone'] = $this->customer->getTelephone();
			$this->session->data['guest']['fax']       = $this->customer->getFax();

			$this->session->data['customer_id']       = $this->customer->getId();
			$this->session->data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$this->session->data['payment_address_id'] = $this->customer->getAddressId();
			
		} elseif( isset($this->session->data['vouchers']) && !empty($this->session->data['vouchers']) ) {
			$vkeys = array_keys($this->session->data['vouchers']);
			$vinfo = $this->session->data['vouchers'][$vkeys[0]];
			$this->session->data['guest'] = array();
			$this->session->data['guest']['firstname'] = $vinfo['from_name'];
			$this->session->data['guest']['lastname']  = '   ';
			$this->session->data['guest']['email']     = $vinfo['from_email'];
			$this->session->data['guest']['telephone'] = '';
			$this->session->data['guest']['fax']       = '   ';
		} else {
			$this->session->data['guest'] = array();
			$this->session->data['guest']['firstname'] = '';
			$this->session->data['guest']['lastname']  = '   ';
			$this->session->data['guest']['email']     = '';
			$this->session->data['guest']['telephone'] = '';
			$this->session->data['guest']['fax']       = '   ';
		}

		
		if ($this->customer->isLogged())
		{
			$this->load->model('account/address');
			$shipping_address = $this->model_account_address->getAddress($this->customer->getAddressId());
		} else {
			$shipping_address['firstname']  = '';
			$shipping_address['lastname']   = '   ';
			$shipping_address['company']    = '';
			$shipping_address['address_1']  = '';
			$shipping_address['address_2']  = '';
			$shipping_address['postcode']   = '';
			$shipping_address['city']       = '';
			$shipping_address['country_id'] = '';
			$shipping_address['zone_id']    = '';
			$shipping_address['country']    = '';
			$shipping_address['iso_code_2'] = '';
			$shipping_address['iso_code_3'] = '';
			$shipping_address['address_format'] = '';
			$shipping_address['zone']       = '';
			$shipping_address['zone_code']  = '';
		}

		$this->session->data['guest']['shipping'] = $shipping_address;
		//$this->session->data['shipping_method']['code'] = 'flat.flat';
		$this->session->data['payment_method']['code'] = 'quickcheckout_cod';	// cash on delivery

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

		if (isset($this->session->data['guest']['shipping']['address_1']))
			$this->data['address_1'] = $this->session->data['guest']['shipping']['address_1'];
		else
			$this->data['address_1'] = '';

		if (isset($this->session->data['guest']['shipping']['address_2']))
			$this->data['address_2'] = $this->session->data['guest']['shipping']['address_2'];
		else
			$this->data['address_2'] = '';
			
		if (isset($this->session->data['guest']['shipping']['city']))
			$this->data['city'] = $city;
		else
			$this->data['city'] = $city;
			


		// Minimum quantity validation
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($this->session->data['cart'] as $key => $quantity) {
				$product_2 = explode(':', $key);

				if ($product_2[0] == $product['product_id']) {
					$product_total += $quantity;
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->session->data['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

				$this->redirect($this->url->link('checkout/cart'));
			}
		}

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      /*	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_cart'),
			'href'      => $this->url->link('checkout/cart'),
        	'separator' => $this->language->get('text_separator')
      	); */

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('checkout/quickcheckout', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

	    $this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_checkout_option'] = $this->language->get('text_checkout_option');
		$this->data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
		$this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
		$this->data['text_modify'] = $this->language->get('text_modify');

		$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = $this->cart->hasShipping();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/quickcheckout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/quickcheckout.tpl';
		} else {
			$this->template = 'default/template/checkout/quickcheckout.tpl';
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

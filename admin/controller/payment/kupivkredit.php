<?php
class ControllerPaymentKupivkredit extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('payment/kupivkredit');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('kupivkredit', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_merch_z'] = $this->language->get('entry_merch_z');
		$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$this->data['entry_result_url'] = $this->language->get('entry_result_url');
		$this->data['entry_success_url'] = $this->language->get('entry_success_url');
		$this->data['entry_fail_url'] = $this->language->get('entry_fail_url');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_donate_me'] = $this->language->get('entry_donate_me');
		
		$this->data['entry_server'] = $this->language->get('entry_server');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_work'] = $this->language->get('entry_work');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['merch_z'])) {
			$this->data['error_merch_z'] = $this->error['merch_z'];
		} else {
			$this->data['error_merch_z'] = '';
		}
		
		if (isset($this->error['secret_key'])) {
			$this->data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$this->data['error_secret_key'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/kupivkredit', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/kupivkredit', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		
		// Номер магазина
		if (isset($this->request->post['kupivkredit_merch_z'])) {
			$this->data['kupivkredit_merch_z'] = $this->request->post['kupivkredit_merch_z'];
		} else {
			$this->data['kupivkredit_merch_z'] = $this->config->get('kupivkredit_merch_z');
		}
		
		// zp_merhant_key
		if (isset($this->request->post['kupivkredit_secret_key'])) {
			$this->data['kupivkredit_secret_key'] = $this->request->post['kupivkredit_secret_key'];
		} else {
			$this->data['kupivkredit_secret_key'] = $this->config->get('kupivkredit_secret_key');
		}
		
		
		// URL
		$this->data['kupivkredit_result_url'] 		= HTTP_CATALOG . 'index.php?route=payment/kupivkredit/callback';
		$this->data['kupivkredit_success_url'] 	= HTTP_CATALOG . 'index.php?route=payment/kupivkredit/success';
		$this->data['kupivkredit_fail_url'] 		= HTTP_CATALOG . 'index.php?route=payment/kupivkredit/fail';
		
		
		if (isset($this->request->post['kupivkredit_order_status_id'])) {
			$this->data['kupivkredit_order_status_id'] = $this->request->post['kupivkredit_order_status_id'];
		} else {
			$this->data['kupivkredit_order_status_id'] = $this->config->get('kupivkredit_order_status_id'); 
		}
		
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['kupivkredit_geo_zone_id'])) {
			$this->data['kupivkredit_geo_zone_id'] = $this->request->post['kupivkredit_geo_zone_id'];
		} else {
			$this->data['kupivkredit_geo_zone_id'] = $this->config->get('kupivkredit_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['kupivkredit_status'])) {
			$this->data['kupivkredit_status'] = $this->request->post['kupivkredit_status'];
		} else {
			$this->data['kupivkredit_status'] = $this->config->get('kupivkredit_status');
		}
		
		if (isset($this->request->post['kupivkredit_server'])) {
			$this->data['kupivkredit_server'] = $this->request->post['kupivkredit_server'];
		} else {
			$this->data['kupivkredit_server'] = $this->config->get('kupivkredit_server');
		}
		
		if (isset($this->request->post['kupivkredit_sort_order'])) {
			$this->data['kupivkredit_sort_order'] = $this->request->post['kupivkredit_sort_order'];
		} else {
			$this->data['kupivkredit_sort_order'] = $this->config->get('kupivkredit_sort_order');
		}
		
		$this->template = 'payment/kupivkredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/kupivkredit')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		// TODO проверку на валидность номера!
		if (!$this->request->post['kupivkredit_merch_z']) {
			$this->error['merch_z'] = $this->language->get('error_merch_z');
		}
		
		if (!$this->request->post['kupivkredit_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
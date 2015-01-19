<?php
class ControllerShippingGeocostPickup extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/geocost_pickup');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('geocost_pickup', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_rate'] = $this->language->get('column_rate');
		$this->data['column_status'] = $this->language->get('column_status');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_geocost_pickup_class'] = $this->language->get('entry_geocost_pickup_class');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('shipping/geocost_pickup', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/geocost_pickup', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'); 
		
		$this->load->model('localisation/delivery_point');
		
		$sort = 'c.name';
		$order = 'ASC';
		
		$data = array(
			'sort'  => $sort,
			'order' => $order
		);
		
		$geo_zones = $this->model_localisation_delivery_point->getDeliveryPoints($data);

		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate'])) {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate'] = $this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate'];
			} else {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate'] = $this->config->get('geocost_pickup_' . $geo_zone['delivery_point_id'] . '_rate');
			}		
			
			if (isset($this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status'])) {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status'] = $this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status'];
			} else {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status'] = $this->config->get('geocost_pickup_' . $geo_zone['delivery_point_id'] . '_status');
			}		

			if (isset($this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode'])) {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode'] = $this->request->post['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode'];
			} else {
				$this->data['geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode'] = $this->config->get('geocost_pickup_' . $geo_zone['delivery_point_id'] . '_yacode');
			}		
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['geocost_pickup_tax_class_id'])) {
			$this->data['geocost_pickup_tax_class_id'] = $this->request->post['geocost_pickup_tax_class_id'];
		} else {
			$this->data['geocost_pickup_tax_class_id'] = $this->config->get('geocost_pickup_tax_class_id');
		}
		
		if (isset($this->request->post['geocost_pickup_status'])) {
			$this->data['geocost_pickup_status'] = $this->request->post['geocost_pickup_status'];
		} else {
			$this->data['geocost_pickup_status'] = $this->config->get('geocost_pickup_status');
		}
		
		if (isset($this->request->post['geocost_pickup_sort_order'])) {
			$this->data['geocost_pickup_sort_order'] = $this->request->post['geocost_pickup_sort_order'];
		} else {
			$this->data['geocost_pickup_sort_order'] = $this->config->get('geocost_pickup_sort_order');
		}	
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->template = 'shipping/geocost_pickup.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/geocost_pickup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
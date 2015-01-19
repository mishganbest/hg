<?php
class ControllerModuleCategoryShipping extends Controller {
    
    public function index() { 
    
		$this->load->language('module/categoryshipping');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				
			$this->model_setting_setting->editSetting('categoryshipping', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/categoryshipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
    
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['entry_showshippingtocustomer'] = $this->language->get('entry_showshippingtocustomer');
		$this->data['entry_showshippingtocustomer_help'] = $this->language->get('entry_showshippingtocustomer_help');
		$this->data['entry_replacement_shipping_help'] = $this->language->get('entry_replacement_shipping_help');
		$this->data['replacement_shipping_not_use'] = $this->language->get('replacement_shipping_not_use');
		$this->data['entry_noshipinng_error'] = $this->language->get('entry_noshipinng_error');
		$this->data['entry_noshipinng_error_help'] = $this->language->get('entry_noshipinng_error_help');
		$this->data['categoryshipping_development'] = $this->language->get('categoryshipping_development');
		$this->data['categoryshipping_contact'] = $this->language->get('categoryshipping_contact');
		
		$this->data['entry_replacement_shipping'] = $this->language->get('entry_replacement_shipping');
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['text_success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/categoryshipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['categoryshipping'])) {
			$this->data['modules'] = $this->request->post['categoryshipping'];
		} elseif ($this->config->get('categoryshipping')) { 
			$this->data['modules'] = $this->config->get('categoryshipping');
		}
		
		$this->load->model('module/categoryshipping');
		$this->data['shippings'] = $this->model_module_categoryshipping->get_shippings_with_names();
		
		$this->data['action'] = $this->url->link('module/categoryshipping', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/categoryshipping.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
    
    }
    
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/categoryshipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	
}
?>
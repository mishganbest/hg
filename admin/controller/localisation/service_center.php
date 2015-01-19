<?php 
class ControllerLocalisationServiceCenter extends Controller {
	private $error = array(); 
   
  	public function index() {
		$this->load->language('localisation/service_center');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/service_center');
		
    	$this->getList();
  	}
              
  	public function insert() {
		$this->load->language('localisation/service_center');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/service_center');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_localisation_service_center->addServiceCenter($this->request->post);
		  	
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
      		$this->redirect($this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->load->language('localisation/service_center');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/service_center');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_localisation_service_center->editServiceCenter($this->request->get['service_center_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('localisation/service_center');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/service_center');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $service_center_id) {
				$this->model_localisation_service_center->deleteServiceCenter($service_center_id);
			}
			      		
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
  	}
    
  	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/service_center/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/service_center/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['service_centers'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$service_center_total = $this->model_localisation_service_center->getTotalServiceCenters();
	
		$results = $this->model_localisation_service_center->getServiceCenters($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/service_center/update', 'token=' . $this->session->data['token'] . '&service_center_id=' . $result['service_center_id'] . $url, 'SSL')
			);
						
			$this->data['service_centers'][] = array(
				'service_center_id' => $result['service_center_id'],
				'city'  => $result['city'],
				'name'            => $result['name'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['service_center_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $service_center_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'localisation/service_center_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
  
  	private function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');
     	
     	$this->data['text_none'] = $this->language->get('text_none');

	$this->data['entry_city'] = $this->language->get('entry_city');
	$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_address'] = $this->language->get('entry_address');
    	$this->data['entry_time'] = $this->language->get('entry_time');
    	$this->data['entry_phone'] = $this->language->get('entry_phone');
    	$this->data['entry_urlserviceinfo'] = $this->language->get('entry_urlserviceinfo');
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	
    	$this->load->model('localisation/service_center');
		
		$this->data['service_centers'] = $this->model_localisation_service_center->getServiceCenters();
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['manufacturer'])) {
			$this->data['error_manufacturer'] = $this->error['manufacturer'];
		} else {
			$this->data['error_manufacturer'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['service_center_id'])) {
			$this->data['action'] = $this->url->link('localisation/service_center/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/service_center/update', 'token=' . $this->session->data['token'] . '&service_center_id=' . $this->request->get['service_center_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('localisation/service_center', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['service_center_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$service_center_info = $this->model_localisation_service_center->getServiceCenter($this->request->get['service_center_id']);
		}
		
		if (isset($this->request->post['city_id'])) {
			$this->data['city_id'] = $this->request->post['city_id'];
		} elseif (!empty($service_center_info)) {
			$this->data['city_id'] = $service_center_info['city_id'];
		} else {
			$this->data['city_id'] = '';
		}
		
		if (isset($this->request->post['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($service_center_info)) {
			$this->data['manufacturer_id'] = $service_center_info['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = '';
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($service_center_info)) {
			$this->data['name'] = $service_center_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['address'])) {
			$this->data['address'] = $this->request->post['address'];
		} elseif (!empty($service_center_info)) {
			$this->data['address'] = $service_center_info['address'];
		} else {
			$this->data['address'] = '';
		}
		
		if (isset($this->request->post['time'])) {
			$this->data['time'] = $this->request->post['time'];
		} elseif (!empty($service_center_info)) {
			$this->data['time'] = $service_center_info['time'];
		} else {
			$this->data['time'] = '';
		}
		
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} elseif (!empty($service_center_info)) {
			$this->data['phone'] = $service_center_info['phone'];
		} else {
			$this->data['phone'] = '';
		}
		
		if (isset($this->request->post['urlserviceinfo'])) {
			$this->data['urlserviceinfo'] = $this->request->post['urlserviceinfo'];
		} elseif (!empty($service_center_info)) {
			$this->data['urlserviceinfo'] = $service_center_info['urlserviceinfo'];
		} else {
			$this->data['urlserviceinfo'] = '';
		}
		
		$this->load->model('localisation/city');
		
		$this->data['cities'] = $this->model_localisation_city->getCities();
		
		$this->load->model('catalog/manufacturer');
		
    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->template = 'localisation/service_center_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/service_center')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ($this->request->post['city_id'] == '0') {
      		$this->error['city'] = $this->language->get('error_city');
    		}
    		
    		if ($this->request->post['manufacturer_id'] == '0') {
      		$this->error['manufacturer'] = $this->language->get('error_manufacturer');
    		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/service_center')) {
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
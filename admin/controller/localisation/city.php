<?php 
class ControllerLocalisationCity extends Controller {
	private $error = array(); 
   
  	public function index() {
		$this->load->language('localisation/city');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
		
    	$this->getList();
  	}
              
  	public function insert() {
		$this->load->language('localisation/city');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_localisation_city->addCity($this->request->post);
		  	
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
						
      		$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	
    	$this->getForm();
  	}

  	public function update() {
		$this->load->language('localisation/city');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	  		$this->model_localisation_city->editCity($this->request->get['city_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('localisation/city');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $city_id) {
				$this->model_localisation_city->deleteCity($city_id);
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
			
			$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href'      => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/city/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/city/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['cities'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$city_total = $this->model_localisation_city->getTotalCities();
	
		$results = $this->model_localisation_city->getCities($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/city/update', 'token=' . $this->session->data['token'] . '&city_id=' . $result['city_id'] . $url, 'SSL')
			);
						
			$this->data['cities'][] = array(
				'city_id' => $result['city_id'],
				'name'            => $result['name'] . (($result['city_id'] == $this->config->get('config_city_id')) ? $this->language->get('text_default') : null),
				'selected'        => isset($this->request->post['selected']) && in_array($result['city_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

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
		
		$this->data['sort_name'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $city_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'localisation/city_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
  
  	private function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_name_in'] = $this->language->get('entry_name_in');
    	$this->data['entry_name_to'] = $this->language->get('entry_name_to');
    	$this->data['entry_name_a'] = $this->language->get('entry_name_a');
    	
	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		
		if (isset($this->error['name_in'])) {
			$this->data['error_name_in'] = $this->error['name_in'];
		} else {
			$this->data['error_name_in'] = array();
		}
		
		if (isset($this->error['name_to'])) {
			$this->data['error_name_to'] = $this->error['name_to'];
		} else {
			$this->data['error_name_to'] = array();
		}
		
		if (isset($this->error['name_a'])) {
			$this->data['error_name_a'] = $this->error['name_a'];
		} else {
			$this->data['error_name_a'] = array();
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
			'href'      => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['city_id'])) {
			$this->data['action'] = $this->url->link('localisation/city/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/city/update', 'token=' . $this->session->data['token'] . '&city_id=' . $this->request->get['city_id'] . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['city_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$city_info = $this->model_localisation_city->getCity($this->request->get['city_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($city_info)) {
			$this->data['name'] = $city_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if (isset($this->request->post['name_in'])) {
			$this->data['name_in'] = $this->request->post['name_in'];
		} elseif (!empty($city_info)) {
			$this->data['name_in'] = $city_info['name_in'];
		} else {
			$this->data['name_in'] = '';
		}
		
		if (isset($this->request->post['name_to'])) {
			$this->data['name_to'] = $this->request->post['name_to'];
		} elseif (!empty($city_info)) {
			$this->data['name_to'] = $city_info['name_to'];
		} else {
			$this->data['name_to'] = '';
		}
		
		if (isset($this->request->post['name_a'])) {
			$this->data['name_a'] = $this->request->post['name_a'];
		} elseif (!empty($city_info)) {
			$this->data['name_a'] = $city_info['name_a'];
		} else {
			$this->data['name_a'] = '';
		}

		$this->template = 'localisation/city_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/city')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['name_in']) < 3) || (utf8_strlen($this->request->post['name_in']) > 32)) {
			$this->error['name_in'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['name_to']) < 3) || (utf8_strlen($this->request->post['name_to']) > 32)) {
			$this->error['name_to'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['name_a']) < 3) || (utf8_strlen($this->request->post['name_a']) > 32)) {
			$this->error['name_a'] = $this->language->get('error_name');
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/city')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('setting/store');
		$this->load->model('catalog/product');
		
		foreach ($this->request->post['selected'] as $city_id) {
			if ($this->config->get('config_city_id') == $city_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$service_centers_total = $this->model_catalog_product->getTotalServiceCentersByCityId($city_id);
		
			if ($service_centers_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_service_centers'), $service_centers_total);	
			}
			
			$delivery_points_total = $this->model_catalog_product->getTotalDeliveryPointsByCityId($city_id);
		
			if ($delivery_points_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delivery_points'), $delivery_points_total);	
			}			
			
	  	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}	  
}
?>
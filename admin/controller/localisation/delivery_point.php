<?php 
class ControllerLocalisationDeliveryPoint extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('localisation/delivery_point');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/delivery_point');
		
		$this->getList();
	}

	public function insert() {
		$this->load->language('localisation/delivery_point');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/delivery_point');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_delivery_point->addDeliveryPoint($this->request->post);
	
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
			
			$this->redirect($this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('localisation/delivery_point');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/delivery_point');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_delivery_point->editDeliveryPoint($this->request->get['delivery_point_id'], $this->request->post);			
			
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
			
			$this->redirect($this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/delivery_point');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/delivery_point');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $delivery_point_id) {
				$this->model_localisation_delivery_point->deleteDeliveryPoint($delivery_point_id);
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

			$this->redirect($this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.name';
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
			'href'      => $this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/delivery_point/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/delivery_point/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	
		$this->data['delivery_points'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$delivery_point_total = $this->model_localisation_delivery_point->getTotalDeliveryPoints();
			
		$results = $this->model_localisation_delivery_point->getDeliveryPoints($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/delivery_point/update', 'token=' . $this->session->data['token'] . '&delivery_point_id=' . $result['delivery_point_id'] . $url, 'SSL')
			);
					
			$this->data['delivery_points'][] = array(
				'delivery_point_id'  => $result['delivery_point_id'],
				'city'  => $result['city'],
				'address'     => utf8_truncate(strip_tags(html_entity_decode($result['address'], ENT_QUOTES, 'UTF-8')), 100, '&hellip;', true),
				'selected' => isset($this->request->post['selected']) && in_array($result['delivery_point_id'], $this->request->post['selected']),
				'action'   => $action			
			);
		}
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_address'] = $this->language->get('column_address');
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

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $delivery_point_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'localisation/delivery_point_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_time'] = $this->language->get('entry_time');
		$this->data['entry_phone'] = $this->language->get('entry_phone');
		$this->data['entry_delivery_period_to_warehouse'] = $this->language->get('entry_delivery_period_to_warehouse');
		$this->data['entry_price_to_warehouse'] = $this->language->get('entry_price_to_warehouse');
		$this->data['entry_delivery_period_to_door'] = $this->language->get('entry_delivery_period_to_door');
		$this->data['entry_price_to_door'] = $this->language->get('entry_price_to_door');
		$this->data['entry_photo_url'] = $this->language->get('entry_photo_url');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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
		
 		if (isset($this->error['address'])) {
			$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = '';
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
			'href'      => $this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!isset($this->request->get['delivery_point_id'])) {
			$this->data['action'] = $this->url->link('localisation/delivery_point/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/delivery_point/update', 'token=' . $this->session->data['token'] . '&delivery_point_id=' . $this->request->get['delivery_point_id'] . $url, 'SSL');
		}
		 
		$this->data['cancel'] = $this->url->link('localisation/delivery_point', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['delivery_point_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$delivery_point_info = $this->model_localisation_delivery_point->getDeliveryPoint($this->request->get['delivery_point_id']);
		}
		
		if (isset($this->request->post['address'])) {
			$this->data['address'] = $this->request->post['address'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['address'] = $delivery_point_info['address'];
		} else {
			$this->data['address'] = '';
		}

		if (isset($this->request->post['time'])) {
			$this->data['time'] = $this->request->post['time'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['time'] = $delivery_point_info['time'];
		} else {
			$this->data['time'] = '';
		}
		
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['phone'] = $delivery_point_info['phone'];
		} else {
			$this->data['phone'] = '';
		}
		
		if (isset($this->request->post['delivery_period_to_warehouse'])) {
			$this->data['delivery_period_to_warehouse'] = $this->request->post['delivery_period_to_warehouse'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['delivery_period_to_warehouse'] = $delivery_point_info['delivery_period_to_warehouse'];
		} else {
			$this->data['delivery_period_to_warehouse'] = '';
		}
		
		if (isset($this->request->post['price_to_warehouse'])) {
			$this->data['price_to_warehouse'] = $this->request->post['price_to_warehouse'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['price_to_warehouse'] = $delivery_point_info['price_to_warehouse'];
		} else {
			$this->data['price_to_warehouse'] = '';
		}
		
		if (isset($this->request->post['delivery_period_to_door'])) {
			$this->data['delivery_period_to_door'] = $this->request->post['delivery_period_to_door'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['delivery_period_to_door'] = $delivery_point_info['delivery_period_to_door'];
		} else {
			$this->data['delivery_period_to_door'] = '';
		}
		
		if (isset($this->request->post['price_to_door'])) {
			$this->data['price_to_door'] = $this->request->post['price_to_door'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['price_to_door'] = $delivery_point_info['price_to_door'];
		} else {
			$this->data['price_to_door'] = '';
		}
		
		if (isset($this->request->post['photo_url'])) {
			$this->data['photo_url'] = $this->request->post['photo_url'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['photo_url'] = $delivery_point_info['photo_url'];
		} else {
			$this->data['photo_url'] = '';
		}

		if (isset($this->request->post['city_id'])) {
			$this->data['city_id'] = $this->request->post['city_id'];
		} elseif (!empty($delivery_point_info)) {
			$this->data['city_id'] = $delivery_point_info['city_id'];
		} else {
			$this->data['city_id'] = '';
		}
		
		$this->load->model('localisation/city');
		
		$this->data['cities'] = $this->model_localisation_city->getCities();

		$this->template = 'localisation/delivery_point_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/delivery_point')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['address']) < 8) || (utf8_strlen($this->request->post['address']) > 255)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		
		if ($this->request->post['city_id'] == '0') {
      		$this->error['city'] = $this->language->get('error_city');
    		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/delivery_point')) {
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
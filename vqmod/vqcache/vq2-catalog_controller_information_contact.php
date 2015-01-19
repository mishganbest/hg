<?php 
class ControllerInformationContact extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('information/contact');

    	$this->document->setTitle($this->language->get('heading_title'));  
	 

			$this->load->model('setting/store');
		
		$this->load->model('module/geo');
		$city = $this->model_module_geo->smarty_function_get_city();
		
	$this->data['contact_description'] = '';
		
		$this->load->model('catalog/product');	
			
		$delivery_points = $this->model_catalog_product->getDeliveryPoints($city);
			
			$this->data['delivery_points'] = array(); 
			
			foreach ($delivery_points as $delivery_point) {
			
			$address = array();
			$address['address'] = $delivery_point['address'];
			$address['time'] = $delivery_point['time'];
			$address['phone'] = $delivery_point['phone'];
			$address['photo_url'] = $delivery_point['photo_url'];
			
			$addresses[] = $address;
			
			if (count($addresses)) { 
		          $i_addresses = array();
		          foreach ($addresses as $addre) {
		          if ($addre['address']) { 
		          	$e_address = $addre['address'];
		          	}else{
		          	$e_address = '';
		          }
		          if ($addre['time']) { 
		          	$e_time = 'Время работы: '.$addre['time'];
		          	}else{
		          	$e_time = '';
		          }
		          if ($addre['phone']) { 
		          	$e_phone = 'Тел: '.$addre['phone'];
		          	}else{
		          	$e_phone = '';
		          }
		          if ($addre['photo_url']) { 
		          	$e_photo_url = '<a class="fancybox" href="'.$addre['photo_url'].'">посмотреть на карте</a>';
		          	}else{
		          	$e_photo_url = '';
		          }
		            
		          $i_addresses[] = '<li>'.$e_address.'&nbsp;&nbsp;'.$e_photo_url.'<br />'.$e_time.'<br />'.$e_phone.'</li>';
		          }
		         
		     	  $i_delivery = implode($i_addresses);
		     	  $delivery = '<ul>'.$i_delivery.'</ul>';
		     	           
			  }
			  
			  if ($city == 'Новосибирск') {
       			  	$phone = $this->config->get('config_telephone');
   		  	  } else {
       			  	$phone = $this->config->get('config_telephone_2');
  			  }
						
			if (!$this->config->get('config_store_id')) {
			$contact_description = html_entity_decode($this->config->get('config_contact_description_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
		
		$find = array(
				'{city}',
				'{city_in}',
				'{city_to}',
				'{city_a}',
				'{phone}',
				'{delivery_points}',
				'{delivery_period_to_warehouse}',
				'{price_to_warehouse}',
				'{delivery_period_to_door}',
				'{price_to_door}'
			);
			

			$replace = array(
				'city' => $delivery_point['name'],
				'city_in' => $delivery_point['name_in'],
				'city_to' => $delivery_point['name_to'],
				'city_a' => $delivery_point['name_a'],
				'phone' => $phone,
				'delivery_points' => html_entity_decode($delivery, ENT_QUOTES, 'UTF-8'),
				'delivery_period_to_warehouse' => $delivery_point['delivery_period_to_warehouse'],
				'price_to_warehouse' => $delivery_point['price_to_warehouse'],
				'delivery_period_to_door' => $delivery_point['delivery_period_to_door'],
				'price_to_door' => $delivery_point['price_to_door']				  
			);
		
		
			$this->data['contact_description'] = str_replace($find, $replace, $contact_description);

			} 
	}
			
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
	  		$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
      		$mail->send();

	  		$this->redirect($this->url->link('information/contact/success'));
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');

			$this->data['text_contact_info'] = $this->language->get('text_contact_info');
			

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->link('information/contact');
		$this->data['store'] = $this->config->get('config_name');
    	$this->data['address'] = nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['fax'] = $this->config->get('config_fax');
    	
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contact.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/contact.tpl';
		} else {
			$this->template = 'default/template/information/contact.tpl';
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

  	public function success() {
		$this->language->load('information/contact');

		$this->document->setTitle($this->language->get('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
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

	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
  	private function validate() {
    	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}

    	if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}
}
?>

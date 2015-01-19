<?php 
// RegEx
define('EMAIL_PATTERN', '/^[^\@]+@.*\.[a-z]{2,6}$/i');
 
class Controllerproductisitestimonial extends Controller {
	private $error = array(); 
	    
  	public function index() {
  	
  	$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/testimonial.css');
  	
		$this->language->load('product/isitestimonial');
		$this->document->SetTitle( $this->language->get('heading_title'));
	   	$this->data['heading_title'] = $this->language->get('heading_title');
		//$this->data['ip'] = $this->request->server['REMOTE_ADDR'];

		$this->load->model('catalog/testimonial');
 
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->data['data']=array();
			$this->data['data']['name'] = strip_tags(html_entity_decode($this->request->post['name']));
			$this->data['data']['city'] = strip_tags(html_entity_decode($this->request->post['city']));
			$this->data['data']['phone'] = strip_tags(html_entity_decode($this->request->post['phone']));
			$this->data['data']['email'] = strip_tags(html_entity_decode($this->request->post['email']));
			
						
      	if (!empty($this->request->post['vk']) && !preg_match("~^(?:f|ht)tps?://~i", $this->request->post['vk'])) { 
      	 $vk_url = "http://" . $this->request->post['vk'];
      	} else {
      	 $vk_url = $this->request->post['vk'];
      	}
      	
      	if (!empty($this->request->post['odnoklass']) && !preg_match("~^(?:f|ht)tps?://~i", $this->request->post['odnoklass'])) { 
      	 $odnoklass_url = "http://" . $this->request->post['odnoklass'];
      	} else {
      	 $odnoklass_url = $this->request->post['odnoklass'];
      	}
				
			$this->data['data']['vk'] = strip_tags(html_entity_decode($vk_url));
			$this->data['data']['odnoklass'] = strip_tags(html_entity_decode($odnoklass_url));
			
			$this->data['data']['rating'] = $this->request->post['rating'];
			$this->data['data']['image'] = 'review/' . $this->request->post['image'];				
			
			$this->data['data']['title'] = strip_tags(html_entity_decode($this->request->post['title']));

			$this->data['data']['description'] = strip_tags(html_entity_decode($this->request->post['description']));

			if ($this->config->get('testimonial_admin_approved')=='')
			$this->model_catalog_testimonial->addTestimonial($this->data['data'], 1);
			else
			$this->model_catalog_testimonial->addTestimonial($this->data['data'], 0);

			$this->session->data['success'] = $this->language->get('text_add');
			
			$this->redirect($this->url->link('product/isitestimonial/success'));
			
		}
			
	
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
	        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
	        	'separator' => false
      	);
      	
      	$this->data['breadcrumbs'][] = array(
	        	'text'      => $this->language->get('testimonial'),
			'href'      => $this->url->link('product/testimonial'),
	        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
	        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/isitestimonial'),
	        	'separator' => $this->language->get('text_separator')
      	);			

	    	$this->data['entry_title'] = $this->language->get('entry_title');
	
	    	$this->data['entry_name'] = $this->language->get('entry_name');
	    	$this->data['entry_city'] = $this->language->get('entry_city');
	    	$this->data['entry_phone'] = $this->language->get('entry_phone');
	    	$this->data['entry_email'] = $this->language->get('entry_email');
	    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
	    	$this->data['entry_vk'] = $this->language->get('entry_vk');
	    	$this->data['entry_odnoklass'] = $this->language->get('entry_odnoklass');
	    	$this->data['entry_file'] = $this->language->get('entry_file');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');
		$this->data['text_note'] = $this->language->get('text_note');
		$this->data['text_conditions'] = $this->language->get('text_conditions');
		
		$this->data['button_upload'] = $this->language->get('button_upload');


		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		if (isset($this->error['title'])) {
    		$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
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
		
		if (isset($this->error['vk'])) {
    			$this->data['error_vk'] = $this->error['vk'];
		} else {
			$this->data['error_vk'] = '';
		}
		
		if (isset($this->error['odnoklass'])) {
    			$this->data['error_odnoklass'] = $this->error['odnoklass'];
		} else {
			$this->data['error_odnoklass'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    		$this->data['button_send'] = $this->language->get('button_send');
    
		$this->data['action'] = $this->url->link('product/isitestimonial');
		
    	
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = '';
		}
		if (isset($this->request->post['city'])) {
			$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} else {
			$this->data['phone'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['vk'])) {
			$this->data['vk'] = $this->request->post['vk'];
		} else {
			$this->data['vk'] = '';
		}
		
		if (isset($this->request->post['odnoklass'])) {
			$this->data['odnoklass'] = $this->request->post['odnoklass'];
		} else {
			$this->data['odnoklass'] = '';
		}
		
		if (isset($this->request->post['rating'])) {
			$this->data['rating'] = $this->request->post['rating'];
		} else {
			if ($this->config->get('testimonial_default_rating')=='')
				$this->data['rating'] = '3';
			else
				$this->data['rating'] = $this->config->get('testimonial_default_rating');

		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} else {
			$this->data['description'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} else {
			$this->data['image'] = '';
		}		
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/isitestimonial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/isitestimonial.tpl';
		} else {
			$this->template = 'default/template/product/isitestimonial.tpl';
		}


		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'		
		);

	
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}

  	public function success() {
		$this->language->load('product/isitestimonial');

		$this->document->SetTitle($this->language->get('isi_testimonial')); 

	     	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        		'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('testimonial'),
			'href'      => $this->url->link('product/testimonial'),
        		'separator' => $this->language->get('text_separator')
      	);			
		
	    	$this->data['heading_title'] = $this->language->get('testimonial');
	
	    	$this->data['text_message'] = $this->language->get('text_message');
	
	    	$this->data['button_continue'] = $this->language->get('button_continue');
	
	    	$this->data['continue'] = $this->url->link('product/testimonial');

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
		
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
	}

	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
  	private function validate() {
  	
  		if ((strlen(utf8_decode($this->request->post['name'])) < 2) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
	      		$this->error['name'] = $this->language->get('error_name');
	    	}
	    	
	    	if (!empty($this->request->post['email']) && !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    		}

	    	if ((strlen(utf8_decode($this->request->post['description'])) < 10) || (strlen(utf8_decode($this->request->post['description'])) > 2000)) {
	      		$this->error['enquiry'] = $this->language->get('error_enquiry');
	    	}
	    	
	    	if (!empty($this->request->post['vk']) && !preg_match("/vk.com/i", $this->request->post['vk'])) {
	      		$this->error['vk'] = $this->language->get('error_social');
	    	}
	    	
	    	if (!empty($this->request->post['odnoklass']) && !preg_match("/odnoklassniki.ru/i", $this->request->post['odnoklass'])) {
	      		$this->error['odnoklass'] = $this->language->get('error_social');
	    	}
	
	    /*	if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
	      		$this->error['captcha'] = $this->language->get('error_captcha');
	    	} */
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  	  
  	}
  	
  	public function upload() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	
			
			$allowed = array(
					'.jpg',
					'.jpeg',
					'.JPG'
				);

			if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
						
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = uniqid('image_').'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
										
	move_uploaded_file($this->request->files['file']['tmp_name'], "image/review/" . $file);
				
			 $this->load->model('tool/image');
				
			 $image = $this->model_tool_image->resize('review/' . $file, 100, 100);	
			 
			 $json['image'] = $image;	
				
			$json['file'] = $file;	
				
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->response->setOutput(json_encode($json));		
	}
  	
}
?>

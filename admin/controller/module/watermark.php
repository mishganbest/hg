<?php
class ControllerModuleWatermark extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/watermark');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if (!isset($this->request->post['watermark']['folders'])) {
				$this->request->post['watermark']['folders'] = array();
			}
			
			$this->model_setting_setting->editSetting('watermark', $this->request->post);		
			
			$this->cache->delete('product');
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			/*$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));*/
			$this->redirect($_SERVER['REQUEST_URI']);
			
		}
		
		$watermark_module = $this->config->get('watermark');
		if (!$watermark_module) {
			$watermark_module = array(
				'status'				=> '0',
				'type'					=> 'img',
				'image'					=> '',
				'position'				=> 'middle',
				'offset'				=> array(
					'x'	=> 0,
					'y'	=> 0
				),
				'min'					=> array(
					'width'  => '',
					'height' => ''
				),
				'opacity'				=> 100,
				'watermark_text'		=> '',
				'watermark_font'		=> 'arial',
				'watermark_font_size'	=> 12,
				'color'					=> '0,0,0',
				'angle'					=> 0,
				'folders'				=> array(),
				'sizes'					=> array(
					'width'  => 'none',
					'height' => 'none'
				),
				'scaling'				=> array(
					'width'  => $this->config->get('config_image_thumb_width'),
					'height' => $this->config->get('config_image_thumb_height')
				)
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_positionings'] = $this->language->get('text_positionings');
		$this->data['text_angle'] = $this->language->get('text_angle');
		$this->data['text_fontsize'] = $this->language->get('text_fontsize');
		$this->data['text_font'] = $this->language->get('text_font');
		$this->data['text_text'] = $this->language->get('text_text');
		$this->data['text_image'] = $this->language->get('text_image');
		$this->data['text_color'] = $this->language->get('text_color');
		$this->data['text_offset'] = $this->language->get('text_offset');
		$this->data['text_px'] = $this->language->get('text_px');
		$this->data['text_opacity'] = $this->language->get('text_opacity');
		$this->data['text_type'] = $this->language->get('text_type');
		$this->data['text_folders'] = $this->language->get('text_folders');
		$this->data['text_min'] = $this->language->get('text_min');
		$this->data['text_status'] = $this->language->get('text_status');
		
		$this->data['help_angle'] = $this->language->get('help_angle');
		$this->data['help_opacity'] = $this->language->get('help_opacity');
		$this->data['help_min'] = $this->language->get('help_min');
		$this->data['help_folders'] = $this->language->get('help_folders');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_clear'] = $this->language->get('button_clear');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['action'] = $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clear'] = $this->url->link('module/watermark/clear', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['watermark_module']['image'])) {
			$this->data['image'] = $this->request->post['watermark_module']['image'];
		} elseif (!empty($watermark_module['image'])) {
			$this->data['image'] = $watermark_module['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');
		$this->document->addScript('/admin/view/javascript/jquery/image.js');
		$this->document->addScript('/admin/view/javascript/jquery/colorpicker.js');
		$this->document->addStyle('/admin/view/stylesheet/colorpicker.css');
		
		if (!empty($watermark_module) && $watermark_module['image'] && file_exists(DIR_IMAGE . $watermark_module['image']) && is_file(DIR_IMAGE . $watermark_module['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($watermark_module['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['statuses'] = array(
			$this->language->get('text_disabled'), 
			$this->language->get('text_enabled')
		);
		
		$this->data['types'] = array(
			'img' 	=> $this->language->get('text_type_image'), 
			'text'	=> $this->language->get('text_type_text')
		);
		
		$this->data['fonts'] = array();
		foreach (glob(DIR_SYSTEM . '/fonts/*') as $font) {
			$info = pathinfo($font);
			$this->data['fonts'][$info['basename']] = ucfirst($info['filename']);
		}
		
		$this->data['positionings'] = array(
			'topleft', 
			'topcenter',
			'topright',
			'middleleft', 
			'middle',
			'middleright',
			'bottomleft', 
			'bottomcenter',
			'bottomright'
		);
		
		$text_image_category = $this->language->get('text_image_category');
		$text_image_thumb = $this->language->get('text_image_thumb');
		$text_image_popup = $this->language->get('text_image_popup');
		$text_image_product = $this->language->get('text_image_product');
		$text_image_additional = $this->language->get('text_image_additional');
		$text_image_related = $this->language->get('text_image_related');
		$text_image_compare = $this->language->get('text_image_compare');
		$text_image_wishlist = $this->language->get('text_image_wishlist');
		$text_image_cart = $this->language->get('text_image_cart');		
		
		$this->data['sizes'] = array(
			'width'	=> array(
				'none' => $this->language->get('text_ignore'),
				$this->config->get('config_image_category_width') => sprintf($text_image_category, $this->config->get('config_image_category_width')),
				$this->config->get('config_image_thumb_width') => sprintf($text_image_thumb, $this->config->get('config_image_thumb_width')),
				$this->config->get('config_image_popup_width') => sprintf($text_image_popup, $this->config->get('config_image_popup_width')),
				$this->config->get('config_image_product_width') => sprintf($text_image_product, $this->config->get('config_image_product_width')),
				$this->config->get('config_image_additional_width') => sprintf($text_image_additional, $this->config->get('config_image_additional_width')),
				$this->config->get('config_image_related_width') => sprintf($text_image_related, $this->config->get('config_image_related_width')),
				$this->config->get('config_image_compare_width') => sprintf($text_image_compare, $this->config->get('config_image_compare_width')),
				$this->config->get('config_image_wishlist_width') => sprintf($text_image_wishlist, $this->config->get('config_image_wishlist_width')),
				$this->config->get('config_image_cart_width') => sprintf($text_image_cart, $this->config->get('config_image_cart_width'))
			),
			'height' => array(
				'none' => $this->language->get('text_ignore'),
				$this->config->get('config_image_category_height') => sprintf($text_image_category, $this->config->get('config_image_category_height')),
				$this->config->get('config_image_thumb_height') => sprintf($text_image_thumb, $this->config->get('config_image_thumb_height')),
				$this->config->get('config_image_popup_height') => sprintf($text_image_popup, $this->config->get('config_image_popup_height')),
				$this->config->get('config_image_product_height') => sprintf($text_image_product, $this->config->get('config_image_product_height')),
				$this->config->get('config_image_additional_height') => sprintf($text_image_additional, $this->config->get('config_image_additional_height')),
				$this->config->get('config_image_related_height') => sprintf($text_image_related, $this->config->get('config_image_related_height')),
				$this->config->get('config_image_compare_height') => sprintf($text_image_compare, $this->config->get('config_image_compare_height')),
				$this->config->get('config_image_wishlist_height') => sprintf($text_image_wishlist, $this->config->get('config_image_wishlist_height')),
				$this->config->get('config_image_cart_height') => sprintf($text_image_cart, $this->config->get('config_image_cart_height'))
			)
		);
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['errors'] = $this->error;
		
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
			'href'      => $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		foreach ($watermark_module as $key => $value) {
			if (isset($this->request->post['watermark_module'][$key])) {
				$value = $this->request->post['watermark_module'][$key];
			}
			$this->data['watermark_module_' . $key] = $value;
		}
		
		$this->data['folders'] = array(
			'all'	=> $this->language->get('text_all_images')
		);
		
		if ($dir_tree = $this->getDirTree(DIR_IMAGE/* . 'data'*/)) {
			$this->data['folders'] += $dir_tree;
		}
		
		$this->template = 'module/watermark.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function getDirTree($dir_name) {
		$dirs = array();
		$tree = glob($dir_name . '/*', GLOB_ONLYDIR);
		if (is_array($tree)) {
			foreach ($tree as $dir) {
				$dir_name = trim(str_replace(DIR_IMAGE/* . 'data'*/, '', $dir), '/');
				$dir_name = iconv('cp1251', 'utf-8//ignore', $dir_name);
				if ($dir_name != 'cache') {
					$dirs[$dir_name] = str_replace('/', $this->language->get('text_separator'), $dir_name);
					if (count(glob($dir . '/*', GLOB_ONLYDIR))) {
						$dirs = array_merge($dirs, $this->getDirTree($dir));
					}
				}
			}
		}
		return $dirs;
	}
	
	public function clear() {
		$this->removeDirRec(DIR_IMAGE . 'cache');
		$this->load->language('module/watermark');
		$this->session->data['success'] = $this->language->get('text_cache_cleared');
		$this->redirect($this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	private function removeDirRec($dir) { 
		if ($objs = glob($dir . "/*")) {
			$dirs = array();
			foreach ($objs as $obj) { 
				if (is_dir($obj)) {
					$dirs[] = $obj;
					if (glob($obj . "/*")) {
						$this->removeDirRec($obj);
					} else {
						rmdir($obj);
					}
				} else {
					 @unlink($obj);
				}
			}
			foreach ($dirs as $obj) {
				rmdir($obj);
			}
		} 
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/watermark')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (isset($this->request->post['watermark'])) {
			switch($this->request->post['watermark']['type']) {
				case 'img':
					if (!$this->request->post['watermark']['image'] || !file_exists(DIR_IMAGE . $this->request->post['watermark']['image']) || !is_file(DIR_IMAGE . $this->request->post['watermark']['image'])) {
						$this->error['image'] = $this->language->get('error_field_image');
					}
					break;
				case 'text':
					if ((utf8_strlen($this->request->post['watermark']['watermark_text']) < 1) || (utf8_strlen($this->request->post['watermark']['watermark_text']) > 255)) {
						$this->error['watermark_text'] = $this->language->get('error_field_text');
					}
					if (!is_numeric($this->request->post['watermark']['watermark_font_size']) || $this->request->post['watermark']['watermark_font_size'] < 0 || $this->request->post['watermark']['watermark_font_size'] > 100) {
						$this->error['watermark_font_size'] = $this->language->get('error_field_font');
					}
					break;
			}
			if (!is_numeric($this->request->post['watermark']['opacity']) || $this->request->post['watermark']['opacity'] < 0 || $this->request->post['watermark']['opacity'] > 100) {
				$this->error['opacity'] = $this->language->get('error_field_opacity');
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
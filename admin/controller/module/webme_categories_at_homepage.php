<?php
class ControllerModuleWebmeCategoriesAtHomepage extends Controller {
	private $error = array();
	private $version = "0.4.ocs1531";
	
	public function index() {
		$this->load->language('module/webme_categories_at_homepage');
		
		$this->document->setTitle($this->language->get('heading_title').sprintf($this->language->get('text_version'), $this->version));
		
		$this->load->model('setting/setting');
		
		/*
		$this->data['webme_categories_at_homepage_category'] = array();
		if (isset($this->request->post['webme_categories_at_homepage_category'])) {
			$this->data['webme_categories_at_homepage_category'] = $this->request->post['webme_categories_at_homepage_category'];
			$this->request->post['webme_categories_at_homepage_category'] = implode("|", $this->request->post['webme_categories_at_homepage_category']);
		} else {
			$this->data['webme_categories_at_homepage_category'] = explode("|", $this->config->get('webme_categories_at_homepage_category'));
		}
		*/
		
		$this->data['success'] = "";
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->request->post['webme_categories_at_homepage_category'] = implode("_", $this->request->post['webme_categories_at_homepage_category']);
			$this->model_setting_setting->editSetting('webme_categories_at_homepage', $this->request->post);
			
			//$this->cache->delete('product');
			
			//$this->session->data['success'] = $this->language->get('text_success');
			$this->data['success'] = $this->language->get('text_success');
			
			//$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title').sprintf($this->language->get('text_version'), $this->version);
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_home'] = $this->language->get('text_home');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_no_limit'] = $this->language->get('entry_no_limit');
		$this->data['entry_limit_default'] = $this->language->get('entry_limit_default');
		
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_random'] = $this->language->get('entry_random');
		$this->data['entry_random_default'] = $this->language->get('entry_random_default');
		
		$this->data['entry_sort_by'] = $this->language->get('entry_sort_by');
		$this->data['entry_sort_by_default'] = $this->language->get('entry_sort_by_default');
		$this->data['entry_sort_by_hint'] = $this->language->get('entry_sort_by_hint');
		
		$this->data['entry_sort_by_default_asc'] = $this->language->get('entry_sort_by_default_asc');
		$this->data['entry_sort_by_name_asc'] = $this->language->get('entry_sort_by_name_asc');
		$this->data['entry_sort_by_name_desc'] = $this->language->get('entry_sort_by_name_desc');
		$this->data['entry_sort_by_price_asc'] = $this->language->get('entry_sort_by_price_asc');
		$this->data['entry_sort_by_price_desc'] = $this->language->get('entry_sort_by_price_desc');
		$this->data['entry_sort_by_rating_asc'] = $this->language->get('entry_sort_by_rating_asc');
		$this->data['entry_sort_by_rating_desc'] = $this->language->get('entry_sort_by_rating_desc');
		$this->data['entry_sort_by_model_asc'] = $this->language->get('entry_sort_by_model_asc');
		$this->data['entry_sort_by_model_desc'] = $this->language->get('entry_sort_by_model_desc');
		$this->data['entry_sort_by_date_added_asc'] = $this->language->get('entry_sort_by_date_added_asc');
		$this->data['entry_sort_by_date_added_desc'] = $this->language->get('entry_sort_by_date_added_desc');
		
		//=============
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		//=============
		
		
		$this->data['entry_category'] = $this->language->get('entry_category');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_modules'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=module/webme_categories_at_homepage&token=' . $this->session->data['token'],
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/webme_categories_at_homepage&token=' . $this->session->data['token'];
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['webme_categories_at_homepage_status'])) {
			$this->data['webme_categories_at_homepage_status'] = $this->request->post['webme_categories_at_homepage_status'];
		} else {
			$this->data['webme_categories_at_homepage_status'] = $this->config->get('webme_categories_at_homepage_status');
		}
		
		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'left',
			'title'    => $this->language->get('text_left')
		);
		
		$this->data['positions'][] = array(
			'position' => 'right',
			'title'    => $this->language->get('text_right')
		);
		
		$this->data['positions'][] = array(
			'position' => 'home',
			'title'    => $this->language->get('text_home')
		);
		
		if (isset($this->request->post['webme_categories_at_homepage_position'])) {
			$this->data['webme_categories_at_homepage_position'] = $this->request->post['webme_categories_at_homepage_position'];
		} else {
			$this->data['webme_categories_at_homepage_position'] = $this->config->get('webme_categories_at_homepage_position');
		}
		
		if (isset($this->request->post['webme_categories_at_homepage_sort_order'])) {
			$this->data['webme_categories_at_homepage_sort_order'] = $this->request->post['webme_categories_at_homepage_sort_order'];
		} else {
			$this->data['webme_categories_at_homepage_sort_order'] = $this->config->get('webme_categories_at_homepage_sort_order');
		}
		
		$this->data['webme_categories_at_homepage_category'] = array();
		if (isset($this->request->post['webme_categories_at_homepage_category'])) {
			$this->data['webme_categories_at_homepage_category'] = explode("_", $this->request->post['webme_categories_at_homepage_category']);
		} else {
			$this->data['webme_categories_at_homepage_category'] = explode("_", $this->config->get('webme_categories_at_homepage_category'));
		}
		
		if (isset($this->request->post['webme_categories_at_homepage_limit'])) {
			$this->data['webme_categories_at_homepage_limit'] = $this->request->post['webme_categories_at_homepage_limit'];
		} else {
			$this->data['webme_categories_at_homepage_limit'] = $this->config->get('webme_categories_at_homepage_limit');
		}
		
		/* use random products? */
		if (isset($this->request->post['webme_categories_at_homepage_random'])) {
			$this->data['webme_categories_at_homepage_random'] = $this->request->post['webme_categories_at_homepage_random'];
		} else {
			$this->data['webme_categories_at_homepage_random'] = $this->config->get('webme_categories_at_homepage_random');
		}
		if ($this->data['webme_categories_at_homepage_random'] == "1") {
			$this->data['webme_categories_at_homepage_random_on'] = "checked";
			$this->data['webme_categories_at_homepage_random_off'] = "";
		} else {
			$this->data['webme_categories_at_homepage_random_on'] = "";
			$this->data['webme_categories_at_homepage_random_off'] = "checked";
		}
		
		/* sort by */
		if (isset($this->request->post['webme_categories_at_homepage_sort_by'])) {
			$this->data['webme_categories_at_homepage_sort_by'] = $this->request->post['webme_categories_at_homepage_sort_by'];
		} else {
			$this->data['webme_categories_at_homepage_sort_by'] = $this->config->get('webme_categories_at_homepage_sort_by');
		}
		
		/* list of sort by - start */
		$this->data['sorts'] = array();
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_default_asc'),
			'value' => 'p.sort_order-ASC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_name_asc'),
			'value' => 'pd.name-ASC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_name_desc'),
			'value' => 'pd.name-DESC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_price_asc'),
			'value' => 'p.price-ASC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_price_desc'),
			'value' => 'p.price-DESC'
		);
		
		if ($this->config->get('config_review')) {
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('entry_sort_by_rating_desc'),
				'value' => 'rating-DESC'
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('entry_sort_by_rating_asc'),
				'value' => 'rating-ASC'
			);
		}
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_model_asc'),
			'value' => 'p.model-ASC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_model_desc'),
			'value' => 'p.model-DESC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_date_added_asc'),
			'value' => 'p.date_added-ASC'
		);
		
		$this->data['sorts'][] = array(
			'text'  => $this->language->get('entry_sort_by_date_added_desc'),
			'value' => 'p.date_added-DESC'
		);
		/* list of sort by - end */
		
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['webme_categories_at_homepage_module'])) {
			$this->data['modules'] = $this->request->post['webme_categories_at_homepage_module'];
		} elseif ($this->config->get('webme_categories_at_homepage_module')) { 
			$this->data['modules'] = $this->config->get('webme_categories_at_homepage_module');
		}
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		
		$this->template = 'module/webme_categories_at_homepage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/webme_categories_at_homepage')) {
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
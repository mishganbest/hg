<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

class ControllerModuleShoputilsCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/shoputils_category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->cache->delete('category.shoputils');

			$this->model_setting_setting->editSetting('shoputils_category', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->makeUrl('extension/module'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_expand_by_button'] = $this->language->get('entry_expand_by_button');
		$this->data['entry_expand_by_button_help'] = $this->language->get('entry_expand_by_button_help');
		$this->data['entry_expand_by_text'] = $this->language->get('entry_expand_by_text');
		$this->data['entry_expand_by_text_help'] = $this->language->get('entry_expand_by_text_help');
        $this->data['entry_path_current'] = $this->language->get('entry_path_current');
        $this->data['entry_path_current_help'] = $this->language->get('entry_path_current_help');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_category_help'] = $this->language->get('entry_category_help');
		$this->data['entry_products'] = $this->language->get('entry_products');
		$this->data['entry_products_help'] = $this->language->get('entry_products_help');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        
  		 $this->data['breadcrumbs'] = array();

   		 $this->data['breadcrumbs'][] = array(
       		'href'      => $this->makeUrl('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		 $this->data['breadcrumbs'][] = array(
       		'href'      => $this->makeUrl('extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);

   		 $this->data['breadcrumbs'][] = array(
       		'href'      => $this->makeUrl('module/shoputils_category'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->makeUrl('module/shoputils_category');

		$this->data['cancel'] = $this->makeUrl('extension/module');

		if (isset($this->request->post['shoputils_category_position'])) {
			$this->data['shoputils_category_position'] = $this->request->post['shoputils_category_position'];
		} else {
			$this->data['shoputils_category_position'] = $this->config->get('shoputils_category_position');
		}

		if (isset($this->request->post['shoputils_category_status'])) {
			$this->data['shoputils_category_status'] = $this->request->post['shoputils_category_status'];
		} else {
			$this->data['shoputils_category_status'] = $this->config->get('shoputils_category_status');
		}

		if (isset($this->request->post['shoputils_category_sort_order'])) {
			$this->data['shoputils_category_sort_order'] = $this->request->post['shoputils_category_sort_order'];
		} else {
			$this->data['shoputils_category_sort_order'] = $this->config->get('shoputils_category_sort_order');
		}

		if (isset($this->request->post['shoputils_category_expand_by_button'])) {
			$this->data['shoputils_category_expand_by_button'] = $this->request->post['shoputils_category_expand_by_button'];
		} else {
			$this->data['shoputils_category_expand_by_button'] = $this->config->get('shoputils_category_expand_by_button');
		}

		if (isset($this->request->post['shoputils_category_expand_by_text'])) {
			$this->data['shoputils_category_expand_by_text'] = $this->request->post['shoputils_category_expand_by_text'];
		} else {
			$this->data['shoputils_category_expand_by_text'] = $this->config->get('shoputils_category_expand_by_text');
		}

		if (isset($this->request->post['shoputils_category_path_current'])) {
			$this->data['shoputils_category_path_current'] = $this->request->post['shoputils_category_path_current'];
		} else {
			$this->data['shoputils_category_path_current'] = $this->config->get('shoputils_category_path_current');
		}

        if (isset($this->request->post['shoputils_category_expand_id'])) {
            $this->data['shoputils_category_expand_id'] = $this->request->post['shoputils_category_expand_id'];
        } else {
            $this->data['shoputils_category_expand_id'] = $this->config->get('shoputils_category_expand_id');
        }

        if (isset($this->request->post['shoputils_category_products'])) {
            $this->data['shoputils_category_products'] = $this->request->post['shoputils_category_products'];
        } else {
            $this->data['shoputils_category_products'] = $this->config->get('shoputils_category_products');
        }
        $this->load->model('shoputils/category');
        $this->data['categories'] = $this->model_shoputils_category->getRootCategories(0);

        $this->load->model('shoputils/design');
        $this->model_shoputils_design->init('shoputils_category');

        $this->data['design_tab'] = $this->model_shoputils_design->getDesignTab();
        $this->data['design_values'] = $this->model_shoputils_design->getDesignValues();
        $this->data['design_script'] = $this->model_shoputils_design->getDesignScript();

		$this->template = 'module/shoputils_category.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/shoputils_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    function makeUrl($route){
        return $this->url->link($route, 'token=' . $this->session->data['token'], 'SSL');
    }
}
?>
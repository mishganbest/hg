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
	protected $category_id = 0;
	protected $path = array();
	protected $expanded = array();
	protected $new_expanded = array();

	public function __construct($registry) {
        parent::__construct($registry);
        $this->category_id = array_shift($this->path);
	}

    protected function index() {

		$this->language->load('module/shoputils_category');

        $this->data['heading_title'] = $this->language->get('heading_title');
		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			
			$this->category_id = end($this->path);
		}
		
        $expanded = trim(isset($_COOKIE['expanded']) ? $_COOKIE['expanded'] : '', ',');
        $expanded = trim(str_replace('null', '', $expanded), ',');
        if ($expanded){
            $this->expanded = explode(',', $expanded);
        } elseif (!$this->category_id) {
            if ($this->config->get('shoputils_category_expand_id')){
                $this->expanded[] = $this->config->get('shoputils_category_expand_id');
            }
        }

        $this->data['expand_by_button'] = $this->config->get('shoputils_category_expand_by_button');
        $this->data['expand_by_text'] = $this->config->get('shoputils_category_expand_by_text');

        $categories = $this->cache->get('category.shoputils.'.(int)$this->config->get('config_store_id').'.'. $this->config->get('config_language_id'));
		if (!$categories){
            $categories = $this->getCategories(0);
            $this->cache->set('category.shoputils.' .(int)$this->config->get('config_store_id').'.'. $this->config->get('config_language_id'), $categories);
        }

        $this->updateExpanded($categories);
        if ($this->config->get('shoputils_category_path_current')){
            $this->updateIsCurrent($categories);
        }

        $this->data['categories'] = $categories;

        setcookie('expanded', implode(',', $this->new_expanded), 0, '/');

		$this->id = 'shoputils_category';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/shoputils_category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/shoputils_category.tpl';
		} else {
			$this->template = 'default/template/module/shoputils_category.tpl';
		}

		$this->render();
  	}

    protected function updateExpanded(&$categories){
        $result = false;
        foreach ($categories as &$category){
            $category['is_current'] = $category['id'] == $this->category_id;
            $category['expanded'] = $this->updateExpanded($category['children']) || in_array($category['id'], $this->expanded);
            if ($category['expanded']){
                $this->new_expanded[] = $category['id'];
            }
            if (!$result){
                $result = $category['is_current'] || $category['expanded'];
            }
        }
        return $result;
    }
	
    protected function updateIsCurrent(&$categories){
        $result = false;
        foreach ($categories as &$category){
            $category['is_current'] = $category['id'] == $this->category_id || $this->updateIsCurrent($category['children']);
            if (!$result){
                $result = $category['is_current'] || $category['expanded'];
            }
        }
        return $result;
    }

	protected function getCategories($parent_id) {
		$result = array();

        $categories = $this->model_catalog_category->getCategories($parent_id);


		foreach ($categories as $category) {
            if ($this->config->get('shoputils_category_products')){
                $data = array(
                    'filter_category_id'  => $category['category_id'],
                    'filter_sub_category' => true
                );

                $product_total = sprintf('(%s)', $this->model_catalog_product->getTotalProducts($data));
            } else {
                $product_total = '';
            }

            $data = array(
                'id' => $category['category_id'],
                'name' => $category['name'].$product_total,
                'href' => $this->url->link('product/category', 'path=' . $category['category_id']),
                'alt' => isset($category['seo_title_alt']) ? $category['seo_title_alt'] :  $category['name'],
                'children' => $this->getCategories($category['category_id']),
            );
            $result[$category['category_id']] = $data;
		}
 
		return $result;
	}
}
?>
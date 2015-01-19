<?php 
class ControllerInformationArticle extends Controller {  
	public function index() { 
		$this->language->load('information/article');
		
		$this->load->model('catalog/article');
		
		$this->load->model('tool/image'); 
			
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_articles'),
		'href'      => $this->url->link('information/articles'),
       		'separator' => $this->language->get('text_separator')
   		);	
			
		if (isset($this->request->get['id'])) {
			$id = '';
		
			$parts = explode('_', (string)$this->request->get['id']);
		
			foreach ($parts as $path_id) {
				if (!$id) {
					$id = $path_id;
				} else {
					$id .= '_' . $path_id;
				}
									
				$article_info = $this->model_catalog_article->getArticle($path_id);
				
				if ($article_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $article_info['name'],
						'href'      => $this->url->link('information/article', 'id=' . $id),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}		
		
			$article_id = array_pop($parts);
		} else {
			$article_id = 0;
		}
		
		$article_info = $this->model_catalog_article->getArticle($article_id);
	
		if ($article_info) {
			if ($article_info['seo_title']) {
		  		$this->document->setTitle($article_info['seo_title']);
			} else {
		  		$this->document->setTitle($article_info['name']);
			}

			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);
			
			$this->data['seo_h1'] = $article_info['seo_h1'];

			$this->data['heading_title'] = $article_info['name'];
			
			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_empty'] = $this->language->get('text_empty');			
			
					
			$this->data['button_continue'] = $this->language->get('button_continue');
					
			if ($article_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($article_info['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$this->data['thumb'] = '';
			}
									
			$this->data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
			
			$this->data['column'] = $article_info['column'];
			
			$url = '';
					
			$this->data['articles'] = array();
			
			$results = $this->model_catalog_article->getArticles($article_id);
			
			foreach ($results as $result) {
				$data = array(
					'filter_article_id'  => $result['article_id'],
					'filter_sub_article' => true	
				);
				
				$this->data['articles'][] = array(
					'name'  => $result['name'],
					'href'  => $this->url->link('information/article', 'id=' . $this->request->get['id'] . '_' . $result['article_id'] . $url)
				);
			}
			
			
	
			
		
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/article.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/article.tpl';
			} else {
				$this->template = 'default/template/information/article.tpl';
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
    	} else {
			$url = '';
			
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
									
			
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('information/article', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
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
  	}
}
?>
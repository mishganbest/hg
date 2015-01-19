<?php  
class ControllerModuleArticle extends Controller {
	protected function index($setting) {
		$this->language->load('module/article');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['id'])) {
			$parts = explode('_', (string)$this->request->get['id']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['article_id'] = $parts[0];
		} else {
			$this->data['article_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('catalog/article');
		
		$this->data['articles'] = array();
					
		$articles = $this->model_catalog_article->getArticles(0);
		
		foreach ($articles as $article) {
			$children_data = array();
			
			$children = $this->model_catalog_article->getArticles($article['article_id']);
			
			foreach ($children as $child) {
				$data = array(
					'filter_article_id'  => $child['article_id'],
					'filter_sub_article' => true
				);		
					
					$children_data[] = array(
						'article_id' => $child['article_id'],
						'name'        => $child['name'],
						'href'        => $this->url->link('information/article', 'id=' . $article['article_id'] . '_' . $child['article_id'])	
					);									
			}
			
			$data = array(
				'filter_article_id'  => $article['article_id'],
				'filter_sub_article' => true	
			);		
			
				$this->data['articles'][] = array(
					'article_id' => $article['article_id'],
					'name'        => $article['name'],
					'children'    => $children_data,
					'href'        => $this->url->link('information/article', 'id=' . $article['article_id'])
				);			
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/article.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/article.tpl';
		} else {
			$this->template = 'default/template/module/article.tpl';
		}
		
		$this->render();
  	}
}
?>
<?php  
class ControllerInformationArticles extends Controller {
	public function index() {
	
		$this->language->load('information/articles');
		
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
		
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/articles.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/articles.tpl';
		} else {
			$this->template = 'default/template/information/articles.tpl';
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
?>
<?php  
class ControllerModuletestimonial extends Controller {
	protected function index($setting) {
	
	$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/testimonial.css');
	
		$this->language->load('module/testimonial');

		$this->data['testimonial_title'] = html_entity_decode($setting['testimonial_title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

	      	$this->data['heading_title'] = $this->language->get('heading_title');
	      	$this->data['text_more'] = $this->language->get('text_more');
	      	$this->data['text_more_title'] = $this->language->get('text_more_title');
		$this->data['isi_testimonial'] = $this->language->get('isi_testimonial');
		$this->data['show_all'] = $this->language->get('show_all');
		$this->data['showall_url'] = $this->url->link('product/testimonial'); 
		$this->data['more'] = $this->url->link('product/testimonial', 'testimonial_id='); 
		$this->data['isitesti'] = $this->url->link('product/isitestimonial');

		$this->load->model('catalog/testimonial');
		
		$this->data['testimonials'] = array();
		
		$this->data['total'] = $this->model_catalog_testimonial->getTotalTestimonials();
		$results = $this->model_catalog_testimonial->getTestimonials(0, $setting['testimonial_limit'], (isset($setting['testimonial_random']))?true:false);


		foreach ($results as $result) {

			if (!isset($setting['testimonial_character_limit']))
				$setting['testimonial_character_limit'] = 0;

			if ($setting['testimonial_character_limit'] > 0) {
			
				$lim = $setting['testimonial_character_limit'];
				if (mb_strlen($result['description'], 'UTF-8') > $lim) {
					$result['description'] = mb_substr($result['description'], 0, strrpos(mb_substr($result['description'],  0, $lim), ' ')). ' ' .'<a href="'.$this->data['more']. $result['testimonial_id'] .'" title="'.$this->data['text_more_title'].'">'. $this->data['text_more'] . '</a>';
				} else {
					$result['description'] = $result['description'];
				}

			}
			
			$this->load->model('tool/image');

			$this->data['testimonials'][] = array(
				'id'			=> $result['testimonial_id'],			  
				'title'			=> $result['title'],
				'thumb'		=> $this->model_tool_image->resize($result['image'], 80, 60),
				'image'		=> $this->model_tool_image->resize($result['image'], 600, 600),
				'description'		=> $result['description'],
				'rating'		=> $result['rating'],
				'name'		=> $result['name'],
				'date_added'	=> $result['date_added'],
				'city'			=> $result['city'],
				'audio'		=> $result['audio'],
				'video'		=> $result['video']

			);
		}
		
		$this->id = 'testimonial';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/testimonial.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/testimonial.tpl';
		} else {
			$this->template = 'default/template/module/testimonial.tpl';
		}
		
		$this->render();
	}
}
?>
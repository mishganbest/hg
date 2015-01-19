<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');

		$this->load->model('catalog/product');
		$this->load->model('module/geo');

		$city = $this->model_module_geo->smarty_function_get_city();
		
			$delivery_points = $this->model_catalog_product->getDeliveryPoints($city);
			
			foreach ($delivery_points as $delivery_point) {
					
				$this->data['delivery_period_to_warehouse'] = $delivery_point['delivery_period_to_warehouse'];
				$this->data['price_to_warehouse'] = $delivery_point['price_to_warehouse'];
				$this->data['price_to_door'] = $delivery_point['price_to_door'];
				$this->data['city_a'] = $delivery_point['name_a'];
				$this->data['city_to'] = $delivery_point['name_to'];
			}
			
/* if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != 0) {
			$d = $delivery_point['delivery_period_to_door'];
		} else {
			$d = 0;
		}
		
    $date=explode(".", date("l.d.m.Y", strtotime((+(int)$d)." days")));
	    switch ($date[0]){
	    case 'Monday': $l='в понедельник'; break;
	    case 'Tuesday': $l='во вторник'; break;
	    case 'Wednesday': $l='в среду'; break;
	    case 'Thursday': $l='в четверг'; break;
	    case 'Friday': $l='в пятницу'; break;
	    case 'Saturday': $l='в субботу'; break;
	    case 'Sunday': $l='в воскресенье'; break;
	    }
	    switch ($date[2]){
	    case 1: $m='января'; break;
	    case 2: $m='февраля'; break;
	    case 3: $m='марта'; break;
	    case 4: $m='апреля'; break;
	    case 5: $m='мая'; break;
	    case 6: $m='июня'; break;
	    case 7: $m='июля'; break;
	    case 8: $m='августа'; break;
	    case 9: $m='сентября'; break;
	    case 10: $m='октября'; break;
	    case 11: $m='ноября'; break;
	    case 12: $m='декабря'; break;
    }
    
if ($delivery_point['delivery_period_to_door'] && $delivery_point['delivery_period_to_door'] != '0') {
			$this->data['delivery_period_to_door'] = $l.'&nbsp;'.(int)$date[1].'&nbsp;'.$m;
		} elseif (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		}  */



		 if (isset($delivery_point['delivery_period_to_door']) && $delivery_point['delivery_period_to_door'] == '0') {
			$this->data['delivery_period_to_door'] = 'в день заказа';
		} else {
			$this->data['delivery_period_to_door'] = '';
		} 

	// $this->data['delivery_period_to_door'] = '';


		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
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
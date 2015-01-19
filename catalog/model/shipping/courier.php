<?php

class ModelShippingCourier extends Model {

 function getQuote() {  
 
        $this->load->model('module/geo');
        
		$city = $this->model_module_geo->smarty_function_get_city();
      
            $this->load->model('catalog/product'); 
            
            $delivery_points = $this->model_catalog_product->getDeliveryPoints($city);
           
           if ($delivery_points) {
					$status = true;
				} else {
					$status = false;
				}
           
           $method_data = array();
           
           if ($status) {
			
			$quote_data = array();
			
			foreach ($delivery_points as $courier) {
				
			if ($this->config->get('courier_min_total_for_change_cost') > $this->cart->getSubTotal()) {
					 $quote_data['courier'] = array(
						 'code' => 'courier.courier',
						 'title' => 'Доставка по ' .$courier['name_to'],
						 'cost' => $courier['price_to_door'],
						 'tax_class_id' => 0,
						 'text' => $this->currency->format($courier['price_to_door'])
					 );
			} else {	
					$quote_data['courier'] = array(
						 'code' => 'courier.courier',
						 'title' => 'Доставка по ' .$courier['name_to'],
						 'cost' => $this->config->get('courier_delivery_price'),
						 'tax_class_id' => 0,
						 'text' => $this->currency->format($this->config->get('courier_delivery_price'))
					 );
			}	 
					 
			
		 }
       	
  
            $method_data = array(
                'code' => 'courier',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('courier_sort_order'),
                'error' => false
            );
        
	}
        return $method_data;
    }

}

?>
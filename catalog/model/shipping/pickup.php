<?php
class ModelShippingPickup extends Model {
	function getQuote($address) {
		$this->load->language('shipping/pickup');

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
			
			foreach ($delivery_points as $i => $pickup) {
				
	
					 $quote_data['pickup_' . $i] = array(
						 'code' => 'pickup.pickup_' . $i,
						 'title' => 'Самовывоз: ' .$pickup['address'],
						 'cost' => $pickup['price_to_warehouse'],
						 'tax_class_id' => 0,
						 'text' => $this->currency->format($pickup['price_to_warehouse'])
					 );
					 
			
		 }
       	
  
            $method_data = array(
                'code' => 'pickup',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('pickup_sort_order'),
                'error' => false
            );
        
	}
        return $method_data;
    }

}

?>
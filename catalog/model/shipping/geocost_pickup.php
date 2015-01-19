<?php 
class ModelShippingGeocostPickup extends Model {    
  	public function getQuote($address) {
		$this->load->language('shipping/geocost_pickup');
		
		$quote_data = array();

		if ($this->config->get('geocost_pickup_status')) {
		
		if (isset($this->session->data['simple']['shipping_address']['city'])) {
			$city = $this->session->data['simple']['shipping_address']['city'];
		}else{
			$this->load->model('module/geo');
			$city = $this->model_module_geo->smarty_function_get_city();
		}

		if ($city == 'Новосибирск'){
		
		 $this->load->model('catalog/product'); 
            
            $delivery_points = $this->model_catalog_product->getDeliveryPoints($city);
           
           if ($delivery_points) {
					$status = true;
				} else {
					$status = false;
				}
      					
			foreach ($delivery_points as $i => $result) {
   				if ($this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_status')) {
   				
   				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_point dp LEFT JOIN " . DB_PREFIX . "cities c ON (dp.city_id = c.city_id) WHERE c.name = '" . $city . "' AND dp.city_id = c.city_id AND delivery_point_id = '" . (int)$result['delivery_point_id'] . "'");
   				
   								
					if ($query->num_rows) {
       					$status = TRUE;
   					} else {
       					$status = FALSE;
   					}
				} else {
					$status = FALSE;
				}
			
				if ($status) {
					$cost = 0.00;
					$geocost = $this->cart->getSubTotal();
					
					$rate = $this->config->get('geocost_pickup_' . $result['delivery_point_id'] . '_rate');

  						$data = explode(':', $rate);

						$terms = count($data);

						if ($terms == 2) {
  					
						if ($data[0] >= $geocost) {							
    							$cost = $data[1];						
  						}else{
    							$cost = 0;							
						}

						} elseif ($terms == 3) {

						if ($data[0] >= $geocost) {							
    							$cost = $data[1];						
  						}elseif ($data[0] < $geocost) {
    							$cost = $data[2];							
						}else{
    							$cost = 0;							
						}

						} elseif ($terms == 4) {

						if ($data[0] >= $geocost) {							
    							$cost = $data[1];						
  						}elseif ($data[0] < $geocost && $data[2] > $geocost) {
    							$cost = $data[3];							
						}else{
    							$cost = 0;							
						}

						} elseif ($terms == 5) {

						if ($data[0] >= $geocost) {							
    							$cost = $data[1];						
  						}elseif ($data[0] < $geocost && $data[2] > $geocost) {
    							$cost = $data[3];							
						}else{
    							$cost = $data[4];							
						}

						} else {
						
							$cost = false;

						}
					

					if ((string)$cost != '') { 
      					$quote_data['geocost_pickup_' . $result['delivery_point_id'] . '_' . $i] = array(
      						'code'         => 'geocost_pickup.geocost_pickup_' . $result['delivery_point_id'] .'_'. $i,
        					'title'        => 'Самовывоз: ' .$result['address'],
        					'cost'         => $cost,
							'tax_class_id' => $this->config->get('geocost_pickup_tax_class_id'),
        					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('geocost_pickup_tax_class_id'), $this->config->get('config_tax')))
      					);	
					}
				}
			}
		    }
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'code'         => 'geocost_pickup',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('geocost_pickup_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
  	}
}
?>
<?php 
class ModelModuleCategoryShipping extends Model {
  
  public function get_shippings() {
    
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'shipping'");
		
		$extensions = array();
		foreach ($query->rows as $row) {
			if ($this->config->get($row['code'] . '_status')) {
				$extensions[$row['code']] = $row['extension_id'];
			}
		}
    
    return $extensions;
    
  }
  
  public function get_shippings_with_names() {
    
    $extensions = $this->get_shippings();
    
    $shippings = array();
    
    $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension_code = basename($file, '.php');
				
				if (isset($extensions[$extension_code])) {
					$this->load->language('shipping/' . $extension_code);
					
					$shippings[] = array('extension_id' => $extensions[$extension_code], 'name' => $this->language->get('heading_title'));
					
				}

			}
		}
    
    return $shippings;
    
  }
  
  
}
?>
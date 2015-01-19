<?php 
class ModelLocalisationCity extends Model {
	
	public function getCities($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "cities";
      		
			$sql .= " ORDER BY name";	
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}					
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}			
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			$city_data = $this->cache->get('city');
		
			if (!$city_data) {
				$query = $this->db->query("SELECT city_id, name FROM " . DB_PREFIX . "cities ORDER BY name");
	
				$city_data = $query->rows;
			
				$this->cache->set('city', $city_data);
			}	
	
			return $city_data;			
		}
	}
	
}
?>
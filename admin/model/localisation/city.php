<?php 
class ModelLocalisationCity extends Model {
	public function addCity($data) {
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "cities SET name = '" . $this->db->escape($data['name']) . "', name_in = '" . $this->db->escape($data['name_in']) . "', name_to = '" . $this->db->escape($data['name_to']) . "', name_a = '" . $this->db->escape($data['name_a']) . "'");
			
		$this->cache->delete('city');
	}

	public function editCity($city_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cities WHERE city_id = '" . (int)$city_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "cities SET city_id = '" . (int)$city_id . "', name = '" . $this->db->escape($data['name']) . "', name_in = '" . $this->db->escape($data['name_in']) . "', name_to = '" . $this->db->escape($data['name_to']) . "', name_a = '" . $this->db->escape($data['name_a']) . "'");
		
		$this->cache->delete('city');
	}
	
	public function deleteCity($city_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cities WHERE city_id = '" . (int)$city_id . "'");
	
		$this->cache->delete('city');
	}
		
	public function getCity($city_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cities WHERE city_id = '" . (int)$city_id . "'");
		
		return $query->row;
	}
	
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

	public function getTotalCities() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cities");
		
		return $query->row['total'];
	}	
}
?>
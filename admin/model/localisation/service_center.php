<?php 
class ModelLocalisationServiceCenter extends Model {
	public function addServiceCenter($data) {
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "service_center SET city_id = '" . (int)$data['city_id'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', time = '" . $this->db->escape($data['time']) . "', phone = '" . $this->db->escape($data['phone']) . "', urlserviceinfo = '" . $this->db->escape($data['urlserviceinfo']) . "'");
			
		$this->cache->delete('service_center');
	}

	public function editServiceCenter($service_center_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "service_center WHERE service_center_id = '" . (int)$service_center_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "service_center SET service_center_id = '" . (int)$service_center_id . "', city_id = '" . (int)$data['city_id'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', time = '" . $this->db->escape($data['time']) . "', phone = '" . $this->db->escape($data['phone']) . "', urlserviceinfo = '" . $this->db->escape($data['urlserviceinfo']) . "'");
		
		$this->cache->delete('service_center');
	}
	
	public function deleteServiceCenter($service_center_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "service_center WHERE service_center_id = '" . (int)$service_center_id . "'");
	
		$this->cache->delete('service_center');
	}
		
	public function getServiceCenter($service_center_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service_center WHERE service_center_id = '" . (int)$service_center_id . "'");
		
		return $query->row;
	}
	
	public function getServiceCenters($data = array()) {
		if ($data) {
			$sql = "SELECT *, sc.name, c.name AS city FROM " . DB_PREFIX . "service_center sc LEFT JOIN " . DB_PREFIX . "cities c ON (sc.city_id = c.city_id)";
			
		$sort_data = array(
			'c.name',
			'sc.name'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.name";	
		}	
			
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
			$service_center_data = $this->cache->get('service_center');
		
			if (!$service_center_data) {
				$query = $this->db->query("SELECT service_center_id, name FROM " . DB_PREFIX . "service_center ORDER BY name");
	
				$service_center_data = $query->rows;
			
				$this->cache->set('service_center', $service_center_data);
			}	
	
			return $service_center_data;			
		}
	}

	public function getTotalServiceCenters() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "service_center");
		
		return $query->row['total'];
	}	
}
?>
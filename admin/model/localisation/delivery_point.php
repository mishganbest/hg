<?php
class ModelLocalisationDeliveryPoint extends Model {
	public function addDeliveryPoint($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_point SET address = '" . $this->db->escape($data['address']) . "', time = '" . $this->db->escape($data['time']) . "', phone = '" . $this->db->escape($data['phone']) . "', delivery_period_to_warehouse = '" . $this->db->escape($data['delivery_period_to_warehouse']) . "', price_to_warehouse = '" . $this->db->escape($data['price_to_warehouse']) . "', delivery_period_to_door = '" . $this->db->escape($data['delivery_period_to_door']) . "', price_to_door = '" . $this->db->escape($data['price_to_door']) . "', photo_url = '" . $this->db->escape($data['photo_url']) . "', city_id = '" . (int)$data['city_id'] . "'");
			
		$this->cache->delete('delivery_point');
	}
	
	public function editDeliveryPoint($delivery_point_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "delivery_point SET address = '" . $this->db->escape($data['address']) . "', time = '" . $this->db->escape($data['time']) . "', phone = '" . $this->db->escape($data['phone']) . "', delivery_period_to_warehouse = '" . $this->db->escape($data['delivery_period_to_warehouse']) . "', price_to_warehouse = '" . $this->db->escape($data['price_to_warehouse']) . "', delivery_period_to_door = '" . $this->db->escape($data['delivery_period_to_door']) . "', price_to_door = '" . $this->db->escape($data['price_to_door']) . "', photo_url = '" . $this->db->escape($data['photo_url']) . "', city_id = '" . (int)$data['city_id'] . "' WHERE delivery_point_id = '" . (int)$delivery_point_id . "'");

		$this->cache->delete('delivery_point');
	}
	
	public function deleteDeliveryPoint($delivery_point_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_point WHERE delivery_point_id = '" . (int)$delivery_point_id . "'");

		$this->cache->delete('delivery_point');	
	}
	
	public function getDeliveryPoint($delivery_point_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_point WHERE delivery_point_id = '" . (int)$delivery_point_id . "'");
		
		return $query->row;
	}
	
	public function getDeliveryPoints($data = array()) {
		$sql = "SELECT *, dp.address, c.name AS city FROM " . DB_PREFIX . "delivery_point dp LEFT JOIN " . DB_PREFIX . "cities c ON (dp.city_id = c.city_id)";
			
		$sort_data = array(
			'c.name',
			'dp.address'
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
	}
	
	public function getDeliveryPointsByCityId($city_id) {
		$delivery_point_data = $this->cache->get('delivery_point.' . (int)$city_id);
	
		if (!$delivery_point_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_point WHERE city_id = '" . (int)$city_id . "' ORDER BY address");
	
			$delivery_point_data = $query->rows;
			
			$this->cache->set('delivery_point.' . (int)$city_id, $delivery_point_data);
		}
	
		return $delivery_point_data;
	}
	
	public function getTotalDeliveryPoints() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "delivery_point");
		
		return $query->row['total'];
	}
				
	public function getTotalDeliveryPointsByCityId($city_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "delivery_point WHERE city_id = '" . (int)$city_id . "'");
	
		return $query->row['total'];
	}
}
?>
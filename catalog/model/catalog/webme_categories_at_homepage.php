<?php
class ModelCatalogWebmeCategoriesAtHomepage extends Model {
	
	public function getTotalProductsByCategoryId($category_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2c.category_id = '" . (int)$category_id . "'");
		
		return $query->row['total'];
	}
	
	public function getProductsByCategoryId($category_id, $sort = 'p.sort_order', $order = 'ASC', $start = 0, $limit = 4) {
		$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM " . DB_PREFIX . "review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "stock_status ss ON (p.stock_status_id = ss.stock_status_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "'";
		
		$sort_data = array(
			'pd.name',
			'p.sort_order',
			'special',
			'rating',
			'p.price',
			'p.model',
			'p.date_added'
		);
		
		$show_random_products = $this->config->get('webme_categories_at_homepage_random');
		if ($show_random_products == 1) {
			$sql .= " ORDER BY RAND()";
		} else {
			if (in_array($sort, $sort_data)) {
				if ($sort == 'pd.name' || $sort == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $sort . ")";
				} else {
					$sql .= " ORDER BY " . $sort;
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
			
			if ($order == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		}
		
		if ($start < 0) {
			$start = 0;
		}
		
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
}
?>
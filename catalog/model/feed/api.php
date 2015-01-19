<?php
#####################################################################################
#  API for Opencart 1.5.x From HostJars http://opencart.hostjars.com    			#
#####################################################################################


class ModelFeedApi extends Model {

	//You can put any DB calls you like in here to post/delete/put/get
	

	public function getCustomers() {
		$query = $this->db->query("SELECT firstname, email, telephone FROM " . DB_PREFIX . "customer");
		
		return $query->rows;
	}
	
	 
	public function getOrders() {
		
		$query = $this->db->query("SELECT o.order_id, o.firstname, o.telephone, o.email, o.shipping_city, o.shipping_address_1, os.name as status, o.date_added, o.total  FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.order_status_id > '0' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'");	
	
		return $query->rows;
	}
	
	public function deleteProduct($product_id) {
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id='" . $this->db->escape($product_id) . "'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id='" . $this->db->escape($product_id) . "'");
	}

}
?>
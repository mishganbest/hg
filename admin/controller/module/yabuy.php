<?php
class ControllerModuleYabuy extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->load->language("module/yabuy");

		$this->document->setTitle($this->language->get("heading_title")); 
		
		$this->load->model("setting/setting");
				
		if (($this->request->server["REQUEST_METHOD"] == "POST") && $this->validate()) {
			$this->request->post['yabuy_payments'] = implode(',', $this->request->post['yabuy_payments']);
			$this->model_setting_setting->editSetting("yabuy", $this->request->post);		
					
			$this->session->data["success"] = $this->language->get("text_success");
						
			$this->redirect($this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL"));
		}
		
		$this->data["heading_title"] = $this->language->get("heading_title");
		
		$this->data["text_enabled"] = $this->language->get("text_enabled");
		$this->data["text_disabled"] = $this->language->get("text_disabled");

		$this->data["text_delivery_id"] = $this->language->get("text_delivery_id");
		$this->data["text_delivery_name"] = $this->language->get("text_delivery_name");
		$this->data["text_delivery_price"] = $this->language->get("text_delivery_price");
		$this->data["text_delivery_from"] = $this->language->get("text_delivery_from");
		$this->data["text_delivery_to"] = $this->language->get("text_delivery_to");
		$this->data["text_delivery_region"] = $this->language->get("text_delivery_region");
		
		$this->data["text_outlet_id"] = $this->language->get("text_outlet_id");
		$this->data["text_outlet_price"] = $this->language->get("text_outlet_price");
		$this->data["text_outlet_zone"] = $this->language->get("text_outlet_zone");
		$this->data["text_outlet_city"] = $this->language->get("text_outlet_city");
		$this->data["text_outlet_postcode"] = $this->language->get("text_outlet_postcode");
		$this->data["text_outlet_address_1"] = $this->language->get("text_outlet_address_1");
		$this->data["text_outlet_address_2"] = $this->language->get("text_outlet_address_2");
		$this->data["text_outlet_price"] = $this->language->get("text_outlet_price");

		$this->data["entry_status"] = $this->language->get("entry_status");
		$this->data["entry_yacompany"] = $this->language->get("entry_yacompany");
		$this->data["entry_yalogin"] = $this->language->get("entry_yalogin");
		$this->data["entry_token"] = $this->language->get("entry_token");
		$this->data["entry_payments"] = $this->language->get("entry_payments");
		$this->data["entry_deliveries"] = $this->language->get("entry_deliveries");
		$this->data["entry_outlets"] = $this->language->get("entry_outlets");
		
		//buttons
		$this->data["button_save"] = $this->language->get("button_save");
		$this->data["button_cancel"] = $this->language->get("button_cancel");
		$this->data["button_add_outlet"] = $this->language->get("button_add_outlet");
		$this->data["button_remove"] = $this->language->get("button_remove");
		
		//errors
		if (isset($this->error["warning"])) {
			$this->data["error_warning"] = $this->error["warning"];
		} else {
			$this->data["error_warning"] = "";
		}
		
		//breadcrumbs
		$this->data["breadcrumbs"] = array();

   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("text_home"),
			"href"      => $this->url->link("common/home", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => false
   		);

   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("text_module"),
			"href"      => $this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => " :: "
   		);
		
   		$this->data["breadcrumbs"][] = array(
       		"text"      => $this->language->get("heading_title"),
			"href"      => $this->url->link("module/yabuy", "token=" . $this->session->data["token"], "SSL"),
      		"separator" => " :: "
   		);
		
		$this->data["action"] = $this->url->link("module/yabuy", "token=" . $this->session->data["token"], "SSL");
		
		$this->data["cancel"] = $this->url->link("extension/module", "token=" . $this->session->data["token"], "SSL");
		
		//------------------------------
		//insert you data
		//------------------------------
		if (isset($this->request->post['yabuy_status'])) {
			$this->data['yabuy_status'] = $this->request->post['yabuy_status'];
		} else {
			$this->data['yabuy_status'] = $this->config->get('yabuy_status');
		}
		
		if (isset($this->request->post['yabuy_yacompany'])) {
			$this->data['yabuy_yacompany'] = $this->request->post['yabuy_yacompany'];
		} else {
			$this->data['yabuy_yacompany'] = $this->config->get('yabuy_yacompany');
		}

		if (isset($this->request->post['yabuy_login'])) {
			$this->data['yabuy_login'] = $this->request->post['yabuy_login'];
		} else {
			$this->data['yabuy_login'] = $this->config->get('yabuy_login');
		}
		
		if (isset($this->request->post['yabuy_token'])) {
			$this->data['yabuy_token'] = $this->request->post['yabuy_token'];
		} else {
			$this->data['yabuy_token'] = $this->config->get('yabuy_token');
		}
		
		if (isset($this->request->post['yabuy_payments'])) {
			$this->data['yabuy_payments'] = $this->request->post['yabuy_payments'];
		} else {
			$this->data['yabuy_payments'] = explode(',', $this->config->get('yabuy_payments'));
		}
		
		$this->data["yabuy_deliveries"] = array();
		if (isset($this->request->post["yabuy_deliveries"])) {
			$this->data["yabuy_deliveries"] = $this->request->post["yabuy_deliveries"];
		} elseif ($this->config->get("yabuy_deliveries")) { 
			$this->data["yabuy_deliveries"] = $this->config->get("yabuy_deliveries");
		}	

		$this->data["yabuy_outlets"] = array();
		if (isset($this->request->post["yabuy_outlets"])) {
			$this->data["yabuy_outlets"] = $this->request->post["yabuy_outlets"];
		} elseif ($this->config->get("yabuy_outlets")) { 
			$this->data["yabuy_outlets"] = $this->config->get("yabuy_outlets");
		}
		

		$this->data["url_csvoutlets"] = $this->url->link('module/yabuy/csvoutlets', '', "SSL");
		$this->data['token'] = $this->session->data["token"];
		if (is_file(DIR_CATALOG . 'controller/yandexbuy/outlets.csv')) {
			$this->data['csvoutlets'] = true;
			$this->data["text_csvoutlets"] = sprintf($this->language->get("text_csvoutlets"), 'catalog/controller/yandexbuy/outlets.csv');
			$this->data["text_show"] = $this->language->get("text_show");
		}
		else {
			$this->data['csvoutlets'] = false;
		}
		
		$this->template = "module/yabuy.tpl";
		$this->children = array(
			"common/header",
			"common/footer",
		);
		
		$this->data["token"] = $this->session->data["token"];
				
		$this->response->setOutput($this->render());
	}
	
	public function install() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT 1");
		if (!isset($query->row['yaorder_id'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `yaorder_id` INT NOT NULL AFTER `order_id`");
		}
	}
	
	public function uninstall() {
	
	}

	public function csvoutlets() {
		$outlets = array();
		if (is_file(DIR_CATALOG . 'controller/yandexbuy/outlets.csv')) {
			$fp = fopen(DIR_CATALOG . 'controller/yandexbuy/outlets.csv', 'r');
			if($fp){
				while ($data = fgets($fp)) {
					$data = explode(';', $data);
					$num = count($data);
					if((int)$num >= 6){
						$outlets[] = array(
							'id' => $data[0],
							'zone' => $data[1],
							'city' => $data[2],
							'postcode' => $data[3],
							'address_1' => $data[4],
							'address_2' => $data[5],
							'price' => (isset($data[6]) ? $data[6] : 0)
						);
					}
				}
			}
			fclose($fp);
		}
		echo json_encode($outlets);
	}
	
	private function validate() {
		if (!$this->user->hasPermission("modify", "module/yabuy")) {
			$this->error["warning"] = $this->language->get("error_permission");
		}

		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>

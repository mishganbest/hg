<?php 
#####################################################################################
#  API for Opencart 1.5.x From HostJars http://opencart.hostjars.com    			#
#####################################################################################
class ControllerFeedApi extends Controller {
	public function index() {
		if ($this->config->get('api_status')) {
			
			$output = '';
			
			if($this->request->server['REQUEST_METHOD'] == 'GET') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif ($this->request->server['REQUEST_METHOD'] == 'POST')	{
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif($this->request->server['REQUEST_METHOD'] == 'PUT') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
				}
			}
			elseif($this->request->server['REQUEST_METHOD'] == 'DELETE') {
				if (isset($this->request->get['func']) && is_callable(array($this, $this->request->get['func']))) {
					if (!isset($this->request->get['param1'])) {
						$output = "DELETE functions require \"param1\"";
					}
					else {
						$output = json_encode(call_user_func(array($this, $this->request->get['func'])));
					}
				}
			}
			
			$this->response->setOutput($output);
		}
	}

	/**
	 *  WRITE YOUR DESIRED API FUNCTIONS HERE:
	 *  
	 *  example calling function getProducts() as restful webservice:
	 *  http://www.example.com/index.php?route=feed/api&func=getProducts
	 * 
	 */


	//"GET" FUNCTIONS:

	// example function - getProducts loads the products model and calls its getProducts function.
	private function getProducts() {
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}
		$this->load->model('catalog/product');
		return $this->model_catalog_product->getProducts();
	}
	// example function - getCustomers from the database
	private function getCustomers() {
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}
		$this->load->model('feed/api');
		return $this->model_feed_api->getCustomers();
	}
	// example function - getOrders from the database
	private function getOrders() {
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			return '';
		}
		$this->load->model('feed/api');
		return $this->model_feed_api->getOrders();
	}
	
	//"DELETE" FUNCTIONS:

	// example function - deleteProduct loads the model and calls its deleteProduct function.
	private function deleteProduct($prodid) {
//		if ($this->request->server['REQUEST_METHOD'] != 'DELETE') {
//			return '';
//		}
		$this->load->model('feed/api');
		return $this->model_feed_api->deleteProduct($this->request->get['param1']);
	}
}
?>
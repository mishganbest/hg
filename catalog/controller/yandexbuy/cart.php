<?php
/**
* Yandex CPA "Покупка на Маркете" для OpenCart (ocStore) 1.5.5.x
*
* @author Alexander Toporkov <toporchillo@gmail.com>
* @copyright (C) 2013- Alexander Toporkov
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
require_once(dirname(__FILE__).'/base.php');

class ControllerYandexbuyCart extends ControllerYandexbuyBase {

	public function index() {
		$postdata = file_get_contents("php://input");
		if (!$postdata) {
			header('HTTP/1.0 404 Not Found');
			echo '<h1>No data posted</h1>';
			exit;
		}
		$this->load->model('catalog/product');
		$data = json_decode($postdata, true);
		
		$currency = $data['cart']['currency']; //Assume RUR
		
		$ret = array('cart'=>array('items'=>array(), 'deliveryOptions'=>array(), 'paymentMethods'=>array()));
		$total = 0;
		
		foreach ($data['cart']['items'] as $item) {
			$offer_id = $item['offerId'];
			$option_value_id = 0;
			$option2_value_id = 0;
			if ($offer_id > 100000000000000) {
				$offer_id = (int)floor($offer_id/100000000000000);
				$option_value_id = (int)floor(($item['offerId'] % 100000000000000) / 10000000);
				$option2_value_id = $item['offerId'] % 10000000;
			}
			elseif ($offer_id > 10000000) {
				$offer_id = (int)floor($offer_id/10000000);
				$option_value_id = $item['offerId'] % 10000000;
			}
			$product_info = $this->model_catalog_product->getProduct($offer_id);
			if ($product_info['status'] != 1
				|| !$product_info['quantity']
				/* || $product_info['stock_status'] == (int)$out_of_stock_id */) {
				continue;
			}

			$count = min($product_info['quantity'], $item['count']);
			if ($count < $product_info['minimum']) {
				$count = $product_info['minimum'];
			}
			$price = ($product_info['special'] ? $product_info['special'] : $product_info['price']);
			if ($option_value_id > 0) {
				$option = $this->getProductOptionData($offer_id, $option_value_id);
				if ($option['price_prefix'] == '+') {
					$price+= $option['price'];
				}
				elseif ($option['price_prefix'] == '-') {
					$price-= $option['price'];
				}
			}
			if ($option2_value_id > 0) {
				$option2 = $this->getProductOptionData($offer_id, $option_value_id);
				if ($option2['price_prefix'] == '+') {
					$price+= $option2['price'];
				}
				elseif ($option2['price_prefix'] == '-') {
					$price-= $option2['price'];
				}
			}
			$total+= floatval($price)*$count;
			$ret['cart']['items'][] = array(
				'feedId'=>$item['feedId'],
				'offerId'=>$item['offerId'],
				'price'=>floatval($price),
				'count'=>intval($count),
				'delivery'=>true
			);			
		}
		$regions = $this->extractRegions($data['cart']['delivery']['region']);
		$ret['cart']['deliveryOptions'] = $this->getShipping($total, $regions);
		$ret['cart']['paymentMethods'] = $this->paymentMethods;
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($ret);
	}
	
	protected function extractRegions($region) {
		$res_regions = array($region['id']);
		if (isset($region['parent']))
			$res_regions = array_merge($res_regions, $this->extractRegions($region['parent']));
		return $res_regions;
	}
}

<?php
class ControllerPaymentQCCod extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/qc_cod.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/qc_cod.tpl';
		} else {
			$this->template = 'default/template/payment/qc_cod.tpl';
		}

		$this->render();
	}

	public function confirm() {
		$this->load->model('checkout/order');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('qc_cod_order_status_id'));
	}
}
?>
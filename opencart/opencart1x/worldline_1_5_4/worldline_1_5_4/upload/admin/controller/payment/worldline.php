<?php
class ControllerPaymentworldline extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/worldline');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
        $this->load->model('payment/worldline');
		$merchant_details = $this->model_payment_worldline->get();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    if(count($this->validate()) == 0){
				$this->model_setting_setting->editSetting('worldline', $this->request->post);

				if(is_array($merchant_details) && !isset($merchant_details[0])){
				    $response = $this->model_payment_worldline->add($this->request->post);
				}else{
				    $response = $this->model_payment_worldline->edit($this->request->post);
				}

				$this->session->data['success'] = $this->language->get('text_success');
    		    $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_for_worldline'] = $this->language->get('text_for_worldline');

		//entry
		$this->data['request_type_T'] = $this->language->get('request_type_T');
		$this->data['merchant_code'] = $this->language->get('merchant_code');
        $this->data['request_type'] = $this->language->get('request_type');
		$this->data['key'] = $this->language->get('key');
        $this->data['iv'] = $this->language->get('iv');
		$this->data['webservice_locator'] = $this->language->get('webservice_locator');
        $this->data['order_status'] = $this->language->get('order_status');
        $this->data['status'] = $this->language->get('status');
		$this->data['sort_order'] = $this->language->get('sort_order');
		$this->data['merchant_scheme_code'] = $this->language->get('merchant_scheme_code');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		// handling error messeges
		if (isset($this->error['merchantCode'])) {
			$this->data['error_merchant_code'] = $this->error['merchantCode'];
		} else {
			$this->data['error_merchant_code'] = '';
		}

		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}

		if (isset($this->error['iv'])) {
			$this->data['error_iv'] = $this->error['iv'];
		} else {
			$this->data['error_iv'] = '';
		}

		if (isset($this->error['requestType'])) {
			$this->data['error_request_type'] = $this->error['requestType'];
		} else {
			$this->data['error_request_type'] = '';
		}

		if (isset($this->error['webserviceLocator'])) {
			$this->data['error_webservice_locator'] = $this->error['webserviceLocator'];
		} else {
			$this->data['error_webservice_locator'] = '';
		}

		if (isset($this->error['orderStatus'])) {
			$this->data['error_order_status'] = $this->error['orderStatus'];
		} else {
			$this->data['error_order_status'] = '';
		}

		if (isset($this->error['sortOrder'])) {
			$this->data['error_sort_order'] = $this->error['sortOrder'];
		} else {
			$this->data['error_sort_order'] = '';
		}

		if (isset($this->error['merchantSchemeCode'])) {
		    $this->data['error_merchant_scheme_code'] = $this->error['merchantSchemeCode'];
		} else {
		    $this->data['error_merchant_scheme_code'] = '';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/worldline', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/worldline', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);

		// set values.
		if (isset($this->request->post['worldline_merchant_code'])) {
			$this->data['worldline_merchant_code'] = $this->request->post['worldline_merchant_code'];
		} else {
			$this->data['worldline_merchant_code'] = $this->config->get('worldline_merchant_code');
		}

		if (isset($this->request->post['worldline_request_type'])) {
			$this->data['worldline_request_type'] = $this->request->post['worldline_request_type'];
		} else {
			$this->data['worldline_request_type'] = $this->config->get('worldline_request_type');
		}

		if (isset($this->request->post['worldline_key'])) {
			$this->data['worldline_key'] = $this->request->post['worldline_key'];
		} else {
			$this->data['worldline_key'] = $this->config->get('worldline_key');
		}

		if (isset($this->request->post['worldline_iv'])) {
			$this->data['worldline_iv'] = $this->request->post['worldline_iv'];
		} else {
			$this->data['worldline_iv'] = $this->config->get('worldline_iv');
		}

		if (isset($this->request->post['worldline_webservice_locator'])) {
			$this->data['worldline_webservice_locator'] = $this->request->post['worldline_webservice_locator'];
		} else {
			$this->data['worldline_webservice_locator'] = $this->config->get('worldline_webservice_locator');
		}

		if (isset($this->request->post['worldline_order_status'])) {
			$this->data['worldline_order_status'] = $this->request->post['worldline_order_status'];
		} else {
			$this->data['worldline_order_status'] = $this->config->get('worldline_order_status');
		}

		if (isset($this->request->post['worldline_status'])) {
			$this->data['worldline_status'] = $this->request->post['worldline_status'];
		} else {
			$this->data['worldline_status'] = $this->config->get('worldline_status');
		}

		if (isset($this->request->post['worldline_sort_order'])) {
			$this->data['worldline_sort_order'] = $this->request->post['worldline_sort_order'];
		} else {
			$this->data['worldline_sort_order'] = $this->config->get('worldline_sort_order');
		}

		if (isset($this->request->post['worldline_merchant_scheme_code'])) {
		    $this->data['worldline_merchant_scheme_code'] = $this->request->post['worldline_merchant_scheme_code'];
		} else {
		    $this->data['worldline_merchant_scheme_code'] = $this->config->get('worldline_merchant_scheme_code');
		}

		$this->template = 'payment/worldline.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->model('payment/worldline');
		$this->model_payment_worldline->install();
	}

	public function uninstall() {
		$this->load->model('payment/worldline');
		$this->model_payment_worldline->uninstall();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/worldline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!trim($this->request->post['worldline_merchant_code'])) {
			$this->error['merchantCode'] = $this->language->get('error_merchant_code');
		}

		if (!trim($this->request->post['worldline_key'])) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!trim($this->request->post['worldline_iv'])) {
			$this->error['iv'] = $this->language->get('error_iv');
		}

		if (!trim($this->request->post['worldline_sort_order'])) {
			$this->error['sortOrder'] = $this->language->get('error_sort_order');
		}

		if (!$this->request->post['worldline_request_type']) {
			$this->error['requestType'] = $this->language->get('error_request_type');
		}
		if (!$this->request->post['worldline_webservice_locator']) {
			$this->error['webserviceLocator'] = $this->language->get('error_webservice_locator');
		}
		if (!$this->request->post['worldline_order_status']) {
			$this->error['orderStatus'] = $this->language->get('error_order_status');
		}

		if (!trim($this->request->post['worldline_merchant_scheme_code'])) {
		    $this->error['merchantSchemeCode'] = $this->language->get('error_merchant_scheme_code');
		}

		return $this->error;
	}
}
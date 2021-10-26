<?php
class ControllerPaymentworldline extends Controller {
	private $error = array();
	protected $data = array();

	public function index() {
		$this->load->language('payment/worldline');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
        $this->load->model('payment/worldline');

        $merchant_details = $this->model_payment_worldline->get();
        $this->data['error_warning'] = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    if(count($this->validate()) == 0){
    		    $this->model_setting_setting->editSetting('worldline', $this->request->post);
    		    $this->session->data['success'] = $this->language->get('text_success');

    		    if(is_array($merchant_details) && !isset($merchant_details[0])){
    		        $response = $this->model_payment_worldline->add($this->request->post);
    		    }else{
    		        $response = $this->model_payment_worldline->edit($this->request->post);
    		    }

    		    if($response === true){
    			    $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    		    }
		    }else if (isset($this->error['warning'])) {
		        $this->data['error_warning'] = $this->error['warning'];
		    }
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_for_worldline'] = $this->language->get('text_for_worldline');

		//values from text box
		$this->data['request_type_T'] = $this->language->get('request_type_T');
		$this->data['verification_enabled_Y'] = $this->language->get('verification_enabled_Y');
		$this->data['verification_enabled_N'] = $this->language->get('verification_enabled_N');
		$this->data['verification_type_S'] = $this->language->get('verification_type_S');
		$this->data['verification_type_O'] = $this->language->get('verification_type_O');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['merchant_code'] = $this->language->get('merchant_code');
		$this->data['request_type'] = $this->language->get('request_type');
        $this->data['verification_enabled'] = $this->language->get('verification_enabled');
        $this->data['verification_type'] = $this->language->get('verification_type');
        $this->data['key'] = $this->language->get('key');
        $this->data['iv'] = $this->language->get('iv');
        $this->data['verification_enabled'] = $this->language->get('verification_enabled');
        $this->data['verification_type'] = $this->language->get('verification_type');
        $this->data['amount'] = $this->language->get('amount');
        $this->data['bank_code'] = $this->language->get('bank_code');
        $this->data['webservice_locator'] = $this->language->get('webservice_locator');
        $this->data['order_status'] = $this->language->get('order_status');
		$this->data['order_status_confirm'] = $this->language->get('order_status_confirm');
		$this->data['order_status_complete'] = $this->language->get('order_status_complete');
		$this->data['order_status_failure'] = $this->language->get('order_status_failure');
		$this->data['order_status_cancel'] = $this->language->get('order_status_cancel');
		$this->data['order_status_abort'] = $this->language->get('order_status_abort');
        $this->data['status'] = $this->language->get('status');
        $this->data['sort_order'] = $this->language->get('sort_order');
        $this->data['merchant_scheme_code'] = $this->language->get('merchant_scheme_code');

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_ip_add'] = $this->language->get('button_ip_add');

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
			'href'      => $this->url->link('payment/cod', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/worldline', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

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

		if (isset($this->request->post['worldline_order_status_confirm'])) {
			$this->data['worldline_order_status_confirm'] = $this->request->post['worldline_order_status_confirm'];
		} else {
			$this->data['worldline_order_status_confirm'] = $this->config->get('worldline_order_status_confirm');
		}

		if (isset($this->request->post['worldline_order_status_complete'])) {
			$this->data['worldline_order_status_complete'] = $this->request->post['worldline_order_status_complete'];
		} else {
			$this->data['worldline_order_status_complete'] = $this->config->get('worldline_order_status_complete');
		}

		if (isset($this->request->post['worldline_order_status_failure'])) {
			$this->data['worldline_order_status_failure'] = $this->request->post['worldline_order_status_failure'];
		} else {
			$this->data['worldline_order_status_failure'] = $this->config->get('worldline_order_status_failure');
		}

		if (isset($this->request->post['worldline_order_status_cancel'])) {
			$this->data['worldline_order_status_cancel'] = $this->request->post['worldline_order_status_cancel'];
		} else {
			$this->data['worldline_order_status_cancel'] = $this->config->get('worldline_order_status_cancel');
		}

		if (isset($this->request->post['worldline_order_status_abort'])) {
			$this->data['worldline_order_status_abort'] = $this->request->post['worldline_order_status_abort'];
		} else {
			$this->data['worldline_order_status_abort'] = $this->config->get('worldline_order_status_abort');
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

		$this->data['button_colours'] = array(
			'orange' => $this->language->get('text_orange'),
			'tan'    => $this->language->get('text_tan')
		);

		$this->data['button_backgrounds'] = array(
			'white' => $this->language->get('text_white'),
			'light' => $this->language->get('text_light'),
			'dark'  => $this->language->get('text_dark'),
		);

		$this->data['button_sizes'] = array(
			'medium'  => $this->language->get('text_medium'),
			'large'   => $this->language->get('text_large'),
			'x-large' => $this->language->get('text_x_large'),
		);

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

	protected function validate() {
		if (!trim($this->request->post['worldline_merchant_code'])) {
			$this->error['warning']['merchant_code'] = $this->language->get('error_merchant_code');
		}

		if (!$this->request->post['worldline_request_type']) {
			$this->error['warning']['access_request_type'] = $this->language->get('error_request_type');
		}

		if (!trim($this->request->post['worldline_key'])) {
			$this->error['warning']['access_key'] = $this->language->get('error_key');
		}

		if (!trim($this->request->post['worldline_iv'])) {
		    $this->error['warning']['access_iv'] = $this->language->get('error_iv');
		}

		if (!$this->request->post['worldline_webservice_locator']) {
		    $this->error['warning']['access_webservice_locator'] = $this->language->get('error_webservice_locator');
		}

		if (!trim($this->request->post['worldline_sort_order'])) {
		    $this->error['warning']['access_sort_order'] = $this->language->get('error_sort_order');
		}

		if (!trim($this->request->post['worldline_merchant_scheme_code'])) {
		    $this->error['warning']['merchant_scheme_code'] = $this->language->get('error_merchant_scheme_code');
		}

		if (!$this->request->post['worldline_order_status_confirm']) {
			$this->error['warning']['order_status_confirm'] = $this->language->get('error_order_status_confirm');
		}

		if (!$this->request->post['worldline_order_status_complete']) {
			$this->error['warning']['order_status_complete'] = $this->language->get('error_order_status_complete');
		}

		if (!$this->request->post['worldline_order_status_failure']) {
			$this->error['warning']['order_status_failure'] = $this->language->get('error_order_status_failure');
		}

		if (!$this->request->post['worldline_order_status_cancel']) {
			$this->error['warning']['order_status_cancel'] = $this->language->get('error_order_status_cancel');
		}

		if (!$this->request->post['worldline_order_status_abort']) {
			$this->error['warning']['order_status_abort'] = $this->language->get('error_order_status_abort');
		}


		return $this->error;
	}
}
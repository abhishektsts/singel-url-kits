<?php 
class ControllerExtensionPaymentworldline extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/worldline');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
        $this->load->model('extension/payment/worldline');

        $merchant_details = $this->model_extension_payment_worldline->get();
        $data['error_warning'] = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    if(count($this->validate()) == 0){
    		    $this->model_setting_setting->editSetting('worldline', $this->request->post);
    		    $this->session->data['success'] = $this->language->get('text_success');

    		    if(is_array($merchant_details) && !isset($merchant_details[0])){
    		        $response = $this->model_extension_payment_worldline->add($this->request->post);
    		    }else{
    		        $response = $this->model_extension_payment_worldline->edit($this->request->post);
    		    }

    		    if($response === true){
    			    $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token']. '&type=payment', true));
    		    }
		    }else if (isset($this->error['warning'])) {
		        $data['error_warning'] = $this->error['warning'];
		    }
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_for_worldline'] = $this->language->get('text_for_worldline');

		//values from text box
		$data['request_type_T'] = $this->language->get('request_type_T');
		$data['verification_enabled_Y'] = $this->language->get('verification_enabled_Y');
		$data['verification_enabled_N'] = $this->language->get('verification_enabled_N');
		$data['verification_type_S'] = $this->language->get('verification_type_S');
		$data['verification_type_O'] = $this->language->get('verification_type_O');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['merchant_code'] = $this->language->get('merchant_code');
		$data['request_type'] = $this->language->get('request_type');
        $data['verification_enabled'] = $this->language->get('verification_enabled');
        $data['verification_type'] = $this->language->get('verification_type');
        $data['key'] = $this->language->get('key');
        $data['iv'] = $this->language->get('iv');
        $data['verification_enabled'] = $this->language->get('verification_enabled');
        $data['verification_type'] = $this->language->get('verification_type');
        $data['amount'] = $this->language->get('amount');
        $data['bank_code'] = $this->language->get('bank_code');
        $data['webservice_locator'] = $this->language->get('webservice_locator');
        $data['order_status'] = $this->language->get('order_status');
		$data['order_status_confirm'] = $this->language->get('order_status_confirm');
		$data['order_status_complete'] = $this->language->get('order_status_complete');
		$data['order_status_failure'] = $this->language->get('order_status_failure');
		$data['order_status_cancel'] = $this->language->get('order_status_cancel');
		$data['order_status_abort'] = $this->language->get('order_status_abort');
        $data['status'] = $this->language->get('status');
        $data['sort_order'] = $this->language->get('sort_order');
        $data['merchant_scheme_code'] = $this->language->get('merchant_scheme_code');

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/worldline', 'token=' . $this->session->data['token'], true)
		);
		
		$data['action'] = $this->url->link('extension/payment/worldline', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

	    if (isset($this->request->post['worldline_merchant_code'])) {
		    $data['worldline_merchant_code'] = $this->request->post['worldline_merchant_code'];
		} else {
		    $data['worldline_merchant_code'] = $this->config->get('worldline_merchant_code');
		}

		if (isset($this->request->post['worldline_request_type'])) {
		    $data['worldline_request_type'] = $this->request->post['worldline_request_type'];
		} else {
		    $data['worldline_request_type'] = $this->config->get('worldline_request_type');
		}

		if (isset($this->request->post['worldline_key'])) {
		    $data['worldline_key'] = $this->request->post['worldline_key'];
		} else {
		    $data['worldline_key'] = $this->config->get('worldline_key');
		}

		if (isset($this->request->post['worldline_iv'])) {
		    $data['worldline_iv'] = $this->request->post['worldline_iv'];
		} else {
		    $data['worldline_iv'] = $this->config->get('worldline_iv');
		}

		if (isset($this->request->post['worldline_webservice_locator'])) {
		    $data['worldline_webservice_locator'] = $this->request->post['worldline_webservice_locator'];
		} else {
		    $data['worldline_webservice_locator'] = $this->config->get('worldline_webservice_locator');
		}

		if (isset($this->request->post['worldline_order_status'])) {
		    $data['worldline_order_status'] = $this->request->post['worldline_order_status'];
		} else {
		    $data['worldline_order_status'] = $this->config->get('worldline_order_status');
		}

		if (isset($this->request->post['worldline_order_status_confirm'])) {
			$data['worldline_order_status_confirm'] = $this->request->post['worldline_order_status_confirm'];
		} else {
			$data['worldline_order_status_confirm'] = $this->config->get('worldline_order_status_confirm');
		}

		if (isset($this->request->post['worldline_order_status_complete'])) {
			$data['worldline_order_status_complete'] = $this->request->post['worldline_order_status_complete'];
		} else {
			$data['worldline_order_status_complete'] = $this->config->get('worldline_order_status_complete');
		}

		if (isset($this->request->post['worldline_order_status_failure'])) {
			$data['worldline_order_status_failure'] = $this->request->post['worldline_order_status_failure'];
		} else {
			$data['worldline_order_status_failure'] = $this->config->get('worldline_order_status_failure');
		}

		if (isset($this->request->post['worldline_order_status_cancel'])) {
			$data['worldline_order_status_cancel'] = $this->request->post['worldline_order_status_cancel'];
		} else {
			$data['worldline_order_status_cancel'] = $this->config->get('worldline_order_status_cancel');
		}

		if (isset($this->request->post['worldline_order_status_abort'])) {
			$data['worldline_order_status_abort'] = $this->request->post['worldline_order_status_abort'];
		} else {
			$data['worldline_order_status_abort'] = $this->config->get('worldline_order_status_abort');
		}

		if (isset($this->request->post['worldline_status'])) {
		    $data['worldline_status'] = $this->request->post['worldline_status'];
		} else {
		    $data['worldline_status'] = $this->config->get('worldline_status');
		}

		if (isset($this->request->post['worldline_sort_order'])) {
		    $data['worldline_sort_order'] = $this->request->post['worldline_sort_order'];
		} else {
		    $data['worldline_sort_order'] = $this->config->get('worldline_sort_order');
		}

		if (isset($this->request->post['worldline_merchant_scheme_code'])) {
		    $data['worldline_merchant_scheme_code'] = $this->request->post['worldline_merchant_scheme_code'];
		} else {
		    $data['worldline_merchant_scheme_code'] = $this->config->get('worldline_merchant_scheme_code');
		}

		$data['button_colours'] = array(
			'orange' => $this->language->get('text_orange'),
			'tan'    => $this->language->get('text_tan')
		);

		$data['button_backgrounds'] = array(
			'white' => $this->language->get('text_white'),
			'light' => $this->language->get('text_light'),
			'dark'  => $this->language->get('text_dark'),
		);

		$data['button_sizes'] = array(
			'medium'  => $this->language->get('text_medium'),
			'large'   => $this->language->get('text_large'),
			'x-large' => $this->language->get('text_x_large'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/payment/worldline.tpl', $data));
	}

	public function install() {
		$this->load->model('extension/payment/worldline');
		$this->model_extension_payment_worldline->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/worldline');
		$this->model_extension_payment_worldline->uninstall();
	}

	protected function validate() {
		if (!trim($this->request->post['worldline_merchant_code'])) {
			$this->error['warning']['merchant_code'] = $this->language->get('error_merchant_code');
		}

		if (!trim($this->request->post['worldline_request_type'])) {
			$this->error['warning']['request_type'] = $this->language->get('error_request_type');
		}

		if (!trim($this->request->post['worldline_key'])) {
			$this->error['warning']['key'] = $this->language->get('error_key');
		}

		if (!trim($this->request->post['worldline_iv'])) {
		    $this->error['warning']['iv'] = $this->language->get('error_iv');
		}

		if (!trim($this->request->post['worldline_webservice_locator'])) {
		    $this->error['warning']['webservice_locator'] = $this->language->get('error_webservice_locator');
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

		if (!trim($this->request->post['worldline_sort_order'])) {
		    $this->error['warning']['sort_order'] = $this->language->get('error_sort_order');
		}

		if (!trim($this->request->post['worldline_merchant_scheme_code'])) {
		    $this->error['warning']['merchant_scheme_code'] = $this->language->get('error_merchant_scheme_code');
		}

		return $this->error;
	}
}
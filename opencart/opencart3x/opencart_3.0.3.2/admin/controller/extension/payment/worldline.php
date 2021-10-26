<?php
class ControllerExtensionPaymentworldline extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('extension/payment/worldline');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/payment/worldline');

		$merchant_details = $this->model_extension_payment_worldline->get();

		$data['error_warning'] = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (count($this->validate()) == 0) {


				$this->model_setting_setting->editSetting('payment_worldline', $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');

				if (is_array($merchant_details) && !isset($merchant_details[0])) {
					$response = $this->model_extension_payment_worldline->add($this->request->post);
				} else {
					$response = $this->model_extension_payment_worldline->edit($this->request->post);
				}

				if ($response === true) {
					$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
				}
			} else if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_for_worldline'] = $this->language->get('text_for_worldline');
		//values from text box
		$data['request_type_T'] = $this->language->get('request_type_T');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['merchant_code'] = $this->language->get('merchant_code');
		$data['request_type'] = $this->language->get('request_type');;
		$data['key'] = $this->language->get('key');
		$data['iv'] = $this->language->get('iv');
		$data['amount'] = $this->language->get('amount');
		$data['bank_code'] = $this->language->get('bank_code');
		$data['webservice_locator'] = $this->language->get('webservice_locator');
		$data['order_status'] = $this->language->get('order_status');
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/worldline', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/payment/worldline', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/payment', 'user_token=' . $this->session->data['user_token'], true);


		if (isset($this->request->post['worldline_merchant_code'])) {
			$data['worldline_merchant_code'] = $this->request->post['worldline_merchant_code'];
		} else {
			$data['worldline_merchant_code'] = $this->config->get('worldline_merchant_code');
		}

		if (isset($this->request->post['payment_worldline_request_type'])) {
			$data['payment_worldline_request_type'] = $this->request->post['payment_worldline_request_type'];
		} else {
			$data['payment_worldline_request_type'] = $this->config->get('payment_worldline_request_type');
		}

		if (isset($this->request->post['payment_worldline_key'])) {
			$data['payment_worldline_key'] = $this->request->post['payment_worldline_key'];
		} else {
			$data['payment_worldline_key'] = $this->config->get('payment_worldline_key');
		}

		if (isset($this->request->post['payment_worldline_iv'])) {
			$data['payment_worldline_iv'] = $this->request->post['payment_worldline_iv'];
		} else {
			$data['payment_worldline_iv'] = $this->config->get('payment_worldline_iv');
		}

		if (isset($this->request->post['payment_worldline_webservice_locator'])) {
			$data['payment_worldline_webservice_locator'] = $this->request->post['payment_worldline_webservice_locator'];
		} else {
			$data['payment_worldline_webservice_locator'] = $this->config->get('payment_worldline_webservice_locator');
		}

		if (isset($this->request->post['payment_worldline_order_status'])) {
			$data['payment_worldline_order_status'] = $this->request->post['payment_worldline_order_status'];
		} else {
			$data['payment_worldline_order_status'] = $this->config->get('payment_worldline_order_status');
		}

		if (isset($this->request->post['payment_worldline_status'])) {
			$data['payment_worldline_status'] = $this->request->post['payment_worldline_status'];
		} else {
			$data['payment_worldline_status'] = $this->config->get('payment_worldline_status');
		}

		if (isset($this->request->post['payment_worldline_sort_order'])) {
			$data['payment_worldline_sort_order'] = $this->request->post['payment_worldline_sort_order'];
		} else {
			$data['payment_worldline_sort_order'] = $this->config->get('payment_worldline_sort_order');
		}

		if (isset($this->request->post['payment_worldline_merchant_scheme_code'])) {
			$data['payment_worldline_merchant_scheme_code'] = $this->request->post['payment_worldline_merchant_scheme_code'];
		} else {
			$data['payment_worldline_merchant_scheme_code'] = $this->config->get('payment_worldline_merchant_scheme_code');
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
		$this->response->setOutput($this->load->view('extension/payment/worldline', $data));
	}

	public function install()
	{
		if ($this->user->hasPermission('modify', 'marketplace/extension')) {
			$this->load->model('extension/payment/worldline');
			$this->model_extension_payment_worldline->install();
		}
	}

	public function uninstall()
	{
		if ($this->user->hasPermission('modify', 'marketplace/extension')) {
			$this->load->model('extension/payment/worldline');
			$this->model_extension_payment_worldline->uninstall();
		}
	}

	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/payment/worldline')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!trim($this->request->post['worldline_merchant_code'])) {
			$this->error['warning']['merchant_code'] = $this->language->get('error_merchant_code');
		}

		if (!trim($this->request->post['payment_worldline_request_type'])) {
			$this->error['warning']['access_request_type'] = $this->language->get('error_request_type');
		}

		if (!trim($this->request->post['payment_worldline_key'])) {
			$this->error['warning']['access_key'] = $this->language->get('error_key');
		}

		if (!trim($this->request->post['payment_worldline_iv'])) {
			$this->error['warning']['access_iv'] = $this->language->get('error_iv');
		}

		if (!trim($this->request->post['payment_worldline_webservice_locator'])) {
			$this->error['warning']['access_webservice_locator'] = $this->language->get('error_webservice_locator');
		}

		if (!$this->request->post['payment_worldline_order_status']) {
			$this->error['warning']['access_order_status'] = $this->language->get('error_order_status');
		}

		if (!trim($this->request->post['payment_worldline_sort_order'])) {
			$this->error['warning']['access_sort_order'] = $this->language->get('error_sort_order');
		}

		if (!trim($this->request->post['payment_worldline_merchant_scheme_code'])) {
			$this->error['warning']['merchant_scheme_code'] = $this->language->get('error_merchant_scheme_code');
		}

		return $this->error;
	}
}

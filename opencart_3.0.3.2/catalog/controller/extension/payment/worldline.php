<?php

require_once DIR_APPLICATION . 'lib/TransactionRequestBean.php';
require_once DIR_APPLICATION . 'lib/TransactionResponseBean.php';

class ControllerExtensionPaymentworldline extends Controller
{
	public function index()
	{

		$this->load->model('extension/payment/worldline');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['continue'] = $this->url->link('checkout/success');

		$shipping_cost = $this->session->data['shipping_method']['cost'];
		$products = $this->cart->getProducts();
		$total_prod_count = count($products);
		$total_prod_init = 0;
		$actual_cost = 0;
		while ($total_prod_init < $total_prod_count) {
			$actual_cost += $products[$total_prod_init]['total'];
			$total_prod_init++;
		}
		$amount = $shipping_cost + $actual_cost;

		// Totalings
		$this->load->model('setting/extension');

		$totaling_data = array();
		$totaling = 0;
		$taxes = $this->cart->getTaxes();



		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('totaling');


			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('totaling/' . $result['code']);

					$this->{'model_totaling_' . $result['code']}->getTotaling($totaling_data, $totaling, $taxes);
				}
			}


			$sort_order = array();


			foreach ($totaling_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totaling_data);
		}

		foreach ($totaling_data as $totaling) {
			if ($totaling['title'] == 'Totaling') {
				$amount = $totaling['value'];
			}
		}


		$transactionRequestBean = new TransactionRequestBean();
		$merchant_details = $this->model_extension_payment_worldline->get();
		$merchant_txn_id = rand(1, 1000000);
		$cur_date = date("d-m-Y");
		$returnUrl = $this->url->link('extension/payment/worldline/getResponse');

		//Setting all values here
		$transactionRequestBean->setMerchantCode($merchant_details[0]['merchant_code']);
		$transactionRequestBean->setRequestType($merchant_details[0]['request_type']);
		$transactionRequestBean->setMerchantTxnRefNumber($merchant_txn_id);
		$transactionRequestBean->setAmount($amount);
		$shoppingCartStr = $merchant_details[0]['merchant_scheme_code'] . '_' . $amount . '_0.0';
		$transactionRequestBean->setShoppingCartDetails($shoppingCartStr);
		$transactionRequestBean->setWebServiceLocator('https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl');

		if ($merchant_details[0]['webservice_locator'] == 'Test') {
			$transactionRequestBean->setBankCode('470');
		}

		if (isset($this->session->data['shipping_address'])) {
			$transactionRequestBean->setCustomerName($this->session->data['shipping_address']['firstname'] . ' ' . $this->session->data['shipping_address']['lastname']);
		}

		$transactionRequestBean->setReturnURL($returnUrl);
		$transactionRequestBean->setTxnDate($cur_date);
		$transactionRequestBean->setKey($merchant_details[0]['key']);
		$transactionRequestBean->setIv($merchant_details[0]['iv']);

		$data['url'] = $transactionRequestBean->getTransactionToken();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'extension/payment/worldline')) {
			return $this->load->view($this->config->get('config_template') . 'extension/payment/worldline', $data);
		} else {
			return $this->load->view('extension/payment/worldline', $data);
		}
	}

	public function getResponse()
	{
		if ($_POST) {

			$response = $_POST;
			$this->load->model('extension/payment/worldline');
			$merchant_details = $this->model_extension_payment_worldline->get();

			if (is_array($response)) {
				$str = $response['msg'];
			} else if (is_string($response) && strstr($response, 'msg=')) {
				$outputStr = str_replace('msg=', '', $response);
				$outputArr = explode('&', $outputStr);
				$str = $outputArr[0];
			} else {
				$str = $response;
			}

			$transactionResponseBean = new TransactionResponseBean();
			$transactionResponseBean->setResponsePayload($str);
			$transactionResponseBean->setKey($merchant_details[0]['key']);
			$transactionResponseBean->setIv($merchant_details[0]['iv']);

			$response = $transactionResponseBean->getResponsePayload();
			$response1 = explode('|', $response);

			$firstToken = explode('=', $response1[0]);
			$status = $firstToken[1];

			if ($status == '300') {
				echo "<script>window.location = '" . $this->url->link('checkout/success') . "'</script>";
			} else {
				$this->session->data['error'] = 'Payment Failed';
				$this->load->model('checkout/order');
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 10);
				echo "<script>window.location = '" . $this->url->link('checkout/cart') . "'</script>";
			}
		}
	}


	public function confirm()
	{
		if ($this->session->data['payment_method']['code'] == 'worldline') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_worldline_order_status'));
		}
	}
}

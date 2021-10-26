<?php
require_once __DIR__.'/../../lib/TransactionRequestBean.php';
require_once __DIR__.'/../../lib/TransactionResponseBean.php';

class ControllerPaymentworldline extends Controller {
	public function index() {
	    $this->load->model('payment/worldline');
		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');

		// Totals
		$this->load->model('extension/extension');

		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
		    $sort_order = array();

		    $results = $this->model_extension_extension->getExtensions('total');

		    foreach ($results as $key => $value) {
		        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		    }

		    array_multisort($sort_order, SORT_ASC, $results);

		    foreach ($results as $result) {
		        if ($this->config->get($result['code'] . '_status')) {
		            $this->load->model('total/' . $result['code']);

		            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
		        }
		    }

		    $sort_order = array();

		    foreach ($total_data as $key => $value) {
		        $sort_order[$key] = $value['sort_order'];
		    }

		    array_multisort($sort_order, SORT_ASC, $total_data);
		}

		foreach ($total_data as $total) {
		    if($total['title'] == 'Total') {
		        $amount = $total['value'];
		    }
		}

		$transactionRequestBean = new TransactionRequestBean();
		$merchant_details = $this->model_payment_worldline->get();
		$merchant_txn_id = rand(1,1000000);
		$cur_date = date("d-m-Y");
		$returnUrl = $this->url->link('payment/worldline/getResponse');

		//Setting all values here
		$transactionRequestBean->setMerchantCode($merchant_details[0]['merchant_code']);
		$transactionRequestBean->setRequestType($merchant_details[0]['request_type']);
		$transactionRequestBean->setMerchantTxnRefNumber($merchant_txn_id);

        if ($merchant_details[0]['webservice_locator'] == 'Test') {
            $transactionRequestBean->setAmount('1.00');
            $transactionRequestBean->setBankCode('470');
            $transactionRequestBean->setWebServiceLocator(
                'https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl');
            $transactionRequestBean->setShoppingCartDetails('test_1.00_0.0');
        } else {
            $transactionRequestBean->setAmount($amount);
            $transactionRequestBean->setWebServiceLocator(
                'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl');
            $shoppingCartStr = $merchant_details[0]['merchant_scheme_code'] . '_' . $amount . '_0.0';
            $transactionRequestBean->setShoppingCartDetails($shoppingCartStr);
        }

        if(isset($this->session->data['shipping_address'])){
            $transactionRequestBean->setCustomerName($this->session->data['shipping_address']['firstname']. ' '.$this->session->data['shipping_address']['lastname']);
        }

		$transactionRequestBean->setReturnURL($returnUrl);

		$transactionRequestBean->setTxnDate($cur_date);
		$transactionRequestBean->setKey($merchant_details[0]['key']);
		$transactionRequestBean->setIv($merchant_details[0]['iv']);

		//Set customer details
		$this->load->model('account/customer');

		if(isset($this->session->data['customer_id']) && !empty($this->session->data['customer_id'])){
			$customerDetails = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
			$transactionRequestBean->setUniqueCustomerId($customerDetails['customer_id']);
		}else{
			$customerDetails = $this->session->data['guest'];
			$transactionRequestBean->setUniqueCustomerId($customerDetails['customer_group_id']);
		}
		$transactionRequestBean->setITC('email:'.$customerDetails['email']);
		$transactionRequestBean->setEmail($customerDetails['email']);
		$transactionRequestBean->setMobileNumber($customerDetails['telephone']);
		$customerName = $customerDetails['firstname']. " ". $customerDetails['lastname'];
		$transactionRequestBean->setCustomerName($customerName);

		$this->data['url'] = $transactionRequestBean->getTransactionToken();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/worldline.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/worldline.tpl';
		} else {
			$this->template = 'default/template/payment/worldline.tpl';
		}

		$this->render();
	}

	public function getResponse() {
	    if($_POST){

	        $response = $_POST;
	        $this->load->model('payment/worldline');
	        $merchant_details = $this->model_payment_worldline->get();

	        if(is_array($response)){
	            $str = $response['msg'];
	        }else if(is_string($response) && strstr($response, 'msg=')){
	            $outputStr = str_replace('msg=', '', $response);
	            $outputArr = explode('&', $outputStr);
	            $str = $outputArr[0];
	        }else {
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

	        if($status == '300') {
	            echo "<script>window.location = '".$this->url->link('checkout/success')."'</script>";
	        } else {
	            echo "<script>window.location = '".$this->url->link('checkout/failure')."'</script>";
	        }
	    }
	}

	public function confirm() {
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('worldline_order_status'));
	}
}
<?php
/*
Plugin Name: Worldline
Plugin URI: 
Description: Worldline ePayments is India's leading digital payment solutions company. Being a company with more than 45 years of global payment experience, we are present in India for over 20 years and are powering over 550,000 businesses with our tailored payment solution.
Version: 1.0
Author: Worldline
Author URI: https://www.worldline.com 
*/
require_once __DIR__ . '/includes/TransactionRequestBean.php';
require_once __DIR__ . '/includes/TransactionResponseBean.php';
if (!defined('ABSPATH'))
	exit;
add_action('plugins_loaded', 'woocommerce_worldline_init', 0);

function woocommerce_worldline_init()
{
	if (!class_exists('WC_Payment_Gateway')) return;
	class WC_worldline extends WC_Payment_Gateway
	{

		public function __construct()
		{

			$this->id           = 'worldline';
			$this->method_title = __('Worldline', 'worldline');
			$this->method_description = __("Worldline ePayments is India's leading digital payment solutions company. Being a company with more than 45 years of global payment experience, we are present in India for over 20 years and are powering over 550,000 businesses with our tailored payment solution.", 'worldline');
			$this->icon         =  plugins_url('images/worldline-checkout.png', __FILE__);
			$this->has_fields   = false;

			$this->init_form_fields();
			$this->init_settings();
			$this->title = $this->settings['title'];
			$this->description      = $this->settings['description'];
			$this->worldline_merchant_code      = $this->settings['worldline_merchant_code'];
			$this->worldline_request_type      = $this->settings['worldline_request_type'];
			$this->worldline_key      = $this->settings['worldline_key'];
			$this->worldline_iv      = $this->settings['worldline_iv'];
			$this->worldline_webservice_locator      = $this->settings['worldline_webservice_locator'];
			$this->worldline_merchant_scheme_code      = $this->settings['worldline_merchant_scheme_code'];
			$this->worldline_redirect_msg      = $this->settings['worldline_redirect_msg'];
			$this->worldline_decline_msg      = $this->settings['worldline_decline_msg'];
			$this->worldline_success_msg      = $this->settings['worldline_success_msg'];
			if ($this->validate_fields()) {
				if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
					add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
				} else {
					add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
				}
			} else {
				$this->settings['title'] = $_POST['woocommerce_worldline_title'];
				$this->settings['description']      = $_POST['woocommerce_worldline_description'];
				$this->settings['worldline_merchant_code']      = $_POST['woocommerce_worldline_worldline_merchant_code'];
				$this->settings['worldline_request_type']     = $_POST['woocommerce_worldline_worldline_request_type'];
				$this->settings['worldline_key']      = $_POST['woocommerce_worldline_worldline_key'];
				$this->settings['worldline_iv']     = $_POST['woocommerce_worldline_worldline_iv'];
				$this->settings['worldline_webservice_locator']      = $_POST['woocommerce_worldline_worldline_webservice_locator'];
				$this->settings['worldline_merchant_scheme_code']     = $_POST['woocommerce_worldline_worldline_merchant_scheme_code'];
				$this->settings['worldline_redirect_msg']     = $_POST['woocommerce_worldline_worldline_redirect_msg'];
				$this->settings['worldline_decline_msg']      = $_POST['woocommerce_worldline_worldline_decline_msg'];
				$this->settings['worldline_success_msg']      = $_POST['woocommerce_worldline_worldline_success_msg'];
			}

			if ($this->worldline_webservice_locator == 'Test') {
				$this->liveurl  = 'https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl';
			} else {
				$this->liveurl  = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';
			}
			$this->notify_url = WC()->api_request_url('WC_worldline');
			$this->msg['message'] = "";
			$this->msg['class']   = "";

			add_action('woocommerce_api_wc_worldline', array($this, 'check_worldline_response'));
			add_action('valid-worldline-request', array($this, 'successful_request'));

			add_action('woocommerce_receipt_worldline', array($this, 'receipt_page'));
			add_action('woocommerce_thankyou_worldline', array($this, 'thankyou_page'));
			add_action('init', 'register_session');
		}

		function register_session()
		{
			if (!session_id())
				session_start();
		}

		public function validate_fields()
		{
			$validate = true;
			if (!empty($_POST) && $_SESSION['validation_status'] != 'Recorded') {
				if (!trim($_POST['woocommerce_worldline_title'])) {
					WC_Admin_Settings::add_error("Title can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_description'])) {
					WC_Admin_Settings::add_error("Description can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_merchant_code'])) {
					WC_Admin_Settings::add_error("Merchant Code can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_request_type'])) {
					WC_Admin_Settings::add_error("Request Type can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_key'])) {
					WC_Admin_Settings::add_error("Key can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_iv'])) {
					WC_Admin_Settings::add_error("IV can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_webservice_locator'])) {
					WC_Admin_Settings::add_error("Webservice Locator can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_merchant_scheme_code'])) {
					WC_Admin_Settings::add_error("Merchant Scheme Code can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_success_msg'])) {
					WC_Admin_Settings::add_error("Success Message can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_decline_msg'])) {
					WC_Admin_Settings::add_error("Decline Message can not be null");
					$validate  = false;
				}
				if (!trim($_POST['woocommerce_worldline_worldline_redirect_msg'])) {
					WC_Admin_Settings::add_error("Redirect Message can not be null");
					$validate  = false;
				}
			} elseif ($_SESSION['validation_status'] == 'Recorded') {
				$validate  = false;
			}
			if ($validate  == false) {
				$_SESSION['validation_status'] = 'Recorded';
			}

			return $validate;
		}
		function init_form_fields()
		{

			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'worldline'),
					'type' => 'checkbox',
					'label' => __('Enable worldline Payment Module.', 'worldline'),
					'default' => 'no'
				),
				'title' => array(
					'title' => __('<span style="color: #a00;">* </span>Title:', 'worldline'),
					'type' => 'text',
					'id' => "title",
					'desc_tip'    => true,
					'placeholder' => __('worldline', 'woocommerce'),
					'description' => __('Your desire title name .it will show during checkout proccess.', 'worldline'),
					'default' => __('Cards / UPI / Netbanking / Wallets', 'worldline')
				),
				'description' => array(
					'title' => __('<span style="color: #a00;">* </span>Description:', 'worldline'),
					'type' => 'textarea',
					'desc_tip'    => true,
					'placeholder' => __('Description', 'woocommerce'),
					'description' => __('Pay securely through worldline.', 'worldline'),
					'default' => __('Pay securely through worldline.', 'worldline')
				),
				'worldline_merchant_code' => array(
					'title' => __('<span style="color: #a00;">* </span>Merchant Code', 'worldline'),
					'type' => 'text',
					'desc_tip'    => true,
					'placeholder' => __('Merchant Code', 'woocommerce'),
					'description' => __('Merchant Code')
				),
				'worldline_request_type' => array(
					'title'       => __('<span style="color: #a00;">* </span>Request Type', 'woocommerce'),
					'type'        => 'select',
					'class'    => 'chosen_select',
					'css'      => 'min-width:350px;',
					'description' => __('Choose request type.', 'woocommerce'),
					'default'     => 'T',
					'desc_tip'    => true,
					'options'     => array(
						'T'          => __('T', 'woocommerce'),
					)
				),
				'worldline_key' => array(
					'title' => __('<span style="color: #a00;">* </span>Key', 'worldline'),
					'type' => 'text',
					'desc_tip'    => true,
					'placeholder' => __('Key', 'woocommerce'),
					'description' => __('Key')
				),
				'worldline_iv' => array(
					'title' => __('<span style="color: #a00;">* </span>IV', 'worldline'),
					'type' => 'text',
					'desc_tip'    => true,
					'placeholder' => __('IV', 'woocommerce'),
					'description' => __('IV')
				),
				'worldline_webservice_locator' => array(
					'title'       => __('<span style="color: #a00;">* </span>Webservice Locator', 'woocommerce'),
					'type'        => 'select',
					'class'    => 'chosen_select',
					'css'      => 'min-width:350px;',
					'description' => __('Choose Webservice Locator.', 'woocommerce'),
					'default'     => 'Test',
					'desc_tip'    => true,
					'options'     => array(
						'Test'          => __('TEST', 'woocommerce'),
						'Live'          => __('LIVE', 'woocommerce'),
					)
				),
				'worldline_merchant_scheme_code' => array(
					'title' => __('<span style="color: #a00;">* </span>Merchant Scheme Code', 'worldline'),
					'type' => 'text',
					'desc_tip'    => true,
					'placeholder' => __('Merchant Scheme Code', 'woocommerce'),
					'description' => __('Merchant Scheme Code')
				),
				'worldline_success_msg' => array(
					'title' => __('<span style="color: #a00;">* </span>Success Message', 'worldline'),
					'type' => 'textarea',
					'desc_tip'    => true,
					'default' => 'Thank you for shopping with us. Your account has been charged and your transaction is successful.',
					'description' => __('Success Message')
				),
				'worldline_decline_msg' => array(
					'title' => __('<span style="color: #a00;">* </span>Decline Message', 'worldline'),
					'type' => 'textarea',
					'desc_tip'    => true,
					'default' => 'Thank you for shopping with us. However, the transaction has been declined.',
					'description' => __('Decline Message')
				),
				'worldline_redirect_msg' => array(
					'title' => __('<span style="color: #a00;">* </span>Redirect Message', 'worldline'),
					'type' => 'textarea',
					'desc_tip'    => true,
					'default' => 'Thank you for your order. We are now redirecting you to worldline to make payment.',
					'description' => __('Redirect Message')
				),

			);
		}

		public function admin_options()
		{
			echo '<h3>' . __('Worldline Payment Gateway', 'worldline') . '</h3>';
			$_SESSION['validation_status'] = 'Displayed';
?>
			<a href="#" target="_blank"><img src="<?php echo $this->icon = plugins_url('images/worldline.png', __FILE__); ?>" /></a>
<?php
			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';
		}

		function payment_fields()
		{
			if ($this->description) echo wpautop(wptexturize($this->description));
		}

		function receipt_page($order)
		{
			echo '<p>' . __('Thank you for your order, please click the button below to pay with worldline.', 'worldline') . '</p>';
			echo $this->generate_worldline_form($order);
		}

		function process_payment($order_id)
		{
			$order = new WC_Order($order_id);
			return array('result' => 'success', 'redirect' => $order->get_checkout_payment_url(true));
		}

		function check_worldline_response()
		{
			global $woocommerce;

			$msg['class']   = 'error';
			$msg['message'] = $this->worldline_decline_msg;
			$cancelInProccess = false;

			if ($_POST) {
				$response = $_POST;
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
				$transactionResponseBean->setKey($this->worldline_key);
				$transactionResponseBean->setIv($this->worldline_iv);

				$response = $transactionResponseBean->getResponsePayload();

				$response1 = explode('|', $response);
				$firstToken = explode('=', $response1[0]);

				$oid = explode('orderid:', $response1[7]);
				$oid_1 = $oid[1];
				$oid_2 = rtrim($oid_1, "}");
				$status = $firstToken[1];
				$order_id = $oid_2;
				$order = new WC_Order($order_id);
				$transauthorised = false;
				if ($order_id != '') {
					try {
						if ($order->status !== 'completed') {
							if ($status == '300') {
								$transauthorised = true;
								$msg['message'] = $this->worldline_success_msg;
								$msg['class'] = 'success';

								if ($order->status != 'processing') {
									$order->payment_complete();
								}
								$woocommerce->cart->empty_cart();
							} else {
								$cancelInProccess = true;
								$order->update_status('cancelled');
								$msg['class'] = 'error';
								$msg['message'] = $this->worldline_decline_msg;
							}
						}
					} catch (Exception $e) {
						$cancelInProccess = true;
						$msg['class'] = 'error';
						$msg['message'] = $this->worldline_decline_msg;
					}
				}
			}

			if (function_exists('wc_add_notice')) {
				wc_add_notice($msg['message'], $msg['class']);
			} else {
				if ($msg['class'] == 'success') {
					$woocommerce->add_message($msg['message']);
				} else {
					$woocommerce->add_error($msg['message']);
				}
				$woocommerce->set_messages();
			}

			if ($cancelInProccess) {
				$redirect_url = wc_get_checkout_url();
				wc_add_notice('Payment Cancel', 'error');
				wp_redirect($redirect_url);
				exit;
			} else {
				$redirect_url = get_permalink(woocommerce_get_page_id('myaccount'));
				wp_redirect($redirect_url);
				exit;
			}
		}

		public function generate_worldline_form($order_id)
		{
			global $woocommerce;
			$order = new WC_Order($order_id);
			WC()->session->set('order_id', $order_id);
			$order_id = $order_id . '_' . date("ymds");
			$transactionRequestBean = new TransactionRequestBean();

			$merchant_txn_id = rand(1, 1000000);
			$cur_date = date("d-m-Y");
			$returnUrl = $this->notify_url;
			$transactionRequestBean->setMerchantCode($this->worldline_merchant_code);
			$transactionRequestBean->setRequestType($this->worldline_request_type);
			$transactionRequestBean->setMerchantTxnRefNumber($merchant_txn_id);

			if ($this->worldline_webservice_locator == 'Test') {
				$transactionRequestBean->setAmount('1.00');
				$transactionRequestBean->setBankCode('470');
				$transactionRequestBean->setWebServiceLocator('https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl');
				$transactionRequestBean->setShoppingCartDetails($this->worldline_merchant_scheme_code . '_1.0_0.0');
			} else {
				$transactionRequestBean->setAmount($order->order_total);
				$transactionRequestBean->setWebServiceLocator('https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl');
				$shoppingCartStr = $this->worldline_merchant_scheme_code . '_' . $order->order_total . '_0.0';
				$transactionRequestBean->setShoppingCartDetails($shoppingCartStr);
			}
			if (isset($order->shipping_first_name) && isset($order->shipping_last_name)) {
				$transactionRequestBean->setCustomerName($order->shipping_first_name . ' ' . $order->shipping_last_name);
			}
			$transactionRequestBean->setReturnURL($returnUrl);

			$transactionRequestBean->setTxnDate($cur_date);
			$transactionRequestBean->setKey($this->worldline_key);
			$transactionRequestBean->setIv($this->worldline_iv);
			$customer_id = WC_Session_Handler::generate_customer_id();
			$transactionRequestBean->setUniqueCustomerId($customer_id);
			$transactionRequestBean->setITC('email:' . $order->billing_email);
			$transactionRequestBean->setEmail($order->billing_email);
			$transactionRequestBean->setMobileNumber($order->billing_phone);
			$customerName = $order->billing_first_name . " " . $order->billing_last_name;
			$transactionRequestBean->setCustomerName($customerName);

			$url = $transactionRequestBean->getTransactionToken();

			wc_enqueue_js('
				$.blockUI({
				message: "' . esc_js(__($this->worldline_redirect_msg, 'woocommerce')) . '",
				baseZ: 99999,
				overlayCSS:
				{
				background: "#fff",
				opacity: 1.0
			},
				css: {
				padding:        "20px",
				zindex:         "9999999",
				textAlign:      "center",
				color:          "#555",
				border:         "3px solid #aaa",
				backgroundColor:"#fff",
				cursor:         "wait",
				lineHeight:     "24px",
			}
			});
				jQuery("#submit_worldline_payment_form").click();
				');

			$form = '<form action="' . esc_url($url) . '" method="post" id="worldline_payment_form" target="_top">
			
			<!-- Button Fallback -->
			<div class="payment_buttons">
			<input type="submit" class="button alt" id="submit_worldline_payment_form" value="' . __('Pay via worldline', 'woocommerce') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woocommerce') . '</a>
			</div>
			<script type="text/javascript">
			jQuery(".payment_buttons").hide();
			</script>
			</form>';
			return $form;
		}

		function get_pages($title = false, $indent = true)
		{
			$wp_pages = get_pages('sort_column=menu_order');
			$page_list = array();
			if ($title) $page_list[] = $title;
			foreach ($wp_pages as $page) {
				$prefix = '';
				// show indented child pages?
				if ($indent) {
					$has_parent = $page->post_parent;
					while ($has_parent) {
						$prefix .=  ' - ';
						$next_page = get_page($has_parent);
						$has_parent = $next_page->post_parent;
					}
				}
				// add to page list array array
				$page_list[$page->ID] = $prefix . $page->post_title;
			}
			return $page_list;
		}
	}
	/**
	 * Add the Gateway to WooCommerce
	 **/
	function woocommerce_add_worldline_gateway($methods)
	{
		$methods[] = 'WC_worldline';
		return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_worldline_gateway');
}

?>
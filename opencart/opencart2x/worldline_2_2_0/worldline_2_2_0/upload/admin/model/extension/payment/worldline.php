<?php

class ModelExtensionPaymentworldline extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "worldline` (
			  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              `merchant_code` varchar(255) NOT NULL,
              `request_type` char(50) NOT NULL,
              `key` varchar(255) NOT NULL,
              `iv` varchar(255) NOT NULL,
              `webservice_locator` varchar(255) NOT NULL,
              `order_status_confirm` varchar(255) NOT NULL,
              `order_status_complete` varchar(255) NOT NULL,
              `order_status_failure` varchar(255) NOT NULL,
              `order_status_cancel` varchar(255) NOT NULL,
              `order_status_abort` varchar(255) NOT NULL,
              `status` enum('1','0') NOT NULL,
              `sort_order` int(10) NOT NULL,
		      `merchant_scheme_code` varchar(255) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `unique_merchant_code` (`merchant_code`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "worldline`;");
	}

	public function add($merchant_details) {
	    if(count($merchant_details) > 0){
		    $this->db->query("INSERT INTO `" . DB_PREFIX . "worldline`
		                     (`merchant_code`, `request_type`, `key`, `iv`, `webservice_locator`, `status`, `sort_order`, `merchant_scheme_code`,
		                      `order_status_confirm`, `order_status_complete`, `order_status_failure`, `order_status_cancel`, `order_status_abort`)
		                     VALUES ('".trim($merchant_details['worldline_merchant_code'])."', '".$merchant_details['worldline_request_type']."',
		                             '".trim($merchant_details['worldline_key'])."', '".trim($merchant_details['worldline_iv'])."',
		                             '".$merchant_details['worldline_webservice_locator']."', '".$merchant_details['worldline_status']."',
		                             '".trim($merchant_details['worldline_sort_order'])."','".trim($merchant_details['worldline_merchant_scheme_code'])."',
		                             '".$merchant_details['worldline_order_status_confirm']."', '".$merchant_details['worldline_order_status_complete']."',
		                             '".$merchant_details['worldline_order_status_failure']."', '".$merchant_details['worldline_order_status_cancel']."',
		                             '".$merchant_details['worldline_order_status_abort']."')");
		    return true;
	    }
	    return false;
	}

	public function get() {
		    return $this->db->query("SELECT * FROM `" . DB_PREFIX . "worldline`")->rows;
	}

	public function edit($merchant_details) {
	    if(count($merchant_details) > 0){
	        $this->db->query("UPDATE `" . DB_PREFIX . "worldline`
	                         SET `merchant_code` = '".trim($merchant_details['worldline_merchant_code'])."',
	                             `request_type` = '".$merchant_details['worldline_request_type']."',
                                 `key` = '".trim($merchant_details['worldline_key'])."',
	                             `iv` = '".trim($merchant_details['worldline_iv'])."',
	                             `webservice_locator` = '".$merchant_details['worldline_webservice_locator']."',
                                 `status` = '".$merchant_details['worldline_status']."',
                                 `sort_order` = '".trim($merchant_details['worldline_sort_order'])."',
	                             `merchant_scheme_code` = '".$merchant_details['worldline_merchant_scheme_code']."',
	                             `order_status_confirm` = '".$merchant_details['worldline_order_status_confirm']."',
	                             `order_status_complete` = '".$merchant_details['worldline_order_status_complete']."',
	                             `order_status_failure` = '".$merchant_details['worldline_order_status_failure']."',
	                             `order_status_cancel` = '".$merchant_details['worldline_order_status_cancel']."',
	                             `order_status_abort` = '".$merchant_details['worldline_order_status_abort']."'");
	        return true;
	    }
	    return false;
	}

}
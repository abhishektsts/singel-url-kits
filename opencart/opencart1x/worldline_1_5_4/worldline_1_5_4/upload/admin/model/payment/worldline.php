<?php

class ModelPaymentworldline extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "worldline` (
			  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              `merchant_code` varchar(255) NOT NULL,
              `request_type` char(50) NOT NULL,
              `key` varchar(255) NOT NULL,
              `iv` varchar(255) NOT NULL,
              `webservice_locator` varchar(255) NOT NULL,
              `order_status` varchar(255) NOT NULL,
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
		                     (`merchant_code`, `request_type`, `key`, `iv`, `webservice_locator`, `order_status`, `status`, `sort_order`, `merchant_scheme_code`)
		                     VALUES ('".trim($merchant_details['worldline_merchant_code'])."', '".$merchant_details['worldline_request_type']."', '".trim($merchant_details['worldline_key'])."',
		                             '".trim($merchant_details['worldline_iv'])."', '".$merchant_details['worldline_webservice_locator']."',
		                             '".$merchant_details['worldline_order_status']."', '".$merchant_details['worldline_status']."',
		                             '".trim($merchant_details['worldline_sort_order'])."','".trim($merchant_details['worldline_merchant_scheme_code'])."' )");
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
                                 `order_status` = '".$merchant_details['worldline_order_status']."',
                                 `status` = '".$merchant_details['worldline_status']."',
                                 `sort_order` = '".trim($merchant_details['worldline_sort_order'])."',
	                             `merchant_scheme_code` = '".$merchant_details['worldline_merchant_scheme_code']."'");
	        return true;
	    }
	    return false;
	}

}
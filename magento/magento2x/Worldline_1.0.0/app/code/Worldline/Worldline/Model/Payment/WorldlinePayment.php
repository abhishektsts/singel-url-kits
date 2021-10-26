<?php


namespace Worldline\Worldline\Model\Payment;

class WorldlinePayment extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "worldlinepayment";
    
    protected $_isInitializeNeeded      = false;
    protected $redirect_uri;
    protected $_canOrder = true;
    protected $_isGateway = true; 

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }


    public function getOrderPlaceRedirectUrl() {
	   return \Magento\Framework\App\ObjectManager::getInstance()
							->get('Magento\Framework\UrlInterface')->getUrl("worldline/redirect");
   } 
}

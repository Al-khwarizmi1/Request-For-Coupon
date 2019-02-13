<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */

 /**
  * Request For Coupon Model
  * 
  * @method Apptha_Reqforcoupon_Model_Reqforcoupon response(string $value, string $value,string $value,int $value)
  * 
  */
class Apptha_Reqforcoupon_Model_Reqforcoupon extends Mage_Core_Model_Abstract {
    /**
     * XML configuration paths
     */
    const XML_PATH_EMAIL_SENDER = 'reqforcoupon/request_coupon/request_sender_email';
    const XML_PATH_EMAIL_TEMPLATE = 'reqforcoupon/request_coupon/request_autogenerate_template';

    
     /**
     * Initialize resources
     */
    public function _construct() {
        parent::_construct();
        $this->_init('reqforcoupon/reqforcoupon');
    }

    /**
     * Request email was send to admin to request enabled product 
     * 
     * @param string $custName
     * @param string $custEmail
     * @param string $adminEmail
     * @param int $productId 
     * 
     * @returns Apptha_Reqforcoupon_IndexController sendreqAction()
     */
    public function response($custName, $custEmail, $adminEmail, $productId) {
        /*get product details using load ()*/
        $_product = Mage::getModel('catalog/product')->load($productId);
        $productName = $_product->getName();//product name
        $urlPath = $_product->getUrlPath();//product url path
        $productURL = Mage::getBaseUrl() . $urlPath;//product url
        
        //assigned name and email id to variable $sender
        $sender = Array('name' => Mage::getStoreConfig('design/head/default_title'),
                        'email' => $adminEmail);
         //To create new object
        $postObject = new Varien_Object();
        //set data to send in the email template 
        $postObject->setData(array('name' => $custName, 
                                   'email' => $adminEmail, 
                                   'productname' => $productName, 
                                   'producturl' => $productURL
                            ));
         /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate = Mage::getModel('core/email_template');
         /*set email subject given by admin */
        $mailTemplate->setTemplateSubject(Mage::getStoreConfig('reqforcoupon/request_coupon/request_subject'));
        // $mailTemplate->addBcc($adminEmail);
        
         /*Send Transactional Email*/
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), 
                        $sender, 
                        $custEmail, 
                        $custName, 
                        array('response' => $postObject)
        );
    }

}
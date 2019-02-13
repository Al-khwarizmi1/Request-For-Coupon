<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_IndexController extends Mage_Core_Controller_Front_Action {
    
    /**
     * XML configuration paths
     */
    const XML_PATH_EMAIL_TEMPLATE = 'reqforcoupon/request_coupon/request_customer_template';

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * 
     * To enable the request coupon option to collected product
     * 
     * @return string $str
     */
    public function reqAction() {

        //To check the module enabled 
        if (!(Mage::helper('reqforcoupon')->moduleEnabled())) {
            $this->norouteAction();
            return;
        }
        //Filter product collection based on attribute status and reqcoupon_status
        $_productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter(array(
                    array('attribute' => 'Status', 'eq' => '1')))
                ->addFieldToFilter(array(
                    array('attribute' => 'reqcoupon_status', 'eq' => '1')));
        if (count($_productCollection) > 0) {
            $count = 0;
            $str = "";
            //To get product id
            foreach ($_productCollection as $_product) {
                $products[$count] = $_product->getId();
                $count = $count + 1;
                $str .= $_product->getId() . ",";
            }
            $str = substr($str, 0, strlen($str) - 1);
        }
        echo $str;
    }

    /**
     * To send the request for the coupon to admin
     * 
     * @return message Success
     */
    public function sendreqAction() {
        //Get the Post data and assigned to variables
        $name = addslashes($this->getRequest()->getPost('req_Name'));
        $email = $this->getRequest()->getPost('req_email');
        $comment = addslashes($this->getRequest()->getPost('req_comment'));
        $productId = $this->getRequest()->getPost('productid');
        $token = $this->getRequest()->getPost('token');
        $cookieValue = Mage::getSingleton('core/cookie')->get('tonke_no');
        //Get product details for customer request with load()
        $_product = Mage::getModel('catalog/product')->load($productId);
        $productName = $_product->getName();//product name
        $urlPath = $_product->getUrlPath();//product url
        $productURL = Mage::getBaseUrl() . $urlPath;//product url
        
        //Get Admin Name and Email id 
        $adminEmail = Mage::getStoreConfig('reqforcoupon/request_coupon/request_admin_email');//Email Id of admin
        $adminName = Mage::getStoreConfig('reqforcoupon/request_coupon/request_admin_name');//Name of admin
        
        //assigned the name and email id to variable
        $sender = Array('name' => $name,
            'email' => $email);
            
        // fetch write database connection that is used in Mage_Core module 
        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        
        //Get table prefix name 
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $reqforcoupon = $tPrefix . 'reqforcoupon';//assign the table name
        
        //To create new object 
        $postObject = new Varien_Object();
        
        //set data to send in the email template 
        $postObject->setData(array('cutname' => $name, 
                                   'comment' => nl2br($comment), 
                                   'email' => $email, 
                                   'productname' => $productName, 
                                   'producturl' => $productURL, 
                                   'admin' => $adminName
                                  ));
        
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate = Mage::getModel('core/email_template');
        
        /*set email subject given by admin */
        $mailTemplate->setTemplateSubject(Mage::getStoreConfig('reqforcoupon/request_coupon/request_subject'));
        //$mailTemplate->addBcc($adminEmail);
        if($cookieValue == $token){
        /*Send Transactional Email*/
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->sendTransactional(Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), 
                                    $sender, 
                                    $adminEmail, 
                                    $adminName, 
                                    array('request' => $postObject)
                    );
         /**
          * Verify mail send successfully ,if not  throws an error message 
          */
        if (!$mailTemplate->getSentSuccess()) {
            $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
        } else {
                // now $write is an instance of Zend_Db_Adapter_Abstract
            $insertCoupon .= "('" . $name . "','" . $email . "','" . $productId . "','".$productName."','" . $comment . "','0',now())";
            
            $write->query("insert into " . $reqforcoupon . " (name,email,product_id,product_name,message,status,created_time)values " . $insertCoupon);
            /*send the confirmation email after request send to admin */
            Mage::getModel('reqforcoupon/reqforcoupon')->response($name, $email, $adminEmail, $productId);
        }
        echo "success";
        die();
    }
   }
}
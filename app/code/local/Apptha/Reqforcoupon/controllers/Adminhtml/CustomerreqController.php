<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */

class Apptha_Reqforcoupon_Adminhtml_CustomerreqController extends Mage_Adminhtml_Controller_action {
    /**
     * XML configuration paths
     */
    const EMAIL_ADMIN_TEMPLATE_XML_PATH = 'reqforcoupon/request_coupon/request_admin_template';

    /**
     * Init actions
     * @return Apptha_Reqforcoupon_Adminhtml_CustomerreqController
     */
    protected function _initAction() {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
                ->_setActiveMenu('reqforcoupon/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }
    /**
     * Index action
     *  
     * To display Request Customer list in grid
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }
    /**
     * Edit Action
     */
 
    public function editAction() {
        //Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('reqforcoupon/customerreq')->load($id);
        //Initial checking
        if ($model->getId() || $id == 0) {
            //Set entered data if was error when we do save
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            
            if (!empty($data)) {
                $model->setData($data);
            }
            //Register model to use later in reqforcoupon
            Mage::register('reqforcoupon_data', $model);
            //loadlayout
            $this->loadLayout();
            //active menu
            $this->_setActiveMenu('reqforcoupon/items');
            //breadcrumbs
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
            //To load layout
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            //load form container with data
            $this->_addContent($this->getLayout()->createBlock('reqforcoupon/adminhtml_customerreq_edit'))
                    ->_addLeft($this->getLayout()->createBlock('reqforcoupon/adminhtml_customerreq_edit_tabs'));

            $this->renderLayout();
        } else {
            //display error message
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reqforcoupon')->__('Item does not exist'));
            //go to grid 
            $this->_redirect('*/*/');
        }
    }
    /**
     * New Action
     * 
     * Forwards on to the edit action
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * Save action
     */
    public function saveAction() {
        //check if data sent
        if ($data = $this->getRequest()->getPost()) {
            //init model and set data
            $model = Mage::getModel('reqforcoupon/reqforcoupon');  
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                
                 //save the data
                $model->save();
                $model->setStatus('1')->save();
              
                //init model 
                $_product = Mage::getModel('catalog/product')->load($data['product_id']);
                $productName = $_product->getName();//product name
                $urlPath = $_product->getUrlPath();//product urlpath
                $productURL = Mage::getBaseUrl() . $urlPath;//product url

                $customer_email = $data['email'];//customer email
                $customer_name = $data['name'];//customer name
                $customer_message = $data['message'];//customer request message to admin
                $admin_reply = $data['admin_reply'];//admin reply message to customer
               
                $storeName = Mage::getStoreConfig("design/head/default_title");//store title
                /*set template id*/
                $templateId = Mage::getStoreConfig(self::EMAIL_ADMIN_TEMPLATE_XML_PATH);
                 /**
                  * @var $mailSubject 
                  * set email subject given by admin 
                  */ 
                $mailSubject = Mage::getStoreConfig('reqforcoupon/request_coupon/request_subject');
                /**
                 * $sender can be of type string or array. You can set identity of
                 * diffrent Store emails (like 'support', 'sales', etc.) found
                 * in "System->Configuration->General->Request Coupon"
                 */
                $sendermail = Mage::getStoreConfig('reqforcoupon/request_coupon/request_admin_email');
                $senderName = Mage::getStoreConfig('reqforcoupon/request_coupon/request_admin_name');
                $sender = Array('name' => $senderName,
                    'email' => $sendermail);

                /**
                 * In case of multiple recipient use array here.
                 */
                $email = $customer_email;

                /**
                 * If $name = null, then magento will parse the email id
                 * and use the base part as name.
                 */
                $name = $customer_name;

                $vars = Array();
                /* An example how you can pass magento objects and normal variables */

                $vars = Array(
                    'productname' => $productName,
                    'prodcuturl' => $productURL,
                    'customer_message' => nl2br($customer_message),
                    'customer_name' => $customer_name,
                    'admin_reply' => nl2br($admin_reply)
                );



                /* This is optional */
                $storeId = Mage::app()->getStore()->getId();
               
                $translate = Mage::getSingleton('core/translate');
                /* @var $mailTemplate Mage_Core_Model_Email_Template 
                 * @var $mailSubject 
                 * Send Transactional Email
                 */
                $mailTemplate = Mage::getModel('core/email_template')
                        ->setTemplateSubject($mailSubject)
                        ->sendTransactional($templateId, $sender, $email, $name, $vars, $storeId);
                $translate->setTranslateInline(true);

                 /**
                  * Verify mail send successfully ,if not  throws an error message 
                  */
                if (!$mailTemplate->getSentSuccess()) {
                    // display error message
                    $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
                    //go to grid 
                    $this->_redirect('*/*/');
                    return;
                } else {
                    // display success message after the email send
                    $this->_getSession()->addSuccess("Mail sent successfully !");
                }
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                //go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                //save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reqforcoupon')->__('Unable to find item to save'));
         // go to grid
        $this->_redirect('*/*/');
    }

    /**
     * To delete all selected rows in the grid
     */
    public function massDeleteAction() {
        //Get the selected Id 
        $reqforcouponIds = $this->getRequest()->getParam('reqforcoupon');
        //Checking it was array
        if (!is_array($reqforcouponIds)) {
            //display the alert message when it was not array
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                
                foreach ($reqforcouponIds as $reqforcouponId) {
                     //init model and delete
                    $reqforcoupon = Mage::getModel('reqforcoupon/reqforcoupon')->load($reqforcouponId);
                    $reqforcoupon->delete();
                }
                // display success message with total count records deleted successfully
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($reqforcouponIds)
                        )
                );
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        //redirect to new index page
        $this->_redirect('*/*/index');
    }
    
    /**
     * Export request customer grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'reqforcoupon.csv';
        $content = $this->getLayout()->createBlock('reqforcoupon/adminhtml_customerreq_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
    /**
     * Export request customer grid to XML format
     */
    public function exportXmlAction() {
        $fileName = 'reqforcoupon.xml';
        $content = $this->getLayout()->createBlock('reqforcoupon/adminhtml_customerreq_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
    /**
     * Prepare file download response
     *
     * @param string $fileName
     * @param string $content
     * @param string $contentType
     */
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}
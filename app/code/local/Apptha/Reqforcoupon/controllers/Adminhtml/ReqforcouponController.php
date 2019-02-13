<?php

/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */

class Apptha_Reqforcoupon_Adminhtml_ReqforcouponController extends Mage_Adminhtml_Controller_action {

    /**
     * Init actions
     * 
     * @return Apptha_Reqforcoupon_Adminhtml_ReqforcouponController
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
     * Product collection in grid
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    /**
     * Mass Disable Action 
     * 
     * To Disable the request for coupon option for the selected product 
     */
    public function massDisableAction() {
        //Get the selected product Ids 
        $productIds = (array) $this->getRequest()->getParam('product');
        $storeId = (int) $this->getRequest()->getParam('store', 0);//store id
        $status = 2;//assign the status as 2 to disable

        try {
            //Update the attribute for the selected product in the grid
            Mage::getSingleton('catalog/product_action')
                    ->updateAttributes($productIds, array('reqcoupon_status' => $status), $storeId);
            //display message with total no of records was updated
            $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been disabled.', count($productIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            //display error message update fails
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            //display error message update fails
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            //display error message update fails
            $this->_getSession()
                    ->addException($e, $this->__('An error occurred while disabling the product(s) status.'));
        }
        //go to grid
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * Mass enable Action 
     * 
     * To enable the request for coupon option for the selected product 
     */
    public function massEnableAction() {
        //Get the selected product Ids 
        $productIds = (array) $this->getRequest()->getParam('product');
        $storeId = (int) $this->getRequest()->getParam('store', 0); //store id
        $status = 1; //assign the status as 1 to enable

        try {

            //Update the attribute for the selected product in the grid
            Mage::getSingleton('catalog/product_action')
                    ->updateAttributes($productIds, array('reqcoupon_status' => $status), $storeId);
            //display message with total no of records was updated
            $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) have been enabled.', count($productIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            //display error message update fails
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            //display error message update fails
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            //display error message update fails
            $this->_getSession()
                    ->addException($e, $this->__('An error occurred while enabling the product(s) status.'));
        }
        //go to grid
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    /**
     * Export request customer grid to CSV format
     */
    public function exportCsvAction() {

        $fileName = 'reqforcoupon.csv'; //File name
        $content = $this->getLayout()->createBlock('reqforcoupon/adminhtml_reqforcoupon_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    /**
     * Export request customer grid to XML format
     */
    public function exportXmlAction() {
        $fileName = 'reqforcoupon.xml';
        $content = $this->getLayout()->createBlock('reqforcoupon/adminhtml_reqforcoupon_grid')
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
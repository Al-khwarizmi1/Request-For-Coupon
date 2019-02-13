<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Customerreq extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_customerreq';
        $this->_blockGroup = 'reqforcoupon';
        $this->_headerText = Mage::helper('reqforcoupon')->__('Manage Request Customers');
        parent::__construct();
        $this->removeButton('add');//add item button was removed
    }

}
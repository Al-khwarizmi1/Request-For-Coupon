<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Customerreq_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('reqforcoupon_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('reqforcoupon')->__('Customer Information'));
    }
    /**
     * Call the editable form
     * @return adminhtml_reqforcoupon_edit_tab_form
     */
    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('reqforcoupon')->__('Customer Information'),
            'title' => Mage::helper('reqforcoupon')->__('Customer Information'),
            'content' => $this->getLayout()->createBlock('reqforcoupon/adminhtml_reqforcoupon_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
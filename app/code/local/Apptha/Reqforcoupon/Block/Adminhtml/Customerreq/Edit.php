<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */

class Apptha_Reqforcoupon_Block_Adminhtml_Customerreq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
     /**
      * Update in database and send email 
      */
    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'reqforcoupon';
        $this->_controller = 'adminhtml_reqforcoupon';
        $this->removeButton('delete');//To remove delete button
        $this->removeButton('reset');//To remove reset button
        $this->_updateButton('save', 'label', Mage::helper('reqforcoupon')->__('Send Mail'));//save button was renamed as send email
        
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('reqforcoupon_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'reqforcoupon_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'reqforcoupon_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
          //check the id existing 
        if (Mage::registry('reqforcoupon_data') && Mage::registry('reqforcoupon_data')->getId()) {
            //send email to customer button will shown
            return Mage::helper('reqforcoupon')->__('Send email to customer');
        } else {
            //Add item button will  show
            return Mage::helper('reqforcoupon')->__('Add Item');
        }
    }

}
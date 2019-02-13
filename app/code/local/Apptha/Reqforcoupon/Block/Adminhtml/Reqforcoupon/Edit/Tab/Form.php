<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Reqforcoupon_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('reqforcoupon_form', array('legend'=>Mage::helper('reqforcoupon')->__('Customer information')));

      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('reqforcoupon')->__('Customer Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
          'readonly' =>'readonly',
      ));

     $fieldset->addField('email', 'hidden', array(
              'name'      => 'email',
          ));
     $fieldset->addField('product_id', 'hidden', array(
              'name'      => 'product_id',
          ));
     $fieldset->addField('status', 'hidden', array(
              'name'      => 'status',
              'value'   =>'1',
          ));
      $fieldset->addField('message', 'editor', array(
          'name'      => 'message',
          'label'     => Mage::helper('reqforcoupon')->__('Customer Message'),
          'title'     => Mage::helper('reqforcoupon')->__('Customer Message'),
          'style'     => 'width:500px; height:300px;',
          'wysiwyg'   => false,
          'required'  => true,
          'readonly' =>'readonly',
      ));
      $fieldset->addField('admin_reply', 'editor', array(
          'name'      => 'admin_reply',
          'label'     => Mage::helper('reqforcoupon')->__('Admin Reply Message'),
          'title'     => Mage::helper('reqforcoupon')->__('Admin Reply Message'),
          'style'     => 'width:500px; height:300px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
      if ( Mage::getSingleton('adminhtml/session')->getReqforcouponData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getReqforcouponData());
          Mage::getSingleton('adminhtml/session')->setReqforcouponData(null);
      } elseif ( Mage::registry('reqforcoupon_data') ) {
          $form->setValues(Mage::registry('reqforcoupon_data')->getData());
      }
      return parent::_prepareForm();
  }
}
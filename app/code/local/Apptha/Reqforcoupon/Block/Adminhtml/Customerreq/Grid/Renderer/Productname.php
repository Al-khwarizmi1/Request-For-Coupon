<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Block_Adminhtml_Customerreq_Grid_Renderer_Productname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

/**
 * 
 * Retrive the product name
 * @param object $row
 * 
 * @return string product name
 */ 
    public function render(Varien_Object $row) {

        $rowValue = $row->getData();//get row data
        $productId = $rowValue['product_id'];//product id
        $_product = Mage::getModel('catalog/product')->load($productId);//init model
        return $_product->getName();//return product name
    }

}
?>

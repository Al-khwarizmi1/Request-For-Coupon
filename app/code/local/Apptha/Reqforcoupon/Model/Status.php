<?php
/**
 * @Author       : Apptha team 
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 * 
 */
/**
 * Product status functionality model
 * 
 * @method Apptha_Reqforcoupon_Model_Status getOptionArray()
 * 
 * @package      : Apptha_Request_Coupon
 * @Author       : Apptha team 
 */
class Apptha_Reqforcoupon_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

     /**
     * Retrieve option array
     *
     * @return array
     */
    
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('reqforcoupon')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('reqforcoupon')->__('Disabled')
        );
    }
}
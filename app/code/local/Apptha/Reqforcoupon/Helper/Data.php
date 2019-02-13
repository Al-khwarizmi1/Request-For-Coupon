<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Verify the module is enabled in the backend
     * @return boolean 
     */
    public function moduleEnabled() {
        return Mage::getStoreConfig('reqforcoupon/request_coupon/activate_apptha_reqforcoupon_enable');
    }

}
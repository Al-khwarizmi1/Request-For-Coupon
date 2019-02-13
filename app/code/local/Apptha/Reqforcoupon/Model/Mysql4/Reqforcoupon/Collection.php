<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Model_Mysql4_Reqforcoupon_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    /**
      * Initialize resource
      */
    public function _construct() {
        parent::_construct();
        $this->_init('reqforcoupon/reqforcoupon');
    }

}
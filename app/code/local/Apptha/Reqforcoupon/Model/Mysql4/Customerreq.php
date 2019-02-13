<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
class Apptha_Reqforcoupon_Model_Mysql4_Customerreq extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        // Note that the reqforcoupon_id refers to the key field in your database table.
        $this->_init('reqforcoupon/reqforcoupon', 'reqforcoupon_id');
    }

}
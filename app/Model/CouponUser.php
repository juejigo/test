<?php

class Model_CouponUser extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'coupon_user';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_CouponUser';
}

?>
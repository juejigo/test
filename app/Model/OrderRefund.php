<?php

class Model_OrderRefund extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_refund';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderRefund';
}

?>
<?php

class Model_OrderDiscount extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_discount';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderDiscount';
}

?>
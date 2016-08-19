<?php

class Model_OrderShipping extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_shipping';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderShipping';
}

?>
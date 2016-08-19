<?php

class Model_OrderItem extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_item';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderItem';
}

?>
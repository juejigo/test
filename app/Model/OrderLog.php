<?php

class Model_OrderLog extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_log';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderLog';
}

?>
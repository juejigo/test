<?php

class Model_OrderActivity extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order_activity';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OrderActivity';
}

?>
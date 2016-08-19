<?php

class Model_PayNotify extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'pay_notify';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_PayNotify';
}

?>
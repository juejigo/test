<?php

class Model_Agent extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'agent';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Agent';
}

?>
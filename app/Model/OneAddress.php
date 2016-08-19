<?php

class Model_OneAddress extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'one_address';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OneAddress';
}

?>
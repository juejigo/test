<?php

class Model_OneAccount extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'one_account';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OneAccount';
}

?>
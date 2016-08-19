<?php

class Model_Funds extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'funds';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Funds';
}

?>
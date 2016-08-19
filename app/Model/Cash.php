<?php

class Model_Cash extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'cash';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Cash';
}

?>
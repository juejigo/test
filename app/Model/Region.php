<?php

class Model_Region extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'region';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Region';
}

?>
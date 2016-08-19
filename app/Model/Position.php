<?php

class Model_Position extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'position';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Position';
}

?>
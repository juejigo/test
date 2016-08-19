<?php

class Model_Envelope extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'envelope';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Envelope';
}

?>
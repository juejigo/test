<?php

class Model_Imei extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'imei';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Imei';
}

?>
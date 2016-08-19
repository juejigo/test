<?php

class Model_Consignee extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'consignee';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Consignee';
}

?>
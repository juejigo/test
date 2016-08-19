<?php

class Model_Product extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Product';
}

?>
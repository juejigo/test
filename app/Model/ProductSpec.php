<?php

class Model_ProductSpec extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_spec';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductSpec';
}

?>
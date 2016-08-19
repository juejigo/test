<?php

class Model_ProductAddon extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_addon';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductAddon';
}

?>
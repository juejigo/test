<?php

class Model_ProductItem extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_item';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductItem';
}

?>
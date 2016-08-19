<?php

class Model_ProductBrand extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_brand';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductBrand';
}

?>
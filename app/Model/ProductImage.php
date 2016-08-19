<?php

class Model_ProductImage extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_image';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductImage';
}

?>
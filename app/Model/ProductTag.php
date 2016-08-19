<?php

class Model_ProductTag extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_tag';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductTag';
}

?>
<?php

class Model_Cart extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'cart';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Cart';
}

?>
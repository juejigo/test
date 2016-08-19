<?php

class Model_Favorite extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'favorite';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Favorite';
}

?>
<?php

class Model_Image extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'image';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Image';
}

?>
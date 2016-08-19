<?php

class Model_Mail extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'mail';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Mail';
}

?>
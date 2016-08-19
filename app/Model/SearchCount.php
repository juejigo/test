<?php

class Model_SearchCount extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'search_count';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_SearchCount';
}

?>
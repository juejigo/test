<?php

class Model_News extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'news';
	
	/**
	 *  @var array
	 */
	protected $_dependentTables = array(
		'Model_NewsData'
	);
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_News';
}

?>
<?php

class Model_NewsCate extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'news_cate';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_NewsCate';
}

?>
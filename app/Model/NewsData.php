<?php

class Model_NewsData extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'news_data';
	
	/**
	 *  @var strng
	 */
	protected $_primary = 'news_id';
	
	/**
	 *  @var array
	 */
	protected $_referenceMap = array(
		'news' => array(
			'columns' => 'news_id',
			'refTableClass' => 'Model_News',
			'refColumns' => 'id',
			'onDelete' => self::CASCADE)
	);
}

?>
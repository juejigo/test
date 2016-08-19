<?php

class Model_ProductTagdata extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_tagdata';
	
	/**
	 *  @var array
	 */
	protected $_primay = array('tag_id','product_id');
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductTagdata';
}

?>
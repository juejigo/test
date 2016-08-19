<?php

class Model_ProductFeedback extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_feedback';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductFeedback';
}

?>
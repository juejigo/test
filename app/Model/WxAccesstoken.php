<?php

class Model_WxAccesstoken extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'wx_accesstoken';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_WxAccesstoken';
}

?>
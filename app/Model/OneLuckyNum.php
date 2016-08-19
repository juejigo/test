<?php

class Model_OneLuckyNum extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'one_lucky_num';
	
	/**
	 *  @var array
	 */
	protected $_primay = array('lucky_num','phase_id');
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OneLuckyNum';
}

?>
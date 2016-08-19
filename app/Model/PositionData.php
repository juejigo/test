<?php

class Model_PositionData extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'position_data';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_PositionData';
}

?>
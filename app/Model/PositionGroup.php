<?php

class Model_PositionGroup extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'position_group';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_PositionGroup';
}

?>
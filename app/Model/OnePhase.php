<?php

class Model_OnePhase extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'one_phase';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OnePhase';
}

?>
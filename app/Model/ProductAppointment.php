<?php

class Model_ProductAppointment extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_appointment';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ProductAppointment';
}

?>
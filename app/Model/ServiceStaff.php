<?php

class Model_ServiceStaff extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'service_staff';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_ServiceStaff';
}
?>
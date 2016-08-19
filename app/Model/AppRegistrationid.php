<?php

class Model_AppRegistrationid extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'app_registrationid';
	
	/**
	 *  @var string
	 */
	protected $_primary = 'member_id';
}

?>
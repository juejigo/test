<?php

class Model_MemberAuth extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'member_auth';

	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_MemberAuth';	
}

?>
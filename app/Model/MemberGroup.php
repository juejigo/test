<?php

class Model_MemberGroup extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'member_group';

	 /**
     *  @var string
     */
    protected $_rowClass = 'Model_Row_MemberGroup';
}

?>
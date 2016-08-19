<?php

class Model_MemberProfile extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'member_profile';
	
	/**
	 *  @var string
	 */
	protected $_primary = 'member_id';
	
	/**
	 *  @var array
	 */
	protected $_referenceMap = array(
		'member' => array(
			'columns' => 'member_id',
			'refTableClass' => 'Model_Member',
			'refColumns' => 'id',
			'onDelete' => self::CASCADE)
	);
}

?>
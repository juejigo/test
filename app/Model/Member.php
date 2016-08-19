<?php

class Model_Member extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'member';
	
	/**
	 *  @var array
	 */
	protected $_dependentTables = array(
		'Model_MemberProfile'
	);
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Member';
	
	/**
	 *  密码加密
	 * 
	 *  @param string $password
	 *  @param string $salt 混淆码
	 *  @return string
	 */
	public function encodePassword($password,$salt)
	{
		return md5(md5($password) . $salt);
	}
}

?>
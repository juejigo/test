<?php

class Core2_Validate_Captcha extends Core_Validate_Abstract 
{
	const NOT_EQUALS = 'notEquals';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_EQUALS => '验证码输入错误'
	);
	
	/**
	 *  @var Zend_Session_Namespace
	 */
	protected $_session = null;
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_session = new Zend_Session_Namespace('captcha');
	}
	
	/**
	 *  检验
	 * 
	 *  与session中值是否一致
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if ($this->_session->word != strtolower($value)) 
		{
			$this->_error(self::NOT_EQUALS);
			return false;
		}
		return true;
	}
}

?>
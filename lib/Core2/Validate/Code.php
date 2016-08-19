<?php

class Core2_Validate_Code extends Core_Validate_Abstract 
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
		$this->_session = new Zend_Session_Namespace('code');
	}
	
	/**
	 *  检验
	 * 
	 *  与session中值是否一致
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (empty($values['mobile']) && !empty($values['account'])) 
		{
			$values['mobile'] = $values['account'];
		}
		
		if ($this->_session->mobile != $values['mobile'] || $this->_session->code != $values['code']) 
		{
			$this->_error(self::NOT_EQUALS);
			return false;
		}
		return true;
	}
}

?>
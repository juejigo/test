<?php

class Core2_Validate_MobileNumber extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '手机号码格式错误'
	);
	
	/**
	 *  检验
	 * 
	 *  正则检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if (!preg_match('/^0?1\d{10}$/',$value))
		{
			$this->_error(self::NOT_VALID);
			return false;
		}
		
		return true;
	}
}

?>
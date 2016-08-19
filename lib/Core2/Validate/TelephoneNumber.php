<?php

class Core2_Validate_TelephoneNumber extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '座机号码格式错误'
	);
	
	/**
	 *  检验
	 * 
	 *  正则检验
	 *  
	 *  @param string $value
	 */
	public function isValid($value)
	{
		if (!preg_match('/^((\+?[0-9]{2,4}\-[0-9]{3,4}\-)|([0-9]{3,4}\-))?([0-9]{7,8})(\-[0-9]+)?$/',$value))
		{
			$this->_error(self::NOT_VALID);
			return false;
		}
		
		return true;
	}
}

?>
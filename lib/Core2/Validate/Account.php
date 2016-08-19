<?php

class Core2_Validate_Account extends Core_Validate_Abstract 
{
	const ALNUM = 'alnum';
	const TOO_LONG = 'tooLong';
	const TOO_SHORT = 'tooShort';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::ALNUM => '只允许数字和字母的组合',
		self::TOO_SHORT => '账号长度不能少于5个字符',
		self::TOO_LONG => '账号长度不能大于30个字符'
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if (!ctype_alnum($value)) 
		{
			$this->_error(self::ALNUM);
			return false;
		}
		
		/* 检验长度 */
		$len = iconv_strlen($value,'utf-8');
		if ($len < 3)
		{
			$this->_error(self::TOO_SHORT);
			return false;
		}
		else if ($len > 30)
		{
			$this->_error(self::TOO_LONG);
			return false;
		}
		
		return true;
	}
}

?>
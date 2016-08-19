<?php

class Core2_Validate_Chinese extends Core_Validate_Abstract 
{
	const INVALID_CHARS = 'invalid_chars';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID_CHARS => '请输入中文'
	);
	
	/**
	 *  检验
	 * 
	 *  检验长度,正则检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		/*
		 *  正则检验
		 */
		
		if (!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$value)) 
		{
			$this->_error(self::INVALID_CHARS);
			return false;
		}
		
		return true;
	}
}

?>
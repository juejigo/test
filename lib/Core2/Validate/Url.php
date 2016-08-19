<?php

class Core2_Validate_Url extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '链接格式错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if (!Zend_Uri::check($value)) 
		{
			$this->_error(self::NOT_VALID);
			return false;
		}
		
		return true;
	}
}

?>
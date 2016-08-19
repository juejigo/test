<?php

class Core2_Validate_ZipCode extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '邮编格式错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if (!preg_match('/[1-9]\d{5}(?!\d)/',$value))
		{
			$this->_error(self::NOT_VALID);
			return false;
		}
		
		return true;
	}
}

?>
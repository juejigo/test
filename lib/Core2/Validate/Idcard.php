<?php

class Core2_Validate_Idcard extends Core_Validate_Abstract 
{
	const INVALID = 'invalid';
	
	const LEN_ERROR = 'lenError';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => '身份证号码不合法',
		self::LEN_ERROR => '身份证号码长度不合法' 
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$len = iconv_strlen($value,'utf-8');
		
		if ($len != 15 && $len != 18) 
		{
			$this->_error(self::LEN_ERROR);
			return false;
		}
		
		if ($len == 15) 
		{
			$value = $this->_15to18($value);
		}
		
		if (!$this->_check18($value)) 
		{
			$this->_error(self::INVALID);
			return false;
		}
		
		return true;
	}
	
	/**
	 *  身份证验证码
	 */
	function _verifyNumber($base)
	{
		if(iconv_strlen($base,'utf-8') != 17)
		{
			return false;
		}
		
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);    // 加权因子
		$verifyNumberList = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');    // 校验码对应值
		
		$sum = 0;
		for ($i = 0;$i < strlen($base);$i++)
		{
			$sum += mb_substr($base,$i,1,'utf-8') * $factor[$i];
		}
		$mod = $sum % 11;
		
		return $verifyNumberList[$mod];
	}
	
	/**
	 *  将15位身份证升级到18位
	 */
	function _15to18($idcardNumber)
	{
		/* 如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码 */ 
		if (array_search(mb_substr($idcardNumber,12,3,'utf-8'),array('996','997','998','999')) !== false)
		{
			$idcardNumber = mb_substr($idcardNumber,0,6,'utf-8') . '18'. mb_substr($idcardNumber,6,9,'utf-8');
		}
		else
		{
			$idcardNumber = mb_substr($idcardNumber,0,6,'utf-8') . '19'. mb_substr($idcardNumber,6,9,'utf-8');
		}
		
		$idcardNumber = $idcardNumber . $this->_verifyNumber($idcardNumber);
		
		return $idcardNumber;
	}
	
	/**
	 *  18位身份证校验码有效性检查
	 */
	protected function _check18($idcardNumber)
	{
		$base = mb_substr($idcardNumber,0,17,'utf-8');
		if ($this->_verifyNumber($base) != strtoupper(mb_substr($idcardNumber,17,1,'utf-8')))
		{
			return false;
		}
		
		return true;
	}
} 

?>
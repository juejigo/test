<?php

class Core2_Filter_Base64Decode implements Zend_Filter_Interface 
{
	/**
	 *  过滤
	 * 
	 *  base64解码
	 * 
	 *  @param string $value
	 *  @return float
	 */
	public function filter($value)
	{
		return base64_decode($value);
	}
}

?>
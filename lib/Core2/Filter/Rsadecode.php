<?php

class Core2_Filter_Rsadecode implements Zend_Filter_Interface 
{
	/**
	 *  过滤
	 * 
	 *  将 RSA 密文解密
	 * 
	 *  @param string $value
	 *  @return float
	 */
	public function filter($value)
	{
		$rsa = new Core2_Rsa();
		return $rsa->decode($value);
	}
}

?>
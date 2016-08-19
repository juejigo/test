<?php

class Core2_MobileLocation
{
	/**
	 *  构造
	 */
	public function __construct($class = '')
	{
		if (empty($class)) 
		{
			$config = Zend_Registry::get('config');
			$class = $config->mobileLocation->default;
		}
		
		$className = 'Core2_MobileLocation_' . ucfirst($class);
		$this->_mobileLocation = new $className();
	}
	
	/**
	 *  获取城市和省份
	 * 
	 *  @param $mobile 手机号码
	 */
	public function location($mobile)
	{
		return $this->_mobileLocation->location($mobile);
	}
	
	/**
	 *  获取城市和省份ID
	 * 
	 *  @param $provinceName 省份名
	 *  @param  $cityName 城市名
	 */
	public function getRegionId($mobile){}
}

?>
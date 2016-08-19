<?php

class Core2_MobileLocation_Abstract
{
	/**
	 *  @var Zend_Http_Client
	 */
	protected $_client = null;
	
	/**
	 *  构造
	 */
	public function __construct($class = '')
	{
		$this->_client = new Zend_Http_Client(
			null,
			array(
				'maxredirects' => 10,
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_FOLLOWLOCATION => true))
		);
	}
	
	/**
	 *  获取城市和省份
	 * 
	 *  @param $mobile 手机号码
	 */
	public function location($mobile){}
	
	/**
	 *  获取城市和省份ID
	 * 
	 *  @param $province 省份名
	 *  @param  $city 城市名
	 */
	public function getRegionId($province,$city)
	{
		$db = Zend_Registry::get('db');
		$ret = array();
		
		
		$ret['province_id'] = $db->select()
				->from(array('r' => 'region'),array('id'))
				->where('r.region_name = ?',$province)
				->where('r.level = ?',1)
				->query()
				->fetchColumn();
		$ret['city_id'] = $db->select()
				->from(array('r' => 'region'),array('id'))
				->where('r.region_name = ?',$city)
				->where('r.level = ?',2)
				->query()
				->fetchColumn();
	     return $ret; 
	}
}

?>
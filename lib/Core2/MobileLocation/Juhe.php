<?php

class Core2_MobileLocation_Juhe extends Core2_MobileLocation_Abstract 
{
	/**
	 *  获取城市和省份
	 * 
	 *  @param $mobile 手机号码
	 */
	public function location($mobile)
	{
		$this->_client->setUri('http://apis.juhe.cn/mobile/get');
		$this->_client->setParameterGet(array(
			'phone' => $mobile,
			'key' => 'b4bb468da7a15fb94314e38d6c3e20d9'
		));
		$response = $this->_client->request(Zend_Http_Client::GET);
		$result = $response->getBody();
		
		$json = json_decode($result,true);
		
		if ($json['resultcode'] == 200) 
		{
			$province = $json['result']['province'];
			$city = "{$json['result']['city']}市";
		 	
		 	return $this->getRegionId($province,$city);
		}
		
		return false;
	}
}

?>
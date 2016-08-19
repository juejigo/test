<?php

class Core2_MobileLocation_Paipai extends Core2_MobileLocation_Abstract 
{
	/**
	 *  获取城市和省份
	 * 
	 *  @param $mobile 手机号码
	 */
	public function location($mobile)
	{
		$this->_client->setUri('http://virtual.paipai.com/extinfo/GetMobileProductInfo?amount=10000&callname=getPhoneNumInfoExtCallback');
		$this->_client->setParameterGet(array(
			'mobile' => $mobile
		));
		$response = $this->_client->request(Zend_Http_Client::GET);
		$result = $response->getBody();
		
		if(!preg_match('/getPhoneNumInfoExtCallback\((.*)\);/SiU',$result,$sjson))
		{
			return false;
 		}
		
 		$sjson  = str_replace("'",'"',$sjson['1']);
		$sjson  = preg_replace("/([\w]+):/i",'"\1":',$sjson);
		$sjson  = preg_replace("/:\"([^\"]+)\"/ie","self::converturlencode('\\1')",$sjson);
		$province = json_decode($sjson,true);
	    $code = urldecode($province['province'].','.$province['cityname']);
		$arr = explode(",",mb_convert_encoding($code,"UTF-8","GBK"));
		$province = $arr['0'];
	 	$city = $arr['1'].'市';
	 	
	 	return $this->getRegionId($province,$city);
	}
	
	static public function converturlencode($str)
	{
		return ':"'.urlencode($str).'"';
	}
}

?>